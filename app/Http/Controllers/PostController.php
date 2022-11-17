<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        //or we can filter only certain routes to require auth middleware
    }

    public function index()
    {
        //get all the following users of current user (auth->user)
        $users = auth()
            ->user()
            ->following->pluck('user_id'); //specify the table_name.property to access a specific table property in the RS
        $users = array_merge($users->toArray(), [auth()->id()]);
        //get only the user id from profile that current user if following
        if (count($users) > 0) {
            // $posts = \App\Models\Post::where('user_id',$users)->latest()->get(); //chronological order
            //we can do pagination
            $posts = Post::whereIn('user_id', $users)
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
        //for each <input> we need to give it name to be able to access (blade)

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
        return redirect('/profile/' . auth()->id());
        //or redirect to certain page
        //return redirect()->route('dashboard'); --> this is a named route
    }
    // public function show($id){
    //     //we can find the post manually
    //     //or use the feature provided by laravel : route model binding
    //     //to use RMB: type hint the variable
    // }

    //RMB
    public function show(Post $post)
    {
        $this->authorize('view', $post->user->profile);
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

        $likeCount = $post->likes->count();
        $commentCount = $post->comments->count();
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

    public function update(Post $post)
    {
        $this->authorize('update', $post->user->profile);
        $post->update([
            'caption' => request('data')['caption'],
        ]);
        return;
    }

    public function destroy(Post $post)
    {
        $this->authorize('update', $post->user->profile);
        $post->delete();
        return;
    }
}
