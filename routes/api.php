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

 Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); 

/**
 * Rotta gestita da un controller 
 * php artisan make:controller Api/PostController -rm Models/Post
 */
Route::get('posts', 'Api\PostController@index');

Route::get('posts/{post}', 'Api\PostController@show');