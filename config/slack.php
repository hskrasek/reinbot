<?php

return [

    /*
    |-------------------------------------------------------------
    | Your token to make Slack Api requests
    |-------------------------------------------------------------
    */

    'token' => env('SLACK_API_TOKEN'),

    'verification_token' => env('SLACK_VERIFICATION_TOKEN'),

    'webhooks' => env('SLACK_WEBHOOK_URL'),

    'development_webhooks' => env('SLACK_DEVELOPMENT_WEBHOOK'),
];
