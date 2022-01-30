<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('guest.welcome');
})->name('guest.home');


Route::resource('posts', PostController::class)->only(['index', 'show']);


Auth::routes();



Route::namespace('Admin')->prefix('admin')->name('admin.')->middleware('auth')->group(function(){

    /* Route DashBoard */
    Route::get('/', 'PageController@index')->name('dashboard');
    /* Route Posts */
    Route::resource('posts', PostController::class);
});


/* route to show all categories  */
Route::get('categories/{category:slug}/posts', 'CategoryController@posts')->name('categories.posts');