<?php

Route::group(['middleware' => 'auth:api'], function () {

    Route::post('/oauth/token/revoke', ['uses' => 'OAuthController@revokeApiToken']);

});
