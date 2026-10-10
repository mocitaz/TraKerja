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

    public function test_portal_is_one_workspace_with_four_tasks_and_private_history(): void
    {
        $owner = User::factory()->create();
        CoverLetter::create(['user_id' => $owner->id, 'company_name' => 'My company', 'job_title' => 'Engineer', 'content' => 'My letter', 'tone' => 'professional', 'language' => 'en']);
        CoverLetter::create(['user_id' => User::factory()->create()->id, 'company_name' => 'Private other company', 'job_title' => 'Engineer', 'content' => 'Secret letter', 'tone' => 'professional', 'language' => 'en']);
        $this->actingAs($owner)->get('/ai-studio?tab=photo')->assertOk()
            ->assertSee('ai-portal')->assertSee('Recent conversations')->assertSee('Analyze CV')
            ->assertSee('Cover letter')->assertSee('Photo studio')->assertSee('Recruiter outreach')
            ->assertSee('My company')->assertDontSee('Private other company')->assertSee('1 credit per photo');
        $this->get('/ai-studio?task=cover-letter&id=2')->assertNotFound();
    }

    public function test_saved_analysis_uses_actual_provider_data_and_escaped_content_in_same_portal(): void
    {
        $owner = User::factory()->create();
        $result = AiAnalyzerResult::create(['user_id' => $owner->id, 'job_description' => 'Actual target description', 'resume_file_name' => 'resume.pdf', 'analysis_result' => ['skor_kecocokan' => 42, 'rencana_aksi' => ['Actual next step', '<script>bad()</script>'], 'custom_provider_field' => ['detail' => 'Actual custom response']]]);
        $this->actingAs($owner)->get(route('ai-analyzer.show', $result))->assertOk()
            ->assertSee('ai-portal')->assertSee('42')->assertSee('Actual next step')
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
    public function test_outreach_templates_are_saved_and_paginated_without_external_provider_or_other_owner_data(): void
    {
        \Illuminate\Support\Facades\Http::preventStrayRequests();
        $owner = User::factory()->create();
        $this->actingAs($owner)->postJson(route('ai-studio.outreach'), [
            'target_company' => 'Actual company', 'job_title' => 'Engineer',
            'channel' => 'Email', 'tone' => 'Friendly', 'recruiter_name' => 'Alex',
        ])->assertOk()->assertJsonPath('is_template', true);
        $draft = \App\Models\OutreachDraft::firstOrFail();
        $this->assertSame($owner->id, $draft->user_id);
        $this->assertStringContainsString('Hi Alex', $draft->content);
        $this->get('/ai-studio?task=outreach&id='.$draft->id)->assertOk()->assertSee('A saved template')->assertSee('Actual company');
        for ($i = 0; $i < 31; $i++) {
            \App\Models\OutreachDraft::create(['user_id' => $owner->id, 'company_name' => 'History '.$i, 'job_title' => 'Engineer', 'channel' => 'Email', 'tone' => 'Professional', 'content' => 'Draft']);
        }
        $this->get('/ai-studio')->assertOk()->assertViewHas('history', fn ($history) => $history->count() === 30 && $history->total() === 32)->assertSee('Older');
        $this->get('/ai-studio?history_page=2')->assertOk()->assertViewHas('history', fn ($history) => $history->count() === 2);
        $this->actingAs(User::factory()->create())->get('/ai-studio?task=outreach&id='.$draft->id)->assertNotFound();
    }

    public function test_cover_letter_provider_result_is_saved_and_failed_provider_returns_honest_unsaved_template(): void
    {
        \Illuminate\Support\Facades\Http::preventStrayRequests();
        $user = User::factory()->create();
        $input = ['company_name' => 'Actual company', 'job_title' => 'Actual role', 'job_description' => str_repeat('Actual requirements. ', 4), 'language' => 'en', 'tone' => 'professional', 'length' => 'standard'];
        \Illuminate\Support\Facades\Http::fake(['*/generate-cl' => \Illuminate\Support\Facades\Http::sequence()->push(['cover_letter' => "Actual provider letter\nSecond paragraph."])->push(['error' => 'Unavailable'], 503)]);
        $this->actingAs($user)->postJson(route('cover-letters.generate'), $input)->assertOk()->assertJsonPath('success', true)->assertJsonPath('id', 1);
        $this->get(route('cover-letters.show', 1))->assertOk()->assertSee('Actual provider letter');
        $credits = $user->fresh()->cover_letter_credits;
        $this->postJson(route('cover-letters.generate'), $input)->assertOk()->assertJsonPath('is_fallback', true)->assertJsonMissingPath('payload_debug');
        $this->assertSame(1, CoverLetter::count());
        $this->assertSame($credits, $user->fresh()->cover_letter_credits);
        $this->postJson(route('cover-letters.generate'), ['company_name' => 'Company'])->assertUnprocessable();
    }

    public function test_analyzer_submission_redirects_to_saved_portal_result_using_real_response(): void
    {
        \Illuminate\Support\Facades\Http::preventStrayRequests();
        \Illuminate\Support\Facades\Http::fake(['*/analyze' => \Illuminate\Support\Facades\Http::response(['rencana_aksi' => ['Rewrite the actual summary.']])]);
        $user = User::factory()->create();
        $this->actingAs($user)->post(route('ai-analyzer.analyze'), [
            'resume' => \Illuminate\Http\UploadedFile::fake()->create('my-resume.pdf', 10, 'application/pdf'),
            'job_description' => str_repeat('Actual job requirement. ', 4),
        ])->assertRedirect(route('ai-analyzer.show', 1));
        $this->get(route('ai-analyzer.show', 1))->assertOk()->assertSee('Rewrite the actual summary.')->assertSee('my-resume.pdf');
    }

}
