<?php

Route::group(['middleware' => 'auth:api', 'prefix' => '/nodes'], function () {
    Route::get('/', 'Api\v1\Nodes\NodesController@nodes')->middleware(['scopes:nodes-read-all']);
    Route::get('/count', 'Api\v1\Nodes\NodesController@count')->middleware(['scopes:nodes-read-all']);
    Route::get('/{nodeId}', 'Api\v1\Nodes\NodesController@node')->middleware(['scopes:nodes-read-all']);
    Route::get('/{nodeId}/dates', 'Api\v1\Nodes\NodesController@nodeDates')->middleware(['scopes:nodes-read-all']);
});
