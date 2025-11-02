<?php

return [
    /*
    |--------------------------------------------------------------------------
    | YUKK Payment Gateway Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for YUKK Payment Gateway integration
    |
    */

    'environment' => env('YUKK_ENVIRONMENT', 'sandbox'), // sandbox or production

    'base_url' => [
        'sandbox' => 'https://dev.api.yukkpay.com',
        'production' => 'https://api.yukkpay.com',
    ],

    'web_url' => [
        'sandbox' => 'https://dev.web.yukkpay.com',
        'production' => 'https://web.yukkpay.com',
    ],

    'client_id' => env('YUKK_CLIENT_ID', 'bdd79914-832e-3fd5-9bac-11be9d90aa92'),
    
    'client_secret' => env('YUKK_CLIENT_SECRET', 'CxZ4PNfYPRDT3efwX2ErbWvnGiqGu28WSSvdHo5r'),
    
    'mid' => env('YUKK_MID', 'PG Sandbox 49197'),
    
    'scope' => 'yukk.payment-gateway',

    // Payment timeouts (in seconds)
    'timeout' => [
        'ovo' => 60,
        'credit_card' => 330,
        'va' => 86400, // 24 hours default
        'qris' => 600, // 10 minutes default
    ],

    // Default session timeout (payment channel timeout + 300 seconds)
    'session_timeout' => 1800, // 30 minutes

    // Webhook & Callback URLs
    'notification_url' => env('YUKK_NOTIFICATION_URL', env('APP_URL') . '/payment/webhook'),
    'callback_url' => env('YUKK_CALLBACK_URL', env('APP_URL') . '/payment/callback'),

    // Premium pricing
    'premium_price' => env('PREMIUM_PRICE', 15000), // Rp 15,000 default (LIFETIME)
    'premium_duration_days' => 36500, // 100 years (lifetime)
];

