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

Route::get('/', 'Common\PostController@getHome');
Route::get("post/{route}", "Common\PostController@getPostDetail");

Route::prefix('admin')->group(function () {
    Route::get('/notpublish/{page?}', 'Admin\AdminController@showNotPublishPost')->middleware('admin');
    Route::get('/login', 'Admin\AdminController@showLoginForm')->middleware('admin-home');
    Route::post('/login', 'Admin\AdminController@login');
    Route::get('/logout', 'Admin\AdminController@logout');
    Route::get('/search', 'Admin\AdminController@search')->middleware('admin');
    Route::prefix('post')->group(function () {
        Route::get('info/{id}', 'Admin\PostController@showPostInfo')->middleware('admin');
        Route::post('info/{id}', 'Admin\PostController@updatePostInfo')->middleware('admin');
        Route::get('create', 'Admin\PostController@showCreatePostForm')->middleware('admin');
        Route::post('create', 'Admin\PostController@createPost')->middleware('admin');
        Route::get('review/{id}', 'Admin\PostController@reviewPost')->middleware('admin');
        Route::get('{id}/tags', 'Admin\PostController@showPostTags')->middleware('admin');
        Route::post('{id}/tags', 'Admin\PostController@addPostTag')->middleware('admin');
    });
    Route::prefix('tag')->group(function() {
        Route::get('/', 'Admin\TagController@getTagHome')->middleware('admin');
        Route::get('/create', 'Admin\TagController@getCreateTag')->middleware('admin');
        Route::post('/create', 'Admin\TagController@createTag')->middleware('admin');
        Route::get('{level}/{id}', 'Admin\TagController@showTagInfo')->middleware('admin');
        Route::post('{level}/{id}', 'Admin\TagController@updateTagInfo')->middleware('admin');
    });
    Route::get('/{page?}', 'Admin\AdminController@getHome')->middleware('admin');
});

Route::post('upload', 'Common\UploadController@uploadImage');

Route::get('/{level1}/{level2?}/{level3?}', function() {
    return view('welcome');
});
