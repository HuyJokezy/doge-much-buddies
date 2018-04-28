<?php

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
    return view('welcome');
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

Route::match(['put', 'patch'], '/postReact/{postReact}', 'PostReactController@update')->name('postReact.update');
Route::post('/postReact', 'PostReactController@store')->name('postReact.store');
Route::delete('/postReact/{postReact}', 'PostReactController@destroy')->name('postReact.destroy');