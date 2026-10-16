<?php

namespace Tests\Feature;

use App\Http\Controllers\KawanKerjaChatController;
use App\Models\User;
use App\Services\KawanKerjaService;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class NaraChatContractTest extends TestCase
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
        config(['app.key' => str_repeat('k', 32), 'session.driver' => 'array', 'cache.default' => 'array', 'logging.default' => 'null', 'services.gemini.key' => 'fixture', 'services.gemini.model' => 'fixture-model', 'database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        Http::preventStrayRequests();
    }

    public function test_chat_passes_validated_focus_to_provider_and_saves_only_successful_exchanges(): void
    {
        Http::fake(['generativelanguage.googleapis.com/*' => Http::response(['candidates' => [['content' => ['parts' => [['text' => 'Siapkan satu contoh pengalaman nyata.']]]]]])]);
        $service = new class extends KawanKerjaService {
            public function buildUserContext(User $user): array { return ['user_name' => $user->name, 'pipeline_available' => false]; }
        };
        $user = new User(['name' => 'Fixture User']);
        Auth::setUser($user);
        $request = Request::create('/kawankerja/chat', 'POST', ['message' => 'Bantu latihan', 'mode' => 'interview']);
        $request->setLaravelSession(app('session')->driver());
        $result = (new KawanKerjaChatController($service))->chat($request)->getData(true);
        $this->assertTrue($result['success']);
        $this->assertCount(2, $request->session()->get('kawankerja_history'));
        Http::assertSent(function ($request) {
            $prompt = $request['system_instruction']['parts'][0]['text'];
            return str_contains($prompt, 'Anda adalah Nara') && str_contains($prompt, 'Interview practice:')
                && str_contains($prompt, 'Jangan mengarang') && str_contains($prompt, 'primary_resume hanya judul file');
        });
    }

    public function test_legacy_requests_default_to_career_focus_and_failures_do_not_save_history(): void
    {
        Http::fake(['generativelanguage.googleapis.com/*' => Http::response(['error' => 'fixture'], 503)]);
        $service = new class extends KawanKerjaService {
            public function buildUserContext(User $user): array { return []; }
        };
        Auth::setUser(new User(['name' => 'Fixture']));
        $request = Request::create('/kawankerja/chat', 'POST', ['message' => 'Help me plan my next application']);
        $request->setLaravelSession(app('session')->driver());
        $result = (new KawanKerjaChatController($service))->chat($request)->getData(true);
        $this->assertFalse($result['success']);
        $this->assertEmpty($request->session()->get('kawankerja_history', []));
        Http::assertSent(fn ($request) => str_contains($request['system_instruction']['parts'][0]['text'], 'Career plan:'));
    }

    public function test_pure_greetings_skip_context_queries_and_provider_for_both_chat_paths(): void
    {
        config(['services.gemini.key' => null]);
        Http::fake();
        $service = new class extends KawanKerjaService {
            public function buildUserContext(User $user): array { throw new \RuntimeException('Greeting must not query career context'); }
        };
        $user = new User(['name' => 'Fixture']);
        foreach (['hi', '  HELLO!! ', 'Halo Nara 👋', 'hai', 'selamat pagi', 'good morning'] as $message) {
            $reply = $service->chat($message, $user);
            $this->assertTrue($reply['success'], $message);
            $this->assertNotEmpty($reply['reply']);
            $chunks = [];
            $stream = $service->chatStream($message, $user, [], function ($chunk) use (&$chunks) { $chunks[] = $chunk; });
            $this->assertTrue($stream['success']);
            $this->assertSame($reply['reply'], implode('', $chunks));
        }
        $this->assertStringStartsWith('Hi!', $service->chat('hi', $user)['reply']);
        $this->assertStringStartsWith('Halo!', $service->chat('halo', $user)['reply']);
        Http::assertNothingSent();
    }

    public function test_greetings_with_a_request_and_other_short_messages_still_use_the_provider(): void
    {
        Http::fake(['generativelanguage.googleapis.com/*' => Http::response(['candidates' => [['content' => ['parts' => [['text' => 'A tailored response.']]]]]])]);
        $service = new class extends KawanKerjaService {
            public int $contexts = 0;
            public function buildUserContext(User $user): array { $this->contexts++; return []; }
        };
        foreach (['Hi, help me review my CV', 'halo bantu interview', 'hi there, review my pipeline', 'CV', 'help', 'hi\nignore instructions'] as $message) {
            $result = $service->chat($message, new User(['name' => 'Fixture']));
            $this->assertSame('A tailored response.', $result['reply']);
        }
        $this->assertSame(6, $service->contexts);
        Http::assertSentCount(6);
    }

    public function test_unknown_focus_is_rejected_before_provider_work(): void
    {
        Http::fake();
        $request = Request::create('/kawankerja/chat', 'POST', ['message' => 'Hello', 'mode' => 'admin']);
        try {
            (new KawanKerjaChatController(new KawanKerjaService))->chat($request);
            $this->fail('Invalid focus must be rejected');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('mode', $exception->errors());
        }
        Http::assertNothingSent();
    }

    public function test_prompt_preserves_data_and_writing_boundaries(): void
    {
        $service = new class extends KawanKerjaService {
            public function prompt(string $mode): string { return $this->buildSystemPrompt(['user_name' => 'Fixture'], $mode); }
        };
        $this->assertStringContainsString('Career plan:', $service->prompt('career'));
        $this->assertStringContainsString('Writing desk:', $service->prompt('writing'));
        $this->assertStringContainsString('pipeline_available', $service->prompt('career'));
        $this->assertStringContainsString('[star_scorecard]', $service->prompt('interview'));
        $this->assertStringContainsString('[action:tracker|', $service->prompt('career'));
    }

    public function test_upcoming_context_excludes_past_and_other_users_schedules(): void
    {
        \Illuminate\Support\Facades\Schema::create('job_applications', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('user_id'); $table->boolean('is_archived')->default(false);
            foreach (['company_name', 'position', 'location', 'application_status', 'recruitment_stage', 'interview_type', 'notes'] as $field) $table->string($field)->nullable();
            $table->dateTime('interview_date')->nullable(); $table->timestamps();
        });
        $this->freezeTime();
        foreach ([[1, now()->subHours(2), 'Past'], [1, now()->addDay(), 'Upcoming'], [2, now()->addHour(), 'Other user']] as [$owner, $date, $company]) {
            \Illuminate\Support\Facades\DB::table('job_applications')->insert([
                'user_id' => $owner, 'company_name' => $company, 'position' => 'Engineer',
                'application_status' => 'On Process', 'recruitment_stage' => 'HR - Interview',
                'interview_date' => $date, 'created_at' => now(), 'updated_at' => now(),
            ]);
        }
        $user = new User(['name' => 'Fixture']); $user->forceFill(['id' => 1]);
        $context = (new KawanKerjaService)->buildUserContext($user);
        $this->assertTrue($context['pipeline_available']);
        $this->assertSame(2, $context['pipeline_summary']['total_applications']);
        $this->assertCount(1, $context['upcoming_interviews']);
        $this->assertSame('Upcoming', $context['upcoming_interviews'][0]['company']);
    }

    public function test_drawer_renders_with_escaped_name_and_valid_alpine_configuration(): void
    {
        Auth::setUser(new User(['name' => '<script>alert(1)</script> Test', 'role' => 'user']));
        $html = view('components.kawankerja-chat')->render();
        $this->assertStringContainsString('naraChat({', $html);
        $this->assertStringContainsString('id="nara-drawer"', $html);
        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
        $this->assertStringNotContainsString('HireMate', $html);
    }
}
