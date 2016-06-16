<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::pattern('id', '[0-9]+');

Route::get('/', [
	'as'   => 'index',
	'uses' => 'PagesController@getHomepage'
]);

Route::group(['middleware' => 'oauth'], function()
{
	Route::get('/menu', [
		'as'   => 'menu',
		'uses' => 'PagesController@getMenu'
	]);
});

Route::group(['prefix' => 'referrals'], function()
{
	Route::group(['middleware' => 'oauth'], function()
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
	Route::group(['middleware' => ['oauth', 'admin']], function()
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
			Route::post('/', [
				'as'   => 'dashboard.newcomers.create',
				'uses' => 'NewcomersController@create'
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
			Route::post('/{id}/members', [
				'as'   => 'dashboard.teams.members',
				'uses' => 'TeamsController@addMember'
			]);
		});

		// WEI registrations
		Route::group(['prefix' => 'wei'], function()
		{
			Route::get('/', [
				'as'   => 'dashboard.wei',
				'uses' => 'WEIRegistrationsController@index'
			]);
			Route::post('/', [
				'as'   => 'dashboard.wei.create',
				'uses' => 'WEIRegistrationsController@create'
			]);
			Route::get('{id}', [
				'as'   => 'dashboard.wei.edit',
				'uses' => 'WEIRegistrationsController@edit'
			]);
			Route::post('{id}', [
				'as'   => 'dashboard.wei.update',
				'uses' => 'WEIRegistrationsController@update'
			]);
			Route::get('{id}/destroy', [
				'as'   => 'dashboard.wei.destroy',
				'uses' => 'WEIRegistrationsController@destroy'
			]);
		});

		// Checks handling.
		Route::group(['prefix' => 'payments'], function()
		{
			Route::get('/', [
				'as'   => 'dashboard.payments',
				'uses' => 'PaymentsController@index'
			]);
			Route::post('/', [
				'as'   => 'dashboard.payments.create',
				'uses' => 'PaymentsController@create'
			]);
			Route::get('/{id}/destroy', [
				'as'   => 'dashboard.payments.destroy',
				'uses' => 'PaymentsController@destroy'
			]);
		});

		// Export.
		Route::group(['prefix' => 'exports'], function()
		{
			Route::get('/', [
				'as'   => 'dashboard.exports',
				'uses' => 'PagesController@getExports'
			]);
			Route::get('/referrals', [
				'as'   => 'dashboard.exports.referrals',
				'uses' => 'PagesController@getExportReferrals'
			]);
			Route::get('/newcomers', [
				'as'   => 'dashboard.exports.newcomers',
				'uses' => 'PagesController@getExportNewcomers'
			]);
		});

		Route::group(['prefix' => 'championship'], function()
		{
			Route::get('/', [
				'as'   => 'dashboard.championship',
				'uses' => 'PagesController@getChampionship'
			]);
			Route::post('/', [
				'as'   => 'dashboard.championship.edit',
				'uses' => 'PagesController@postChampionship'
			]);
		});
	});
});

Route::get('/scores', [
	'as'   => 'championship.display',
	'uses' => 'PagesController@getScores'
]);

Route::group(['prefix' => 'oauth'], function()
{
	Route::get('authorize', [
		'as'   => 'oauth.auth',
		'uses' => 'OAuthController@auth'
	]);

	Route::get('callback', [
		'as'   => 'oauth.callback',
		'uses' => 'OAuthController@callback'
	]);

	Route::get('logout', [
		'as'     => 'oauth.logout',
		'middleware' => 'oauth',
		'uses'   => 'OAuthController@logout'
	]);
});
