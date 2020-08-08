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
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'theme' => env('THEME'),

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
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

    'admitted_api' => [
         'baseuri' => env('ADMITTEDAPI_BASEURI', 'https://allegorix.utt.fr'),
         'basepath' => env('ADMITTEDAPI_BASEPATH', '/API_BDE/index.php'),
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

    'slack' => [
        'exception_webhook' => env('SLACK_EXCEPTION_WEBHOOK', ''),
    ],

    'utt' => [
        'wifi_subnet' => env('UTT_WIFI_SUBNET', '10.25.0.0/16,10.18.0.0/16'),
    ],

    'reentry' => [
        'tc' => [
            'date' => env('REENTRY_TC_DATE', 'Lundi 31 Août'),
            'time' => env('REENTRY_TC_TIME', '8h'),
        ],
        'branches' => [
            'date' => env('REENTRY_MASTERS_DATE', 'Mardi 1 Septembre'),
            'time' => env('REENTRY_MASTERS_TIME', '8h'),
        ],
        'masters' => [
            'date' => env('REENTRY_MASTERS_DATE', 'Lundi 7 Septembre'),
            'time' => env('REENTRY_MASTERS_TIME', '8h'),
        ],
    ],

    'partners' => [
        'ada' => 'true',
        'mgel' => 'true',
        'damonte' => 'true',
        'beijaflore' => 'true',
        'popeye' => 'true',
    ],
];
