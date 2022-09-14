<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{   
    public function __construct()
    {
        $this->middleware('auth');
        //every single route in this class will require 
        //authorization to be able to access
    }

    public function index(){
        //get all the following users of current user (auth->user)
        
        $users = auth()->user()->following()->pluck('profiles.user_id'); //get only the user id from profile
        if($users->count() > 0){
            // $posts = \App\Models\Post::where('user_id',$users)->latest()->get(); //chronological order
            //we can do pagination
            $posts = \App\Models\Post::whereIn('user_id',$users)->with('user')->latest()->paginate(5);
            //load it with user
            //with('user') is talking about the relationship (post model --> user() method)

            return view('welcome',compact('posts'));
        }else{
            $posts = [];
            return view('welcome',compact('posts'));
        }
        
    }

    //prevent user from the create()/store() method if they are unauthenticated
    public function create(){
        return view('posts.create');
    }

    public function store(){
        //without validation
        // $data = request()->all();
        //request('image'); --> to get specific input
        // dd($data);    
        // to view the request and its' data after posting
        //for each <input> we need to give it name to be able to access

        //with validation
        //for post method we need to validate our request before storing
        //return data after validation
        $data = request()->validate([
            'caption' => 'required',
            'image' => ['required','image'],
        ]);
        //store image to uploads folder (local storage, local proj)
        //this will return our file path
        $imagePath = request('image')->store('uploads','public');
        //we want to save this $imagepath to our db

        //resize image before saving to db
        $image = Image::make(public_path("storage/$imagePath"))->fit(1200,1200);
        //fit --> exclude any img portion that exceeds 1200
        //make = make our image wrapped around intervention

        $image->save(); //save the image, it will modify the image (specified img path)


        //get authenticated user, get the posts table and create
        //laravel will automatically add the user id into the post
        auth()->user()->posts()->create([
            'caption' => $data['caption'],
            'image' => $imagePath,
        ]);//this will ensure that the post belongs to the user who created it
        //if we are not signed in it will fail (cannot get userid)

        //this does not work, because we dont have reference to user id
        // \App\Models\Post::create($data);

        //redirect to user's profile
        return redirect('/profile/' . auth()->user()->id);
        
    }
    // public function show($id){
    //     //we can find the post manually
    //     //or use the feature provided by laravel : route model binding
    //     //to use RMB: type hint the variable
    // }

    //RMB
    public function show(\App\Models\Post $post){
        $follows = auth()->user() ? auth()->user()->following->contains($post->user_id) : false;
        //laravel will fetch our post based on id automatically
        //automatically does findorfail for us
        return view('posts.view', compact('post','follows'));

        //compact('post') --> will match any variable that has the string
        //$post matched
        //compact('post) is equivalent to
        // [
        //     'post' => $post
        // ]
    }

   
}