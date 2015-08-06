<?php

Route::pattern('id', '[0-9]+');

Route::get('/', [
	'as'   => 'index',
	'uses' => 'PagesController@getHomepage'
]);

Route::group(['before' => 'oauth'], function()
{
	Route::get('/menu', [
		'as'   => 'menu',
		'uses' => 'PagesController@getMenu'
	]);
});

Route::group(['prefix' => 'referrals'], function()
{
	Route::group(['before' => 'oauth'], function()
	{
		Route::get('/', [
			'as'   => 'referrals.edit',
			'uses' => 'ReferralsController@edit'
	 	]);

		Route::post('/', [
			'as'   => 'referrals.update',
			'uses' => 'ReferralsController@update'
		]);
		Route::get('/destroy', [
			'as'   => 'referrals.destroy',
			'uses' => 'ReferralsController@destroy'
		]);
	});
});

Route::group(['prefix' => 'dashboard'], function()
{
	Route::group(['before' => ['oauth', 'admin']], function()
	{
		Route::get('/', [
			'as'   => 'dashboard.index',
			'uses' => 'DashboardController@getIndex'
		]);

		// Validate the texts.
		Route::group(['prefix' => 'validation'], function()
		{
			Route::get('/', [
				'as'   => 'dashboard.validation',
				'uses' => 'ReferralsController@getValidation'
			]);
			Route::post('/', [
				'uses' => 'ReferralsController@postValidation'
			]);
		});

		// Delete and edit referrals.
		Route::group(['prefix' => 'referrals'], function()
		{
			Route::get('/', [
				'as'   => 'dashboard.referrals',
				'uses' => 'ReferralsController@index'
			]);
			Route::post('/', [
				'uses' => 'ReferralsController@postReferrals'
			]);
		});

		// Add or remove administrators.
		Route::group(['prefix' => 'administrators'], function()
		{
			Route::get('/', [
				'as'   => 'dashboard.administrators',
				'uses' => 'DashboardController@getAdministrators'
			]);
			Route::post('/', [
				'uses' => 'DashboardController@postAdministrators'
			]);
		});

		// Newcomers management.
		Route::group(['prefix' => 'newcomers'], function()
		{
			Route::get('/', [
				'as'   => 'dashboard.newcomers',
				'uses' => 'NewcomersController@index'
			]);
			Route::get('/{id}', [
				'as'   => 'dashboard.newcomers.profile',
				'uses' => 'NewcomersController@show',
			]);
		});

		// Teams management.
		Route::group(['prefix' => 'teams'], function()
		{
			Route::get('/', [
				'as'   => 'dashboard.teams',
				'uses' => 'TeamsController@index'
			]);
			Route::post('/', [
				'as'   => 'dashboard.teams.create',
				'uses' => 'TeamsController@create'
			]);
			Route::get('/{id}', [
				'as'   => 'dashboard.teams.edit',
				'uses' => 'TeamsController@edit'
			]);
			Route::post('/{id}', [
				'as'   => 'dashboard.teams.update',
				'uses' => 'TeamsController@update'
			]);
			Route::get('/{id}/destroy', [
				'as'   => 'dashboard.teams.destroy',
				'uses' => 'TeamsController@destroy'
			]);
			Route::get('/{id}/members', [
				'as'   => 'dashboard.teams.members',
				'uses' => 'TeamsController@members'
			]);
		});
	});
});

Route::group(['prefix' => 'oauth'], function()
{
	Route::get('authorize', [
		'as'   => 'oauth.authorize',
		'uses' => 'OAuthController@authorize'
	]);

	Route::get('callback', [
		'as'   => 'oauth.callback',
		'uses' => 'OAuthController@callback'
	]);

	Route::get('logout', [
		'as'     => 'oauth.logout',
		'before' => 'oauth',
		'uses'   => 'OAuthController@logout'
	]);

});
