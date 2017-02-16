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

Route::get('user/current', 'UserController@getCurrent');
Route::post('user/update-info', 'UserController@updateInfo');
Route::post('user/update-password', 'UserController@updatePassword');
Route::post('user/upload-picture', 'UserController@uploadPicture');
Route::get('user/delete-picture', 'UserController@deleteProfilePicture');
