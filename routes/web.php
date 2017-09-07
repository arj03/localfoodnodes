<?php

// Settings
Route::get('/settings/locale/{locale}', 'SettingsController@changeLocale');

// Index
Route::get('/', 'IndexController@index');

// Map
Route::get('/map/content', 'MapController@getMapContent');

// Auth
Route::get('/login', 'AuthController@login');
Route::get('/login/facebook', 'AuthController@facebookLogin');
Route::get('/login/facebook/callback', 'AuthController@facebookLoginCallback');
Route::get('/logout', 'AuthController@logout');
Route::post('/authenticate', 'AuthController@authenticate');

// Password Reset
Route::group(['prefix' => '/password'], function () {
    Route::get('/reset', 'ResetPasswordController@initForm');
    Route::post('/email', 'ResetPasswordController@sendLink');
    Route::get('/reset/{token}', 'ResetPasswordController@resetForm');
    Route::post('/reset', 'ResetPasswordController@reset');
});

// Cart
Route::group(['prefix' => '/checkout', 'middleware' => 'auth.route'], function () {
    Route::get('/', 'CartController@index');
    Route::post('/item/add', 'CartController@addItem');
    Route::post('/item/{CartDateItemLinkId}/update', 'CartController@updateItem');
    Route::get('/item/{CartDateItemLinkId}/remove', 'CartController@removeItem');
    Route::get('/order/create', 'OrderController@createOrder');
});

// Account create
Route::get('/account/user/create/{type?}', 'Admin\UserController@create');
Route::post('/account/user/insert', 'Admin\UserController@insert');
Route::get('/account/user/migrate', 'Admin\UserController@migrateEditAccount');
Route::post('/account/user/migrate-update', 'Admin\UserController@migrateUpdateAccount');

Route::get('/account/user/activate/token/{token}', 'Admin\UserController@activateToken'); // Activate account even if user is not logged in

// Admin routes
Route::group(['prefix' => '/account', 'middleware' => 'auth.route'], function () {
    // User
    Route::group(['prefix' => '/user'], function () {
        Route::get('/activate', 'Admin\UserController@activate');
        Route::get('/activate/resend', 'Admin\UserController@activateResend');
        Route::get('/', 'Admin\UserController@index');
        Route::get('/edit', 'Admin\UserController@edit');
        Route::post('/update', 'Admin\UserController@update');
        Route::get('/delete', 'Admin\UserController@delete');
        Route::get('/delete/confirm', 'Admin\UserController@deleteConfirm');
        Route::get('/password/edit', 'Admin\UserController@editPassword');
        Route::post('/password/update', 'Admin\UserController@updatePassword');
        Route::get('/pickups', 'Admin\UserController@pickups');
        Route::get('/orders/producer/{producerId}', 'Admin\UserController@producerOrders');
        Route::get('/orders/product/{productId}', 'Admin\UserController@productOrders');

        // Route::get('/orders', 'Admin\UserController@orders');
        Route::get('/order/{orderItemId}', 'Admin\UserController@order');
        Route::get('/order/{orderItemId}/delete', 'Admin\UserController@deleteOrderItem');
        Route::post('/membership/callback', 'Admin\UserController@membershipCallback');
        Route::get('/node/{nodeId}', 'Admin\UserController@toggleNode');
        Route::get('/events', 'Admin\UserController@events');
        Route::get('/event/{eventId}', 'Admin\UserController@toggleEvent');
        Route::get('/membership', 'Admin\UserController@membership');
    });

    // Node
    Route::group(['prefix' => '/node'], function () {
        Route::get('/create', 'Admin\NodeController@create');
        Route::post('/insert', 'Admin\NodeController@insert');
        Route::get('/{nodeId}', 'Admin\NodeController@index');
        Route::get('/{nodeId}/edit', 'Admin\NodeController@edit');
        Route::post('/{nodeId}/update', 'Admin\NodeController@update');
        Route::get('/{nodeId}/delete', 'Admin\NodeController@delete');
        Route::get('/{nodeId}/delete/confirm', 'Admin\NodeController@deleteConfirm');
        Route::get('/{nodeId}/leave', 'Admin\NodeController@leave');
        Route::get('/{nodeId}/users', 'Admin\NodeController@users');
        Route::get('/{nodeId}/producers', 'Admin\NodeController@producers');
        Route::get('/{nodeId}/producer/{producerId}/blacklist', 'Admin\NodeController@blacklistProducer');

        Route::post('/{nodeId}/delivery/add', 'Admin\NodeController@addDelivery');
        Route::get('/{nodeId}/delivery/{date}/delete', 'Admin\NodeController@deleteDelivery');

        Route::post('/{nodeId}/invite/send', 'Admin\NodeController@sendAdminInvite');
        Route::get('/{nodeId}/invite/accept', 'Admin\NodeController@acceptInvite');
        Route::get('/{nodeId}/invite/{userId}/cancel', 'Admin\NodeController@cancelInvite');
    });

    // Producer
    Route::group(['prefix' => '/producer'], function () {
        Route::get('/create', 'Admin\ProducerController@create');
        Route::post('/insert', 'Admin\ProducerController@insert');
        Route::get('/{producerId}', 'Admin\ProducerController@index');
        Route::get('/{producerId}/edit', 'Admin\ProducerController@edit');
        Route::post('/{producerId}/update', 'Admin\ProducerController@update');
        Route::get('/{producerId}/delete', 'Admin\ProducerController@delete');
        Route::get('/{producerId}/delete/confirm', 'Admin\ProducerController@deleteConfirm');
        Route::get('/{producerId}/leave', 'Admin\ProducerController@leave');
        Route::get('/{producerId}/products', 'Admin\ProducerController@products');
        Route::get('/{producerId}/deliveries', 'Admin\ProducerController@deliveries');
        Route::get('/{producerId}/delivery/{orderDateId}/picklist', 'Admin\ProducerController@picklist');
        Route::get('/{producerId}/orders/user/{userId}', 'Admin\OrderController@userOrders');
        Route::get('/{producerId}/orders/product/{productId}', 'Admin\OrderController@productOrders');
        Route::get('/{producerId}/order/{orderId}', 'Admin\OrderController@order');
        Route::post('/{producerId}/invite/send', 'Admin\ProducerController@sendAdminInvite');
        Route::get('/{producerId}/invite/accept', 'Admin\ProducerController@acceptInvite');
        Route::get('/{producerId}/invite/{userId}/cancel', 'Admin\ProducerController@cancelInvite');
        Route::get('/{producerId}/image/{imageId}/delete', 'Admin\ProducerController@deleteImage');

        // Producer node map
        Route::get('/{producerId}/map/nodes', 'Admin\ProducerController@mapGetNodes');
        Route::post('/{producerId}/map/node/add', 'Admin\ProducerController@mapAddNode');
        Route::post('/{producerId}/map/node/remove', 'Admin\ProducerController@mapRemoveNode');

        // Producer order
        Route::get('/{producerId}/order/{orderItemId}/status/{status}', 'Admin\OrderController@changeOrderStatus');
    });

    // Product
    Route::group(['prefix' => '/producer/{producerId}/product'], function () {
        Route::get('/create', 'Admin\ProductController@create');
        Route::post('/insert', 'Admin\ProductController@insert');
        Route::get('/{productId}/edit', 'Admin\ProductController@edit');
        Route::post('/{productId}/update', 'Admin\ProductController@update');
        Route::get('/{productId}/delete', 'Admin\ProductController@delete');
        Route::get('/{productId}/delete/confirm', 'Admin\ProductController@deleteConfirm');
        Route::get('/{productId}/production/{productionId}/delete', 'Admin\ProductProductionController@deleteProduction');
        Route::post('/{productId}/set-package-unit', 'Admin\ProductController@setPackageUnit');

        // Variants
        Route::get('/{productId}/variants', 'Admin\ProductVariantController@index');
        Route::get('/{productId}/variant/create', 'Admin\ProductVariantController@create');
        Route::post('/{productId}/variant/insert', 'Admin\ProductVariantController@insert');
        Route::get('/{productId}/variant/{variantId}/edit', 'Admin\ProductVariantController@edit');
        Route::post('/{productId}/variant/{variantId}/update', 'Admin\ProductVariantController@update');
        Route::get('/{productId}/variant/{variantId}/delete', 'Admin\ProductVariantController@delete');
        Route::get('/{productId}/variant/{variantId}/set-main-variant', 'Admin\ProductVariantController@setMainVariant');

        // Production
        Route::get('/{productId}/production', 'Admin\ProductProductionController@index');
        Route::post('/{productId}/production/update', 'Admin\ProductProductionController@update');
        Route::get('/{productId}/production/adjustment', 'Admin\ProductProductionController@adjustment');
        Route::post('/{productId}/production/adjustment/update', 'Admin\ProductProductionController@updateAdjustment');

        // Deliveries
        Route::get('/{productId}/deliveries', 'Admin\ProductController@editDeliveries');
        Route::post('/{productId}/deliveries/update', 'Admin\ProductController@updateDeliveries');
    });

    // Events
    Route::group(['prefix' => '/{ownerType}/{ownerId}'], function () {
        Route::get('/events', 'Admin\EventController@index');
        Route::get('/event/create', 'Admin\EventController@create');
        Route::post('/event/insert', 'Admin\EventController@insert');
        Route::get('/event/{eventId}/edit', 'Admin\EventController@edit');
        Route::post('/event/{eventId}/update', 'Admin\EventController@update');
        Route::get('/event/{eventId}/delete', 'Admin\EventController@delete');
        Route::get('/event/{eventId}/deleteConfirm', 'Admin\EventController@deleteConfirm');
        Route::get('/event/{eventId}/guests', 'Admin\EventController@guests');
    });

    // Image
    Route::group(['prefix' => '/image'], function () {
        Route::get('/{imageId}/delete', 'Admin\ImageController@delete');
    });
});

// Admin
Route::group(['prefix' => '/admin'], function () {
    // Email
    Route::group(['prefix' => '/email'], function () {
        Route::get('/user/activation/{userId}', 'Admin\EmailController@userActivation');
        Route::get('/user/reset-password/{userId}', 'Admin\EmailController@resetPassword');
        Route::get('/order/producer', 'Admin\EmailController@orderProducer');
        Route::get('/order/customer', 'Admin\EmailController@orderCustomer');
    });
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
