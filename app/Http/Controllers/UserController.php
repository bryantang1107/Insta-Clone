<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function index(User $user)
    {
        //view user profile
        //check if user that views is currently following the profile
        $follows = auth()->user()
            ? auth()
                ->user()
                ->following->contains($user->profile)
            : 0;
        $followerCount = $user->profile->followers->count();
        $followingCount = $user->following->count();
        if (auth()->user()) {
            $followRequest = auth()
                ->user()
                ->requestFollow->contains($user->id);
            return view(
                'profile.home',
                compact(
                    'user',
                    'follows',
                    'followerCount',
                    'followingCount',
                    'followRequest'
                )
            );
        } else {
            return view(
                'profile.home',
                compact('user', 'follows', 'followerCount', 'followingCount')
            );
        }

        //compact --> match the string with the var name
    }
    public function edit(User $user)
    {
        $this->authorize('update', $user->profile);
        // check if user is authorized to update profile
        return view('profile.edit', compact('user'));
    }

    public function update(User $user)
    {
        $this->authorize('update', $user->profile);
        $data = request()->validate([
            'title' => 'required',
            'description' => 'required',
            'url' => 'url', //a validator that validates url
            'image' => 'image', //validate that its image
            'is_private' => '',
        ]);
        $data['is_private'] = !empty($data['is_private']) ? 1 : 0;
        if (request('image')) {
            $imagePath = request('image')->store('profile', 'public');
            $image = Image::make(public_path("storage/$imagePath"))->fit(
                150,
                150
            );
            $image->save();
            $imageArray = ['image' => $imagePath];
        }
        $user->profile()->update(array_merge($data, $imageArray ?? []));
        return redirect('/profile/' . auth()->user()->id);
    }
    public function getUser($search)
    {
        $users = DB::table('users')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('username', 'LIKE', "%{$search}%")
            ->orWhere('name', 'LIKE', "%{$search}%")
            ->select('users.username', 'profiles.image', 'users.id')
            ->get();
        return $users;
    }

    public function destroy(User $user)
    {
        $user->delete();
        Session::flush();
        Auth::logout();
        return redirect('/login');
    }

    public function getActivityFollow()
    {
        $id = auth()->user()->id;
        return Activity::join('users', 'activities.user_id', '=', 'users.id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('activities.target_user_id', $id)
            ->where('activities.type', 'request')
            ->orderBy('activities.type', 'DESC')
            ->select(
                'profiles.image',
                'activities.message',
                'activities.type',
                'users.username',
                'users.id'
            )
            ->orderBy('activities.created_at', 'DESC')
            ->get();
    }
    public function getActivities()
    {
        $id = auth()->user()->id;
        return Activity::join('users', 'activities.user_id', '=', 'users.id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('activities.target_user_id', $id)
            ->where('activities.type', '!=', 'request')
            ->select(
                'profiles.image',
                'activities.message',
                'activities.type',
                'users.username',
                'users.id',
                'activities.post_id'
            )
            ->orderBy('activities.created_at', 'DESC')
            ->get();
    }

    public function getFollowers(User $user)
    {
        if (!auth()->user()) {
            return $user->profile->followers;
        }
        $followers = $user->profile->followers->pluck('profile');

        $helper = new Helper();

        $data = $helper->getUser($followers);
        foreach ($data as $index => $item) {
            $user = User::find($item->user_id);
            if (!empty($user)) {
                $data[$index]['user'] = $user;
                //alternative
                //$data[$index]->user = $user;
            }
        }

        return $data;
    }

    public function getFollowing(User $user)
    {
        if (!auth()->user()) {
            return $user->following;
        }
        $followings = $user->following;
        $helper = new Helper();
        $data = $helper->getUser($followings);
        return $data;
    }
}
