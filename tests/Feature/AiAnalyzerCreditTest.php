<?php

namespace Tests\Feature;

use App\Http\Controllers\AiAnalyzerController;
use App\Models\AiAnalyzerResult;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AiAnalyzerCreditTest extends TestCase
{
    public function createApplication()
    {
        $app = require dirname(__DIR__, 2).'/bootstrap/app.php';
        $app->useEnvironmentPath('/dev/null');
        $this->traitsUsedByTest = array_flip(class_uses_recursive(static::class));
        $app->make(Kernel::class)->bootstrap();
        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key' => str_repeat('a', 32), 'cache.default' => 'array',
            'database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:',
            'logging.default' => 'null', 'session.driver' => 'array']);
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('ai_credits')->default(1);
            $table->string('role')->default('user');
            $table->boolean('is_premium')->default(false);
            $table->string('payment_status')->default('free');
            $table->boolean('has_used_ai_analyzer_trial')->default(false);
            $table->timestamp('ai_analyzer_trial_used_at')->nullable();
            $table->timestamps();
        });
        Schema::create('ai_analyzer_results', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('user_id');
            $table->text('job_description'); $table->string('resume_file_name');
            $table->text('analysis_result'); $table->timestamps();
        });
        DB::table('users')->insert(['id' => 1, 'ai_credits' => 1]);
        Cache::put('setting_monetization_enabled', true);
        Http::preventStrayRequests();
    }

    private function submit(User $user)
    {
        Auth::setUser($user);
        $request = Request::create('/ai-analyzer/analyze', 'POST', [
            'job_description' => str_repeat('Requirements for the job. ', 4),
        ], [], ['resume' => UploadedFile::fake()->create('resume.pdf', 10, 'application/pdf')]);
        $request->setLaravelSession(app('session.store'));
        return app(AiAnalyzerController::class)->analyze($request);
    }

    public function test_two_stale_models_cannot_spend_the_same_last_credit(): void
    {
        $first = User::findOrFail(1);
        $second = User::findOrFail(1);
        $this->assertTrue($first->incrementAiAnalyzerCount());
        $this->assertFalse($second->incrementAiAnalyzerCount());
        $this->assertSame(0, User::findOrFail(1)->ai_credits);
    }

    public function test_credit_is_reserved_before_provider_and_overlapping_request_is_denied(): void
    {
        $first = User::findOrFail(1);
        $second = User::findOrFail(1);
        $calls = 0;
        Http::fake(function () use ($second, &$calls) {
            $calls++;
            $this->assertSame(0, User::findOrFail(1)->ai_credits);
            $denied = $this->submit($second);
            $this->assertTrue($denied->getSession()->get('errors')->has('analyze_error'));
            return Http::response(['rencana_aksi' => ['Improve summary']]);
        });
        $response = $this->submit($first);
        $this->assertSame(1, $calls);
        $this->assertSame(0, User::findOrFail(1)->ai_credits);
        $this->assertSame(1, AiAnalyzerResult::count());
        $this->assertSame(route('ai-analyzer.show', 1), $response->getTargetUrl());
    }

    public function test_provider_failure_refunds_credit_without_losing_a_concurrent_topup(): void
    {
        Http::fake(function () {
            $this->assertSame(0, User::findOrFail(1)->ai_credits);
            User::findOrFail(1)->addAiCredits(5);
            return Http::response(['error' => 'Unavailable'], 503);
        });
        $response = $this->submit(User::findOrFail(1));
        $this->assertTrue($response->getSession()->get('errors')->has('analyze_error'));
        $this->assertSame(6, User::findOrFail(1)->ai_credits);
        $this->assertSame(0, AiAnalyzerResult::count());
    }

    public function test_connection_failure_refunds_credit(): void
    {
        Http::fake(fn () => throw new \Illuminate\Http\Client\ConnectionException('Timeout'));
        $response = $this->submit(User::findOrFail(1));
        $this->assertTrue($response->getSession()->get('errors')->has('analyze_error'));
        $this->assertSame(1, User::findOrFail(1)->ai_credits);
    }

    public function test_invalid_success_payload_is_refunded_and_not_saved(): void
    {
        Http::fake(['*' => Http::response('not JSON', 200)]);
        $response = $this->submit(User::findOrFail(1));
        $this->assertTrue($response->getSession()->get('errors')->has('analyze_error'));
        $this->assertSame(1, User::findOrFail(1)->ai_credits);
        $this->assertSame(0, AiAnalyzerResult::count());
    }

    public function test_free_mode_does_not_charge_or_refund_on_provider_failure(): void
    {
        Cache::put('setting_monetization_enabled', false);
        Http::fake(['*' => Http::response(['error' => 'Unavailable'], 503)]);
        $this->submit(User::findOrFail(1));
        $this->assertSame(1, User::findOrFail(1)->ai_credits);
    }

    public function test_provider_error_with_success_status_is_refunded(): void
    {
        Http::fake(['*' => Http::response(['error' => 'Analysis failed'])]);
        $response = $this->submit(User::findOrFail(1));
        $this->assertNotNull($response->getSession()->get('errors'));
        $this->assertTrue($response->getSession()->get('errors')->has('analyze_error'));
        $this->assertSame(1, User::findOrFail(1)->ai_credits);
        $this->assertSame(0, AiAnalyzerResult::count());
    }

    public function test_free_mode_success_with_zero_balance_does_not_debit(): void
    {
        Cache::put('setting_monetization_enabled', false);
        DB::table('users')->where('id', 1)->update(['ai_credits' => 0]);
        Http::fake(['*' => Http::response(['rencana_aksi' => ['Improve summary']])]);
        $response = $this->submit(User::findOrFail(1));
        $this->assertSame(route('ai-analyzer.show', 1), $response->getTargetUrl());
        $this->assertSame(0, User::findOrFail(1)->ai_credits);
        $this->assertSame(1, AiAnalyzerResult::count());
    }

    public function test_rejected_request_does_not_refund_credit_reserved_by_another_request(): void
    {
        $stale = User::findOrFail(1);
        DB::table('users')->where('id', 1)->update(['ai_credits' => 0]);
        $response = $this->submit($stale);
        $this->assertTrue($response->getSession()->get('errors')->has('analyze_error'));
        $this->assertSame(0, User::findOrFail(1)->ai_credits);
        Http::assertNothingSent();
    }

    public static function unusableResults(): array
    {
        return [
            'empty object' => ['{}'], 'null' => ['null'], 'html' => ['<html>Service unavailable</html>'],
            'metadata only' => [['status' => 'ok', 'request_id' => '123']],
            'error envelope' => [['success' => false, 'message' => 'Unavailable']],
            'invalid success flag' => [['success' => null, 'rencana_aksi' => ['Improve summary']]],
            'nested error envelope' => [['status' => 'error', 'rencana_aksi' => ['Try again later']]],
            'empty actions' => [['rencana_aksi' => []]],
            'blank actions' => [['rencana_aksi' => ['  ']]],
            'wrong action type' => [['rencana_aksi' => [true]]],
            'wrong summary type' => [['ringkasan' => false, 'rencana_aksi' => ['Improve summary']]],
            'invalid score' => [['skor_kecocokan' => 120, 'rencana_aksi' => ['Improve summary']]],
            'score only' => [['skor_kecocokan' => 80]],
            'empty section' => [['pendidikan' => ['teks_revisi' => '', 'alasan_perubahan' => ' ']]],
            'wrong section shape' => [['pendidikan' => 'Education', 'rencana_aksi' => ['Improve summary']]],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('unusableResults')]
    public function test_unusable_success_payload_never_consumes_credit_or_saves_result(mixed $payload): void
    {
        Http::fake(['*' => Http::response($payload)]);
        $response = $this->submit(User::findOrFail(1));
        $this->assertNotNull($response->getSession()->get('errors'));
        $this->assertSame(1, User::findOrFail(1)->ai_credits);
        $this->assertFalse(User::findOrFail(1)->has_used_ai_analyzer_trial);
        $this->assertSame(0, AiAnalyzerResult::count());
    }

    public function test_storage_failure_refunds_credit_and_rolls_back_trial_marker(): void
    {
        Http::fake(['*' => Http::response(['rencana_aksi' => ['Improve summary']])]);
        DB::unprepared("CREATE TRIGGER fail_analysis BEFORE INSERT ON ai_analyzer_results BEGIN SELECT RAISE(ABORT, 'forced test failure'); END");
        $this->submit(User::findOrFail(1));
        $this->assertSame(1, User::findOrFail(1)->ai_credits);
        $this->assertFalse(User::findOrFail(1)->has_used_ai_analyzer_trial);
        $this->assertSame(0, AiAnalyzerResult::count());
    }

    public static function usableResults(): array
    {
        return [
            'actions' => [['rencana_aksi' => ['Improve summary']]],
            'summary and score' => [['skor_kecocokan' => 0, 'ringkasan' => 'Your profile lacks the required experience.']],
            'section feedback' => [['pendidikan' => ['teks_revisi' => '', 'alasan_perubahan' => 'Add your graduation year.']]],
            'complete result with extra fields' => [['success' => true, 'status' => 'ok', 'skor_kecocokan' => 85.5,
                'ringkasan' => 'Relevant experience with gaps in metrics.', 'rencana_aksi' => ['Quantify your achievements.'],
                'pengalaman_kerja' => ['teks_revisi' => 'Built a reporting tool.', 'alasan_perubahan' => 'Explain the outcome.'],
                'custom_provider_field' => ['detail' => 'Preserve the actual provider result.']]],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('usableResults')]
    public function test_valid_results_are_saved_exactly_and_charge_one_credit(array $payload): void
    {
        Http::fake(['*' => Http::response($payload)]);
        $response = $this->submit(User::findOrFail(1));
        $this->assertSame(route('ai-analyzer.show', 1), $response->getTargetUrl());
        $this->assertSame($payload, AiAnalyzerResult::sole()->analysis_result);
        $this->assertSame(0, User::findOrFail(1)->ai_credits);
        $this->assertTrue(User::findOrFail(1)->has_used_ai_analyzer_trial);
    }

    public function test_trial_marker_failure_also_rolls_back_the_saved_result(): void
    {
        Http::fake(['*' => Http::response(['rencana_aksi' => ['Improve summary']])]);
        DB::unprepared("CREATE TRIGGER fail_trial BEFORE UPDATE OF has_used_ai_analyzer_trial ON users BEGIN SELECT RAISE(ABORT, 'forced test failure'); END");
        $response = $this->submit(User::findOrFail(1));
        $this->assertNotNull($response->getSession()->get('errors'));
        $this->assertSame(1, User::findOrFail(1)->ai_credits);
        $this->assertSame(0, AiAnalyzerResult::count());
    }
}
