<?php

Route::group(['middleware' => 'auth:api', 'prefix' => '/products'], function () {
    Route::get('/', 'Api\v1\Products\ProductsController@products')->middleware(['scopes:products-read-all']);
    Route::get('/{productId}', 'Api\v1\Products\ProductsController@product')->middleware(['scopes:products-read-all']);
    Route::get('/{productId}/dates', 'Api\v1\Products\ProductsController@dates')->middleware(['scopes:products-read-all']);
});
