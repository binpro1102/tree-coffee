<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;


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



// CRUD table brands
Route::get('brand', [BrandController::class, 'brandList']);
Route::post('brand', [BrandController::class, 'create']);
Route::get('brand/{id}', [BrandController::class, 'show']);
Route::put('brand/{id}', [BrandController::class, 'update']);
Route::delete('brand/{id}', [BrandController::class, 'destroy']);


 Route::post('users/update-role', 'App\Http\Controllers\UserController@updateRole');
 Route::post('users', 'App\Http\Controllers\UserController@update');



