<?php

Route::group(['middleware' => 'auth:api', 'prefix' => '/users'], function () {
    Route::get('/', 'Api\v1\Users\UsersController@users')->middleware(['scope:users-read-all']);
    Route::get('/self', 'Api\v1\Users\UsersController@self')->middleware(['scope:users-read-self']);
    Route::put('/', 'Api\v1\Users\UsersController@update')->middleware(['scope:users-modify']);
    Route::get('/nodes', 'Api\v1\Users\NodesController@nodes')->middleware(['scopes:users-nodes-read']);
    Route::get('/orders', 'Api\v1\Users\OrdersController@orders')->middleware(['scopes:users-orders-read']);

    Route::get('/self/cart', 'Api\v1\Users\CartController@getCart')->middleware(['scopes:users-cart']);
    Route::post('/self/cart', 'Api\v1\Users\CartController@addToCart')->middleware(['scopes:users-cart']);
    Route::put('/self/cart', 'Api\v1\Users\CartController@updateCart')->middleware(['scopes:users-cart']);
    Route::delete('/self/cart', 'Api\v1\Users\CartController@removeFromCart')->middleware(['scopes:users-cart']);
});
