<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('admin')->group(function() {
    Route::get('tag/childs/{level}/{id}', "Admin\TagController@getTagChilds");
    Route::prefix('post')->group(function() {
        Route::delete('{id}','Admin\PostController@deletePost');
        Route::delete('tag/{id}', "Admin\PostController@removePostTag");
    });
});
