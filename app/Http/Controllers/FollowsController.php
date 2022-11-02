<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FollowsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //every single route in this class will require
        //authorization to be able to access
    }
    public function store(\App\Models\User $user)
    {
        //request/follow private account
        if (
            $user->profile->is_private == 1 &&
            request('data')['type'] == 'request'
        ) {
            $activity = Activity::where('user_id', auth()->user()->id)
                ->where('target_user_id', $user->id)
                ->where('type', 'request')
                ->first();
            if (!empty($activity)) {
                $activity->delete();
            } else {
                Activity::create([
                    'user_id' => auth()->user()->id,
                    'target_user_id' => $user->id,
                    'type' => 'request',
                    'message' => "requested to follow you",
                ]);
            }

            return auth()
                ->user()
                ->following()
                ->toggle([$user->profile->id => ['status' => 'pending']]);
        }
        //unfollow private account
        elseif (
            $user->profile->is_private == 1 &&
            request('data')['type'] == 'follow'
        ) {
            if (request('data')['is_following']) {
                $activity = Activity::where('user_id', auth()->user()->id)
                    ->where('target_user_id', $user->id)
                    ->where('type', 'accept')
                    ->first();
                $activity2 = Activity::where('user_id', $user->id)
                    ->where('target_user_id', auth()->user()->id)
                    ->where('type', 'accept')
                    ->first();
                if (!empty($activity)) {
                    $activity->delete();
                }
                if (!empty($activity2)) {
                    $activity2->delete();
                }
            }
            return auth()
                ->user()
                ->following()
                ->toggle($user->profile->id);
        }
        //follow/unfollow public account
        elseif ($user->profile->is_private == 0) {
            $activity = Activity::where('user_id', auth()->user()->id)
                ->where('type', 'follow')
                ->first();
            if (!empty($activity)) {
                $activity->delete();
            } else {
                Activity::create([
                    'user_id' => auth()->user()->id,
                    'target_user_id' => $user->id,
                    'type' => 'follow',
                    'message' => "followed you",
                ]);
            }

            return auth()
                ->user()
                ->following()
                ->toggle([$user->profile->id => ['status' => 'accepted']]);
        }

        //M-N rs provides toggle method (check if user_id and profile_id exist in the row)
        //toggle user's profile (automatically save user_id and profile_id in the pivot table)
        //to enable automatic storing of user_id naming convention of column = user_id
        //+ auth()->user(), laravel will take this user id and store in the pivot table
    }

    public function update()
    {
        $this->authorize('update', auth()->user()->profile);
        $this->authorize('privateAccount', auth()->user()->profile);
        //accept follow request
        if (request('data')['decision'] == 'accept') {
            //tell user, follow accepted
            Activity::create([
                'user_id' => auth()->user()->id,
                'target_user_id' => request('data')['user_id'],
                'type' => 'accept',
                'message' => 'has accepted your follow request!',
            ]);
            //change activity type
            $activity = Activity::where('target_user_id', auth()->user()->id)
                ->where('user_id', request('data')['user_id'])
                ->where('type', 'request')
                ->first();

            if (!empty($activity)) {
                $activity->type = 'accept';
                $activity->message = 'is now following you!';
                $activity->save();
            }
            return DB::table('profile_user')
                ->where('user_id', request('data')['user_id'])
                ->update([
                    'status' => 'accepted',
                ]);
        } elseif (request('data')['decision'] == 'decline') {
            //decline follow request
            $activity = Activity::where('user_id', request('data')['user_id'])
                ->where('target_user_id', auth()->user()->id)
                ->where('type', 'request')
                ->first();
            if (!empty($activity)) {
                $activity->delete();
            }
            //remove pivot table
            $user = User::find(request('data')['user_id']);
            return $user->following()->toggle(auth()->id());
        }
    }
    public function removeFollower(User $user)
    {
        $this->authorize('update', $user->profile);
        $activities = Activity::whereIn('user_id', [$user->id, auth()->id()])
            ->whereIn('target_user_id', [$user->id, auth()->id()])
            ->whereIn('type', ['accept'])
            ->get();
        foreach ($activities as $activity) {
            $activity->delete();
        }
        return $user->following()->toggle(auth()->id());
    }
}
