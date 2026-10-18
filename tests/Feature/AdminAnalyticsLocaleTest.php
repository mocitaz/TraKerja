<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AdminAnalyticsLocaleTest extends TestCase
{
    use RefreshDatabase;

    public function createApplication()
    {
        $app = require dirname(__DIR__, 2).'/bootstrap/app.php';
        $app->useEnvironmentPath('/dev/null');
        $this->traitsUsedByTest = array_flip(class_uses_recursive(static::class));
        $app->make(Kernel::class)->bootstrap();
        config(['app.key' => str_repeat('t', 32)]);
        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
        $this->travelTo(Carbon::parse('2026-10-18 12:00:00', 'Asia/Jakarta'));
    }

    public function test_indonesian_admin_analytics_renders_translated_cohort_months(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_admin' => true, 'locale' => 'id']);

        $this->actingAs($admin)->get(route('admin.analytics'))
            ->assertOk()->assertSee('Mei 2026')->assertSee('Okt 2026');
    }

    public function test_cached_analytics_does_not_reuse_another_admins_language(): void
    {
        $english = User::factory()->create(['role' => 'admin', 'is_admin' => true, 'locale' => 'en']);
        $indonesian = User::factory()->create(['role' => 'admin', 'is_admin' => true, 'locale' => 'id']);

        $this->actingAs($english)->get(route('admin.analytics'))->assertOk()->assertSee('May 2026');
        $this->actingAs($indonesian)->get(route('admin.analytics'))->assertOk()->assertSee('Mei 2026');
        $this->actingAs($english)->get(route('admin.analytics'))->assertOk()->assertSee('May 2026');
    }

    public function test_retention_window_is_calculated_from_dates_at_month_end(): void
    {
        $this->travelTo(Carbon::parse('2026-10-31 12:00:00', 'Asia/Jakarta'));
        app()->setLocale('id');
        $member = User::factory()->create(['created_at' => '2026-05-10 12:00:00']);
        \App\Models\UserActivity::forceCreate([
            'user_id' => $member->id, 'activity_type' => 'job_add', 'description' => 'Synthetic event',
            'status' => 'success', 'created_at' => '2026-06-10 12:00:00',
        ]);
        $component = new \App\Livewire\Admin\Analytics;
        $cohorts = (new \ReflectionMethod($component, 'fetchRetentionCohort'))->invoke($component);

        $this->assertSame(['Mei 2026', 'Jun 2026', 'Jul 2026', 'Agt 2026', 'Sep 2026', 'Okt 2026'], array_column($cohorts, 'month'));
        $this->assertSame([true, true, true, true, false, false], array_column($cohorts, 'mature'));
        $this->assertSame(1, $cohorts[0]['retained']);
        $this->assertSame(100.0, $cohorts[0]['rate']);
    }

    public function test_each_reporting_period_renders_in_both_languages(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_admin' => true]);
        foreach (['en', 'id'] as $locale) {
            app()->setLocale($locale);
            $component = \Livewire\Livewire::actingAs($admin)->test(\App\Livewire\Admin\Analytics::class);
            foreach (['all', '7', '30', '90'] as $period) {
                $component->call('setPeriod', $period)->assertSet('periodFilter', $period)->assertHasNoErrors();
            }
        }
    }

    public function test_analytics_still_requires_an_admin_account(): void
    {
        $this->get(route('admin.analytics'))->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create())->get(route('admin.analytics'))->assertForbidden();
    }
}
