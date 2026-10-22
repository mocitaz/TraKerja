<?php

namespace Tests\Feature;

use App\Mail\WelcomeMail;
use App\Models\{EmailBlastTemplate, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\{Http, Mail};
use Tests\TestCase;

class AdminEmailComposerTest extends TestCase
{
    use RefreshDatabase;

    private function draft(): array
    {
        return ['name' => 'Career check-in', 'language' => 'en', 'subject' => 'Take your next step', 'content' => 'Take a moment to review your applications and decide what you want to work on next.', 'button_text' => 'Open tracker', 'button_url' => 'https://trakerja.com/dashboard'];
    }

    public function test_gemini_uses_brand_references_and_returns_a_draft_without_saving_or_sending(): void
    {
        Mail::fake();
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        config(['services.gemini.key' => 'fake-key', 'services.gemini.email_model' => 'gemini-test']);
        Http::fake(['generativelanguage.googleapis.com/*' => Http::response(['candidates' => [['finishReason' => 'STOP', 'content' => ['parts' => [['text' => json_encode($this->draft() + ['notes' => 'Check the link.'])]]]]]])]);
        $this->postJson(route('admin.email-blast.templates.generate'), ['prompt' => 'Write a friendly reminder to review job applications.', 'language' => 'en'])
            ->assertOk()->assertJsonPath('draft.subject', 'Take your next step')->assertJsonPath('draft.language', 'en');
        Http::assertSent(fn ($request) => $request->hasHeader('x-goog-api-key', 'fake-key')
            && str_contains($request['systemInstruction']['parts'][0]['text'], 'No HTML')
            && str_contains($request['contents'][0]['parts'][0]['text'], 'voice_references')
            && $request['generationConfig']['responseMimeType'] === 'application/json');
        $this->assertDatabaseCount('email_blast_templates', 0);
        Mail::assertNothingSent();
    }

    public function test_template_requires_preview_of_exact_content_and_human_review_before_save(): void
    {
        Mail::fake();
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);
        $draft = $this->draft();
        $this->postJson(route('admin.email-blast.templates.store'), $draft)->assertUnprocessable()->assertJsonValidationErrors(['reviewed', 'review_token']);
        $preview = $this->postJson(route('admin.email-blast.templates.preview'), $draft)->assertOk()->assertJsonStructure(['html', 'review_token']);
        $token = $preview->json('review_token');
        $this->postJson(route('admin.email-blast.templates.store'), array_replace($draft, ['content' => 'Changed message.', 'review_token' => $token, 'reviewed' => true]))
            ->assertUnprocessable()->assertJsonValidationErrors('review_token');
        $this->postJson(route('admin.email-blast.templates.store'), $draft + ['review_token' => $token, 'reviewed' => false])->assertUnprocessable();
        $this->postJson(route('admin.email-blast.templates.store'), $draft + ['review_token' => $token, 'reviewed' => true])->assertCreated();
        $template = EmailBlastTemplate::firstOrFail();
        $this->assertSame($admin->id, $template->reviewed_by);
        $this->assertNotNull($template->reviewed_at);
        $this->get(route('admin.email-blast'))->assertOk()->assertViewHas('savedTemplates', fn ($templates) => $templates->contains('id', $template->id));
        Mail::assertNothingSent();
    }

    public function test_language_is_applied_to_preview_and_delivery_and_urls_are_validated(): void
    {
        Mail::fake();
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $member = User::factory()->create();
        $this->post(route('admin.email-blast.preview'), ['email_type' => 'welcome', 'email_language' => 'id'])
            ->assertOk()->assertSee('lang="id"', false)->assertSee(trans('emails.welcome.heading', [], 'id'));
        $this->post(route('admin.email-blast.preview'), ['email_type' => 'welcome', 'email_language' => 'en'])
            ->assertOk()->assertSee('lang="en"', false)->assertSee(trans('emails.welcome.heading', [], 'en'));
        $this->postJson(route('admin.email-blast.send-single'), ['email_type' => 'welcome', 'email_language' => 'id', 'user_id' => $member->id])->assertOk();
        Mail::assertSent(WelcomeMail::class, fn ($mail) => $mail->locale === 'id' && $mail->hasTo($member->email));
        $this->postJson(route('admin.email-blast.templates.preview'), array_replace($this->draft(), ['button_url' => 'javascript:alert(1)']))->assertUnprocessable()->assertJsonValidationErrors('button_url');
        $this->postJson(route('admin.email-blast.preview'), ['email_type' => 'custom', 'custom_subject' => 'Hi', 'custom_content' => 'Hello', 'custom_button_text' => 'Go'])->assertUnprocessable()->assertJsonValidationErrors('custom_button_url');
    }

    public function test_provider_failures_and_unsafe_output_do_not_persist_a_template(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        config(['services.gemini.key' => null]);
        Http::preventStrayRequests();
        $payload = ['prompt' => 'Please write a friendly update for members.', 'language' => 'id'];
        $this->postJson(route('admin.email-blast.templates.generate'), $payload)->assertStatus(503);
        Http::assertNothingSent();
        config(['services.gemini.key' => 'fake-key', 'services.gemini.email_model' => 'gemini-test']);
        Http::fake(['*' => Http::response(['candidates' => [['finishReason' => 'STOP', 'content' => ['parts' => [['text' => json_encode(array_replace($this->draft(), ['content' => '<script>alert(1)</script>'.str_repeat('x', 50), 'notes' => '']))]]]]]])]);
        $this->postJson(route('admin.email-blast.templates.generate'), $payload)->assertStatus(503);
        $this->assertDatabaseCount('email_blast_templates', 0);
    }

    public function test_every_catalogue_template_renders_in_both_email_languages(): void
    {
        Mail::fake();
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        foreach (array_keys(\App\Support\EmailTemplateCatalog::mailables()) as $type) {
            foreach (['id', 'en'] as $language) {
                $this->post(route('admin.email-blast.preview'), ['email_type' => $type, 'email_language' => $language])
                    ->assertOk()->assertSee('lang="'.$language.'"', false);
            }
        }
        Mail::assertNothingSent();
    }

    public function test_template_endpoints_are_restricted_to_administrators(): void
    {
        $this->actingAs(User::factory()->create());
        foreach (['generate', 'preview', 'store'] as $action) {
            $this->postJson(route('admin.email-blast.templates.'.$action), $this->draft())->assertForbidden();
        }
    }
}
