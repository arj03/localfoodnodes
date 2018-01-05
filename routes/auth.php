<?php

// Auth
Route::get('/login', 'AuthController@login');
Route::get('/logout', 'AuthController@logout');
Route::post('/authenticate', 'AuthController@authenticate');

// Password Reset
Route::group(['prefix' => '/password'], function () {
    Route::get('/reset', 'ResetPasswordController@initForm');
    Route::post('/email', 'ResetPasswordController@sendLink');
    Route::get('/reset/{token}', 'ResetPasswordController@resetForm');
    Route::post('/reset', 'ResetPasswordController@reset');
});

// Admin
Route::group(['prefix' => '/admin'], function () {
    // Auth
    Route::get('/login', 'Admin\AdminAuthController@login');
    Route::get('/logout', 'Admin\AdminAuthController@logout');
    Route::post('/authenticate', 'Admin\AdminAuthController@authenticate');

    // Password Reset
    Route::group(['prefix' => '/password'], function () {
        Route::get('/reset', 'Admin\AdminResetPasswordController@initForm');
        Route::post('/email', 'Admin\AdminResetPasswordController@sendLink');
        Route::get('/reset/{token}', 'Admin\AdminResetPasswordController@resetForm');
        Route::post('/reset', 'Admin\AdminResetPasswordController@reset');
    });
});
