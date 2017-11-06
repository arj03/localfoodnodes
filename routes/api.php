<?php

use Illuminate\Http\Request;

Route::group(['middleware' => 'auth:api', 'prefix' => '/users'], function () {
    Route::get('/', 'Api\v1\Users\UsersController@users')->middleware(['scope:users-read-all,users-read-self']);
    Route::put('/', 'Api\v1\Users\UsersController@update')->middleware(['scope:users-modify']);
    Route::get('/nodes', 'Api\v1\Users\UsersController@nodes')->middleware(['scopes:users-nodes-read']);
    Route::get('/orders', 'Api\v1\Users\OrdersController@orders')->middleware(['scopes:users-orders-read']);
});

Route::group(['middleware' => 'auth:api', 'prefix' => '/economy'], function () {
    Route::get('/transactions', 'Api\v1\Economy\EconomyController@transactions')->middleware(['scopes:organization-transactions-read']);
    Route::post('/transactions', 'Api\v1\Economy\EconomyController@uploadTransactionsFile')->middleware(['scopes:organizations-transactions-modify']);
    Route::put('/transactions', 'Api\v1\Economy\EconomyController@updateTransaction')->middleware(['scopes:organizations-transactions-modify']);
    Route::get('/transactions/categories', 'Api\v1\Economy\EconomyController@transactionCategories')->middleware(['scopes:organization-transactions-read']);
});

Route::group(['middleware' => 'auth:api', 'prefix' => '/orders'], function () {
    Route::get('/', 'Api\v1\Orders\OrdersController@orders')->middleware(['scopes:orders-read-all']);
});

Route::group(['prefix' => '/nodes'], function () {
    Route::get('/', 'Api\v1\NodesController@nodes');
});
