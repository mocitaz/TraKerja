<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force app URL and HTTPS only in production to avoid cross-domain issues during local dev
        if (app()->environment('production')) {
            $appUrl = config('app.url');
            if (! empty($appUrl)) {
                URL::forceRootUrl($appUrl);
                URL::forceScheme('https');
            }
        }
    }
}
