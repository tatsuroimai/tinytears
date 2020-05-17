<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/user', 'UserController@index')->name('user.index')->middleware('auth');

Route::get('/user/userEdit', 'UserController@userEdit')->name('user.userEdit')->middleware('auth');
Route::post('/user/userEdit', 'UserController@userUpdate')->name('user.userUpdate')->middleware('auth');

Route::get('/user/changepassword', 'UserController@showChangePasswordForm')->middleware('auth');
Route::post('/user/changepassword', 'UserController@changePassword')->name('user.changepassword')->middleware('auth');

Route::get('/user/delete', 'UserController@delete')->name('user.delete')->middleware('auth');
Route::post('/user/delete', 'UserController@remove')->name('user.remove')->middleware('auth');

Route::get('/post', 'PostController@index')->name('post.index')->middleware('auth');

Route::get('/post/add', 'PostController@add')->name('post.add')->middleware('auth');
Route::post('/post/add', 'PostController@create')->name('post.create')->middleware('auth');

Route::get('/post/show', 'PostController@show')->name('post.show')->middleware('auth');

Route::get('/post/edit', 'PostController@edit')->name('post.edit')->middleware('auth');
Route::post('/post/edit', 'PostController@update')->name('post.update')->middleware('auth');

Route::get('/post/delete', 'PostController@delete')->name('post.delete')->middleware('auth');
Route::post('/post/delete', 'PostController@remove')->name('post.remove')->middleware('auth');


Route::post('/comment/add', 'CommentController@add')->name('comment.add')->middleware('auth');