<?php

use App\Http\Controllers\Admin\AdminCategoryController as AdminAdminCategoryController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\CategoryController;
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
    /* Route Categories */
    Route::get('admin/categories/index', 'Admin\CategoryController@index')->name('admin.categories.index');
});


/* route to show all categories  */
Route::get('categories/{category:slug}/posts', 'CategoryController@posts')->name('categories.posts');