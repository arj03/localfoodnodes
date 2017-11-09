<?php

Route::group(['middleware' => 'auth:api', 'prefix' => '/users'], function () {
    Route::get('/', 'Api\v1\Users\UsersController@users')->middleware(['scope:users-read-all,users-read-self']);
    Route::put('/', 'Api\v1\Users\UsersController@update')->middleware(['scope:users-modify']);
    Route::get('/nodes', 'Api\v1\Users\UsersController@nodes')->middleware(['scopes:users-nodes-read']);
    Route::get('/orders', 'Api\v1\Users\OrdersController@orders')->middleware(['scopes:users-orders-read']);
});
