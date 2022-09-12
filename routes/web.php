<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about',function(){
    return view('about');
});

Route::get('/lmao', function () {
    return 23;
});



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
