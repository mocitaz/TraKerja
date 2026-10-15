<?php

namespace Tests\Feature;

use App\Http\Controllers\CvBuilderController;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CvExportQuotaTest extends TestCase
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
        config(['app.key' => str_repeat('e', 32), 'cache.default' => 'array',
            'database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:',
            'logging.default' => 'null', 'session.driver' => 'array']);
        Schema::create('users', function (Blueprint $t) {
            $t->id(); $t->string('name')->default('Test User'); $t->string('payment_status')->default('free');
            $t->boolean('is_premium')->default(false); $t->timestamp('premium_until')->nullable();
            $t->integer('cv_generated_this_month')->default(0); $t->date('last_cv_generation_reset')->nullable();
            $t->timestamps();
        });
        foreach (['user_experiences', 'user_educations', 'user_skills', 'user_organizations', 'user_achievements', 'user_projects'] as $table) {
            Schema::create($table, function (Blueprint $t) { $t->id(); $t->integer('user_id'); $t->integer('display_order'); });
        }
        DB::table('users')->insert(['id' => 1, 'cv_generated_this_month' => 2, 'last_cv_generation_reset' => now()->toDateString()]);
        Cache::put('setting_monetization_enabled', true);
    }

    private function export(User $user, string $template = 'minimal')
    {
        Auth::setUser($user);
        return app(CvBuilderController::class)->export(Request::create('/cv/export', 'POST', ['template' => $template]));
    }

    private function fakeRenderer(?callable $onDownload = null): void
    {
        $pdf = \Mockery::mock(\Barryvdh\DomPDF\PDF::class);
        $pdf->shouldReceive('setPaper')->andReturnSelf();
        $pdf->shouldReceive('setOptions')->andReturnSelf();
        $pdf->shouldReceive('download')->andReturnUsing(function () use ($onDownload) {
            if ($onDownload) $onDownload();
            return response('%PDF-1.4 synthetic renderer output', 200, ['Content-Type' => 'application/pdf']);
        });
        Pdf::shouldReceive('loadView')->andReturn($pdf);
    }

    public function test_invalid_or_unauthorized_template_does_not_consume_quota(): void
    {
        foreach (['invalid', 'creative', 'elegant'] as $template) {
            $response = $this->export(User::findOrFail(1), $template);
            $this->assertSame(302, $response->getStatusCode());
            $this->assertSame(2, User::findOrFail(1)->cv_generated_this_month);
        }
    }

    public function test_render_failure_during_download_does_not_consume_quota(): void
    {
        $this->fakeRenderer(fn () => throw new \RuntimeException('Render failed'));
        try {
            $response = $this->export(User::findOrFail(1));
            $this->assertSame(302, $response->getStatusCode());
        } finally {
            $this->assertSame(2, User::findOrFail(1)->cv_generated_this_month);
        }
    }

    public function test_quota_is_charged_only_after_pdf_bytes_exist(): void
    {
        $this->fakeRenderer(function () {
            $this->assertSame(2, User::findOrFail(1)->cv_generated_this_month);
        });
        $response = $this->export(User::findOrFail(1));
        $this->assertSame('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringStartsWith('%PDF-', $response->getContent());
        $this->assertSame(3, User::findOrFail(1)->cv_generated_this_month);
    }

    public function test_stale_models_cannot_both_consume_last_export(): void
    {
        $first = User::findOrFail(1); $second = User::findOrFail(1);
        $this->assertTrue($first->incrementCvGenerationCount());
        $this->assertFalse($second->incrementCvGenerationCount());
        $this->assertSame(3, User::findOrFail(1)->cv_generated_this_month);
    }

    public function test_stale_month_reset_cannot_erase_another_requests_consumption(): void
    {
        DB::table('users')->where('id', 1)->update(['cv_generated_this_month' => 3,
            'last_cv_generation_reset' => now()->subMonthNoOverflow()->toDateString()]);
        $first = User::findOrFail(1); $stale = User::findOrFail(1);
        $this->assertTrue($first->incrementCvGenerationCount());
        $stale->checkCvGenerationReset();
        $this->assertSame(1, User::findOrFail(1)->cv_generated_this_month);
    }

    public function test_successfully_rendered_pdf_is_not_returned_when_last_quota_was_spent_elsewhere(): void
    {
        $user = User::findOrFail(1);
        $this->fakeRenderer(function () {
            DB::table('users')->where('id', 1)->update(['cv_generated_this_month' => 3]);
        });
        $response = $this->export($user);
        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame(3, User::findOrFail(1)->cv_generated_this_month);
    }

    public function test_free_mode_and_premium_exports_do_not_consume_quota(): void
    {
        $this->fakeRenderer();
        Cache::put('setting_monetization_enabled', false);
        $this->assertSame(200, $this->export(User::findOrFail(1), 'elegant')->getStatusCode());
        Cache::put('setting_monetization_enabled', true);
        DB::table('users')->where('id', 1)->update(['is_premium' => true, 'payment_status' => 'paid']);
        $this->assertSame(200, $this->export(User::findOrFail(1), 'creative')->getStatusCode());
        $this->assertSame(2, User::findOrFail(1)->cv_generated_this_month);
    }

    public function test_pdf_setup_failure_does_not_consume_quota(): void
    {
        Pdf::shouldReceive('loadView')->andThrow(new \RuntimeException('Invalid template markup'));
        $this->assertSame(302, $this->export(User::findOrFail(1))->getStatusCode());
        $this->assertSame(2, User::findOrFail(1)->cv_generated_this_month);
    }

    public function test_real_dompdf_output_is_returned_before_charging_successful_export(): void
    {
        $renderer = new \Barryvdh\DomPDF\PDF(new \Dompdf\Dompdf(), app('config'), app('files'), app('view'));
        $renderer->loadHTML('<html><body><h1>Test resume</h1><p>Local fixture without external assets.</p></body></html>');
        Pdf::shouldReceive('loadView')->with('cv-templates.minimal', \Mockery::type('array'))->andReturn($renderer);
        $response = $this->export(User::findOrFail(1));
        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringStartsWith('%PDF-', $response->getContent());
        $this->assertStringContainsString('attachment;', $response->headers->get('Content-Disposition'));
        $this->assertSame(3, User::findOrFail(1)->cv_generated_this_month);
    }

    public function test_failed_quota_write_does_not_return_pdf_or_increment_counter(): void
    {
        $this->fakeRenderer();
        DB::unprepared("CREATE TRIGGER fail_quota BEFORE UPDATE OF cv_generated_this_month ON users BEGIN SELECT RAISE(ABORT, 'forced test failure'); END");
        $response = $this->export(User::findOrFail(1));
        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame(2, User::findOrFail(1)->cv_generated_this_month);
    }
}
