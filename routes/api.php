<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\OrderDetailController;
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
Route::get('brand/list', [BrandController::class, 'brandList']);
Route::post('brand/create', [BrandController::class, 'create']);
Route::get('brand', [BrandController::class, 'show']);
Route::put('brand/update', [BrandController::class, 'update']);
Route::delete('brand', [BrandController::class, 'destroy']);






// // CRUD table restaurant
Route::get('restaurant/list', [RestaurantController::class, 'restaurantList']);
Route::post('restaurant/create', [RestaurantController::class, 'create']);
Route::get('restaurant', [RestaurantController::class, 'show']);
Route::put('restaurant/update', [RestaurantController::class, 'update']);
Route::delete('restaurant', [RestaurantController::class, 'destroy']);

// // CRUD table restaurant_image
Route::get('restaurant-image/list', [RestaurantImageController::class, 'RestaurantImageList']);
Route::post('restaurant-image/create', [RestaurantImageController::class, 'create']);
Route::get('restaurant-image', [RestaurantImageController::class, 'show']);
Route::put('restaurant-image/update', [RestaurantImageController::class, 'update']);
Route::delete('restaurant-image', [RestaurantImageController::class, 'destroy']);

// CRUD table payment
Route::get('payment/list', [PaymentController::class, 'paymentList']);
Route::post('payment/create', [PaymentController::class, 'create']);
Route::get('payment', [PaymentController::class, 'show']);
Route::put('payment/update', [PaymentController::class, 'update']);
Route::delete('payment', [PaymentController::class, 'destroy']);

// CRUD table order
Route::get('order/list', [OrderController::class, 'orderList']);
Route::post('order/create', [OrderController::class, 'create']);
Route::get('order', [OrderController::class, 'show']);
Route::put('order/update', [OrderController::class, 'update']);
Route::delete('order', [OrderController::class, 'destroy']);

// CRUD table product
Route::get('product/list', 'App\Http\Controllers\ProductController@list');
Route::get('product/search', 'App\Http\Controllers\ProductController@search');
Route::get('product', 'App\Http\Controllers\ProductController@get');
Route::post('product/create', 'App\Http\Controllers\ProductController@store');
Route::post('product/update', 'App\Http\Controllers\ProductController@update');
Route::delete('product', 'App\Http\Controllers\ProductController@delete');

// CRUD table order-detail
Route::get('order-detail/list', [OrderDetailController::class, 'orderDetailList']);
Route::post('order-detail/create', [OrderDetailController::class, 'create']);
Route::get('order-detail', [OrderDetailController::class, 'show']);
Route::put('order-detail/update', [OrderDetailController::class, 'update']);
Route::delete('order-detail', [OrderDetailController::class, 'destroy']);


