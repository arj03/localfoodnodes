<?php

Route::get('/token', 'Admin\AdminController@getApiAccessToken'); // Only needed for file uploads...
Route::any('/api-proxy', 'Admin\AdminController@apiProxy'); // Use /api-proxy for everything else

Route::get('/', 'Admin\AdminController@index');
Route::get('/users', 'Admin\AdminController@users');
Route::get('/orders', 'Admin\AdminController@orders');
Route::get('/economy', 'Admin\AdminController@economy');
Route::get('/api', 'Admin\AdminController@api');
Route::get('/translations', '\LocalFoodNodes\LanguageTable\Controller@index');

// Email
Route::group(['prefix' => '/email'], function () {
    Route::get('/', 'Admin\EmailController@index');
    Route::get('/user/activation', 'Admin\EmailController@userActivation');
    Route::get('/user/reset-password', 'Admin\EmailController@resetPassword');
    Route::get('/order/producer', 'Admin\EmailController@orderProducer');
    Route::get('/order/customer', 'Admin\EmailController@orderCustomer');
});
