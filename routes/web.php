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

/* Route::get('blog/{group}', 'postController@index')->name('blog.index')->middleware('verified'); */
Route::resource('{group}/blog', 'postController')->middleware('verified');

Route::resources([
  'comment'=> 'CommentController',
  'user_page'=> 'UserController',
  'admin' => 'AdminController',
  'group' => 'GroupController'
]);

Route::post('group/{group}', 'GroupController@searchResult')->name('group.searchResult');

Route::post('admin/{admin}', 'AdminController@searchResult')->name('admin.searchResult');

Route::post('group/{group}/{user}', 'GroupController@join')->name('group.join');

Route::get('/', 'WelcomeController@index')->name('welcome');

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');
