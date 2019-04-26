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

Route::get("/tags", "Common\TagController@getTags");
Route::prefix('admin')->group(function() {
    Route::prefix('tag')->group(function() {
        Route::get('childs/{level}/{id}', "Admin\TagController@getTagChilds");
        Route::delete('{level}/{id}', "Admin\TagController@deleteTag");
    });
    Route::prefix('post')->group(function() {
        Route::get('{id}', 'Admin\PostController@getPostInfo');
        Route::delete('{id}','Admin\PostController@deletePost');
        Route::delete('tag/{id}', "Admin\PostController@removePostTag");
        Route::put('publish/{id}', "Admin\PostController@publishPost");
    });
});
