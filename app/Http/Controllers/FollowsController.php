<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        //returns an array of attach and detach
    }
}
