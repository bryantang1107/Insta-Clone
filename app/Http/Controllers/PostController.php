<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //every single route in this class will require
        //authorization to be able to access

        //or we can filter only certain routes to require auth middleware
    }

    public function index()
    {
        //get all the following users of current user (auth->user)

        $users = auth()
            ->user()
            ->following()
            ->pluck('profiles.user_id');
        //get only the user id from profile that current user if following
        if ($users->count() > 0) {
            // $posts = \App\Models\Post::where('user_id',$users)->latest()->get(); //chronological order
            //we can do pagination
            $posts = \App\Models\Post::whereIn('user_id', $users)
                ->with('user')
                ->latest()
                ->paginate(5);
            $likedPost = auth()
                ->user()
                ->liking()
                ->pluck('post_user.post_id');
            //with is for eager loading. Along the main model (Post), Laravel will preload the relationship(s) you specify (user).
            //load it with user so we can display user info (by accessing via $post->user->username)
            return view('welcome', compact('posts', 'likedPost'));
        } else {
            $posts = [];
            $likedPost = [];
            return view('welcome', compact('posts', 'likedPost'));
        }
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store()
    {
        //without validation
        // $data = request()->all();
        //request('image'); --> to get specific input
        //for each <input> we need to give it name to be able to access

        //or get only specfic request input
        //$request->only('caption');

        //with validation
        //for post method we need to validate our request before storing
        //return data (associative array) after validation
        $data = request()->validate([
            'caption' => 'required', //for unique you have to specify the model name (unique:posts)
            'image' => ['required', 'image'],
        ]);
        //store image to uploads folder (local storage, local proj)
        //this will return our file path /uploads/filename.png
        $imagePath = request('image')->store('uploads', 'public');
        //default store in storage/app, we store in storage/app/public/uploads

        //we want to save this $imagepath to our db

        //get the image from public/storage/imagepath and resize it
        //(to be publicly accessible: run php artisan storage:link) -->link local storage to public storage
        $image = Image::make(public_path("storage/$imagePath"))->fit(
            1200,
            1200
        );
        //fit --> crop any img portion that exceeds 1200

        $image->save(); //save the image, it will modify the image (specified img path)

        //get authenticated user, get the posts table and create
        //laravel will automatically add the user id into the post (create using auth()->user())
        auth()
            ->user()
            ->posts()
            ->create([
                //get the post model (eloquent) and create
                //(must use (), else it will return user's existing post (object property) AKA collection instead of relations)
                //we need the relationship to create a new model instance (row)
                'caption' => $data['caption'],
                'image' => $imagePath,
            ]); //this will ensure that the post belongs to the user who created it
        //foreign key automatically added
        //if we are not signed in it will fail (cannot get the foreign key aka parent key)

        //this does not work, because we dont have reference to user id
        // \App\Models\Post::create($data);

        //redirect to user's profile
        return redirect('/profile/' . auth()->user()->id);
        //or redirect to certain page
        //return redirect()->route('dashboard'); --> this is a named route
    }
    // public function show($id){
    //     //we can find the post manually
    //     //or use the feature provided by laravel : route model binding
    //     //to use RMB: type hint the variable
    // }

    //RMB
    public function show(\App\Models\Post $post)
    {
        //we can use contains (function offered by collection)
        $follows = auth()->user()
            ? auth()
                ->user()
                ->following->contains($post->user_id)
            : false;
        //check if the current post is liked by current user
        $likedPost = auth()->user()
            ? auth()
                ->user()
                ->liking->contains($post->id)
            : false;

        $likeCount = Cache::remember(
            'count.like' . auth()->user(),
            now()->addSeconds(30), //30 seconds cache
            function () use ($post) {
                return $post->likes->count();
            }
        );
        $commentCount = Cache::remember(
            'count.comment' . auth()->user(),
            now()->addSeconds(30), //30 seconds cache
            function () use ($post) {
                return $post->comments->count();
            }
        );
        //laravel will fetch our post based on id automatically
        //automatically does findorfail for us
        return view(
            'posts.view',
            compact('post', 'follows', 'likedPost', 'likeCount', 'commentCount')
        );

        //compact('post') --> will match any variable that has the string
        //$post matched
        //compact('post) is equivalent to
        // [
        //     'post' => $post
        // ]
    }

    public function update(\App\Models\Post $post)
    {
        $this->authorize('update', auth()->user()->profile);
        $post->update([
            'caption' => request('data')['caption'],
        ]);
        return;
    }

    public function destroy(\App\Models\Post $post)
    {
        $this->authorize('update', auth()->user()->profile);
        $post->delete();
        return;
    }
}
