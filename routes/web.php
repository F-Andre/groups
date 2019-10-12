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

Route::resource('{groupName}/posts', 'postController')->middleware('verified');

Route::resource('comment', 'CommentController')->middleware('verified');

Route::resources(['user_page'=> 'UserController']);

Route::resource('group', 'GroupController')->middleware('verified');
Route::post('group/{groupName}/searchGroup', 'GroupController@searchResult')->name('group.searchResult')->middleware('verified');
Route::post('group/{groupName}/{userId}', 'GroupController@joinDemand')->name('group.joinDemand')->middleware('verified');
Route::post('invitMember/{groupName}/{userId}', 'GroupController@invitMember')->name('group.invitMember')->middleware('verified');
Route::post('contactModal/{groupName}/{userId}', 'GroupController@contactModal')->name('group.contactModal')->middleware('verified');

Route::resource('{groupName}/admin', 'AdminController')->middleware('verified');
Route::post('{groupName}/admin/{admin}', 'AdminController@searchResult')->name('admin.searchResult')->middleware('verified');
Route::post('{groupName}/deregisterUser', 'AdminController@deregisterUser')->name('admin.deregisterUser')->middleware('verified');
Route::post('{groupName}/joinGroup', 'AdminController@joinGroup')->name('admin.joinGroup')->middleware('verified');
Route::post('{groupName}/warnUser', 'AdminController@warnUser')->name('admin.warnUser')->middleware('verified');
Route::post('{groupName}/adminSwitch', 'AdminController@adminSwitch')->name('admin.adminSwitch')->middleware('verified');

Route::get('/', 'WelcomeController@index')->name('welcome');
Route::get('cookies', 'WelcomeController@cookies')->name('cookies');

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');
