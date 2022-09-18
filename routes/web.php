<?php

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

Route::get('/lmao', function () {
    return 23;
});

Auth::routes();

//all these routes follow the restful laravel controllers concept

Route::get('/email', function () {
    return new NewUserWelcomeMail();
});

Route::get('/', [PostController::class, 'index']);

//route names should always be unique
/*
Why use route names?

Generating URLs...
$url = route('profile');
 
Generating Redirects...
return redirect()->route('profile');

Generating Navigation (navigate to this route explicitly)
return to_route('profile');

For route name with parameters, pass in via second parameter:
$url = route('profile',['id' => 1]);
*/
Route::post('/follow/{user}', [FollowsController::class, 'store'])->name(
    'follow.store'
);

Route::post('/likes/{post}', [LikesController::class, 'store']);

Route::get('/profile/{user}', [UserController::class, 'index'])->name(
    'profile.show'
);

Route::get('/p/create', [PostController::class, 'create'])->name('post.create');

Route::post('/p', [PostController::class, 'store'])->name('store');

Route::get('/p/{post}', [PostController::class, 'show'])->name('post.show');

Route::get('/profile/{user}/edit', [UserController::class, 'edit'])->name(
    'profile.edit'
);

Route::put('/profile/{user}', [UserController::class, 'update'])->name(
    'profile.update'
);

// Route::get('/about',function(){
//     return view('about');
// });

//for route that only returns view, accepts third parameter (data)
Route::view('/about', 'about');

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

Route::get('/user/{id}', function (Request $request, $id) {
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