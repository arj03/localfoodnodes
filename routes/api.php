<?php

use Illuminate\Http\Request;

Route::group(['middleware' => 'auth:api', 'prefix' => '/users'], function () {
    Route::get('/', 'Api\v1\Users\UsersController@users')->middleware(['scope:users-read-all,users-read-self']);
    Route::get('/nodes', 'Api\v1\Users\UsersController@nodes')->middleware(['scopes:users-nodes-read']);

    // Route::post('/nodes', 'Api\v1\Users\UsersController@users')->middleware(['scopes:']);
    // Route::delete('/nodes/{nodeId}', 'Api\v1\Users\UsersController@users')->middleware(['scopes:']);

    // Route::get('/cart', 'Api\v1\UsersController@users')->middleware(['scopes:']);
    // Route::post('/cart', 'Api\v1\UsersController@users')->middleware(['scopes:']);
    // Route::get('/cart/{cartItemId}', 'Api\v1\UsersController@users')->middleware(['scopes:']);

    Route::get('/orders', 'Api\v1\Users\OrdersController@orders')->middleware(['scopes:users-orders-read']);

    // Route::post('/orders', 'Api\v1\UsersController@users')->middleware(['scopes:']);
    // Route::get('/orders/{orderDateItemId}', 'Api\v1\UsersController@users')->middleware(['scopes:']);

});

Route::group(['prefix' => '/nodes'], function () {
    Route::get('/', 'Api\v1\NodesController@nodes');
});
