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

/* route to show all posts to visitors  */
Route::resource('posts', PostController::class)->only(['index', 'show']);

/* route to show the guest.contacts view  */
Route::get('contacts', 'PageController@contacts')->name('guest.contacts');
/* route to send the form */
Route::post('contacts', 'PageController@sendContactForm')->name('guest.contacts.send');

//route to block the recordings of other users
/* Auth::routes(['register' => false]); */
Auth::routes();



Route::namespace('Admin')->prefix('admin')->name('admin.')->middleware('auth')->group(function(){
    
    
    /* Route DashBoard */
    Route::get('/', 'PageController@index')->name('dashboard');
    /* Route Posts */
    Route::resource('posts', PostController::class);
    /* Route Categories */
    Route::resource('categories', CategoryController::class);
    /* Route tags */
    Route::resource('tags', TagController::class);
});


/* route to show all categories  */
Route::get('categories/{category:slug}/posts', 'CategoryController@posts')->name('categories.posts');

/* route to show all tags */

Route::get('tags/{tag:slug}/posts', 'TagController@posts')->name('tags.posts');