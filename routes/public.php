<?php

// Settings
Route::get('/settings/locale/{locale}', 'SettingsController@changeLocale');

// Index
Route::get('/', 'IndexController@index');

// Map
Route::get('/map/content', 'MapController@getMapContent');

// Account create
Route::get('/account/user/create/{type?}', 'Account\UserController@create');
Route::post('/account/user/insert', 'Account\UserController@insert');
Route::get('/account/user/migrate', 'Account\UserController@migrateEditAccount');
Route::post('/account/user/migrate-update', 'Account\UserController@migrateUpdateAccount');
Route::get('/account/user/activate/token/{token}', 'Account\UserController@activateToken'); // Activate account even if user is not logged in

// Cart - needs auth since cart only works for logged in users
Route::group(['prefix' => '/checkout', 'middleware' => 'auth.account'], function () {
    Route::get('/', 'CartController@index');
    Route::post('/item/add', 'CartController@addItem');
    Route::post('/items/add', 'CartController@addItems');
    Route::post('/item/{CartDateItemLinkId}/update', 'CartController@updateItem');
    Route::get('/item/{CartDateItemLinkId}/remove', 'CartController@removeItem');
    Route::get('/order/create', 'OrderController@createOrder');
});

// Static pages
Route::get('/find-out-more', 'PageController@findOutMore');
Route::get('/membership', 'PageController@membership');

// Landing page catcher
Route::get('/landing-page/{segments}', function() {
    return redirect('/');
})->where('segments', '(.*)');
Route::get('/launch/{segments}', function() {
    return redirect('/');
})->where('segments', '(.*)');

Route::get('/landing-page/{wildcard?}', 'IndexController@index');

// Certificate
Route::get('/.well-known/acme-challenge/{id}', function($id) {
    return Redirect::to('/.well-known/acme-challenge/' . $id);
});

// Page - There routes must be in the bottom of this file because else they'll match every request
Route::get('/{type}/{slug}/{subType?}/{subSlug?}', 'PermalinkController@route');
