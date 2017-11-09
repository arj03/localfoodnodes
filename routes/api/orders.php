<?php

Route::group(['middleware' => 'auth:api', 'prefix' => '/orders'], function () {
    Route::get('/', 'Api\v1\Orders\OrdersController@orders')->middleware(['scopes:orders-read-all']);
});
