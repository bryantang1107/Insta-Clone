<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }
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
        $posts = $user->posts()->paginate(9);
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
                    'followRequest',
                    'posts'
                )
            );
        } else {
            return view(
                'profile.home',
                compact(
                    'user',
                    'follows',
                    'followerCount',
                    'followingCount',
                    'posts'
                )
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
        return redirect('/profile/' . auth()->id());
    }
    public function getUser()
    {
        $search = request('data')['username'];
        $users = User::with('profile')
            ->where('username', 'LIKE', "%{$search}%")
            ->orWhere('name', 'LIKE', "%{$search}%")
            ->paginate(5);
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
        $id = auth()->id();
        return Activity::with('user.profile')
            ->where('target_user_id', $id)
            ->where('type', 'request')
            ->orderBy('activities.created_at', 'DESC')
            ->get();
    }
    public function getActivities()
    {
        $id = auth()->id();
        return Activity::with('user.profile')
            ->where('target_user_id', $id)
            ->where('type', '!=', 'request')
            ->orderBy('activities.created_at', 'DESC')
            ->get();
    }

    public function getFollowers(User $user)
    {
        $followers = $user->profile->followers()->paginate(5);
        $followers = $followers->pluck('profile');
        if (!auth()->user()) {
            foreach ($followers as $index => $follower) {
                $user_follower = User::find($follower->user_id);
                if (!empty($user_follower)) {
                    $followers[$index]['user'] = $user_follower;
                }
            }
            return $followers;
        }

        $data = $this->helper->getUser($followers);
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
        $followings = $user->following()->paginate(5);
        if (!auth()->user()) {
            return $followings;
        }
        $data = $this->helper->getUser($followings);
        return $data;
    }
}
