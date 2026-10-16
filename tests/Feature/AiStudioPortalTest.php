<?php
namespace Tests\Feature;

use App\Models\AiAnalyzerResult;
use App\Models\AiPhoto;
use App\Models\CoverLetter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AiStudioPortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Support\Facades\Queue::fake();
        \Illuminate\Support\Facades\Storage::fake('local');
        config(['services.gemini.key' => 'test', 'services.gemini.model' => 'gemini-test', 'services.gemini.studio_models' => []]);
    }

    private function reply(array $data): array
    {
        return ['candidates' => [['finishReason' => 'STOP', 'content' => ['parts' => [['text' => json_encode($data)]]]]]];
    }

    public function test_portal_is_one_workspace_with_four_tasks_and_private_history(): void
    {
        $owner = User::factory()->create();
        CoverLetter::create(['user_id' => $owner->id, 'company_name' => 'My company', 'job_title' => 'Engineer', 'content' => 'My letter', 'tone' => 'professional', 'language' => 'en']);
        CoverLetter::create(['user_id' => User::factory()->create()->id, 'company_name' => 'Private other company', 'job_title' => 'Engineer', 'content' => 'Secret letter', 'tone' => 'professional', 'language' => 'en']);
        $this->actingAs($owner)->get('/ai-studio?tab=photo')->assertOk()
            ->assertSee('ai-portal')->assertSee('Recent requests')->assertSee('Analyze CV')
            ->assertSee('Cover letter')->assertSee('Photo studio')->assertSee('Recruiter outreach')
            ->assertSee('My company')->assertDontSee('Private other company')->assertSee('Requests and results are saved in History.');
        $this->get('/ai-studio?task=cover-letter&id=2')->assertNotFound();
    }

    public function test_saved_analysis_uses_actual_provider_data_and_escaped_content_in_same_portal(): void
    {
        $owner = User::factory()->create();
        $result = AiAnalyzerResult::create(['user_id' => $owner->id, 'job_description' => 'Actual target description', 'resume_file_name' => 'resume.pdf', 'analysis_result' => ['skor_kecocokan' => 42, 'rencana_aksi' => ['Actual next step', '<script>bad()</script>'], 'custom_provider_field' => ['detail' => 'Actual custom response']]]);
        $this->actingAs($owner)->get(route('ai-analyzer.show', $result))->assertOk()
            ->assertSee('ai-portal')->assertDontSee('id="studio-file"', false)->assertSee('Create another review')->assertSee('42')->assertSee('Actual next step')
            ->assertSee('Actual custom response')->assertDontSee('<script>bad()</script>', false)
            ->assertDontSee('Roadmap to 95%+')->assertDontSee('Just Now');
        $this->actingAs(User::factory()->create())->get(route('ai-analyzer.show', $result))->assertForbidden();
    }

    public function test_photo_failure_and_completed_result_share_portal_and_preserve_retry_contract(): void
    {
        $owner = User::factory()->create();
        $photo = new AiPhoto();
        $photo->forceFill(['user_id' => $owner->id, 'type' => 'enhance', 'original_photo_name' => 'portrait.jpg', 'request_key' => (string) \Illuminate\Support\Str::uuid(), 'status' => 'failed', 'credit_state' => 'refunded', 'error_message' => 'Provider unavailable'])->save();
        $this->actingAs($owner)->get(route('ai-photo.show', $photo))->assertOk()
            ->assertSee('ai-portal')->assertSee('Provider unavailable')->assertSee('refunded')->assertDontSee('Download photo');
        $photo->forceFill(['status' => 'completed', 'result_url' => 'javascript:alert(1)'])->save();
        $this->get(route('ai-photo.show', $photo))->assertOk()->assertDontSee('javascript:alert(1)', false);
        $this->actingAs(User::factory()->create())->get(route('ai-photo.show', $photo))->assertNotFound();
    }
    public function test_outreach_is_ai_generated_saved_and_paginated_without_other_owner_data(): void
    {
        \Illuminate\Support\Facades\Http::preventStrayRequests();
        \Illuminate\Support\Facades\Http::fake(['https://generativelanguage.googleapis.com/*' => \Illuminate\Support\Facades\Http::response($this->reply(['content' => 'Hi Alex, I am interested in the Engineer role at Actual company. May we discuss the application process?']))]);
        $owner = User::factory()->create();
        $this->actingAs($owner)->postJson(route('ai-studio.outreach'), [
            'request_key' => (string) \Illuminate\Support\Str::uuid(),
            'target_company' => 'Actual company', 'job_title' => 'Engineer',
            'channel' => 'Email', 'tone' => 'Friendly', 'recruiter_name' => 'Alex',
        ])->assertAccepted()->assertJsonPath('status', 'queued');
        app(\App\Services\AiStudioProcessingService::class)->process(1);
        $draft = \App\Models\OutreachDraft::firstOrFail();
        $this->assertSame($owner->id, $draft->user_id);
        $this->assertStringContainsString('Hi Alex', $draft->content);
        $this->get('/ai-studio?task=outreach&id='.$draft->id)->assertOk()->assertDontSee('A saved template')->assertSee('Actual company');
        for ($i = 0; $i < 31; $i++) {
            \App\Models\OutreachDraft::create(['user_id' => $owner->id, 'company_name' => 'History '.$i, 'job_title' => 'Engineer', 'channel' => 'Email', 'tone' => 'Professional', 'content' => 'Draft']);
        }
        $this->get('/ai-studio')->assertOk()->assertViewHas('history', fn ($history) => $history->count() === 30 && $history->total() === 32)->assertSee('Older');
        $this->get('/ai-studio?history_page=2')->assertOk()->assertViewHas('history', fn ($history) => $history->count() === 2);
        $this->actingAs(User::factory()->create())->get('/ai-studio?task=outreach&id='.$draft->id)->assertNotFound();
    }

    public function test_cover_letter_and_analyzer_are_saved_as_queued_requests_in_private_history(): void
    {
        \Illuminate\Support\Facades\Http::preventStrayRequests();
        $user = User::factory()->create();
        $input = ['request_key' => (string) \Illuminate\Support\Str::uuid(), 'company_name' => 'Actual company', 'job_title' => 'Actual role', 'job_description' => str_repeat('Actual requirements. ', 4), 'language' => 'en', 'tone' => 'professional', 'length' => 'standard'];
        $this->actingAs($user)->postJson(route('cover-letters.generate'), $input)->assertAccepted()->assertJsonPath('request_id', 1);
        $this->get('/ai-studio?task=cover-letter&run=1')->assertOk()->assertSee('Your request is saved.')->assertSee('Actual company');
        $this->get('/ai-studio')->assertOk()->assertViewHas('history', fn ($history) => $history->total() === 1);
        $this->actingAs(User::factory()->create())->get('/ai-studio?run=1')->assertNotFound();
        $this->get(route('ai-studio.status', 1))->assertNotFound();
        $this->postJson(route('ai-studio.retry', 1), ['request_key' => (string) \Illuminate\Support\Str::uuid()])->assertNotFound();
        \Illuminate\Support\Facades\Http::assertNothingSent();
    }
    public function test_context_selectors_only_accept_owned_applications_and_pdf_resumes(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $application = \App\Models\JobApplication::factory()->create(['user_id' => $user->id, 'company_name' => 'Owned target', 'position' => 'Owned engineer', 'notes' => 'Private notes are not job requirements']);
        $foreignApplication = \App\Models\JobApplication::factory()->create(['user_id' => $other->id, 'company_name' => 'Foreign target']);
        $path = 'resumes/'.$user->id.'/cv.pdf';
        \Illuminate\Support\Facades\Storage::disk('local')->put($path, "%PDF-1.4\nActual CV");
        $resume = \App\Models\UserResume::create(['user_id' => $user->id, 'title' => 'Owned CV', 'file_path' => $path, 'file_name' => 'cv.pdf', 'file_type' => 'application/pdf', 'file_size' => 24]);
        $foreignResume = \App\Models\UserResume::create(['user_id' => $other->id, 'title' => 'Foreign CV', 'file_path' => 'resumes/'.$other->id.'/cv.pdf', 'file_name' => 'cv.pdf', 'file_type' => 'application/pdf', 'file_size' => 24]);
        $input = ['request_key' => (string) \Illuminate\Support\Str::uuid(), 'application_id' => $application->id, 'resume_id' => $resume->id, 'job_description' => str_repeat('Actual role requirements. ', 4)];
        $this->actingAs($user)->get('/ai-studio')->assertOk()->assertSee('Owned CV')->assertDontSee('Foreign CV')->assertDontSee('Foreign target');
        $this->postJson(route('cover-letters.generate'), array_replace($input, ['application_id' => $foreignApplication->id]))->assertUnprocessable();
        $this->postJson(route('cover-letters.generate'), array_replace($input, ['resume_id' => $foreignResume->id]))->assertUnprocessable();
        $this->postJson(route('cover-letters.generate'), array_replace($input, ['job_description' => '']))->assertUnprocessable();
        $this->postJson(route('cover-letters.generate'), $input)->assertAccepted();
        $run = \App\Models\AiStudioRun::firstOrFail();
        $this->assertSame('Owned target', $run->payload['company_name']);
        $this->assertSame('Owned engineer', $run->payload['job_title']);
        $this->assertStringNotContainsString('Private notes', json_encode($run->payload));
        $this->assertNotSame($path, $run->source_path);
        $this->assertStringNotContainsString('Owned target', $run->getRawOriginal('payload'));
        \Illuminate\Support\Facades\Storage::disk('local')->delete($path);
        $this->assertTrue(\Illuminate\Support\Facades\Storage::disk('local')->exists($run->source_path));
    }

    public function test_pending_capacity_and_admin_access_are_enforced_for_free_outreach(): void
    {
        $user = User::factory()->create();
        $input = ['target_company' => 'Actual company', 'job_title' => 'Engineer', 'purpose' => 'follow-up'];
        $this->actingAs($user);
        for ($i = 0; $i < 2; $i++) $this->postJson(route('ai-studio.outreach'), $input + ['request_key' => (string) \Illuminate\Support\Str::uuid()])->assertAccepted();
        $this->postJson(route('ai-studio.outreach'), $input + ['request_key' => (string) \Illuminate\Support\Str::uuid()])->assertUnprocessable();
        $admin = User::factory()->create(['role' => 'admin', 'is_admin' => true]);
        $this->actingAs($admin)->postJson(route('ai-studio.outreach'), $input + ['request_key' => (string) \Illuminate\Support\Str::uuid()])->assertForbidden();
        $this->get(route('ai-studio.status', 1))->assertForbidden();
    }

    public function test_rollout_without_new_table_shows_a_safe_message_instead_of_server_error(): void
    {
        \Illuminate\Support\Facades\Schema::drop('ai_studio_runs');
        $user = User::factory()->create();
        $this->actingAs($user)->get('/ai-studio')->assertOk()->assertSee('AI Studio is being updated.');
        $this->postJson(route('ai-studio.outreach'), ['request_key' => (string) \Illuminate\Support\Str::uuid()])->assertUnprocessable()->assertJsonValidationErrors('request');
    }

}
