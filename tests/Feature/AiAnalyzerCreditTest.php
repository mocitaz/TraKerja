<?php

namespace Tests\Feature;

use App\Jobs\ProcessAiStudio;
use App\Models\{AiAnalyzerResult, AiStudioRun, CoverLetter, Setting, User};
use App\Services\AiStudioProcessingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\{Http, Queue, Storage};
use Illuminate\Support\Str;
use Tests\TestCase;

class AiAnalyzerCreditTest extends TestCase
{
    use RefreshDatabase;

    public function createApplication()
    {
        $app = require dirname(__DIR__, 2).'/bootstrap/app.php';
        $app->useEnvironmentPath('/dev/null');
        $this->traitsUsedByTest = array_flip(class_uses_recursive(static::class));
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        config(['app.key' => str_repeat('a', 32)]);
        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake(); Storage::fake('local'); Http::preventStrayRequests();
        Setting::set('monetization_enabled', true);
        config(['services.gemini.key' => 'test-not-a-real-key', 'services.gemini.model' => 'gemini-test', 'services.gemini.studio_models' => []]);
    }

    private function input(string $task = 'analyzer', ?string $key = null): array
    {
        $data = ['request_key' => $key ?? (string) Str::uuid(), 'job_description' => str_repeat('Build reliable software and maintain APIs. ', 3)];
        if ($task === 'analyzer') $data['resume'] = UploadedFile::fake()->createWithContent('resume.pdf', "%PDF-1.4\n test CV");
        else $data += ['company_name' => 'Actual company', 'job_title' => 'Engineer', 'language' => 'en', 'tone' => 'professional', 'length' => 'short'];
        return $data;
    }

    public static function reply(array $data, string $finish = 'STOP'): array
    {
        return ['candidates' => [['finishReason' => $finish, 'content' => ['parts' => [['text' => json_encode($data)]]]]]];
    }

    public static function analysis(): array
    {
        return ['readable' => true, 'score' => 60, 'summary' => 'Relevant API experience, deployment evidence missing.',
            'findings' => [['requirement' => 'API development', 'evidence' => 'Maintained APIs at Actual company', 'status' => 'supported', 'recommendation' => 'Clarify the API scope.']],
            'actions' => ['Explain the API scope without inventing metrics.'], 'limitations' => ['Deployment experience is not mentioned.']];
    }

    public function test_duplicate_request_reserves_one_credit_and_never_calls_old_provider(): void
    {
        $user = User::factory()->create(['ai_credits' => 1]);
        $input = $this->input();
        $this->actingAs($user)->postJson(route('ai-analyzer.analyze'), $input)->assertAccepted()->assertJsonPath('status', 'queued');
        $this->postJson(route('ai-analyzer.analyze'), $input)->assertAccepted()->assertJsonPath('request_id', 1);
        $this->postJson(route('ai-analyzer.analyze'), $this->input())->assertUnprocessable();
        $this->assertSame(0, $user->fresh()->ai_credits);
        $this->assertSame(1, AiStudioRun::count()); Queue::assertPushed(ProcessAiStudio::class, 1); Http::assertNothingSent();
        Http::fake(function ($request) {
            $this->assertStringStartsWith('https://generativelanguage.googleapis.com/', $request->url());
            $this->assertArrayHasKey('inlineData', $request['contents'][0]['parts'][1]);
            return Http::response(self::reply(self::analysis()));
        });
        app(AiStudioProcessingService::class)->process(1);
        app(AiStudioProcessingService::class)->process(1);
        $this->assertSame(1, AiAnalyzerResult::count());
        $this->assertSame('consumed', AiStudioRun::first()->credit_state);
        $this->assertSame([], AiStudioRun::first()->payload);
        $this->assertSame([], Storage::disk('local')->allFiles()); Http::assertSentCount(1);
    }

    public function test_cover_letter_reserves_before_provider_and_only_charges_once(): void
    {
        $user = User::factory()->create(['cl_credits' => 1]);
        $this->actingAs($user)->postJson(route('cover-letters.generate'), $this->input('cover-letter'))->assertAccepted();
        $this->postJson(route('cover-letters.generate'), $this->input('cover-letter'))->assertUnprocessable();
        Http::fake(function () use ($user) {
            $this->assertSame(0, $user->fresh()->cl_credits);
            return Http::response(self::reply(['content' => 'Dear team, I am applying for the Engineer role at Actual company. Thank you for considering my application.']));
        });
        app(AiStudioProcessingService::class)->process(1);
        $this->assertSame(1, CoverLetter::count()); $this->assertSame(0, $user->fresh()->cl_credits);
        $this->get(route('ai-studio.status', 1))->assertOk()->assertJsonPath('status', 'completed')->assertJsonMissingPath('payload');
    }

    public static function invalidResults(): array
    {
        return [
            'empty' => [[], 'STOP'], 'error' => [['error' => 'bad'], 'STOP'],
            'empty analysis' => [['readable' => true, 'score' => 90, 'summary' => 'No evidence', 'findings' => [], 'actions' => [], 'limitations' => []], 'STOP'],
            'truncated' => [self::analysis(), 'MAX_TOKENS'],
            'unreadable' => [array_replace(self::analysis(), ['readable' => false]), 'STOP'],
            'invalid score' => [array_replace(self::analysis(), ['score' => 101]), 'STOP'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('invalidResults')]
    public function test_invalid_results_refund_without_losing_a_concurrent_topup(array $data, string $finish): void
    {
        $user = User::factory()->create(['ai_credits' => 1]);
        $this->actingAs($user)->postJson(route('ai-analyzer.analyze'), $this->input())->assertAccepted();
        Http::fake(function () use ($user, $data, $finish) {
            $user->addAiCredits(5);
            return Http::response(self::reply($data, $finish));
        });
        $service = app(AiStudioProcessingService::class); $service->process(1); $service->fail(1, 'processing');
        $this->assertSame(6, $user->fresh()->ai_credits);
        $this->assertSame('refunded', AiStudioRun::first()->credit_state);
        $this->assertSame(0, AiAnalyzerResult::count());
    }

    public function test_failures_retry_transient_only_and_keep_private_inputs_for_explicit_retry(): void
    {
        $user = User::factory()->create(['ai_credits' => 1]);
        $this->actingAs($user)->postJson(route('ai-analyzer.analyze'), $this->input())->assertAccepted();
        $status = 503;
        Http::fake(function () use (&$status) { return Http::response(['error' => ['message' => 'private provider detail']], $status); });
        $service = app(AiStudioProcessingService::class); $service->process(1);
        Http::assertSentCount(2);
        $this->assertSame(1, $user->fresh()->ai_credits);
        $this->get(route('ai-studio.status', 1))->assertOk()->assertJsonPath('status', 'failed')->assertDontSee('private provider detail');
        $this->postJson(route('ai-studio.retry', 1), ['request_key' => (string) Str::uuid()])->assertAccepted()->assertJsonPath('request_id', 2);
        $this->assertSame(0, $user->fresh()->ai_credits);
        $status = 404;
        $service->process(2); Http::assertSentCount(3);
        $this->assertSame(1, $user->fresh()->ai_credits);
    }

    public function test_no_key_and_rate_limits_fail_safely_without_local_template(): void
    {
        $user = User::factory()->create(['cl_credits' => 1]);
        $this->actingAs($user)->postJson(route('cover-letters.generate'), $this->input('cover-letter'))->assertAccepted();
        config(['services.gemini.key' => null]); app(AiStudioProcessingService::class)->process(1);
        Http::assertNothingSent(); $this->assertSame(0, CoverLetter::count());
        $this->assertSame(1, $user->fresh()->cl_credits);
        config(['services.gemini.key' => 'test']);
        $this->postJson(route('cover-letters.generate'), $this->input('cover-letter'))->assertAccepted();
        Http::fake(['*' => Http::response([], 429)]); app(AiStudioProcessingService::class)->process(2);
        Http::assertSentCount(1); $this->assertSame(1, $user->fresh()->cl_credits);
    }

    public function test_free_mode_and_expired_worker_cannot_publish_after_refund(): void
    {
        Setting::set('monetization_enabled', false);
        $user = User::factory()->create(['ai_credits' => 0]);
        $this->actingAs($user)->postJson(route('ai-analyzer.analyze'), $this->input())->assertAccepted();
        Http::fake(function () {
            app(AiStudioProcessingService::class)->fail(1, 'expired');
            return Http::response(self::reply(self::analysis()));
        });
        app(AiStudioProcessingService::class)->process(1);
        $this->assertSame(0, $user->fresh()->ai_credits);
        $this->assertSame('failed', AiStudioRun::first()->status);
        $this->assertSame('none', AiStudioRun::first()->credit_state);
        $this->assertSame(0, AiAnalyzerResult::count());
    }

    public function test_expiry_recovery_refunds_once_and_cleans_old_pdf(): void
    {
        $user = User::factory()->create(['ai_credits' => 1]);
        $this->actingAs($user)->postJson(route('ai-analyzer.analyze'), $this->input())->assertAccepted();
        $this->travel(31)->minutes(); $service = app(AiStudioProcessingService::class); $service->recover(); $service->recover();
        $this->assertSame(1, $user->fresh()->ai_credits); $this->assertSame('failed', AiStudioRun::first()->status);
        $this->travel(8)->days(); $service->recover();
        $this->assertSame([], Storage::disk('local')->allFiles()); $this->assertSame([], AiStudioRun::first()->payload);
    }

    public function test_database_failure_never_keeps_the_reserved_credit(): void
    {
        $user = User::factory()->create(['cl_credits' => 1]);
        $this->actingAs($user)->postJson(route('cover-letters.generate'), $this->input('cover-letter'))->assertAccepted();
        Http::fake(['*' => Http::response(self::reply(['content' => str_repeat('Valid letter content. ', 5)]))]);
        CoverLetter::creating(fn () => throw new \RuntimeException('save failed'));
        try { app(AiStudioProcessingService::class)->process(1); }
        finally { CoverLetter::flushEventListeners(); }
        $this->assertSame(1, $user->fresh()->cl_credits); $this->assertSame(0, CoverLetter::count());
        $this->assertSame('refunded', AiStudioRun::first()->credit_state);
    }
    public function test_lost_queue_dispatch_refunds_the_reservation(): void
    {
        $user = User::factory()->create(['cl_credits' => 1]);
        \Illuminate\Support\Facades\Bus::shouldReceive('dispatch')->once()->andThrow(new \RuntimeException('queue offline'));
        $this->actingAs($user)->postJson(route('cover-letters.generate'), $this->input('cover-letter'))->assertAccepted()->assertJsonPath('status', 'failed');
        $this->assertSame(1, $user->fresh()->cl_credits);
        $this->assertSame('refunded', AiStudioRun::first()->credit_state);
        Http::assertNothingSent();
    }

    public function test_connection_failure_and_empty_writing_result_refund(): void
    {
        $user = User::factory()->create(['cl_credits' => 1]);
        $this->actingAs($user)->postJson(route('cover-letters.generate'), $this->input('cover-letter'))->assertAccepted();
        $connectionFailure = true;
        Http::fake(function () use (&$connectionFailure) {
            if ($connectionFailure) throw new \Illuminate\Http\Client\ConnectionException('test timeout');
            return Http::response(self::reply(['content' => '']));
        });
        app(AiStudioProcessingService::class)->process(1);
        $this->assertSame(1, $user->fresh()->cl_credits);
        $connectionFailure = false;
        $this->postJson(route('cover-letters.generate'), $this->input('cover-letter'))->assertAccepted();
        app(AiStudioProcessingService::class)->process(2);
        $this->assertSame(1, $user->fresh()->cl_credits);
        $this->assertSame(0, CoverLetter::count());
    }

    public function test_account_cleanup_removes_only_the_owners_studio_sources(): void
    {
        $user = User::factory()->create();
        Storage::disk('local')->put('ai-studio-sources/'.$user->id.'/cv.pdf', 'private source');
        Storage::disk('local')->put('ai-studio-sources/999/cv.pdf', 'other source');
        app(\App\Services\AccountResumeCleanup::class)->clean($user);
        $this->assertFalse(Storage::disk('local')->exists('ai-studio-sources/'.$user->id.'/cv.pdf'));
        $this->assertTrue(Storage::disk('local')->exists('ai-studio-sources/999/cv.pdf'));
    }

    public function test_recovery_skips_unmigrated_database_without_dispatching_jobs(): void
    {
        \Illuminate\Support\Facades\Schema::drop('ai_studio_runs');
        app(AiStudioProcessingService::class)->recover();
        Queue::assertNothingPushed();
        $this->artisan('ai-studio:recover')
            ->expectsOutputToContain('AI Studio recovery skipped')
            ->assertSuccessful();
        Http::assertNothingSent();
    }

}
