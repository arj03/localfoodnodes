<?php

// User
Route::group(['prefix' => '/user'], function () {
    Route::get('/activate', 'Account\UserController@activate');
    Route::get('/activate/resend', 'Account\UserController@activateResend');
    Route::get('/', 'Account\UserController@index');
    Route::get('/edit', 'Account\UserController@edit');
    Route::post('/update', 'Account\UserController@update');
    Route::get('/delete', 'Account\UserController@delete');
    Route::get('/delete/confirm', 'Account\UserController@deleteConfirm');
    Route::get('/password/edit', 'Account\UserController@editPassword');
    Route::post('/password/update', 'Account\UserController@updatePassword');
    Route::get('/pickups', 'Account\UserController@pickups');
    Route::get('/orders/producer/{producerId}', 'Account\UserController@producerOrders');
    Route::get('/orders/product/{productId}', 'Account\UserController@productOrders');

    // Route::get('/orders', 'Account\UserController@orders');
    Route::get('/order/{orderItemId}', 'Account\UserController@order');
    Route::get('/order/{orderItemId}/delete', 'Account\UserController@deleteOrderItem');
    Route::post('/membership/callback', 'Account\UserController@membershipCallback');
    Route::get('/node/{nodeId}', 'Account\UserController@toggleNode');
    Route::get('/events', 'Account\UserController@events');
    Route::get('/event/{eventId}', 'Account\UserController@toggleEvent');
    Route::get('/membership', 'Account\UserController@membership');
});

// Node
Route::group(['prefix' => '/node'], function () {
    Route::get('/create', 'Account\NodeController@create');
    Route::post('/insert', 'Account\NodeController@insert');
    Route::get('/{nodeId}', 'Account\NodeController@index');
    Route::get('/{nodeId}/edit', 'Account\NodeController@edit');
    Route::get('/{nodeId}/delivery/edit', 'Account\NodeController@deliveryEdit');
    Route::post('/{nodeId}/delivery/update', 'Account\NodeController@deliveryUpdate');
    Route::post('/{nodeId}/update', 'Account\NodeController@update');
    Route::get('/{nodeId}/delete', 'Account\NodeController@delete');
    Route::get('/{nodeId}/delete/confirm', 'Account\NodeController@deleteConfirm');
    Route::get('/{nodeId}/leave', 'Account\NodeController@leave');
    Route::get('/{nodeId}/users', 'Account\NodeController@users');
    Route::get('/{nodeId}/producers', 'Account\NodeController@producers');
    Route::get('/{nodeId}/producer/{producerId}/blacklist', 'Account\NodeController@blacklistProducer');

    Route::post('/{nodeId}/delivery/add', 'Account\NodeController@addDelivery');
    Route::get('/{nodeId}/delivery/{date}/delete', 'Account\NodeController@deleteDelivery');

    Route::post('/{nodeId}/invite/send', 'Account\NodeController@sendAdminInvite');
    Route::get('/{nodeId}/invite/accept', 'Account\NodeController@acceptInvite');
    Route::get('/{nodeId}/invite/{userId}/cancel', 'Account\NodeController@cancelInvite');
});

// Producer
Route::group(['prefix' => '/producer'], function () {
    Route::get('/create', 'Account\ProducerController@create');
    Route::post('/insert', 'Account\ProducerController@insert');
    Route::get('/{producerId}', 'Account\ProducerController@index');
    Route::get('/{producerId}/edit', 'Account\ProducerController@edit');
    Route::post('/{producerId}/update', 'Account\ProducerController@update');
    Route::get('/{producerId}/delete', 'Account\ProducerController@delete');
    Route::get('/{producerId}/delete/confirm', 'Account\ProducerController@deleteConfirm');
    Route::get('/{producerId}/leave', 'Account\ProducerController@leave');
    Route::get('/{producerId}/products', 'Account\ProducerController@products');
    Route::get('/{producerId}/deliveries', 'Account\ProducerController@deliveries');
    Route::get('/{producerId}/delivery/{orderDateId}/picklist', 'Account\ProducerController@picklist');
    Route::get('/{producerId}/orders/user/{userId}', 'Account\OrderController@userOrders');
    Route::get('/{producerId}/orders/product/{productId}', 'Account\OrderController@productOrders');
    Route::get('/{producerId}/order/{orderId}', 'Account\OrderController@order');
    Route::post('/{producerId}/invite/send', 'Account\ProducerController@sendAdminInvite');
    Route::get('/{producerId}/invite/accept', 'Account\ProducerController@acceptInvite');
    Route::get('/{producerId}/invite/{userId}/cancel', 'Account\ProducerController@cancelInvite');
    Route::get('/{producerId}/image/{imageId}/delete', 'Account\ProducerController@deleteImage');

    // Producer node map
    Route::get('/{producerId}/map/nodes', 'Account\ProducerController@mapGetNodes');
    Route::post('/{producerId}/map/node/add', 'Account\ProducerController@mapAddNode');
    Route::post('/{producerId}/map/node/remove', 'Account\ProducerController@mapRemoveNode');

    // Producer order
    Route::get('/{producerId}/order/{orderItemId}/status/{status}', 'Account\OrderController@changeOrderStatus');
});

// Product
Route::group(['prefix' => '/producer/{producerId}/product'], function () {
    Route::get('/create', 'Account\ProductController@create');
    Route::post('/insert', 'Account\ProductController@insert');
    Route::get('/{productId}/edit', 'Account\ProductController@edit');
    Route::post('/{productId}/update', 'Account\ProductController@update');
    Route::get('/{productId}/delete', 'Account\ProductController@delete');
    Route::get('/{productId}/delete/confirm', 'Account\ProductController@deleteConfirm');
    Route::get('/{productId}/production/{productionId}/delete', 'Account\ProductProductionController@deleteProduction');
    Route::post('/{productId}/set-package-unit', 'Account\ProductController@setPackageUnit');

    // Variants
    Route::get('/{productId}/variants', 'Account\ProductVariantController@index');
    Route::get('/{productId}/variant/create', 'Account\ProductVariantController@create');
    Route::post('/{productId}/variant/insert', 'Account\ProductVariantController@insert');
    Route::get('/{productId}/variant/{variantId}/edit', 'Account\ProductVariantController@edit');
    Route::post('/{productId}/variant/{variantId}/update', 'Account\ProductVariantController@update');
    Route::get('/{productId}/variant/{variantId}/delete', 'Account\ProductVariantController@delete');
    Route::get('/{productId}/variant/{variantId}/set-main-variant', 'Account\ProductVariantController@setMainVariant');

    // Production
    Route::get('/{productId}/production', 'Account\ProductProductionController@index');
    Route::post('/{productId}/production/update', 'Account\ProductProductionController@update');
    Route::get('/{productId}/production/adjustment', 'Account\ProductProductionController@adjustment');
    Route::post('/{productId}/production/adjustment/update', 'Account\ProductProductionController@updateAdjustment');

    // Deliveries
    Route::get('/{productId}/deliveries', 'Account\ProductController@editDeliveries');
    Route::post('/{productId}/deliveries/update', 'Account\ProductController@updateDeliveries');
});

// Events
Route::group(['prefix' => '/{ownerType}/{ownerId}'], function () {
    Route::get('/events', 'Account\EventController@index');
    Route::get('/event/create', 'Account\EventController@create');
    Route::post('/event/insert', 'Account\EventController@insert');
    Route::get('/event/{eventId}/edit', 'Account\EventController@edit');
    Route::post('/event/{eventId}/update', 'Account\EventController@update');
    Route::get('/event/{eventId}/delete', 'Account\EventController@delete');
    Route::get('/event/{eventId}/deleteConfirm', 'Account\EventController@deleteConfirm');
    Route::get('/event/{eventId}/guests', 'Account\EventController@guests');
});

// Image
Route::group(['prefix' => '/image'], function () {
    Route::get('/{imageId}/delete', 'Account\ImageController@delete');
});
