<?php

Route::group(['middleware' => 'auth:api', 'prefix' => '/orders'], function () {
    Route::get('/', 'Api\v1\Orders\OrdersController@orders')->middleware(['scopes:orders-read-all']);
    Route::get('/item/{orderItemId}', 'Api\v1\Orders\OrdersController@orderItem')->middleware(['scopes:orders-read-all']);
    Route::get('/date/{orderDateId}', 'Api\v1\Orders\OrdersController@orderDate')->middleware(['scopes:orders-read-all']);

    Route::get('/count', 'Api\v1\Orders\OrdersController@count')->middleware(['scopes:orders-read-all']);
    Route::get('/circulation', 'Api\v1\Orders\OrdersController@circulation')->middleware(['scopes:orders-read-all']);
});
