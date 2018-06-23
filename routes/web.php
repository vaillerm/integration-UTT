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

Route::get('/emails/unsubscribe/{email}', [
    'as'   => 'emails.unsubscribe',
    'uses' => 'EmailsController@getUnsubscribe'
]);

Route::get('/emails/opening/{mail_id}.png', [
    'as'   => 'emails.opening',
    'uses' => 'EmailsController@trackOpening'
]);

Route::group(['middleware' => 'oauth'], function () {
    Route::get('/menu', [
        'as'   => 'menu',
        'middleware' => 'authorize:student',
        'uses' => 'PagesController@getMenu'
    ]);
});

Route::get('/qrcode/{id}.png', [
    'as'   => 'pages.qrcode',
    'uses' => 'PagesController@getQrCode'
]);

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

        // Event model's routes
        Route::group(['middleware' => 'authorize:admin'], function () {
            Route::get('/event', ['uses' => 'EventController@index']);
            Route::get('/event/create', ['uses' => 'EventController@create']);
            Route::get('/event/edit/{id}', ['uses' => 'EventController@edit']);
            Route::post('/event', ['uses' => 'EventController@store']);
            Route::delete('/event/{id}', ['uses' => 'EventController@destroy']);
            Route::put('/event/{id}', ['uses' => 'EventController@update']);
        });

        // Checkin model's routes
        Route::group(['middleware' => 'authorize:admin'], function () {
            Route::get('/checkin', ['uses' => 'CheckinController@index', 'as'=> 'dashboard.checkin']);
            Route::get('/checkin/create', ['uses' => 'CheckinController@create']);
            Route::get('/checkin/edit/{id}', ['uses' => 'CheckinController@edit']);
            Route::post('/checkin', ['uses' => 'CheckinController@store']);
            Route::delete('/checkin/{id}', ['uses' => 'CheckinController@destroy']);
            Route::put('/checkin/{id}', ['uses' => 'CheckinController@update']);
        });

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
            Route::get('/slides/tc', [
                'as'   => 'dashboard.referrals.slides.tc',
                'middleware' => 'authorize:admin',
                'uses' => 'ReferralsController@slidesTC'
            ]);
            Route::get('/signs/tc', [
                'as'   => 'dashboard.referrals.signs.tc',
                'middleware' => 'authorize:admin',
                'uses' => 'ReferralsController@signsTC'
            ]);
            Route::get('/slides/branch', [
                'as'   => 'dashboard.referrals.slides.branch',
                'middleware' => 'authorize:admin',
                'uses' => 'ReferralsController@slidesBranch'
            ]);
            Route::get('/signs/branch', [
                'as'   => 'dashboard.referrals.signs.branch',
                'middleware' => 'authorize:admin',
                'uses' => 'ReferralsController@signsBranch'
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

        // Emails management.
        Route::group(['prefix' => 'emails'], function () {
            Route::get('/', [
                'as'   => 'dashboard.emails.index',
                'middleware' => 'authorize:admin',
                'uses' => 'EmailsController@getIndex'
            ]);
            /**
            Route::get('/preview/{id}', [
                'as'   => 'dashboard.emails.preview',
                'middleware' => 'authorize:admin',
                'uses' => 'EmailsController@getPreview'
            ]);
             * **/

            Route::get('/preview/{id}/{user_id?}', [
                'as'   => 'dashboard.emails.revisionpreview',
                'middleware' => 'authorize:admin',
                'uses' => 'EmailsController@getRevisionPreview'
            ]);

        });


        // WEI registrations
        Route::group(['prefix' => 'wei'], function () {

            Route::get('/', [
                'as'   => 'dashboard.wei',
                'middleware' => 'authorize:volunteer,wei',
                'uses' => 'WEIController@etuHome'
            ]);

            Route::get('/pay', [
                'as'   => 'dashboard.wei.pay',
                'middleware' => 'authorize:volunteer,wei',
                'uses' => 'WEIController@etuPay'
            ]);

            Route::post('/pay', [
                'as'   => 'dashboard.wei.pay.submit',
                'middleware' => 'authorize:volunteer,wei',
                'uses' => 'WEIController@etuPaySubmit'
            ]);

            Route::get('/guarantee', [
                'as'   => 'dashboard.wei.guarantee',
                'middleware' => 'authorize:volunteer,wei',
                'uses' => 'WEIController@etuGuarantee'
            ]);

            Route::post('/guarantee', [
                'as'   => 'dashboard.wei.guarantee.submit',
                'middleware' => 'authorize:volunteer,wei',
                'uses' => 'WEIController@etuGuaranteeSubmit'
            ]);


            Route::get('/graph', [
                'as'   => 'dashboard.wei.graph',
                'middleware' => 'authorize:admin',
                'uses' => 'WEIController@adminGraph'
            ]);

            Route::get('/search', [
                'as'   => 'dashboard.wei.search',
                'middleware' => 'authorize:moderator',
                'uses' => 'WEIController@userSearch'
            ]);

            Route::get('/edit/student/{id}', [
                'as'   => 'dashboard.wei.student.edit',
                'middleware' => 'authorize:moderator',
                'uses' => 'WEIController@studentEdit'
            ]);

            Route::post('/edit/student/{id}', [
                'as'   => 'dashboard.wei.student.edit.submit',
                'middleware' => 'authorize:moderator',
                'uses' => 'WEIController@studentEditSubmit'
            ]);

            Route::get('/checkin/{id}', [
                'as'   => 'dashboard.wei.checkin',
                'middleware' => 'authorize:moderator',
                'uses' => 'WEIController@checkIn'
            ]);

            Route::post('/search', [
                'as'   => 'dashboard.wei.search.submit',
                'middleware' => 'authorize:moderator',
                'uses' => 'WEIController@userSearchSubmit'
            ]);

            Route::get('/list', [
                'as'   => 'dashboard.wei.list',
                'middleware' => 'authorize:admin',
                'uses' => 'WEIController@list'
            ]);

            Route::get('/assign/team', [
                'as'   => 'dashboard.wei.assign.team',
                'middleware' => 'authorize:admin',
                'uses' => 'WEIController@adminTeamAssignation'
            ]);

            Route::get('/bus/list', [
                'as'   => 'dashboard.wei.bus.list',
                'middleware' => 'authorize:admin',
                'uses' => 'WEIController@adminBusList'
            ]);

            Route::post('/assign/team', [
                'middleware' => 'authorize:admin',
                'uses' => 'WEIController@adminTeamAssignation'
            ]);

            Route::get('/checkin/generateBus', [
                'as'   => 'dashboard.wei.bus.generate.checklist',
                'middleware' => 'authorize:admin',
                'uses' => 'WEIController@adminBusGenerateChecklist'
            ]);

        });

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
        Route::group(['prefix' => 'exports'], function () {
            // Route::get('/', [
            // 	'as'   => 'dashboard.exports',
            // 	'uses' => 'PagesController@getExports'
            // ]);
            Route::get('/referrals', [
                'as'   => 'dashboard.exports.referrals',
                'uses' => 'PagesController@getExportReferrals'
            ]);
            Route::get('/newcomers', [
                'as'   => 'dashboard.exports.newcomers',
                'uses' => 'PagesController@getExportNewcomers'
            ]);
            Route::get('/teams', [
                'as'   => 'dashboard.exports.teams',
                'uses' => 'PagesController@getExportTeams'
            ]);
            Route::get('/students', [
                'as'   => 'dashboard.exports.students',
                'uses' => 'PagesController@getExportStudents'
            ]);
        });

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
            Route::get('/list-preferences/{filter?}', [
                'as'   => 'dashboard.students.list.preferences',
                'middleware' => 'authorize:admin',
                'uses' => 'StudentsController@listByPreferences'
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

        Route::group(['prefix' => 'configs'], function () {
            Route::get('/parameters', [
                'as'   => 'dashboard.configs.parameters',
                'middleware' => 'authorize:admin',
                'uses' => 'SettingsController@getIndex'
            ]);

            Route::get('/parameters/edit/{settings_name}', [
                'as'   => 'dashboard.configs.parameters.edit',
                'middleware' => 'authorize:admin',
                'uses' => 'SettingsController@getEdit'
            ]);

            Route::post('/parameters/edit/{settings_name}', [
                'as'   => 'dashboard.configs.parameters.edit',
                'middleware' => 'authorize:admin',
                'uses' => 'SettingsController@postEdit'
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

Route::get('/referral/{user_id}/{hash}', [
    'as'   => 'newcomer.referral.autorisation',
    'uses' => 'NewcomersController@loginAndSendCoordonate'
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

Route::get('/team/{step?}', [
    'as'   => 'newcomer.team',
    'middleware' => 'authorize:newcomer',
    'uses' => 'NewcomersController@TeamForm'
]);

Route::get('/wei', [
    'as'   => 'newcomer.wei',
    'middleware' => 'authorize:newcomer',
    'uses' => 'WEIController@newcomersHome'
]);

Route::get('/wei/pay', [
    'as'   => 'newcomer.wei.pay',
    'middleware' => 'authorize:newcomer,wei',
    'uses' => 'WEIController@newcomersPay'
]);

Route::post('/wei/pay', [
    'as'   => 'newcomer.wei.pay.submit',
    'middleware' => 'authorize:newcomer,wei',
    'uses' => 'WEIController@newcomersPaySubmit'
]);

Route::get('/wei/guarantee', [
    'as'   => 'newcomer.wei.guarantee',
    'middleware' => 'authorize:newcomer,wei',
    'uses' => 'WEIController@newcomersGuarantee'
]);

Route::post('/wei/guarantee', [
    'as'   => 'newcomer.wei.guarantee.submit',
    'middleware' => 'authorize:newcomer,wei',
    'uses' => 'WEIController@newcomersGuaranteeSubmit'
]);

Route::get('/wei/authorization', [
    'as'   => 'newcomer.wei.authorization',
    'middleware' => 'authorize:newcomer,wei',
    'uses' => 'WEIController@newcomersAuthorization'
]);

Route::get('/contact', [
    'as'   => 'newcomer.contact',
    'middleware' => 'authorize:newcomer',
    'uses' => 'NewcomersController@contact'
]);

Route::post('/contact', [
    'as'   => 'newcomer.contact.submit',
    'middleware' => 'authorize:newcomer',
    'uses' => 'NewcomersController@contactSubmit'
]);

Route::get('/faq', [
    'as'   => 'newcomer.faq',
    'middleware' => 'authorize:newcomer',
    'uses' => 'PagesController@getFAQ'
]);

Route::get('/deals', [
    'as'   => 'newcomer.deals',
    'middleware' => 'authorize:newcomer',
    'uses' => 'PagesController@getDeals'
]);

Route::get('/done', [
    'as'   => 'newcomer.done',
    'middleware' => 'authorize:newcomer',
    'uses' => 'PagesController@getNewcomersDone'
]);

Route::get('/etupay', [
    'as'   => 'etupay',
    'uses' => 'EtupayController@etupayReturn'
]);

Route::post('/etupay/callback', [
    'as'   => 'etupay.callback',
    'uses' => 'EtupayController@etupayCallback'
]);

Route::get("/challenge/add", [
	'as' => "challenge.add",
	"uses" => "ChallengeController@displayForm"
]);

Route::post("/challenge/add", [
	'as' => "challenge.add",
	"uses" => "ChallengeController@addChallenge"
]);
