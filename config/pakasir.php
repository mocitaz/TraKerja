<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pakasir API Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Pakasir project slug and API key.
    | These can be found in your Pakasir Project details page.
    |
    */

    'slug' => env('PAKASIR_SLUG', 'depodomain'),
    'api_key' => env('PAKASIR_API_KEY', ''),
    'webhook_secret' => env('PAKASIR_WEBHOOK_SECRET', ''),
    'sandbox' => filter_var(env('PAKASIR_SANDBOX', false), FILTER_VALIDATE_BOOLEAN),
    
    /*
    |--------------------------------------------------------------------------
    | Premium Configuration
    |--------------------------------------------------------------------------
    */
    
    'premium_price' => env('PREMIUM_PRICE', 9999),
    'premium_duration_days' => env('PREMIUM_DURATION_DAYS', 36500), // Default to 100 years (lifetime)
];
