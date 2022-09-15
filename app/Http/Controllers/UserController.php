<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function index(User $user)
    {
        //check if the request's route's name is called 'profile.show'
        // request()->route()->named('profile.show')

        // Note: request() can replace Request $request (in the params)

        //view user profile
        //check if user that views is authenticated, if true then check if viewer is following the viewed user profile
        $follows = auth()->user()
            ? auth()
                ->user()
                ->following->contains($user->id)
            : false;
        $followerCount = Cache::remember(
            'count.follower' . $user->id,
            now()->addSeconds(30),
            function () use ($user) {
                return $user->profile->followers->count();
            }
        );
        //now()->addSeconds(30) expiration time
        //the callback function will run if the key 'count.follower' is not there
        $followingCount = Cache::remember(
            'count.following' . $user->id,
            now()->addSeconds(30),
            function () use ($user) {
                return $user->following->count();
            }
        );
        return view(
            'profile.home',
            compact('user', 'follows', 'followerCount', 'followingCount')
        ); //compact --> match the string with the var name
        //(access using the same string in the front end)

        //this user has all the relationships to other table
        //defined in the modal class
        //this $user variable have access to all the methods in the modal
    }
    public function edit(User $user)
    {
        //$user is the viewed user
        $this->authorize('update', $user->profile);
        // $this->authorize('update',$user->profile); //authorize if user can update on a provided profile
        //user will not be able to view the edit page on profile that does not belong to them
        return view('profile.edit', compact('user'));
    }

    public function update(User $user)
    {
        //in oop php, we pass function as string
        $this->authorize('update', $user->profile); //refer to profilepolicy
        $data = request()->validate([
            'title' => 'required',
            'description' => 'required',
            'url' => 'url', //a validator that validates url
            'image' => 'image', //validate that its image
        ]);
        if (request('image')) {
            $imagePath = request('image')->store('profile', 'public');
            $image = Image::make(public_path("storage/$imagePath"))->fit(
                150,
                150
            );
            $image->save();
            $imageArray = ['image' => $imagePath];
        }
        //$data has array of key image, we use merge to override the image key with image paths
        $user->profile()->update(array_merge($data, $imageArray ?? [])); //update profile with validated new data
        // $user = \App\Models\User::find($user_id); //is equivalent to the type hint (\App\Models\User $user)
        return redirect('/profile/' . auth()->user()->id);
    }
}
