<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RetiredAdminScraperTest extends TestCase
{
    use RefreshDatabase;

    public function test_scraper_dashboard_and_navigation_are_removed(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $this->get('/admin/scraper')->assertNotFound();
        $this->assertFalse(Route::has('admin.scraper'));
        $this->get('/admin')->assertOk()->assertDontSee('/admin/scraper', false);
        $this->assertFalse(class_exists(\App\Livewire\Admin\ScraperDashboard::class));
    }

    public function test_old_crawl_command_and_automatic_schedule_are_removed(): void
    {
        $this->assertArrayNotHasKey('jobs:run-scraper', Artisan::all());
        foreach (app(Schedule::class)->events() as $event) {
            $this->assertStringNotContainsString('jobs:run-scraper', $event->command ?? '');
        }
    }

    public function test_user_coming_soon_page_and_tracker_import_routes_are_preserved(): void
    {
        Setting::set('explore_maintenance', true);
        $this->actingAs(User::factory()->create(['email_verified_at' => now()]));
        $this->get(route('jobs.explore'))->assertOk()->assertSee('Something massive is cooking');
        $this->assertTrue(Route::has('jobs.scrape-url'));
        $this->assertTrue(Route::has('jobs.scrape-html'));
    }
}
