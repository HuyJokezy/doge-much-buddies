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

// Get all information of the current logged in user (except password, tokens)
Route::middleware('auth:api')->get('/user', 'API\\APIController@getUser');

// Get list of dogs of the current logged in user
Route::middleware('auth:api')->get('/user/dogs', 'API\\APIController@getOwnerDogs');

// Get list of dogs which the current logged in user follows
Route::middleware('auth:api')->get('/user/follows', 'API\\APIController@getUserFollowDogs');

// Get list of reaction of the post with post_id = {id}
Route::get('/post/{id}/post_reacts', 'API\\APIController@getPostReactions');

// Get list of dogs are tagged on the post with post_id = {id}
Route::get('/post/{id}/tags', 'API\\APIController@getDogsTaggedInPost');

// Get list of comments of the post with post_id = {id}
Route::get('post/{id}/post_comments', 'API\\APIController@getComment');

// Get list of dog's images having dog_id = {id}
// Only the owner and who follows that dog can get, else return 403
Route::middleware('auth:api')->get('/dog/{id}/images', 'API\\APIController@getDogImages');

// Get list of taggable dogs
Route::middleware('auth:api')->get('taggableDogs', 'API\\APIController@getTaggableDogs');