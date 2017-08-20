<?php

Route::get('/oauth/etuutt/link', ['uses' => 'OAuthController@getRedirectLink']);
Route::post('/oauth/etuutt/callback', ['uses' => 'OAuthController@mobileCallback']);

Route::group(['middleware' => 'auth:api'], function () {

    Route::post('/oauth/token/revoke', ['uses' => 'OAuthController@revokeApiToken']);
    Route::post('/oauth/token/check', ['uses' => 'OAuthController@checkApiToken']);

    Route::get('/student/{id?}', ['uses' => 'StudentsController@index']);
    Route::put('/student/{id}', ['uses' => 'StudentsController@update']);

    Route::get('/team/{id?}', ['uses' => 'TeamsController@index']);

    Route::get('/checkin/{id}', ['uses' => 'CheckinController@show']);
    Route::get('/checkin', ['uses' => 'CheckinController@index']);
    Route::post('/checkin', ['uses' => 'CheckinController@store']);
    Route::put('/checkin/{id}/student', ['uses' => 'CheckinController@addStudent']);

    Route::get('/message', ['uses' => 'MessageController@index']);
    Route::post('/message', ['uses' => 'MessageController@store']);

    Route::post('/notification', ['uses' => 'NotificationController@store']);

});
