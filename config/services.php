<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'theme' => env('THEME'),

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'etuutt' => [
         'baseuri' => [
             'api'    => env('ETUUTT_BASEURI_API', 'https://etu.utt.fr'),
             'public' => env('ETUUTT_BASEURI_PUBLIC', 'https://etu.utt.fr'),
         ],
         'client' => [
             'id'     => env('ETUUTT_CLIENT_ID'),
             'secret' => env('ETUUTT_CLIENT_SECRET'),
         ],
         'mobile_client' => [
             'id'     => env('ETUUTT_MOBILE_CLIENT_ID'),
             'secret' => env('ETUUTT_MOBILE_CLIENT_SECRET'),
         ]
    ],

    'site' => [
        'loginOpen' => env('LOGIN_OPEN'),
    ],

    'referral' => [
        'opening' => env('REFERRAL_OPENING'),
        'fakeDeadline' => env('REFERRAL_DEADLINE_FAKE'),
        'deadline' => env('REFERRAL_DEADLINE'),
    ],
    'ce' => [
        'opening' => env('CE_OPENING'),
        'fakeDeadline' => env('CE_DEADLINE_FAKE'),
        'deadline' => env('CE_DEADLINE'),
        'maxTeamTc' => env('CE_MAXTEAM_TC'),
        'maxTeamBranch' => env('CE_MAXTEAM_BRANCH'),
        'teamNameOpening' => env('CE_TEAM_NAME_OPENING'),
    ],
    'wei' => [
        'open' => env('WEI_OPEN'),
        'price' => env('WEI_PRICES_REGISTRATION'),
        'price-ce' => env('WEI_PRICES_CE'),
        'price-orga' => env('WEI_PRICES_ORGA'),
        'price-other' => env('WEI_PRICES_OTHER'),
        'guaranteePrice' => env('WEI_PRICES_GUARANTEE'),
        'sandwichPrice' => env('WEI_PRICES_SANDWICH'),
        'start' => env('WEI_DATES_START'),
        'registrationStart' => env('WEI_REGISTRATION_START'),
        'registrationEnd' => env('WEI_REGISTRATION_END'),
        'newcomerMax' => env('WEI_NEWCOMER_MAX'),
    ],
    'etupay' => [
        'uri' => [
            'initiate' => env('ETUPAY_URI_INITIATE'),
        ],
        'id' => env('ETUPAY_ID'),
        'key' => env('ETUPAY_KEY'),
    ],

    'version' => [
         'hash' => env('VERSION_HASH', 'none'),
    ],
];
