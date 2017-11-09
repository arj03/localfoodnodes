<?php

Route::group(['middleware' => 'auth:api', 'prefix' => '/economy'], function () {
    Route::get('/transactions', 'Api\v1\Economy\EconomyController@transactions')->middleware(['scopes:organization-transactions-read']);
    Route::post('/transactions', 'Api\v1\Economy\EconomyController@uploadTransactionsFile')->middleware(['scopes:organizations-transactions-modify']);
    Route::put('/transactions', 'Api\v1\Economy\EconomyController@updateTransaction')->middleware(['scopes:organizations-transactions-modify']);
    Route::get('/transactions/categories', 'Api\v1\Economy\EconomyController@transactionCategories')->middleware(['scopes:organization-transactions-read']);
});
