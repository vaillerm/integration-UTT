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
    'uses' => 'All\PagesController@getHomepage'
]);

Route::get('/emails/unsubscribe/{email}', [
    'as'   => 'emails.unsubscribe',
    'uses' => 'All\EmailsController@getUnsubscribe'
]);

Route::get('/emails/opening/{mail_id}.png', [
    'as'   => 'emails.opening',
    'uses' => 'All\EmailsController@trackOpening'
]);

Route::group(['middleware' => 'oauth'], function () {
    Route::get('/menu', [
        'as'   => 'menu',
        'middleware' => 'authorize:student',
        'uses' => 'Students\PagesController@getMenu'
    ]);
});

Route::get('/trombi', [
    'as'   => 'trombi',
    'uses' => 'All\PagesController@getTrombi'
]);

Route::get('/trombi/phone/{id}.png', [
    'as'   => 'trombi.phone',
    'uses' => 'All\PagesController@getTrombiPhome'
]);

Route::get('/qrcode/{id}.png', [
    'as'   => 'pages.qrcode',
    'uses' => 'All\PagesController@getQrCode'
]);

Route::group(['prefix' => 'referrals'], function () {
    Route::group(['middleware' => 'oauth'], function () {
        Route::get('/firsttime', [
            'as'   => 'referrals.firsttime',
            'middleware' => 'authorize:student',
            'uses' => 'Students\ReferralsController@firstTime'
        ]);

        Route::get('/', [
            'as'   => 'referrals.edit',
            'middleware' => 'authorize:referral,edit',
            'uses' => 'Students\ReferralsController@edit'
        ]);

        Route::post('/', [
            'as'   => 'referrals.update',
            'middleware' => 'authorize:referral,edit',
            'uses' => 'Students\ReferralsController@update'
        ]);
        Route::get('/destroy', [
            'as'   => 'referrals.destroy',
            'middleware' => 'authorize:referral,edit',
            'uses' => 'Students\ReferralsController@destroy'
        ]);
    });
});

Route::group(['prefix' => 'dashboard'], function () {
    Route::group(['middleware' => ['oauth']], function () {
        Route::get('/', [
            'as'   => 'dashboard.index',
            'middleware' => 'authorize:volunteer',
            'uses' => 'Students\PagesController@getDashboardHome'
        ]);

        // Event model's routes
        Route::group(['middleware' => 'authorize:admin'], function () {
            Route::get('/event', ['uses' => 'Admin\EventController@index']);
            Route::get('/event/create', ['uses' => 'Admin\EventController@create']);
            Route::get('/event/edit/{id}', ['uses' => 'Admin\EventController@edit']);
            Route::post('/event', ['uses' => 'Admin\EventController@store']);
            Route::delete('/event/{id}', ['uses' => 'Admin\EventController@destroy']);
            Route::put('/event/{id}', ['uses' => 'Admin\EventController@update']);
        });

        // Checkin model's routes
        Route::group(['middleware' => 'authorize:admin'], function () {
            Route::get('/checkin', ['uses' => 'Admin\CheckinController@index', 'as'=> 'dashboard.checkin']);
            Route::get('/checkin/create', ['uses' => 'Admin\CheckinController@create']);
            Route::get('/checkin/edit/{id}', ['uses' => 'Admin\CheckinController@edit']);
            Route::post('/checkin', ['uses' => 'Admin\CheckinController@store']);
            Route::delete('/checkin/{id}', ['uses' => 'Admin\CheckinController@destroy']);
            Route::put('/checkin/{id}', ['uses' => 'Admin\CheckinController@update']);
        });

        // Delete, validate and edit referrals.
        Route::group(['prefix' => 'referrals'], function () {
            Route::get('/validation', [
                'as'   => 'dashboard.referrals.validation',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\ReferralsController@getValidation'
            ]);
            Route::post('/validation', [
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\ReferralsController@postValidation'
            ]);
            Route::get('/list', [
                'as'   => 'dashboard.referrals.list',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\ReferralsController@index'
            ]);
            Route::get('/match/{force?}', [
                'as'   => 'dashboard.referrals.match',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\ReferralsController@matchToNewcomers',
            ]);
            Route::get('/prematch', [
                'as'   => 'dashboard.referrals.prematch',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\ReferralsController@prematch'
            ]);
            Route::post('/prematch', [
                'as'   => 'dashboard.referrals.prematch.submit',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\ReferralsController@prematchSubmit'
            ]);
            Route::get('/slides/tc', [
                'as'   => 'dashboard.referrals.slides.tc',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\ReferralsController@slidesTC'
            ]);
            Route::get('/signs/tc', [
                'as'   => 'dashboard.referrals.signs.tc',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\ReferralsController@signsTC'
            ]);
            Route::get('/slides/branch', [
                'as'   => 'dashboard.referrals.slides.branch',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\ReferralsController@slidesBranch'
            ]);
            Route::get('/signs/branch', [
                'as'   => 'dashboard.referrals.signs.branch',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\ReferralsController@signsBranch'
            ]);
        });

        // ce
        Route::group(['prefix' => 'ce'], function () {
            Route::get('/firsttime', [
                'as'   => 'dashboard.ce.firsttime',
                'middleware' => 'authorize:student',
                'uses' => 'Students\TeamController@firstTime'
            ]);

            Route::get('/teamlist', [
                'as'   => 'dashboard.ce.teamlist',
                'middleware' => 'authorize:ce',
                'uses' => 'Students\TeamController@teamList'
            ]);

            Route::post('/teamcreate', [
                'as'   => 'dashboard.ce.teamcreate',
                'middleware' => 'authorize:ce,create',
                'uses' => 'Students\TeamController@teamCreate'
            ]);

            Route::get('/myteam', [
                'as'   => 'dashboard.ce.myteam',
                'middleware' => 'authorize:ce',
                'uses' => 'Students\TeamController@myteam'
            ]);

            Route::post('/myteam', [
                'middleware' => 'authorize:ce,edit',
                'uses' => 'Students\TeamController@myTeamSubmit'
            ]);

            Route::get('/add', [
                'as'   => 'dashboard.ce.add',
                'middleware' => 'authorize:ce,edit',
                'uses' => 'Students\TeamController@add'
            ]);

            Route::get('/add/{login}', [
                'as'   => 'dashboard.ce.addsubmit',
                'middleware' => 'authorize:ce,edit',
                'uses' => 'Students\TeamController@addSubmit'
            ]);

            Route::get('/join', [
                'as'   => 'dashboard.ce.join',
                'middleware' => 'authorize:ce,join',
                'uses' => 'Students\TeamController@join'
            ]);

            Route::get('/unjoin', [
                'as'   => 'dashboard.ce.unjoin',
                'middleware' => 'authorize:ce,join',
                'uses' => 'Students\TeamController@unjoin'
            ]);
        });

        // Teams management.
        Route::group(['prefix' => 'teams'], function () {
            Route::get('/', [
                'as'   => 'dashboard.teams.list',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\TeamsController@list'
            ]);
            Route::get('/{id}/validate', [
                'as'   => 'dashboard.teams.validate',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\TeamsController@adminValidate'
            ]);
            Route::get('/{id}/unvalidate', [
                'as'   => 'dashboard.teams.unvalidate',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\TeamsController@adminUnvalidate'
            ]);
            Route::get('/{id}/edit', [
                'as'   => 'dashboard.teams.edit',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\TeamsController@edit'
            ]);
            Route::post('/{id}/edit', [
                'as'   => 'dashboard.teams.edit.submit',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\TeamsController@editSubmit'
            ]);
            // Not tested : TODO Test it !
            Route::get('/match', [
                'as'   => 'dashboard.teams.match',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\TeamsController@matchToNewcomers'
            ]);
            Route::get('/{id}/members', [
                'as'   => 'dashboard.teams.members',
                'uses' => 'Admin\TeamsController@members'
            ]);
            Route::post('/{id}/members', [
                'as'   => 'dashboard.teams.members',
                'uses' => 'Admin\TeamsController@addMember'
            ]);
        });

        // Newcomers management.
        Route::group(['prefix' => 'newcomers'], function () {
            Route::get('/', [
                'as'   => 'dashboard.newcomers.list',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\NewcomersController@list'
            ]);
            Route::get('/progress', [
                'as'   => 'dashboard.newcomers.list-progress',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\NewcomersController@listProgress'
            ]);
            Route::post('/create', [
                'as'   => 'dashboard.newcomers.create',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\NewcomersController@create'
            ]);
            Route::get('/unsync/{id}', [
                'as'   => 'dashboard.newcomers.unsync',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\NewcomersController@Unsync'
            ]);
            Route::post('/createcsv', [
                'as'   => 'dashboard.newcomers.createcsv',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\NewcomersController@createcsv'
            ]);
        });

        // Emails management.
        Route::group(['prefix' => 'emails'], function () {
            Route::get('/', [
                'as'   => 'dashboard.emails.index',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\EmailsController@getIndex'
            ]);
            Route::post('/create', [
                'as'   => 'dashboard.email.create',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\EmailsController@createTemplate'
            ]);

            Route::get('/edit/{id}', [
                'as'   => 'dashboard.email.edit',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\EmailsController@editTemplate'
            ]);

            Route::post('/edit/{id}', [
                'as'   => 'dashboard.email.edit.submit',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\EmailsController@editTemplateSubmit'
            ]);
            Route::get('/preview/{id}/{user_id?}', [
                'as'   => 'dashboard.emails.templatepreview',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\EmailsController@getTemplatePreview'
            ]);

            Route::get('/schedule/{id}/{cronId?}', [
                'as'   => 'dashboard.email.schedule',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\EmailsController@scheduleTemplate'
            ]);

            Route::get('/cancel/{id}', [
                'as'   => 'dashboard.email.cancel',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\EmailsController@cancelCron'
            ]);

            Route::post('/schedule/{id}', [
                'as'   => 'dashboard.email.schedule.submit',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\EmailsController@scheduleTemplateSubmit'
            ]);

        });


        // WEI registrations
        Route::group(['prefix' => 'wei'], function () {

            Route::get('/', [
                'as'   => 'dashboard.wei',
                'middleware' => 'authorize:volunteer,wei',
                'uses' => 'Students\WEIController@etuHome'
            ]);

            Route::get('/health', [
                'as'   => 'dashboard.wei.health',
                'middleware' => 'authorize:volunteer,wei',
                'uses' => 'Students\WEIController@healthForm'
            ]);

            Route::post('/health', [
                'as'   => 'dashboard.wei.health.submit',
                'middleware' => 'authorize:volunteer,wei',
                'uses' => 'Students\WEIController@healthFormSubmit'
            ]);

            Route::get('/pay', [
                'as'   => 'dashboard.wei.pay',
                'middleware' => 'authorize:volunteer,wei',
                'uses' => 'Students\WEIController@etuPay'
            ]);

            Route::post('/pay', [
                'as'   => 'dashboard.wei.pay.submit',
                'middleware' => 'authorize:volunteer,wei',
                'uses' => 'Students\WEIController@etuPaySubmit'
            ]);

            Route::get('/guarantee', [
                'as'   => 'dashboard.wei.guarantee',
                'middleware' => 'authorize:volunteer,wei',
                'uses' => 'Students\WEIController@etuGuarantee'
            ]);

            Route::post('/guarantee', [
                'as'   => 'dashboard.wei.guarantee.submit',
                'middleware' => 'authorize:volunteer,wei',
                'uses' => 'Students\WEIController@etuGuaranteeSubmit'
            ]);

            Route::get('/graph', [
                'as'   => 'dashboard.wei.graph',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\WEIController@adminGraph'
            ]);

            Route::get('/search', [
                'as'   => 'dashboard.wei.search',
                'middleware' => 'authorize:moderator',
                'uses' => 'Admin\WEIController@userSearch'
            ]);

            Route::get('/edit/student/{id}', [
                'as'   => 'dashboard.wei.student.edit',
                'middleware' => 'authorize:moderator',
                'uses' => 'Admin\WEIController@studentEdit'
            ]);

            Route::post('/edit/student/{id}', [
                'as'   => 'dashboard.wei.student.edit.submit',
                'middleware' => 'authorize:moderator',
                'uses' => 'Admin\WEIController@studentEditSubmit'
            ]);

            Route::get('/checkin/{id}', [
                'as'   => 'dashboard.wei.checkin',
                'middleware' => 'authorize:moderator',
                'uses' => 'Admin\WEIController@checkIn'
            ]);

            Route::post('/search', [
                'as'   => 'dashboard.wei.search.submit',
                'middleware' => 'authorize:moderator',
                'uses' => 'Admin\WEIController@userSearchSubmit'
            ]);

            Route::get('/list', [
                'as'   => 'dashboard.wei.list',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\WEIController@list'
            ]);

            Route::get('/assign/team', [
                'as'   => 'dashboard.wei.assign.team',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\WEIController@adminTeamAssignation'
            ]);

            Route::get('/bus/list', [
                'as'   => 'dashboard.wei.bus.list',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\WEIController@adminBusList'
            ]);

            Route::post('/assign/team', [
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\WEIController@adminTeamAssignation'
            ]);

            Route::get('/checkin/generateBus', [
                'as'   => 'dashboard.wei.bus.generate.checklist',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\WEIController@adminBusGenerateChecklist'
            ]);

        });

        // Export.
        Route::group(['prefix' => 'exports'], function () {
            Route::get('/referrals', [
                'as'   => 'dashboard.exports.referrals',
                'uses' => 'Admin\ExportController@getExportReferralsToNewcomers'
            ]);
            Route::get('/newcomers', [
                'as'   => 'dashboard.exports.newcomers',
                'uses' => 'Admin\ExportController@getExportNewcomersToReferrals'
            ]);
            Route::get('/teams', [
                'as'   => 'dashboard.exports.teams',
                'uses' => 'Admin\ExportController@getExportTeams'
            ]);
            Route::get('/students', [
                'as'   => 'dashboard.exports.students',
                'uses' => 'Admin\ExportController@getExportStudents'
            ]);
        });

        Route::group(['prefix' => 'championship'], function()
        {
            Route::get('/', [
                'as'   => 'dashboard.championship',
                'uses' => 'Admin\ScoreController@getChampionship'
            ]);
            Route::post('/', [
                'as'   => 'dashboard.championship.edit',
                'uses' => 'Admin\ScoreController@postChampionship'
            ]);
        });

        Route::group(['prefix' => 'students'], function () {
            Route::get('/list/{filter?}', [
                'as'   => 'dashboard.students.list',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\StudentsController@list'
            ]);
            Route::get('/list-preferences/{filter?}', [
                'as'   => 'dashboard.students.list.preferences',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\StudentsController@listByPreferences'
            ]);
            Route::get('/profil', [
                'as'   => 'dashboard.students.profil',
                'middleware' => 'authorize:student',
                'uses' => 'Students\ProfileController@profil'
            ]);
            Route::post('/profil', [
                'as'   => 'dashboard.students.profil.submit',
                'middleware' => 'authorize:student',
                'uses' => 'Students\ProfileController@profilSubmit'
            ]);
            Route::get('/{id}/edit', [
                'as'   => 'dashboard.students.edit',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\StudentsController@edit'
            ]);
            Route::post('/{id}/edit', [
                'as'   => 'dashboard.students.edit.submit',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\StudentsController@editSubmit'
            ]);
            Route::get('/add', [
                'as'   => 'dashboard.students.add',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\StudentsController@add'
            ]);
            Route::post('/add', [
                'as'   => 'dashboard.students.add',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\StudentsController@add'
            ]);
            Route::get('/add/{login}', [
                'as'   => 'dashboard.students.addsubmit',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\StudentsController@addSubmit'
            ]);
            Route::get('/add/{login}', [
                'as'   => 'dashboard.students.addsubmit',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\StudentsController@addSubmit'
            ]);
            Route::get('/{id}/generate-password', [
                'as'   => 'dashboard.students.generatePassword',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\StudentsController@generatePassword'
            ]);
        });

        Route::group(['prefix' => 'configs'], function () {
            Route::get('/parameters', [
                'as'   => 'dashboard.configs.parameters',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\SettingsController@getIndex'
            ]);

            Route::get('/parameters/edit/{settings_name}', [
                'as'   => 'dashboard.configs.parameters.edit',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\SettingsController@getEdit'
            ]);

            Route::post('/parameters/edit/{settings_name}', [
                'as'   => 'dashboard.configs.parameters.edit',
                'middleware' => 'authorize:admin',
                'uses' => 'Admin\SettingsController@postEdit'
            ]);
        });
    });
});

Route::get('/scores', [
    'as'   => 'championship.display',
    'uses' => 'All\ScoreController@getScores'
]);

Route::group(['prefix' => 'oauth'], function () {
    Route::get('authorize', [
        'as'   => 'oauth.auth',
        'uses' => 'All\OAuthController@auth'
    ]);

    Route::get('callback', [
        'as'   => 'oauth.callback',
        'uses' => 'All\OAuthController@callback'
    ]);

    Route::get('logout', [
        'as'     => 'oauth.logout',
        'middleware' => 'oauth',
        'uses'   => 'All\OAuthController@logout'
    ]);
});

// Contact page
Route::get('/contact', [
    'as'   => 'contact',
    'uses' => 'All\ContactController@contact'
]);

Route::post('/contact', [
    'as'   => 'contact.submit',
    'uses' => 'All\ContactController@contactSubmit'
]);

// Newcomer website
Route::get('/login', [
    'as'   => 'newcomer.auth.login',
    'uses' => 'All\AuthController@login'
]);
Route::post('/login', [
    'as'   => 'newcomer.auth.login.submit',
    'uses' => 'All\AuthController@loginSubmit'
]);

Route::get('/logout', [
    'as'   => 'newcomer.auth.logout',
    'middleware' => 'authorize:newcomer',
    'uses' => 'All\AuthController@logout'
]);

// Not test because we needs to generate the hash ; TODO test it
// Route::get('/referral/{user_id}/{hash}', [
//     'as'   => 'newcomer.referral.autorisation',
//     'uses' => 'NewcomersController@loginAndSendCoordonate'
// ]);

Route::get('/home', [
    'as'   => 'newcomer.home',
    'middleware' => 'authorize:newcomer',
    'uses' => 'Newcomers\PagesController@getNewcomersHomepage'
]);

Route::get('/profil', [
    'as'   => 'newcomer.profil',
    'middleware' => 'authorize:newcomer',
    'uses' => 'Newcomers\StepsController@profilForm'
]);

Route::post('/profil', [
    'as'   => 'newcomer.profil.submit',
    'middleware' => 'authorize:newcomer',
    'uses' => 'Newcomers\StepsController@profilFormSubmit'
]);

Route::get('/referral/{step?}', [
    'as'   => 'newcomer.referral',
    'middleware' => 'authorize:newcomer',
    'uses' => 'Newcomers\StepsController@referralForm'
]);

Route::post('/referral', [
    'as'   => 'newcomer.referral.submit',
    'middleware' => 'authorize:newcomer',
    'uses' => 'Newcomers\StepsController@referralFormSubmit'
]);

Route::get('/team/{step?}', [
    'as'   => 'newcomer.team',
    'middleware' => 'authorize:newcomer',
    'uses' => 'Newcomers\StepsController@TeamForm'
]);

Route::get('/backtoschool/{step?}', [
    'as'   => 'newcomer.backtoschool',
    'middleware' => 'authorize:newcomer',
    'uses' => 'Newcomers\StepsController@BackToSchool'
]);

Route::get('/wei', [
    'as'   => 'newcomer.wei',
    'middleware' => 'authorize:newcomer',
    'uses' => 'Newcomers\WEIController@newcomersHome'
]);

Route::get('/wei/pay', [
    'as'   => 'newcomer.wei.pay',
    'middleware' => 'authorize:newcomer,wei',
    'uses' => 'Newcomers\WEIController@newcomersPay'
]);

Route::post('/wei/pay', [
    'as'   => 'newcomer.wei.pay.submit',
    'middleware' => 'authorize:newcomer,wei',
    'uses' => 'Newcomers\WEIController@newcomersPaySubmit'
]);

Route::get('/wei/guarantee', [
    'as'   => 'newcomer.wei.guarantee',
    'middleware' => 'authorize:newcomer,wei',
    'uses' => 'Newcomers\WEIController@newcomersGuarantee'
]);

Route::post('/wei/guarantee', [
    'as'   => 'newcomer.wei.guarantee.submit',
    'middleware' => 'authorize:newcomer,wei',
    'uses' => 'Newcomers\WEIController@newcomersGuaranteeSubmit'
]);

Route::get('/wei/authorization', [
    'as'   => 'newcomer.wei.authorization',
    'middleware' => 'authorize:newcomer,wei',
    'uses' => 'Newcomers\WEIController@newcomersAuthorization'
]);

Route::get('/faq', [
    'as'   => 'newcomer.faq',
    'middleware' => 'authorize:newcomer',
    'uses' => 'Newcomers\PagesController@getFAQ'
]);

Route::get('/deals', [
    'as'   => 'newcomer.deals',
    'middleware' => 'authorize:newcomer',
    'uses' => 'Newcomers\PagesController@getDeals'
]);

Route::get('/done', [
    'as'   => 'newcomer.done',
    'middleware' => 'authorize:newcomer',
    'uses' => 'Newcomers\PagesController@getNewcomersDone'
]);

Route::get('/etupay', [
    'as'   => 'etupay',
    'uses' => 'All\EtupayController@etupayReturn'
]);

Route::post('/etupay/callback', [
    'as'   => 'etupay.callback',
    'uses' => 'All\EtupayController@etupayCallback'
]);

/**
 * Routes for challenges
 */
Route::group(['prefix' => 'challenges'], function() {

    /**
     * Admin authorization
     */
    Route::group(['middleware' => 'authorize:orga'], function() {

        Route::get('add', [
            'as' => 'challenges.add',
            'uses' => 'Challenges\ChallengeController@addForm'
        ]);

        Route::post('add', [
            'as' => 'challenges.add',
            'uses' => 'Challenges\ChallengeController@add'
        ]);

        Route::delete('/{id}', [
            'as' => 'challenges.delete',
            'uses' => 'Challenges\ChallengeController@delete'
        ]);

        Route::get('{challengeId}/modify', 'Challenges\ChallengeController@modifyChallengeForm')->name('challenges.modifyForm');
        Route::post('{challengeId}/modify', 'Challenges\ChallengeController@modify')->name('challenges.modify');

        Route::group(['prefix' => 'validations'], function() {
            Route::get('/','Challenges\ChallengeValidationController@list' )->name('validation.list');
            Route::post('{validationId}/validate', 'Challenges\ChallengeValidationController@accept')->name('validation.accept');
            Route::get('{validationId}/refuse', 'Challenges\ChallengeValidationController@refuseForm')->name('validation.refuseForm');
            Route::post('{validationId}/refuse', 'Challenges\ChallengeValidationController@refuse')->name('validation.refuse');
            Route::post('{validationId}/reset', 'Challenges\ChallengeValidationController@resetStatus')->name('validation.reset');
        });

        /**
         * Okay this part is to handle the images taken by
         * the teams to validate their challenges
         * It is not supposed to be public, that's why there's a controller
         * doing the job
         */
        Route::group(['prefix' => 'proof'], function(){
            Route::get('{name}/smallpic', 'Challenges\ValidationPic@showSmall')->name('validation_proofs.small');
            Route::get('{name}', 'Challenges\ValidationPic@show')->name('validation_proofs.normal');
        });


    });
    Route::get('{id}/submit', [
        'as' => 'challenges.submitForm',
        'uses' => 'Challenges\ChallengeController@submitChallengeForm'
    ]);

    Route::post('team/{teamId}/challenge/{challengeId}/submit', 'Challenges\ChallengeValidationController@create')->name('validation.create');
    /**
     * No specific authorization required here
     */

    Route::get('team/', 'Students\TeamController@challenges')->name('challenges.sent');


    Route::get('/', [
        'as' => 'challenges.list',
        'uses' => 'Challenges\ChallengeController@list'
    ]);


    Route::get('/leaderboard', [
        'as' => 'challenges.faction_leaderboard',
        'uses' => 'All\FactionsController@leaderboard'
    ]);

});
