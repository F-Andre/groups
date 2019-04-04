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

Route::resource('blog', 'postController', ['except' => 'show']);

Route::resource('comment', 'CommentController');

Route::post('admin/{admin}', 'AdminController@searchResult')->name('admin.searchResult');
Route::resource('admin', 'AdminController');

Route::resource('user_page', 'UserController');

Route::get('/', 'WelcomeController@index')->name('welcome');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
