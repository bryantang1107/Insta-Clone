<?php

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get("/test",function(){
    return "Testing";
});

// Route::get('/products',[ProductController::class,'show']);

// Route::get('/products',function(){
//     return Product::all();
// });
// Route::post('/products',function(){
//     return Product::create([
//         'name' => 'Product One',
//         'slug' => 'p1',
//         'description' => 'This is product one',
//         "price" => 300
//     ]);
// });


Route::resource('products',ProductController::class);
Route::get('/products/search/{name}',[ProductController::class,'search']);

// Route::get('/products',[ProductController::class,'index']);
Route::post('/products',[ProductController::class,'store']);
Route::delete('/products/{id}',[ProductController::class,'destroy']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
