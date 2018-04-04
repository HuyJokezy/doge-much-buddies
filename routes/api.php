<?php

use Illuminate\Http\Request;

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
Route::middleware('auth:api')->get('/user/dogs', function (Request $request) {
    $user = $request->user();
    return $user->dogs()->get();
});
Route::middleware('auth:api')->get('/user/follows', function (Request $request) {
    $user = $request->user();
    return $user->follows()->get();
});
Route::get('/post/{id}/post_react', function ($id) {
    $post = App\Post::find($id);
    return $post->reactedBy()->get();
});
Route::get('post/{id}/post_comments', function ($id) {
    $post = App\Post::find($id);
    return $post->comments()->get();
});
Route::middleware('auth:api')->get('/user/dog/{id}/images', function ($id) {
    $dog = App\Dog::find($id);
    return $dog->images()->get();
});