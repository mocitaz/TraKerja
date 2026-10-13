<?php

namespace Tests\Unit;

use App\Models\JobApplication;
use App\Models\User;
use App\Models\UserExperience;
use App\Models\UserResume;
use App\Models\UserSkill;
use App\Services\KawanKerjaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class KawanKerjaServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_compiles_rich_user_context(): void
    {
        $user = User::factory()->create(['name' => 'Budi Santoso']);
        UserSkill::create([
            'user_id' => $user->id,
            'skill_name' => 'Laravel',
            'category' => 'Backend',
        ]);
        UserExperience::create([
            'user_id' => $user->id,
            'company_name' => 'Gojek',
            'position' => 'Senior Backend Engineer',
            'description' => 'Built high-scale microservices',
            'start_date' => now()->subYears(2),
        ]);
        JobApplication::create([
            'user_id' => $user->id,
            'company_name' => 'Tokopedia',
            'position' => 'Senior Backend Engineer',
            'location' => 'Jakarta',
            'application_status' => 'On Process',
            'recruitment_stage' => 'User - Interview',
            'application_date' => now()->subDays(5),
            'interview_date' => now()->addDays(2),
        ]);
        UserResume::create([
            'user_id' => $user->id,
            'title' => 'CV Backend Tech',
            'file_path' => 'resumes/1/test.pdf',
            'file_name' => 'test.pdf',
            'file_size' => 1024,
            'file_type' => 'application/pdf',
            'is_primary' => true,
        ]);

        $service = new KawanKerjaService();
        $context = $service->buildUserContext($user);

        $this->assertSame('Budi Santoso', $context['user_name']);
        $this->assertContains('Laravel', $context['skills']);
        $this->assertCount(1, $context['experiences']);
        $this->assertSame('Senior Backend Engineer', $context['experiences'][0]['title']);
        $this->assertSame('Gojek', $context['experiences'][0]['company']);
        $this->assertCount(1, $context['active_applications']);
        $this->assertSame('Tokopedia', $context['active_applications'][0]['company']);
        $this->assertSame('CV Backend Tech', $context['primary_resume']);
        $this->assertSame(1, $context['pipeline_summary']['total_active']);
        $this->assertSame(1, $context['pipeline_summary']['in_interview_stages']);
        $this->assertCount(1, $context['upcoming_interviews']);
        $this->assertSame('Tokopedia', $context['upcoming_interviews'][0]['company']);
    }

    public function test_it_calls_gemini_api_and_returns_clean_response(): void
    {
        config(['services.gemini.key' => 'fake-test-key']);

        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-lite-latest:generateContent*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Halo! Tentu, untuk interview di Tokopedia kamu bisa fokus pada arsitektur database dan scaling.']
                            ]
                        ]
                    ]
                ]
            ], 200),
        ]);

        $user = User::factory()->create();
        $service = new KawanKerjaService();
        $result = $service->chat('Tips interview Tokopedia dong', $user);

        $this->assertTrue($result['success']);
        $this->assertStringContainsString('Tokopedia', $result['reply']);
    }

    public function test_it_falls_back_to_secondary_model_on_failure(): void
    {
        config(['services.gemini.key' => 'fake-test-key']);

        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-lite-latest:generateContent*' => Http::response(['error' => 'quota exceeded'], 429),
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite:generateContent*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Jawaban dari fallback model 3.1 flash lite.']
                            ]
                        ]
                    ]
                ]
            ], 200),
        ]);

        $user = User::factory()->create();
        $service = new KawanKerjaService();
        $result = $service->chat('Halo', $user);

        $this->assertTrue($result['success']);
        $this->assertStringContainsString('fallback model', $result['reply']);
    }

    public function test_system_prompt_instructs_action_cards_for_navigation(): void
    {
        $service = app(KawanKerjaService::class);
        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('buildSystemPrompt');
        $method->setAccessible(true);

        $prompt = $method->invoke($service, ['user_name' => 'Luthfi']);

        $this->assertStringContainsString('[action:', $prompt);
    }

    public function test_system_prompt_instructs_star_scorecard_syntax(): void
    {
        $service = app(KawanKerjaService::class);
        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('buildSystemPrompt');
        $method->setAccessible(true);

        $prompt = $method->invoke($service, ['user_name' => 'Luthfi']);

        $this->assertStringContainsString('[star_scorecard]', $prompt);
    }

    public function test_it_returns_unavailable_message_when_models_fail_or_quota_runs_out(): void
    {
        config(['services.gemini.key' => 'fake-test-key']);

        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-lite-latest:generateContent*' => Http::response(['error' => 'quota exceeded'], 429),
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite:generateContent*' => Http::response(['error' => 'quota exceeded'], 429),
        ]);

        $user = User::factory()->create();
        $service = new KawanKerjaService();
        $result = $service->chat('Halo', $user);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('sedang tidak tersedia', $result['reply']);
    }
}
