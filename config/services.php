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

    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('GITHUB_REDIRECT'),
    ],

    'horizon' => [
        'token' => env('HORIZON_TOKEN'),
    ],

    'pirsch' => [
        'access_key' => env('PIRSCH_ACCESS_KEY'),
        'client_id' => env('PIRSCH_CLIENT_ID'),
        'client_secret' => env('PIRSCH_CLIENT_SECRET'),
        'domain_id' => env('PIRSCH_DOMAIN_ID'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'cloudflare_images' => [
        'account_id' => env('CLOUDFLARE_IMAGES_ACCOUNT_ID'),
        'account_hash' => env('CLOUDFLARE_IMAGES_ACCOUNT_HASH'),
        'token' => env('CLOUDFLARE_API_TOKEN'),
    ],

    'forge' => [
        'heatbeats' => [
            'generate-sitemap' => env('FORGE_HEARTBEAT_URL_GENERATE_SITEMAP'),
            'ingest-job-feeds' => env('FORGE_HEARTBEAT_URL_INJEST_JOB_FEEDS'),
            'refresh-user-data' => env('FORGE_HEARTBEAT_URL_REFRESH_USER_DATA'),
            'sync-visitors' => env('FORGE_HEARTBEAT_URL_SYNC_VISITORS'),
            'purge-unconfirmed-subscribers' => env('FORGE_HEARTBEAT_URL_PURGE_UNCONFIRMED_SUBSCRIBERS'),
        ],
    ],

    'smartproxy' => [
        'proxy_username' => env('SMARTPROXY_PROXY_USERNAME'),
        'proxy_password' => env('SMARTPROXY_PROXY_PASSWORD'),
    ],

];
