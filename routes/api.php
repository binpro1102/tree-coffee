<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\RestaurantImageController;


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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('register', 'App\Http\Controllers\AuthController@register');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::get('user-profile', 'App\Http\Controllers\AuthController@userProfile');



});

// user
Route::post('users/update-role', 'App\Http\Controllers\UserController@updateRole');
Route::post('users', 'App\Http\Controllers\UserController@update');

// CRUD table brands
Route::get('brand', [BrandController::class, 'brandList']);
Route::post('brand', [BrandController::class, 'create']);
Route::get('brand/{id}', [BrandController::class, 'show']);
Route::put('brand/{id}', [BrandController::class, 'update']);
Route::delete('brand/{id}', [BrandController::class, 'destroy']);






// // CRUD table restaurant
Route::get('restaurant', [RestaurantController::class, 'restaurantList']);
Route::post('restaurant', [RestaurantController::class, 'create']);
Route::get('restaurant/{id}', [RestaurantController::class, 'show']);
Route::put('restaurant/{id}', [RestaurantController::class, 'update']);
Route::delete('restaurant/{id}', [RestaurantController::class, 'destroy']);

// // CRUD table restaurant_image
Route::get('restaurant_img', [RestaurantImageController::class, 'RestaurantImageList']);
Route::post('restaurant_img', [RestaurantImageController::class, 'create']);
Route::get('restaurant_img/{id}', [RestaurantImageController::class, 'show']);
Route::put('restaurant_img/{id}', [RestaurantImageController::class, 'update']);
Route::delete('restaurant_img/{id}', [RestaurantImageController::class, 'destroy']);


// CRUD table blog
Route::get('blog/list', 'App\Http\Controllers\BlogController@list');
Route::get('blog/search', 'App\Http\Controllers\BlogController@search');
Route::get('blog', 'App\Http\Controllers\BlogController@get');
Route::post('blog/create', 'App\Http\Controllers\BlogController@store');
Route::post('blog/update', 'App\Http\Controllers\BlogController@update');
Route::delete('blog', 'App\Http\Controllers\BlogController@delete');

