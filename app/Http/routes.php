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

Route::group(['middleware' => 'oauth'], function () {
    Route::get('/menu', [
        'as'   => 'menu',
        'middleware' => 'authorize:student',
        'uses' => 'PagesController@getMenu'
    ]);
});

Route::group(['prefix' => 'referrals'], function () {
    Route::group(['middleware' => 'oauth'], function () {
        Route::get('/firsttime', [
            'as'   => 'referrals.firsttime',
            'middleware' => 'authorize:student',
            'uses' => 'ReferralsController@firstTime'
        ]);

        Route::get('/', [
            'as'   => 'referrals.edit',
            'middleware' => 'authorize:referral,edit',
            'uses' => 'ReferralsController@edit'
        ]);

        Route::post('/', [
            'as'   => 'referrals.update',
            'middleware' => 'authorize:referral,edit',
            'uses' => 'ReferralsController@update'
        ]);
        Route::get('/destroy', [
            'as'   => 'referrals.destroy',
            'middleware' => 'authorize:referral,edit',
            'uses' => 'ReferralsController@destroy'
        ]);
    });
});

Route::group(['prefix' => 'dashboard'], function () {
    Route::group(['middleware' => ['oauth']], function () {
        Route::get('/', [
            'as'   => 'dashboard.index',
            'middleware' => 'authorize:volunteer',
            'uses' => 'DashboardController@getIndex'
        ]);

        // Delete, validate and edit referrals.
        Route::group(['prefix' => 'referrals'], function () {
            Route::get('/validation', [
                'as'   => 'dashboard.referrals.validation',
                'middleware' => 'authorize:admin',
                'uses' => 'ReferralsController@getValidation'
            ]);
            Route::post('/validation', [
                'middleware' => 'authorize:admin',
                'uses' => 'ReferralsController@postValidation'
            ]);
            Route::get('/list', [
                'as'   => 'dashboard.referrals.list',
                'middleware' => 'authorize:admin',
                'uses' => 'ReferralsController@index'
            ]);
            Route::get('/match', [
                'as'   => 'dashboard.referrals.match',
                'middleware' => 'authorize:admin',
                'uses' => 'ReferralsController@matchToNewcomers'
            ]);
            Route::get('/prematch', [
                'as'   => 'dashboard.referrals.prematch',
                'middleware' => 'authorize:admin',
                'uses' => 'ReferralsController@prematch'
            ]);
            Route::post('/prematch', [
                'as'   => 'dashboard.referrals.prematch.submit',
                'middleware' => 'authorize:admin',
                'uses' => 'ReferralsController@prematchSubmit'
            ]);
        });

        // ce
        Route::group(['prefix' => 'ce'], function () {
            Route::get('/firsttime', [
                'as'   => 'dashboard.ce.firsttime',
                'middleware' => 'authorize:student',
                'uses' => 'CEController@firstTime'
            ]);

            Route::get('/teamlist', [
                'as'   => 'dashboard.ce.teamlist',
                'middleware' => 'authorize:ce',
                'uses' => 'CEController@teamList'
            ]);

            Route::post('/teamcreate', [
                'as'   => 'dashboard.ce.teamcreate',
                'middleware' => 'authorize:ce,create',
                'uses' => 'CEController@teamCreate'
            ]);

            Route::get('/myteam', [
                'as'   => 'dashboard.ce.myteam',
                'middleware' => 'authorize:ce',
                'uses' => 'CEController@myteam'
            ]);

            Route::post('/myteam', [
                'middleware' => 'authorize:ce,edit',
                'uses' => 'CEController@myTeamSubmit'
            ]);

            Route::get('/add', [
                'as'   => 'dashboard.ce.add',
                'middleware' => 'authorize:ce,edit',
                'uses' => 'CEController@add'
            ]);

            Route::get('/add/{login}', [
                'as'   => 'dashboard.ce.addsubmit',
                'middleware' => 'authorize:ce,edit',
                'uses' => 'CEController@addSubmit'
            ]);

            Route::get('/join', [
                'as'   => 'dashboard.ce.join',
                'middleware' => 'authorize:ce,join',
                'uses' => 'CEController@join'
            ]);

            Route::get('/unjoin', [
                'as'   => 'dashboard.ce.unjoin',
                'middleware' => 'authorize:ce,join',
                'uses' => 'CEController@unjoin'
            ]);
        });

        // Teams management.
        Route::group(['prefix' => 'teams'], function () {
            Route::get('/', [
                'as'   => 'dashboard.teams.list',
                'middleware' => 'authorize:admin',
                'uses' => 'TeamsController@list'
            ]);
            Route::get('/{id}/validate', [
                'as'   => 'dashboard.teams.validate',
                'middleware' => 'authorize:admin',
                'uses' => 'TeamsController@adminValidate'
            ]);
            Route::get('/{id}/unvalidate', [
                'as'   => 'dashboard.teams.unvalidate',
                'middleware' => 'authorize:admin',
                'uses' => 'TeamsController@adminUnvalidate'
            ]);
            Route::get('/{id}/edit', [
                'as'   => 'dashboard.teams.edit',
                'middleware' => 'authorize:admin',
                'uses' => 'TeamsController@edit'
            ]);
            Route::post('/{id}/edit', [
                'as'   => 'dashboard.teams.edit.submit',
                'middleware' => 'authorize:admin',
                'uses' => 'TeamsController@editSubmit'
            ]);
            Route::get('/match', [
                'as'   => 'dashboard.teams.match',
                'middleware' => 'authorize:admin',
                'uses' => 'TeamsController@matchToNewcomers'
            ]);
            // 	Route::post('/{id}/members', [
            // 		'as'   => 'dashboard.teams.members',
            // 		'uses' => 'TeamsController@addMember'
            // 	]);
        });

        // Newcomers management.
        Route::group(['prefix' => 'newcomers'], function () {
            Route::get('/', [
                'as'   => 'dashboard.newcomers.list',
                'middleware' => 'authorize:admin',
                'uses' => 'NewcomersController@list'
            ]);
            Route::post('/create', [
                'as'   => 'dashboard.newcomers.create',
                'middleware' => 'authorize:admin',
                'uses' => 'NewcomersController@create'
            ]);
            Route::post('/createcsv', [
                'as'   => 'dashboard.newcomers.createcsv',
                'middleware' => 'authorize:admin',
                'uses' => 'NewcomersController@createcsv'
            ]);

            Route::get('/letter/{id}', [
                'as'   => 'dashboard.newcomers.letter',
                'middleware' => 'authorize:admin',
                'uses' => 'NewcomersController@letter',
            ]);
            Route::get('/letter/{id}-{limit}', [
                'as'   => 'dashboard.newcomers.letters',
                'middleware' => 'authorize:admin',
                'uses' => 'NewcomersController@letter',
            ]);

            Route::get('/letter/{id}-{limit}/{category}', [
                'as'   => 'dashboard.newcomers.filtered_letters',
                'middleware' => 'authorize:admin',
                'uses' => 'NewcomersController@letter',
            ]);
        });


        // WEI registrations
        // Route::group(['prefix' => 'wei'], function()
        // {
        // 	Route::get('/', [
        // 		'as'   => 'dashboard.wei',
        // 		'uses' => 'WEIRegistrationsController@index'
        // 	]);
        // 	Route::post('/', [
        // 		'as'   => 'dashboard.wei.create',
        // 		'uses' => 'WEIRegistrationsController@create'
        // 	]);
        // 	Route::get('{id}', [
        // 		'as'   => 'dashboard.wei.edit',
        // 		'uses' => 'WEIRegistrationsController@edit'
        // 	]);
        // 	Route::post('{id}', [
        // 		'as'   => 'dashboard.wei.update',
        // 		'uses' => 'WEIRegistrationsController@update'
        // 	]);
        // 	Route::get('{id}/destroy', [
        // 		'as'   => 'dashboard.wei.destroy',
        // 		'uses' => 'WEIRegistrationsController@destroy'
        // 	]);
        // });

        // Checks handling.
        // Route::group(['prefix' => 'payments'], function()
        // {
        // 	Route::get('/', [
        // 		'as'   => 'dashboard.payments',
        // 		'uses' => 'PaymentsController@index'
        // 	]);
        // 	Route::post('/', [
        // 		'as'   => 'dashboard.payments.create',
        // 		'uses' => 'PaymentsController@create'
        // 	]);
        // 	Route::get('/{id}/destroy', [
        // 		'as'   => 'dashboard.payments.destroy',
        // 		'uses' => 'PaymentsController@destroy'
        // 	]);
        // });

        // Export.
        // Route::group(['prefix' => 'exports'], function()
        // {
        // 	Route::get('/', [
        // 		'as'   => 'dashboard.exports',
        // 		'uses' => 'PagesController@getExports'
        // 	]);
        // 	Route::get('/referrals', [
        // 		'as'   => 'dashboard.exports.referrals',
        // 		'uses' => 'PagesController@getExportReferrals'
        // 	]);
        // 	Route::get('/newcomers', [
        // 		'as'   => 'dashboard.exports.newcomers',
        // 		'uses' => 'PagesController@getExportNewcomers'
        // 	]);
        // });

        // Route::group(['prefix' => 'championship'], function()
        // {
        // 	Route::get('/', [
        // 		'as'   => 'dashboard.championship',
        // 		'uses' => 'PagesController@getChampionship'
        // 	]);
        // 	Route::post('/', [
        // 		'as'   => 'dashboard.championship.edit',
        // 		'uses' => 'PagesController@postChampionship'
        // 	]);
        // });

        Route::group(['prefix' => 'students'], function () {
            Route::get('/list/{filter?}', [
                'as'   => 'dashboard.students.list',
                'middleware' => 'authorize:admin',
                'uses' => 'StudentsController@list'
            ]);
            Route::get('/profil', [
                'as'   => 'dashboard.students.profil',
                'middleware' => 'authorize:student',
                'uses' => 'StudentsController@profil'
            ]);
            Route::post('/profil', [
                'as'   => 'dashboard.students.profil.submit',
                'middleware' => 'authorize:student',
                'uses' => 'StudentsController@profilSubmit'
            ]);
            Route::get('/{id}/edit', [
                'as'   => 'dashboard.students.edit',
                'middleware' => 'authorize:admin',
                'uses' => 'StudentsController@edit'
            ]);
            Route::post('/{id}/edit', [
                'as'   => 'dashboard.students.edit.submit',
                'middleware' => 'authorize:admin',
                'uses' => 'StudentsController@editSubmit'
            ]);
        });
    });
});

Route::get('/scores', [
    'as'   => 'championship.display',
    'uses' => 'PagesController@getScores'
]);

Route::group(['prefix' => 'oauth'], function () {
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

// Newcomer website
Route::get('/login', [
    'as'   => 'newcomer.auth.login',
    'uses' => 'authController@login'
]);
Route::post('/login', [
    'as'   => 'newcomer.auth.login.submit',
    'uses' => 'authController@loginSubmit'
]);
Route::get('/logout', [
    'as'   => 'newcomer.auth.logout',
    'middleware' => 'authorize:newcomer',
    'uses' => 'authController@logout'
]);
Route::get('/home', [
    'as'   => 'newcomer.home',
    'middleware' => 'authorize:newcomer',
    'uses' => 'PagesController@getNewcomersHomepage'
]);

Route::get('/myletter', [
    'as'   => 'newcomer.myletter',
    'middleware' => 'authorize:newcomer',
    'uses' => 'NewcomersController@myLetter'
]);

Route::get('/profil', [
    'as'   => 'newcomer.profil',
    'middleware' => 'authorize:newcomer',
    'uses' => 'NewcomersController@profilForm'
]);

Route::post('/profil', [
    'as'   => 'newcomer.profil.submit',
    'middleware' => 'authorize:newcomer',
    'uses' => 'NewcomersController@profilFormSubmit'
]);

Route::get('/referral/{step?}', [
    'as'   => 'newcomer.referral',
    'middleware' => 'authorize:newcomer',
    'uses' => 'NewcomersController@referralForm'
]);

Route::post('/referral', [
    'as'   => 'newcomer.referral.submit',
    'middleware' => 'authorize:newcomer',
    'uses' => 'NewcomersController@referralFormSubmit'
]);

Route::get('/done', [
    'as'   => 'newcomer.done',
    'middleware' => 'authorize:newcomer',
    'uses' => 'PagesController@getNewcomersDone'
]);
