<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('articles', 'App\Http\Controllers\ArticleController@index');
Route::get('articles/{article}', 'App\Http\Controllers\ArticleController@show');
Route::post('articles', 'App\Http\Controllers\ArticleController@store');
Route::put('articles/{article}', 'App\Http\Controllers\ArticleController@update');
Route::delete('articles/{article}', 'App\Http\Controllers\ArticleController@delete');


Route::get('blog/list', 'App\Http\Controllers\BlogController@list');
Route::get('blog/search', 'App\Http\Controllers\BlogController@search');

Route::get('blog/{blog_id}', 'App\Http\Controllers\BlogController@get');

Route::post('blog/create', 'App\Http\Controllers\BlogController@store');

Route::post('blog/{blog_id}', 'App\Http\Controllers\BlogController@update');

Route::delete('blog/{blog_id}', 'App\Http\Controllers\BlogController@delete');


