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
        config(['services.openai.key' => 'test-not-a-real-key', 'services.openai.model' => 'gpt-5-mini', 'services.openai.studio_models' => []]);
    }

    private function input(string $task = 'analyzer', ?string $key = null): array
    {
        $data = ['request_key' => $key ?? (string) Str::uuid(), 'job_description' => str_repeat('Build reliable software and maintain APIs. ', 3)];
        if ($task === 'analyzer') $data['resume'] = UploadedFile::fake()->createWithContent('resume.pdf', "%PDF-1.4\n test CV");
        else $data += ['company_name' => 'Actual company', 'job_title' => 'Engineer', 'language' => 'en', 'tone' => 'professional', 'length' => 'short'];
        return $data;
    }

    public static function reply(array $data, string $finish = 'stop'): array
    {
        return ['choices' => [['index' => 0, 'message' => ['role' => 'assistant', 'content' => json_encode($data)], 'finish_reason' => $finish]]];
    }

    /** A recruiter-style review as the model returns it (schema v3). */
    public static function analysis(): array
    {
        return ['readable' => true, 'jd_quality' => 'full', 'verdict' => 'polish', 'verdict_reason' => 'You have relevant API work. Show deployment before applying.',
            'first_impression' => ['reads_as' => 'Backend engineer, mid level', 'standout' => 'Maintained APIs at Actual company', 'mismatch' => ''],
            'score_breakdown' => [
                ['dimension' => 'skills', 'score' => 70, 'note' => 'APIs match.'], ['dimension' => 'seniority', 'score' => 60, 'note' => 'Mid level.'],
                ['dimension' => 'impact', 'score' => 40, 'note' => 'No numbers.'], ['dimension' => 'leadership', 'score' => 50, 'note' => 'Some ownership.'],
                ['dimension' => 'clarity', 'score' => 80, 'note' => 'Clean layout.'],
            ],
            'strengths' => [['point' => 'Real API maintenance', 'evidence' => 'Maintained APIs at Actual company']],
            'findings' => [['requirement' => 'API development', 'source' => 'job_description', 'priority' => 'must', 'status' => 'supported', 'evidence' => 'Maintained APIs at Actual company', 'recommendation' => 'Clarify the API scope.']],
            'rewrites' => [['before' => 'Maintained APIs', 'after' => 'Maintained [number] APIs serving [number of users]', 'why' => 'Shows scale.']],
            'keywords' => ['present' => ['API'], 'missing' => ['Deployment']],
            'red_flags' => [], 'interview_questions' => [['question' => 'How did you deploy?', 'why' => 'Not in CV', 'how_to_answer' => 'Use a real release.']],
            'actions' => [['priority' => 'now', 'action' => 'Explain the API scope without inventing metrics.']],
            'limitations' => [], 'summary' => 'Relevant API experience, deployment evidence missing.'];
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
            $this->assertSame('https://api.openai.com/v1/chat/completions', $request->url());
            $this->assertSame('file', $request['messages'][1]['content'][1]['type'], 'the CV PDF travels as a file');
            $this->assertStringStartsWith('data:application/pdf;base64,', $request['messages'][1]['content'][1]['file']['file_data']);
            $this->assertTrue($request['response_format']['json_schema']['strict']);
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
            'empty analysis' => [array_replace(self::analysis(), ['findings' => [], 'actions' => []]), 'STOP'],
            'truncated' => [self::analysis(), 'length'],
            'unreadable' => [array_replace(self::analysis(), ['readable' => false]), 'STOP'],
            'no score breakdown' => [array_replace(self::analysis(), ['score_breakdown' => []]), 'STOP'],
            'unknown verdict' => [array_replace(self::analysis(), ['verdict' => 'maybe']), 'STOP'],
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
        config(['services.openai.key' => null]); app(AiStudioProcessingService::class)->process(1);
        Http::assertNothingSent(); $this->assertSame(0, CoverLetter::count());
        $this->assertSame(1, $user->fresh()->cl_credits);
        config(['services.openai.key' => 'test']);
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

    public function test_a_review_with_more_items_than_shown_is_trimmed_not_failed(): void
    {
        $user = User::factory()->create(['ai_credits' => 1]);
        $this->actingAs($user)->postJson(route('ai-analyzer.analyze'), $this->input())->assertAccepted();
        $long = self::analysis();
        $long['actions'] = array_map(fn ($i) => ['priority' => 'now', 'action' => "Step {$i}: tighten one bullet."], range(1, 14));
        $long['findings'] = array_fill(0, 34, $long['findings'][0]);
        Http::fake(['*' => Http::response(self::reply($long))]);

        app(AiStudioProcessingService::class)->process(1);

        $this->assertSame('completed', AiStudioRun::first()->status);
        $saved = AiAnalyzerResult::first()->analysis_result;
        $this->assertCount(8, $saved['actions']);
        $this->assertCount(8, $saved['rencana_aksi'], 'older readers still get the steps as text');
        $this->assertCount(12, $saved['findings']);
        $this->assertSame('consumed', AiStudioRun::first()->credit_state);
    }

    public function test_the_review_is_recruiter_style_with_a_weighted_score_in_the_members_language(): void
    {
        $user = User::factory()->create(['ai_credits' => 1, 'locale' => 'id']);
        $this->actingAs($user)->postJson(route('ai-analyzer.analyze'), $this->input())->assertAccepted();
        $sent = null;
        Http::fake(function ($request) use (&$sent) {
            $sent ??= $request; // the review itself; a tidy-up call may follow

            return Http::response(self::reply(array_replace(self::analysis(), ['jd_quality' => 'thin'])));
        });

        app(AiStudioProcessingService::class)->process(1);

        $prompt = $sent['messages'][0]['content'];
        $this->assertStringContainsString('senior recruiter', $prompt);
        $this->assertStringContainsString('Bahasa Indonesia', $prompt, 'the member\'s language setting decides');
        $this->assertSame('medium', $sent['reasoning_effort'], 'the background review thinks longer');
        $saved = AiAnalyzerResult::first()->analysis_result;
        $this->assertSame(3, $saved['schema_version']);
        $this->assertSame('id', $saved['language']);
        // 70*30 + 60*20 + 40*20 + 50*15 + 80*15 = 6050 / 100
        $this->assertSame(61, $saved['skor_kecocokan'], 'the total is computed from the breakdown, not taken from the model');
        $this->assertSame('thin', $saved['jd_quality']);

        $page = $this->get(route('ai-analyzer.show', AiAnalyzerResult::first()))->assertOk();
        $page->assertSee('Perlu dipoles sebelum melamar')->assertSee('Rincian skor')->assertSee('Contoh tulis ulang')->assertSee('Maintained [number] APIs serving [number of users]')
            ->assertSee('How did you deploy?')->assertSee('Deployment')->assertSee('Berdasarkan deskripsi lowongan yang singkat.')->assertSee('Unduh PDF')->assertSee('Laporan Review CV');
    }

    public function test_the_review_language_follows_the_setting_even_when_the_request_asks_otherwise(): void
    {
        $user = User::factory()->create(['ai_credits' => 1, 'locale' => 'en']);
        $this->actingAs($user)->postJson(route('ai-analyzer.analyze'), $this->input() + ['language' => 'id'])->assertAccepted();
        $this->assertSame('en', AiStudioRun::first()->payload['language']);
    }

    public function test_a_review_written_in_another_language_is_translated_once_and_never_shown_mixed(): void
    {
        $user = User::factory()->create(['ai_credits' => 0, 'locale' => 'en']);
        $this->actingAs($user)->postJson(route('ai-analyzer.analyze'), $this->input())->assertStatus(422); // no credit: checked elsewhere
        $review = app(\App\Services\StudioAiService::class);
        $stored = (fn () => $this->review(\Tests\Feature\AiAnalyzerCreditTest::analysis(), 'en'))->call($review);
        $result = AiAnalyzerResult::create(['user_id' => $user->id, 'job_description' => 'Lead frontend', 'resume_file_name' => 'cv.pdf', 'analysis_result' => $stored]);

        $user->forceFill(['locale' => 'id'])->save(); // the member switches their language setting
        // Before the translation exists, the labels follow the review's language (English), with the translator waiting.
        $this->actingAs($user->fresh())->get(route('ai-analyzer.show', $result))->assertOk()
            ->assertSee('Polish before applying')->assertDontSee('Perlu dipoles sebelum melamar')->assertSee('Menerjemahkan review ini ke Bahasa Indonesia');

        $translated = array_replace(self::analysis(), []);
        Http::fake(['*' => Http::response(['choices' => [['message' => ['role' => 'assistant', 'content' => json_encode([
            'target_role' => 'Backend Engineer', 'verdict_reason' => 'Pengalaman API-mu relevan.', 'summary' => 'Ringkasan.',
            'first_impression' => ['reads_as' => 'Backend engineer', 'standout' => 'API', 'mismatch' => ''],
            'score_breakdown' => array_fill(0, 5, ['note' => 'Catatan.']), 'strengths' => [['point' => 'Pemeliharaan API', 'evidence' => 'Bukti']],
            'findings' => [['requirement' => 'Pengembangan API', 'evidence' => 'Bukti', 'recommendation' => 'Perjelas cakupan API.']],
            'rewrites' => [['why' => 'Menunjukkan skala.']], 'red_flags' => [],
            'interview_questions' => [['question' => 'Bagaimana kamu deploy?', 'why' => 'Tidak ada di CV', 'how_to_answer' => 'Pakai rilis nyata.']],
            'actions' => [['action' => 'Jelaskan cakupan API.']], 'limitations' => [],
        ])], 'finish_reason' => 'stop']]])]);

        $this->postJson(route('ai-analyzer.translate', $result))->assertOk()->assertJson(['ok' => true]);
        $this->postJson(route('ai-analyzer.translate', $result))->assertOk();
        Http::assertSentCount(1); // kept, not translated twice

        $id = $result->fresh()->analysis_result['translations']['id'];
        $this->assertSame('id', $id['language']);
        $this->assertSame('Maintained [number] APIs serving [number of users]', $id['rewrites'][0]['after'], 'the rewrite stays in the CV language');
        $this->assertSame('Menunjukkan skala.', $id['rewrites'][0]['why']);
        $this->assertSame(['API'], $id['keywords']['present']);
        $this->assertSame(61, $id['skor_kecocokan'], 'scores are not touched');

        $this->get(route('ai-analyzer.show', $result))->assertOk()->assertSee('Perlu dipoles sebelum melamar')->assertSee('Pengalaman API-mu relevan.')
            ->assertDontSee('Menerjemahkan review ini');
        $this->actingAs(User::factory()->create())->postJson(route('ai-analyzer.translate', $result))->assertForbidden();
    }

    public function test_an_indonesian_review_with_english_sections_is_detected_as_mixed(): void
    {
        $review = ['verdict_reason' => 'Pengalaman kamu cukup relevan untuk posisi ini.', 'limitations' => ['The job description is short, so the review is based on the title.'],
            'actions' => [['action' => 'Add the numbers to your latest role and show the impact.'], ['action' => 'Tambahkan angka pada peran terakhir kamu.']],
            'interview_questions' => [['question' => 'How did you handle the deployment in your last project?']], 'red_flags' => [], 'findings' => [], 'strengths' => []];
        $this->assertTrue(\App\Services\StudioAiService::isMixed($review, 'id'));

        $clean = ['verdict_reason' => 'Pengalaman kamu cukup relevan untuk posisi ini.', 'limitations' => ['Deskripsi lowongan singkat, jadi review ini berdasarkan judulnya.'],
            'actions' => [['action' => 'Tambahkan angka pada peran terakhir kamu dan tunjukkan dampaknya.']], 'interview_questions' => [], 'red_flags' => [], 'findings' => [], 'strengths' => []];
        $this->assertFalse(\App\Services\StudioAiService::isMixed($clean, 'id'));
    }

    public function test_a_mixed_review_in_the_members_language_shows_the_tidy_up_banner(): void
    {
        $user = User::factory()->create(['locale' => 'id']);
        $stored = (fn () => $this->review(\Tests\Feature\AiAnalyzerCreditTest::analysis(), 'id'))->call(app(\App\Services\StudioAiService::class));
        $stored['actions'] = [['action' => 'Add the numbers to your latest role and show the impact.', 'priority' => 'high'], ['action' => 'Rewrite the summary so that it matches the role.', 'priority' => 'medium']];
        $result = AiAnalyzerResult::create(['user_id' => $user->id, 'job_description' => 'Lead frontend', 'resume_file_name' => 'cv.pdf', 'analysis_result' => $stored]);

        $this->assertTrue(\App\Services\StudioAiService::isMixed($stored, 'id'));
        $this->actingAs($user)->get(route('ai-analyzer.show', $result))->assertOk()->assertSee('Menerjemahkan review ini ke Bahasa Indonesia');
    }
}
