<?php
use App\Models\Post;
use App\User;
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


/* 
    Metodo "Lungo"
Route::get('posts', function(){
    $posts = Post::all();
    return response()->json([
        'response' => $posts
    ]);
}); */


/* 
    Senza paginazione
Route::get('posts', function(){
    $posts = Post::all();
    return $posts;
}); */

/* 
    Con la paginazione
Route::get('posts', function(){
    $posts = Post::paginate(10);
    return $posts;
}); */



/* 
    Con la paginazione e relazione users*/
Route::get('posts', function(){
    $posts = Post::with(['user'])->get();
    return $posts;
}); 