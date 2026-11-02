<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'apify' => [
        'token'        => env('APIFY_TOKEN', env('APIFY_API_TOKEN')),
        'tiktok_actor' => env('APIFY_TIKTOK_ACTOR', env('APIFY_TIKTOK_ACTOR_ID', 'clockworks/free-tiktok-scraper')),
        'ig_actor'     => env('APIFY_IG_ACTOR', env('APIFY_INSTAGRAM_ACTOR_ID', 'apify/instagram-scraper')),
    ],

];
