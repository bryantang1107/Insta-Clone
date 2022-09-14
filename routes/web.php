<?php

use App\Http\Controllers\FollowsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Mail\NewUserWelcomeMail;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//in the web.php we handle user request from browser url 
//we return blade file rather than vue components
//the blade file will include vue components
//we must register the components in the resources/js/app.js


Route::get('/about',function(){
    return view('about');
});

Route::get('/lmao', function () {
    return 23;
});



Auth::routes();

//all these routes follow the restful laravel controllers concept

Route::get('/email',function(){
    return new NewUserWelcomeMail();
});

Route::get('/',[PostController::class, 'index']);

Route::post('/follow/{user}',[FollowsController::class,'store'])->name('follow.store');

Route::get('/profile/{user}', [UserController::class, 'index'])->name('profile.show');

Route::get('/p/create',[PostController::class, 'create'])->name('post.create');

Route::post('/p',[PostController::class, 'store'])->name('store');

Route::get('/p/{post}',[PostController::class, 'show'])->name('post.show');

Route::get('/profile/{user}/edit',[UserController::class, 'edit'])->name('profile.edit');

Route::put('/profile/{user}',[UserController::class, 'update'])->name('profile.update');