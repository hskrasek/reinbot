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

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'botman' => [
        'slack_token'      => env('SLACK_API_TOKEN'),
        'slack_user_token' => env('SLACK_USER_API_TOKEN'),
    ],

    'destiny' => [
        'client_id'          => env('DESTINY_CLIENT_ID'),
        'client_secret'      => env('DESTINY_CLIENT_SECRET'),
        'key'                => env('DESTINY_API_KEY'),
        'slack_web_hook'     => env('DESTINY_SLACK_WEBHOOK'),
        'xur_slack_web_hook' => env('DESTINY_XUR_SLACK_WEBHOOK'),
        'xur_messages'       => [
            'My movements are not predictable, even to me...',
            'Do not be alarmed, I know no reason to cause you harm.',
            'My actions are not my own...',
            'It is my fate to help you. This, I know.',
            'I come bearing help.',
            'I think you have terrible need of my gifts...',
            'The Nine show you these...',
            'I bring gifts of the Nine...Gifts, you sorely need...',
            'These are from the Nine..',
        ],
    ],
];
