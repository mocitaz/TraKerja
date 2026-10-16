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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
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

    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT_URI'),
    ],

    'linkedin-openid' => [
        'client_id'     => env('LINKEDIN_CLIENT_ID'),
        'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
        'redirect'      => env('LINKEDIN_REDIRECT_URI'),
    ],

    'turnstile' => [
        'site_key' => env('TURNSTILE_SITE_KEY', '0x4AAAAAADVwvVUur2OE6_b9'),
        'secret_key' => env('TURNSTILE_SECRET_KEY', '0x4AAAAAADVwvVXbLO-XxaY8vtaI7CMsw28'),
    ],

    'photo_api' => [
        'url' => env('PHOTO_API_URL', 'https://aiphoto.apitrakerja.online'),
    ],

    'admin' => [
        'email' => env('ADMIN_EMAIL', 'infoteknalogi@gmail.com'),
    ],

    'telegram' => [
        'bot_token' => env('TELEGRAM_BOT_TOKEN'),
        'channel_id' => env('TELEGRAM_CHANNEL_ID'),
        'enabled' => env('TELEGRAM_NOTIF_ENABLED', false),
        'webhook_secret' => env('TELEGRAM_WEBHOOK_SECRET'),
        'allowed_user_ids' => array_values(array_filter(array_map('trim', explode(',', (string) env('TELEGRAM_ALLOWED_USER_IDS', ''))), fn ($id) => $id !== '')),
        'allowed_chat_ids' => array_values(array_filter(array_map('trim', explode(',', (string) env('TELEGRAM_ALLOWED_CHAT_IDS', ''))), fn ($id) => $id !== '')),
    ],

    'gemini' => [
        'key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-flash-lite-latest'),
        'studio_models' => [
            'analyzer' => env('GEMINI_STUDIO_ANALYZER_MODEL', env('GEMINI_MODEL', 'gemini-flash-lite-latest')),
            'cover-letter' => env('GEMINI_STUDIO_WRITING_MODEL', env('GEMINI_MODEL', 'gemini-flash-lite-latest')),
            'outreach' => env('GEMINI_STUDIO_WRITING_MODEL', env('GEMINI_MODEL', 'gemini-flash-lite-latest')),
        ],
    ],

    'openai' => [
        'key' => env('OPENAI_API_KEY'),
    ],

];

