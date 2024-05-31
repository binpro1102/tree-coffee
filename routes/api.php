<?php

use App\Http\Controllers\OrderController;
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
Route::get('brand-list', [BrandController::class, 'brandList']);
Route::post('brand', [BrandController::class, 'create']);
Route::get('brand', [BrandController::class, 'show']);
Route::put('brand', [BrandController::class, 'update']);
Route::delete('brand', [BrandController::class, 'destroy']);






// // CRUD table restaurant
Route::get('restaurant-list', [RestaurantController::class, 'restaurantList']);
Route::post('restaurant', [RestaurantController::class, 'create']);
Route::get('restaurant', [RestaurantController::class, 'show']);
Route::put('restaurant', [RestaurantController::class, 'update']);
Route::delete('restaurant', [RestaurantController::class, 'destroy']);

// // CRUD table restaurant_image
Route::get('restaurant_img-list', [RestaurantImageController::class, 'RestaurantImageList']);
Route::post('restaurant_img', [RestaurantImageController::class, 'create']);
Route::get('restaurant_img', [RestaurantImageController::class, 'show']);
Route::put('restaurant_img', [RestaurantImageController::class, 'update']);
Route::delete('restaurant_img', [RestaurantImageController::class, 'destroy']);

// CRUD table payment
Route::get('payment-list', [PaymentController::class, 'paymentList']);
Route::post('payment', [PaymentController::class, 'create']);
Route::get('payment', [PaymentController::class, 'show']);
Route::put('payment', [PaymentController::class, 'update']);
Route::delete('payment', [PaymentController::class, 'destroy']);

// CRUD table order
Route::get('order-list', [OrderController::class, 'orderList']);
Route::post('order', [OrderController::class, 'create']);
Route::get('order', [OrderController::class, 'show']);
Route::put('order', [OrderController::class, 'update']);
Route::delete('order', [OrderController::class, 'destroy']);

