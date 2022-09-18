<?php

namespace App\Http\Controllers;

class FollowsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //every single route in this class will require 
        //authorization to be able to access
    }
    public function store(\App\Models\User $user){
        //store followers
        return auth()->user()->following()->toggle($user->profile); 
        //M-N rs provides toggle method
        //toggle user's profile (automatically save user_id and profile_id in the pivot table)
        //to enable automatic storing of user_id naming convention of column = user_id
        //+ auth()->user(), laravel will take this user id and store in the pivot table
        //returns an array of attach and detach (relationships)
    }
}