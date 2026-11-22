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
        // Local and staging copies often share the production bot and channel; they stay silent unless this is on.
        'allow_non_production' => (bool) env('TELEGRAM_ALLOW_NON_PRODUCTION', false),
        'webhook_secret' => env('TELEGRAM_WEBHOOK_SECRET'),
        'allowed_user_ids' => array_values(array_filter(array_map('trim', explode(',', (string) env('TELEGRAM_ALLOWED_USER_IDS', ''))), fn ($id) => $id !== '')),
        'allowed_chat_ids' => array_values(array_filter(array_map('trim', explode(',', (string) env('TELEGRAM_ALLOWED_CHAT_IDS', ''))), fn ($id) => $id !== '')),
    ],

    // Jooble official job API (free key: https://jooble.org/api/about). Used by the job engine.
    'jooble' => [
        'key' => env('JOOBLE_API_KEY'),
    ],

    'openai' => [
        // User-facing AI (Nara, Nara Admin, AI Studio, email templates, copilot). Same key as the job engine unless
        // OPENAI_API_KEY is set; these calls are not counted in the job engine's daily budget.
        'key' => env('OPENAI_API_KEY') ?: env('OPENAI_JOBS_API_KEY'),
        'model' => env('OPENAI_MODEL', 'gpt-5-mini'),
        'fallback_model' => env('OPENAI_MODEL_FALLBACK', 'gpt-5-nano'),
        // minimal | low | medium | high. "low" keeps replies around 10 s; "medium" was about twice as slow for drafts
        // and admin analysis without a visible gain. Heavy tasks (evaluating answers, drafts, strategy) can get their own
        // effort and OPENAI_STRONG_MODEL can name a larger model for them (empty: same model).
        'reasoning_effort' => env('OPENAI_REASONING_EFFORT', 'low'),
        'strong_reasoning_effort' => env('OPENAI_STRONG_REASONING_EFFORT', 'low'),
        'strong_model' => env('OPENAI_STRONG_MODEL'),
        'nara_tools' => env('NARA_TOOLS', true),
        'email_model' => env('OPENAI_EMAIL_MODEL'),
        // The CV review runs in the background: it can think longer for a better review (about 30-60 s).
        'studio_reasoning_effort' => env('OPENAI_STUDIO_ANALYZER_REASONING_EFFORT', 'medium'),
        'studio_models' => [
            'analyzer' => env('OPENAI_STUDIO_ANALYZER_MODEL'),
            'cover-letter' => env('OPENAI_STUDIO_WRITING_MODEL'),
            'outreach' => env('OPENAI_STUDIO_WRITING_MODEL'),
        ],

        // Job-data engine only (scraping, extraction, cleanup). Uses its own key so it never
        // activates the admin copilot's OpenAI fallback, and has its own daily spending cap.
        'jobs' => [
            'key' => env('OPENAI_JOBS_API_KEY'),
            'model' => env('OPENAI_JOBS_MODEL', 'gpt-5-nano'),
            'fallback_model' => env('OPENAI_JOBS_MODEL_FALLBACK', 'gpt-5-mini'),
            'embedding_model' => env('OPENAI_JOBS_EMBEDDING_MODEL', 'text-embedding-3-small'),
            // 'flex' is about half the standard price in exchange for slower, occasionally queued responses;
            // fine for background extraction. Use 'default' only for interactive needs.
            'service_tier' => env('OPENAI_JOBS_SERVICE_TIER', 'flex'),
            'daily_budget_usd' => (float) env('OPENAI_JOBS_DAILY_BUDGET_USD', 0.25),
        ],
    ],

];

