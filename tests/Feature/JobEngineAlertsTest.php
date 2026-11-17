<?php

namespace Tests\Feature;

use App\Console\Commands\JobEngineDailyReport;
use App\JobEngine\Ingest\EngineAlerts;
use App\Models\JobSource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class JobEngineAlertsTest extends TestCase
{
    use RefreshDatabase;

    public function test_alert_message_is_html_with_level_title_and_escaped_details(): void
    {
        $text = app(EngineAlerts::class)->format(EngineAlerts::CRITICAL, 'Dealls: scraper failing', ['Reason' => 'Run failed: <timeout>', 'Empty' => null]);

        $this->assertStringStartsWith('🔴 <b>JOB ENGINE · CRITICAL</b>', $text);
        $this->assertStringContainsString('<b>Dealls: scraper failing</b>', $text);
        $this->assertStringContainsString('• <b>Reason:</b> Run failed: &lt;timeout&gt;', $text);
        $this->assertStringNotContainsString('Empty', $text);
        $this->assertStringContainsString('WIB', $text);
    }

    public function test_an_open_problem_is_sent_once_then_resolved(): void
    {
        $alerts = app(EngineAlerts::class);
        $alerts->raise('watchdog:queue:ingest', EngineAlerts::CRITICAL, 'Queue "ingest" is not being processed');
        $first = Cache::get('engine_alert:watchdog:queue:ingest');
        $this->travel(30)->minutes();
        $alerts->raise('watchdog:queue:ingest', EngineAlerts::CRITICAL, 'Queue "ingest" is not being processed');

        $this->assertSame($first['sent_at'], Cache::get('engine_alert:watchdog:queue:ingest')['sent_at'], 'not repeated within 6 hours');
        $this->assertSame(['watchdog:queue:ingest'], $alerts->openKeys());

        $alerts->resolve('watchdog:queue:ingest');
        $this->assertSame([], $alerts->openKeys());
        $this->assertNull(Cache::get('engine_alert:watchdog:queue:ingest'));
    }

    public function test_watchdog_finds_a_stuck_queue_and_resolves_it_once_it_drains(): void
    {
        DB::table('jobs')->insert(['queue' => 'ingest', 'payload' => '{}', 'attempts' => 0, 'reserved_at' => null, 'available_at' => now()->subMinutes(40)->timestamp, 'created_at' => now()->subMinutes(40)->timestamp]);

        $this->artisan('jobs:watchdog --print')->expectsOutputToContain('[queue:ingest] Queue "ingest" is not being processed')->assertSuccessful();
        $this->artisan('jobs:watchdog')->assertSuccessful();
        $this->assertContains('watchdog:queue:ingest', app(EngineAlerts::class)->openKeys());

        DB::table('jobs')->delete();
        $this->artisan('jobs:watchdog')->expectsOutput('All clear.')->assertSuccessful();
        $this->assertSame([], app(EngineAlerts::class)->openKeys());
    }

    public function test_watchdog_closes_runs_left_running_by_a_dead_worker(): void
    {
        $source = JobSource::create(['slug' => 'workable', 'name' => 'Workable', 'kind' => 'ats', 'tier' => 1, 'is_active' => true, 'health' => 'ok']);
        $old = \App\Models\CrawlRun::create(['job_source_id' => $source->id, 'run_type' => 'discover', 'trigger' => 'schedule', 'status' => 'running', 'started_at' => now()->subHours(13)]);
        $live = \App\Models\CrawlRun::create(['job_source_id' => $source->id, 'run_type' => 'discover', 'trigger' => 'schedule', 'status' => 'running', 'started_at' => now()->subMinutes(5)]);

        $this->artisan('jobs:watchdog --print')->expectsOutputToContain('stuck in "running"')->assertSuccessful();
        $this->assertSame('running', $old->fresh()->status, '--print changes nothing');

        $this->artisan('jobs:watchdog')->expectsOutput('All clear.')->assertSuccessful();
        $this->assertSame('failed', $old->fresh()->status);
        $this->assertNotNull($old->fresh()->finished_at);
        $this->assertSame('running', $live->fresh()->status, 'a run inside the job timeout is left alone');
        $this->assertSame([], app(EngineAlerts::class)->openKeys(), 'reported once, not kept open');
    }

    public function test_watchdog_flags_a_rejected_openai_key(): void
    {
        foreach (range(1, 3) as $i) {
            DB::table('ai_calls')->insert(['purpose' => 'enrich', 'model' => 'gpt-5-nano', 'input_hash' => "h{$i}", 'status' => 'error', 'response' => json_encode(['error' => '{"error":{"message":"Incorrect API key provided: sk-proj-abc123","code":"invalid_api_key"}}']), 'created_at' => now()]);
        }

        $this->artisan('jobs:watchdog --print')
            ->expectsOutputToContain('OpenAI rejected the API key')
            ->doesntExpectOutputToContain('sk-proj-abc123')
            ->assertSuccessful();
    }

    public function test_source_health_change_opens_and_closes_an_alert(): void
    {
        $source = JobSource::create(['slug' => 'demo', 'name' => 'Demo Source', 'kind' => 'api', 'tier' => 1, 'is_active' => true, 'health' => 'ok']);
        $health = app(\App\JobEngine\Ingest\SourceHealth::class);
        $run = fn (string $status) => \App\Models\CrawlRun::create(['job_source_id' => $source->id, 'run_type' => 'discover', 'trigger' => 'test', 'status' => $status, 'started_at' => now(), 'finished_at' => now(), 'error_summary' => $status === 'failed' ? 'HTTP 503' : null]);

        $health->afterRun($source, $run('failed'), 100);
        $this->assertContains('source-health:demo', app(EngineAlerts::class)->openKeys());
        $this->assertStringStartsWith('Run failed', $source->fresh()->health_reason);

        $health->afterRun($source->fresh(), $run('success'), 100);
        $this->assertNotContains('source-health:demo', app(EngineAlerts::class)->openKeys());
    }

    public function test_daily_report_is_html(): void
    {
        $text = JobEngineDailyReport::format(['open' => 1200, 'new' => 40, 'closed' => 3, 'runs' => 30, 'failed_runs' => 1, 'problem_sources' => ['Dealls' => 'failing'], 'ai_usd' => 0.12, 'failed_jobs' => 0, 'pending_pairs' => 2, 'reports' => 0, 'anomalies' => []]);

        $this->assertStringContainsString('<b>JOB ENGINE · DAILY REPORT</b>', $text);
        $this->assertStringContainsString('Open jobs: <b>1.200</b>', $text);
        $this->assertStringContainsString('Dealls: failing', $text);
        $this->assertStringNotContainsString('*', $text, 'no Markdown in an HTML message');
    }
}
