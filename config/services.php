<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'google' => [
        'maps' => [
            'geocoding' => 'AIzaSyDEbbhR7zpRJpUX7Eby1s2LikRRoGOtBXQ',
            'javascript' => 'AIzaSyAvOkjjArNAXV3Y-MvrLLS6s_V4yTixnsA',
            'embed' => 'AIzaSyAnJrRwv_3rDvc6XUD137g7l-FfX-SqU78'
        ]
    ]

];
