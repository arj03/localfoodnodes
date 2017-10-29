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

Route::group(['prefix' => '/users'], function () {
    Route::get('/', 'Api\UserController@index');
    Route::post('/login', 'Api\UserController@login');
    Route::get('/logout', 'Api\UserController@logout');
});

Route::group(['prefix' => '/orders'], function () {
    Route::get('/', 'Api\OrderController@index');
});
