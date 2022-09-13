<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(\App\Models\User $user){
        return view('profile.home',compact('user')); //compact --> match the string with the var name 
        //(access using the same string in the front end)

        //this user has all the relationships to other table
        //defined in the modal class
        //this variable have access to all the methods in the modal
    }
    public function edit(\App\Models\User $user){
        $this->authorize('update',$user->profile);
        // $this->authorize('update',$user->profile); //authorize if user can update on a provided profile
        //user will not be able to view the edit page on profile that does not belong to them
        return view('profile.edit', compact('user'));
    }

    public function update(\App\Models\User $user){
        $this->authorize('update',$user->profile);
        $data = request()->validate([
            'title' => 'required',
            'description' => 'required',
            'url' => 'url' //a validator that validates url
        ]);

        //only authenticated user can update
        auth()->user()->profile->update($data); //update profile with validated new data
        // $user = \App\Models\User::find($user_id); //is equivalent to the type hint (\App\Models\User $user)
        
        return redirect('/profile/' . auth()->user()->id);
        
    }
}