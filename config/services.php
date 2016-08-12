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
    ],

    'referral' => [
        'fakeDeadline' => env('REFERRAL_DEADLINE_FAKE'),
        'deadline' => env('REFERRAL_DEADLINE'),
    ],
    'ce' => [
        'fakeDeadline' => env('CE_DEADLINE_FAKE'),
        'deadline' => env('CE_DEADLINE'),
        'maxteam' => env('CE_MAXTEAM'),
    ],
    'wei' => [
        'registrationStart' => env('WEI_REGISTRATION_START'),
    ],

    'version' => [
         'hash' => env('VERSION_HASH', 'none'),
    ],
];
