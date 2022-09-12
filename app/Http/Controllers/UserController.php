<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index($user){
        $user = User::findOrFail($user); 
        //if fail it will return a 404 error
        return view('profile.home',[
            'user'=>$user,
        ]);
    }
}
