<?php

Route::group(['middleware' => 'auth:api', 'prefix' => '/producers'], function () {
    Route::get('/', 'Api\v1\Producers\ProducersController@producers')->middleware(['scopes:producers-read-all']);
    Route::get('/count', 'Api\v1\Producers\ProducersController@count')->middleware(['scopes:producers-read-all']);
});
