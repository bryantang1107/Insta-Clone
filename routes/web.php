<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikesController;
use App\Mail\NewUserWelcomeMail;
use Illuminate\Support\Facades\Auth;

/*
These routes are loaded by the RouteServiceProvider within a group which
contains the "web" middleware group. Now create something great!
*/

Auth::routes(); //generate all the routes for user authentication
Route::view('/about', 'about');
Route::get('/email', function () {
    return new NewUserWelcomeMail();
});

Route::controller(PostController::class)->group(function () {
    Route::get('/', [PostController::class, 'index']);
    Route::prefix('/p')->group(function () {
        Route::get('/create', 'create');
        Route::post('', 'store');
        Route::get('/{post}', 'show');
        Route::post('/{post}', 'update'); //update caption
        Route::delete('/{post}', 'destroy'); //delete post
    });
});
//for route that only returns view, accepts third parameter (data)

//Like/Unlike User Post
Route::post('/likes/{post}', [LikesController::class, 'store']);

Route::controller(UserController::class)->group(function () {
    Route::prefix('/profile')->group(function () {
        Route::get('/{user}', 'index'); //get user profile
        Route::get('/{user}/followers', 'getFollowers'); //get user profile's followers
        Route::get('/{user}/following', 'getFollowing'); //get user profile's following
        Route::get('/{user}/edit', 'edit'); //get edit profile view
        Route::put('/{user}', 'update'); //update user profile
        Route::delete('/{user}', 'destroy'); //delete account
    });
    Route::prefix('/user/activity')->group(function () {
        Route::get('/follow', 'getActivityFollow');
        Route::get('/all', 'getActivities');
    });
    //Search for user (input)
    Route::get('/user/{search}', [UserController::class, 'getUser']);
});

Route::controller(FollowsController::class)->group(function () {
    //Follow/Unfollow User
    Route::post('/follow/{user}', 'store');
    //Accept or Decline Following Request
    Route::put('/user/follow', 'update');
    //Remove Follower
    Route::delete('/profile/remove/{user}', 'removeFollower');
});

Route::controller(CommentController::class)->group(function () {
    Route::get('/p/comment/{post}', 'index');
    Route::post('/p/comment/{post}', 'store');
});

/*
Route::get('/example/{some}/{param}',function($param1, $param2){
    //param1 = some, param2 = param (based on their order)
    // Route parameters are injected into route callbacks or controllers
    //the names of the route callback / controller arguments do not matter.(good to follow naming convention)
})*/

//parameters and dependency injection

/*
Dependencies- an essential functionality, library or piece of code that's essential for a different part of the code to work

If your route has dependencies that you would like the Laravel service container to automatically 
inject into your route's callback/controller, you should list your route parameters after your dependencies:

Route::get('/user/{id}', function (Request $request (dependency injection), $id (parameters)) {
    return 'User '.$id;
});*/

/*optional parameter
Make sure to give the route's corresponding variable a default value:

Route::get('/user/{name?}', function ($name = null/'John') {
    return $name;
});

*/

/*regular expression constraints
You may constrain the format of your route parameters using the where method on a route instance. 
The where method accepts the name of the parameter and a 
regular expression defining how the parameter should be constrained:

Route::get('/user/{name}', function ($name) {
    //
})->where('name', '[A-Za-z]+');
 
Route::get('/user/{id}', function ($id) {
    //
})->where('id', '[0-9]+');
 
Route::get('/user/{id}/{name}', function ($id, $name) {
    //
})->where(['id' => '[0-9]+', 'name' => '[a-z]+']);

Helper function (for regularly used regular expression)
Route::get('/user/{id}/{name}', function ($id, $name) {
    //
})->whereNumber('id')->whereAlpha('name');
 
Route::get('/user/{name}', function ($name) {
    //
})->whereAlphaNumeric('name');
 
Route::get('/user/{id}', function ($id) {
    //
})->whereUuid('id');
 
Route::get('/category/{category}', function ($category) {
    //
})->whereIn('category', ['movie', 'song', 'painting']);

*/

/*
Encoded forward slashes
The Laravel routing component allows all characters except 
/ to be present within route parameter values. You must explicitly allow 
/ to be part of your placeholder using a where condition regular expression:

Route::get('/search/{search}', function ($search) {
    return $search;
})->where('search', '.*');

Encoded forward slashes are only supported within the last route segment.
*/
