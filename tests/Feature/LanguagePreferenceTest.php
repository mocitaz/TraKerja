<?php

namespace Tests\Feature;

use App\Mail\WelcomeMail;
use App\Models\{AiStudioRun, JobApplication, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\{Http, Mail, Queue, Schema};
use Illuminate\Support\Str;
use Tests\TestCase;

class LanguagePreferenceTest extends TestCase
{
    use RefreshDatabase;

    public function createApplication()
    {
        $app = require dirname(__DIR__, 2).'/bootstrap/app.php';
        $app->useEnvironmentPath('/dev/null');
        $this->traitsUsedByTest = array_flip(class_uses_recursive(static::class));
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        config(['app.key' => str_repeat('l', 32)]);
        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests(); Mail::fake(); Queue::fake();
    }

    public function test_new_visitors_start_in_english_even_with_an_indonesian_browser(): void
    {
        $this->withHeader('Accept-Language', 'id-ID,id;q=0.9')->get('/login')
            ->assertOk()->assertHeader('Content-Language', 'en')
            ->assertSee('class="language-switch__flag"', false)
            ->assertSee('type="radio" name="locale" value="en" checked', false);
    }

    public function test_guest_switch_persists_in_session_and_cookie_and_preserves_local_destination(): void
    {
        $this->post(route('locale.update'), ['locale' => 'id', 'return_to' => '/login?next=profile'])
            ->assertRedirect('/login?next=profile')->assertSessionHas('locale', 'id')->assertCookie('locale', 'id');
        $this->get('/login')->assertOk()->assertHeader('Content-Language', 'id')->assertSee('lang="id"', false);
        $this->assertSame('en', app()->getLocale());
    }

    public function test_account_choice_overrides_other_device_cookie_and_survives_new_session(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->postJson(route('locale.update'), ['locale' => 'id'])->assertOk()->assertJsonPath('locale', 'id');
        $this->assertSame('id', $user->fresh()->locale);
        $this->flushSession();
        $this->actingAs($user->fresh())->withCookie('locale', 'en')->get('/profile')->assertOk()
            ->assertHeader('Content-Language', 'id')->assertSee('Pengaturan profil')->assertSee('Bahasa Indonesia');
    }

    public function test_invalid_languages_and_external_redirects_are_rejected(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->postJson(route('locale.update'), ['locale' => '../../secret'])->assertUnprocessable();
        $this->assertNull($user->fresh()->locale);
        foreach (['https://other.example', '//other.example', '/\\other.example', "/\r\nLocation: other.example"] as $target) {
            $this->post(route('locale.update'), ['locale' => 'en', 'return_to' => $target])->assertRedirect('/');
        }
    }

    public function test_unmigrated_user_table_still_supports_session_language_switch(): void
    {
        Schema::table('users', fn ($table) => $table->dropColumn('locale'));
        $this->actingAs(User::factory()->create())->postJson(route('locale.update'), ['locale' => 'id'])
            ->assertOk()->assertSessionHas('locale', 'id');
        $this->get('/profile')->assertOk()->assertHeader('Content-Language', 'id');
    }

    public function test_user_authored_names_and_application_values_are_not_translated(): void
    {
        $user = User::factory()->create(['name' => 'Home']); $user->forceFill(['locale' => 'id'])->save();
        $job = JobApplication::factory()->create(['user_id' => $user->id, 'position' => 'Support', 'company_name' => 'Applications', 'notes' => 'Save changes']);
        $this->actingAs($user)->get(route('jobs.show', $job))->assertOk()->assertSee('Applications')->assertSee('Support')->assertSee('Save changes');
        $this->assertSame('Applications', $job->fresh()->company_name);
        $this->assertSame('Save changes', $job->fresh()->notes);
        $this->assertSame('Home', $user->fresh()->name);
    }

    public function test_admin_and_public_layouts_have_language_controls(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_admin' => true]); $admin->forceFill(['locale' => 'id'])->save();
        $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('data-language-form', false)->assertSee('lang="id"', false);
        $this->postJson(route('locale.update'), ['locale' => 'en'])->assertOk();
        $this->actingAs($admin->fresh())->get('/profile')->assertOk()->assertHeader('Content-Language', 'en');
    }

    public function test_mail_uses_recipient_preference_without_leaking_locale_to_next_recipient(): void
    {
        $id = User::factory()->create(); $id->forceFill(['locale' => 'id'])->save();
        $en = User::factory()->create(); $en->forceFill(['locale' => 'en'])->save();
        Mail::to($id)->send(new WelcomeMail($id)); Mail::to($en)->send(new WelcomeMail($en));
        Mail::assertSent(WelcomeMail::class, fn ($mail) => $mail->hasTo($id->email) && $mail->locale === 'id');
        Mail::assertSent(WelcomeMail::class, fn ($mail) => $mail->hasTo($en->email) && $mail->locale === 'en');
    }

    public function test_studio_defaults_to_display_language_but_accepts_explicit_document_language(): void
    {
        $user = User::factory()->create(); $user->forceFill(['locale' => 'id'])->save();
        $input = ['request_key' => (string) Str::uuid(), 'company_name' => 'Actual company', 'job_title' => 'Engineer', 'job_description' => str_repeat('Build reliable APIs with the team. ', 3)];
        $this->actingAs($user)->postJson(route('cover-letters.generate'), $input)->assertAccepted();
        $this->assertSame('id', AiStudioRun::first()->payload['language']);
        $input['request_key'] = (string) Str::uuid(); $input['language'] = 'en';
        $this->postJson(route('cover-letters.generate'), $input)->assertAccepted();
        $this->assertSame('en', AiStudioRun::latest('id')->first()->payload['language']);
    }
}
