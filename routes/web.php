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

Route::resource('{group}/posts', 'postController')->middleware('verified');
Route::resource('{group}/admin', 'AdminController')->middleware('verified');

Route::resources([
  'comment'=> 'CommentController',
  'user_page'=> 'UserController',
  'group' => 'GroupController'
]);

Route::post('group/{group}', 'GroupController@searchResult')->name('group.searchResult');

Route::post('{group}/admin/{admin}', 'AdminController@searchResult')->name('admin.searchResult');

Route::post('group/{group}/{user}', 'GroupController@join')->name('group.join');

Route::get('/', 'WelcomeController@index')->name('welcome');

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');
