<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Models\JobApplication;
use App\Models\UserGoal;
use App\Observers\JobApplicationObserver;
use App\Observers\UserGoalObserver;
use App\Observers\CvDataObserver;
use App\Services\ActivityLogger;
use App\Models\UserExperience;
use App\Models\UserEducation;
use App\Models\UserSkill;
use App\Models\UserProject;
use App\Models\UserOrganization;
use App\Models\UserAchievement;

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
        Carbon::setLocale(config('app.locale', 'id'));
        date_default_timezone_set(config('app.timezone', 'Asia/Jakarta'));
        
        // Register Observers
        JobApplication::observe(JobApplicationObserver::class);
        UserGoal::observe(UserGoalObserver::class);
        
        // CV Builder Observers
        UserExperience::observe(CvDataObserver::class);
        UserEducation::observe(CvDataObserver::class);
        UserSkill::observe(CvDataObserver::class);
        UserProject::observe(CvDataObserver::class);
        UserOrganization::observe(CvDataObserver::class);
        UserAchievement::observe(CvDataObserver::class);

        // Register Auth Event Listeners
        Event::listen(function (Login $event) {
            ActivityLogger::log('login', 'User berhasil login ke sistem', 'success', [], $event->user->id);
        });

        Event::listen(function (Logout $event) {
            if ($event->user) {
                ActivityLogger::log('logout', 'User keluar dari sistem', 'success', [], $event->user->id);
            }
        });
        
        // Force app URL and HTTPS only in production to avoid cross-domain issues during local dev
        if (app()->environment('production')) {
            $appUrl = config('app.url');
            if (! empty($appUrl)) {
                URL::forceRootUrl($appUrl);
                URL::forceScheme('https');
            }
            
            // Note: Livewire assets should be served as static files
            // Files are in public_html/livewire/ and public_html/vendor/livewire/
            // .htaccess will handle serving these files directly
        }
    }
}
