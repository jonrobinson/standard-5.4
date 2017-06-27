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
use Illuminate\Support\Facades\Log;

/***************************************************************************************
 ** AUTHENTICATION
 ***************************************************************************************/

Route::get('user/confirm-email/{token}', 'UserController@confirmEmail');

Route::get('/', 'HomeController@index')->name('home');

Route::get('/', function () {
    log::error('is this being hit?');
    return view('welcome');
});

Route::get('/logout', function () {
    if (Auth::check()) {
        Auth::logout();
    }
    return redirect()->route('home');
});

Auth::routes();
