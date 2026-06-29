<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Scraper Headless Browser Microservice URL
    |--------------------------------------------------------------------------
    |
    | The URL where the headless Express.js + Puppeteer rendering microservice
    | is running. If null or unreachable, the system will fall back to Guzzle.
    |
    */
    'microservice_url' => env('SCRAPER_MICROSERVICE_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Rotating Proxy List
    |--------------------------------------------------------------------------
    |
    | A list of proxies used to rotate IP addresses during Guzzle direct
    | HTTP GET requests to prevent Cloudflare/anti-bot rate limiting.
    |
    */
    'proxies' => env('SCRAPER_PROXIES') ? explode(',', env('SCRAPER_PROXIES')) : [],
];
