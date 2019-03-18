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
Route::prefix('admin')->group(function () {
    Route::get('/login', 'Admin\AdminController@showLoginForm')->middleware('admin-home');
    Route::post('/login', 'Admin\AdminController@login');
    Route::get('/', 'Admin\AdminController@getHome')->middleware('admin');
    Route::get('/logout', 'Admin\AdminController@logout');
    Route::get('/create', 'Admin\AdminController@showCreatePostForm')->middleware('admin');
    Route::post('/create', 'Admin\AdminController@createPost')->middleware('admin');
    Route::prefix('tag')->group(function() {
        Route::get('/', 'Admin\TagController@getTagHome')->middleware('admin');
        Route::get('/create', 'Admin\TagController@getCreateTag')->middleware('admin');
        Route::post('/create', 'Admin\TagController@createTag')->middleware('admin');
    });
    // Route::get('/post/{id}', )
});

Route::post('upload', 'Common\UploadController@uploadImage');

Route::get("post/{route}", "Common\PostController@getPostDetail");
