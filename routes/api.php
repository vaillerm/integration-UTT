<?php

Route::get('/oauth/etuutt/link', ['uses' => 'Api\OAuthController@getRedirectLink']);
Route::post('/oauth/etuutt/callback', ['uses' => 'Api\OAuthController@mobileCallback']);


Route::get('/student/autocomplete', ['uses' => 'Api\StudentsController@autocomplete']);


Route::group(['middleware' => 'auth:api'], function () {

    Route::post('/oauth/token/revoke', ['uses' => 'Api\OAuthController@revokeApiToken']);
    Route::post('/oauth/token/check', ['uses' => 'Api\OAuthController@checkApiToken']);

    Route::get('/student/{id}', ['uses' => 'Api\StudentsController@show']);
    Route::get('/student', ['uses' => 'Api\StudentsController@index']);
    // Route::put('/student/{id}', ['uses' => 'Api\StudentsController@update']);

    Route::get('/team/{id}', ['uses' => 'Api\TeamsController@show']);
    Route::get('/team', ['uses' => 'Api\TeamsController@index']);

    Route::get('/checkin/{id}', ['uses' => 'Api\CheckinController@show']);
    Route::get('/checkin', ['uses' => 'Api\CheckinController@index']);
    Route::post('/checkin', ['uses' => 'Api\CheckinController@store']);
    Route::put('/checkin/{id}/student', ['uses' => 'Api\CheckinController@addUser']);

    Route::post('/notification', ['uses' => 'Api\NotificationController@store']);

    Route::get('/event', ['uses' => 'Admin\EventController@index']);
    Route::get('/event/{id}', ['uses' => 'Api\EventController@show']);

    Route::post('/rallye/{id}', ['uses' => 'Api\RallyeController@store']);

    Route::get('/points', ['uses' => 'Api\PointController@show']);

});
