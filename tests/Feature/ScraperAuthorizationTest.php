<?php

namespace Tests\Feature;

use App\Livewire\Admin\ScraperDashboard;
use App\Models\User;
use App\Models\ScraperSource;
use App\Services\DeadLinkDetector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class ScraperAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        require_once __DIR__ . '/../Support/ScraperWorkerTrap.php';
        Queue::fake();
        Http::fake();
        Http::preventStrayRequests();
    }

    public static function actions(): array
    {
        $methods = ['boot', 'mount', 'loadSources', 'editSource', 'saveSourceSettings', 'triggerManualCrawl', 'startQueueWorker',
            'verifyActiveListings', 'runTargetedScraping', 'cancelTargetedScraping', 'checkProxyHealth',
            'runVisualSandboxTest', 'runTestSandbox', 'clearLiveLogs', 'getTargetMajorsListProperty',
            'restoreReportedJob', 'closeReportedJob', 'render'];
        $cases = [];
        foreach (['guest', 'user', 'premium'] as $role) {
            foreach ($methods as $method) $cases[$role . ' ' . $method] = [$role, $method];
        }
        return $cases;
    }

    #[DataProvider('actions')]
    public function test_component_actions_reject_non_admin_before_side_effects(string $role, string $method): void
    {
        if ($role !== 'guest') $this->actingAs(User::factory()->create(['is_premium' => $role === 'premium', 'payment_status' => $role === 'premium' ? 'paid' : 'free']));
        $component = new ScraperDashboard;
        $component->delay_between_requests_ms = 1000;
        $component->max_concurrency = 1;
        $component->frequency_minutes = 60;
        $component->is_active = true;
        $component->targetSector = 'Synthetic sector';
        $component->targetMajor = 'Synthetic major';
        $component->sandboxUrl = 'https://example.test/job';
        $component->sandboxTitleSelector = 'h1';
        $component->testUrl = 'https://example.test/job';
        $component->selectedSourceId = 1;
        $args = match ($method) {
            'editSource', 'restoreReportedJob', 'closeReportedJob' => [1],
            'verifyActiveListings', 'runTestSandbox' => [app(DeadLinkDetector::class)],
            default => [],
        };
        $denied = false;
        try {
            $component->$method(...$args);
        } catch (HttpException $e) {
            $this->assertSame(403, $e->getStatusCode());
            $denied = true;
        }
        $this->assertTrue($denied, 'Action must check admin role before work or validation');
        $this->assertDatabaseCount('scraper_sources', 0);
        Http::assertNothingSent();
        Queue::assertNothingPushed();
    }

    public function test_admin_opening_dashboard_does_not_seed_sources(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))->get('/admin/scraper')->assertOk();
        $this->assertDatabaseCount('scraper_sources', 0);
    }

    public function test_sources_are_seeded_explicitly(): void
    {
        $this->seed(\Database\Seeders\ScraperSourceSeeder::class);
        $this->assertSame(3, ScraperSource::count());
        $this->seed(\Database\Seeders\ScraperSourceSeeder::class);
        $this->assertSame(3, ScraperSource::count());
    }
}
