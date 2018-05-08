<?php

use App\User;

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
    // $user = \Auth::user();
    return redirect()->route('home');
    // return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('user', 'UserController');
Route::get('/user/{user}/myDog', 'UserController@myDog')->name('user.myDog');
Route::get('/user/{user}/myFriend', 'UserController@myFriend')->name('user.myFriend');

// Dog route
Route::resource('dog', 'DogController');

// Post route
Route::resource('post', 'PostController');

// PostReact route
Route::match(['put', 'patch'], '/post/{id}/post_reacts', 'PostReactController@update')->name('postReact.update');
Route::post('/post/{id}/post_reacts', 'PostReactController@store')->name('postReact.store');
Route::delete('/post/{id}/post_reacts', 'PostReactController@destroy')->name('postReact.destroy');

// PostComment route
Route::match(['put', 'patch'], '/post/{id}/post_comments/{cmtid}', 'PostCommentController@update')->name('postComment.update');
Route::post('/post/{id}/post_comments', 'PostCommentController@store')->name('postComment.store');
Route::delete('/post/{id}/post_comments/{cmtid}', 'PostCommentController@destroy')->name('postComment.destroy');

// Friend route
Route::get('/friend/requests', 'FriendController@requests')->name('friend.requests');
Route::post('/user/{target}/addFriend', 'FriendController@addFriend')->name('user.addFriend');
Route::post('/friend/response/{target}', 'FriendController@response')->name('friend.response');