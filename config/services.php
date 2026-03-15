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

    'cloudflare_images' => [
        'account_id' => env('CLOUDFLARE_IMAGES_ACCOUNT_ID'),
        'account_hash' => env('CLOUDFLARE_IMAGES_ACCOUNT_HASH'),
        'token' => env('CLOUDFLARE_API_TOKEN'),
    ],

    'forge' => [
        'heartbeats' => [
            'generate_sitemap' => env('FORGE_HEARTBEAT_URL_GENERATE_SITEMAP'),
            'refresh_user_data' => env('FORGE_HEARTBEAT_URL_REFRESH_USER_DATA'),
            'sync_visitors' => env('FORGE_HEARTBEAT_URL_SYNC_VISITORS'),
        ],
    ],

    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('GITHUB_REDIRECT'),
    ],

    'postmark' => [
        'key' => env('POSTMARK_API_KEY', env('POSTMARK_TOKEN')),
        'token' => env('POSTMARK_TOKEN', env('POSTMARK_API_KEY')),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'search_console' => [
        'property' => env('SEARCH_CONSOLE_PROPERTY'),
        'sitemap_url' => env('SEARCH_CONSOLE_SITEMAP_URL'),
        'token_uri' => env('SEARCH_CONSOLE_TOKEN_URI', 'https://oauth2.googleapis.com/token'),
        'oauth' => [
            'client_id' => env('SEARCH_CONSOLE_OAUTH_CLIENT_ID'),
            'client_secret' => env('SEARCH_CONSOLE_OAUTH_CLIENT_SECRET'),
            'refresh_token' => env('SEARCH_CONSOLE_OAUTH_REFRESH_TOKEN'),
        ],
        'service_account' => [
            'client_email' => env('SEARCH_CONSOLE_SERVICE_ACCOUNT_EMAIL'),
            'private_key' => env('SEARCH_CONSOLE_SERVICE_ACCOUNT_PRIVATE_KEY'),
        ],
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
