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

/***************************************************************************************
 ** AUTHENTICATION
 ***************************************************************************************/

Route::auth();
Route::get('user/confirm-email/{token}', 'UserController@confirmEmail');

Route::get('/', function () {
    return view('welcome');
});
Route::get('/{catchall?}', function () {
    return response()->view('main.index');
})->where('catchall', '(.*)');

Auth::routes();

Route::get('/home', 'HomeController@index');
