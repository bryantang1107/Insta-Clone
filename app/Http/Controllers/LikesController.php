<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LikesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //every single route in this class will require 
        //authorization to be able to access
    }
    public function store(\App\Models\Post $post){
        //store followers
        return auth()->user()->liking()->toggle($post);
        //returns an array of attach and detach (relationships)
    }
}