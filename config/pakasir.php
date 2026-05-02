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
    
    /*
    |--------------------------------------------------------------------------
    | Premium Configuration
    |--------------------------------------------------------------------------
    */
    
    'premium_price' => env('PREMIUM_PRICE', 15000),
    'premium_duration_days' => env('PREMIUM_DURATION_DAYS', 365), // Default to 1 year or lifetime
];
