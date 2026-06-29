<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ScraperSource;
use App\Models\JobPosting;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Cache;

class ScraperDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user to test restricted dashboard routes
        $this->adminUser = User::factory()->create([
            'role' => 'admin',
        ]);
    }

    /** @test */
    public function admin_can_access_scraper_dashboard()
    {
        $response = $this->actingAs($this->adminUser)
            ->get('/admin/scraper');

        $response->assertStatus(200);
        $response->assertSee('Scraper Engine');
    }

    /** @test */
    public function dashboard_interactive_tabs_transition_correctly()
    {
        $component = Livewire::actingAs($this->adminUser)
            ->test(\App\Livewire\Admin\ScraperDashboard::class);

        // Verify default tab shows Dashboard stats and metrics
        $component->assertSet('activeTab', 'dashboard');
        $component->assertSee('Metrik Grafik Performa Platform');

        // Transition to Control Tab
        $component->set('activeTab', 'control');
        $component->assertSet('activeTab', 'control');
        $component->assertSee('Proxy Pool Monitor');
        $component->assertSee('Queue Worker Trigger');

        // Transition to Target Tab
        $component->set('activeTab', 'target');
        $component->assertSet('activeTab', 'target');
        $component->assertSee('Pilih Sektor Ekonomi');

        // Transition to Sandbox Tab
        $component->set('activeTab', 'sandbox');
        $component->assertSet('activeTab', 'sandbox');
        $component->assertSee('DOM Selectors Configuration');
    }

    /** @test */
    public function admin_can_dispatch_targeted_scraping_mission()
    {
        Queue::fake();

        $component = Livewire::actingAs($this->adminUser)
            ->test(\App\Livewire\Admin\ScraperDashboard::class)
            ->set('targetJobsCount', 45)
            ->set('targetSector', 'Sektor Informasi dan Komunikasi (TIK)')
            ->set('targetMajor', 'Teknik Informatika')
            ->call('runTargetedScraping');

        $component->assertHasNoErrors();
        Queue::assertPushed(\App\Jobs\TargetedScraperJob::class, function ($job) {
            return $job->target === 45 && $job->sector === 'Sektor Informasi dan Komunikasi (TIK)' && $job->major === 'Teknik Informatika';
        });
    }

    /** @test */
    public function admin_can_trigger_visual_sandbox_test()
    {
        $component = Livewire::actingAs($this->adminUser)
            ->test(\App\Livewire\Admin\ScraperDashboard::class)
            ->set('sandboxUrl', 'https://id.linkedin.com/jobs/view/12345')
            ->set('sandboxTitleSelector', 'h1.topcard__title')
            ->call('runVisualSandboxTest');

        $component->assertHasNoErrors();
        $this->assertTrue(count($component->get('sandboxLogs')) > 0);
    }

    /** @test */
    public function admin_can_check_proxy_health_status()
    {
        // Mock proxy config values
        config(['scraper.proxies' => ['http://127.0.0.1:8080']]);

        $component = Livewire::actingAs($this->adminUser)
            ->test(\App\Livewire\Admin\ScraperDashboard::class)
            ->call('checkProxyHealth');

        $component->assertHasNoErrors();
        $this->assertTrue(count($component->get('proxyStatusList')) > 0);
    }

    /** @test */
    public function admin_can_moderate_reported_expired_jobs()
    {
        $source = ScraperSource::create([
            'name' => 'LinkedIn Scraper',
            'target_domain' => 'linkedin.com',
            'seed_url' => 'https://linkedin.com',
            'selectors_config' => ['title' => '.job-title'],
            'is_active' => true,
        ]);

        $job = JobPosting::create([
            'scraper_source_id' => $source->id,
            'title' => 'Ruby Dev',
            'company_name' => 'RubyCorp',
            'description' => 'Ruby details',
            'raw_url' => 'https://linkedin.com/jobs/view/9991',
            'unique_hash' => md5('https://linkedin.com/jobs/view/9991'),
            'status' => 'active',
            'report_dead_count' => 3,
        ]);

        $component = Livewire::actingAs($this->adminUser)
            ->test(\App\Livewire\Admin\ScraperDashboard::class)
            ->set('activeTab', 'reports')
            ->assertSee('Moderasi Laporan')
            ->assertSee('Ruby Dev')
            ->call('restoreReportedJob', $job->id)
            ->assertSee('Berhasil memulihkan lowongan');

        $this->assertEquals(0, $job->fresh()->report_dead_count);
        $this->assertEquals('active', $job->fresh()->status);

        $component->call('closeReportedJob', $job->id)
            ->assertSee('Berhasil menutup/mengarsipkan lowongan');

        $this->assertEquals('closed', $job->fresh()->status);
    }
}
