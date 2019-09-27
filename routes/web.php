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

Route::post('{group}/admin/{admin}', 'AdminController@searchResult')->name('admin.searchResult')->middleware('verified');
Route::post('{group}/deregisterUser', 'AdminController@deregisterUser')->name('admin.deregisterUser')->middleware('verified');
Route::post('{group}/joinGroup', 'AdminController@joinGroup')->name('admin.joinGroup')->middleware('verified');
Route::post('{group}/warnUser', 'AdminController@warnUser')->name('admin.warnUser')->middleware('verified');
Route::post('{group}/adminSwitch', 'AdminController@adminSwitch')->name('admin.adminSwitch')->middleware('verified');

Route::post('group/{group}/{user}', 'GroupController@joinDemand')->name('group.joinDemand')->middleware('verified');

Route::get('/', 'WelcomeController@index')->name('welcome');

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');
