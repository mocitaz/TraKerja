<?php

namespace Tests\Feature;

use App\Mail\CustomEmailBlastMail;
use App\Mail\OtpVerificationMail;
use App\Mail\WelcomeMail;
use App\Models\User;
use App\Support\EmailBannerCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailBannerTest extends TestCase
{
    use RefreshDatabase;

    public function test_each_banner_previews_without_sending_and_default_has_no_banner(): void
    {
        Mail::fake();
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $payload = ['email_type' => 'custom', 'custom_subject' => 'Career news', 'custom_content' => 'A useful update.'];

        foreach (EmailBannerCatalog::all() as $id => $banner) {
            $this->assertFileExists(public_path($banner['path']));
            $this->post(route('admin.email-blast.preview'), $payload + ['banner' => $id])
                ->assertOk()->assertSee(asset($banner['path']), false)->assertSee('A useful update.');
        }
        $this->post(route('admin.email-blast.preview'), $payload)->assertOk()->assertDontSee('images/email-banners/');
        Mail::assertNothingSent();
    }

    public function test_preview_and_delivery_use_the_same_banner_for_custom_and_predefined_mail(): void
    {
        Mail::fake();
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $recipient = User::factory()->create();
        foreach (['custom' => CustomEmailBlastMail::class, 'welcome' => WelcomeMail::class] as $type => $class) {
            $payload = ['email_type' => $type, 'user_id' => $recipient->id, 'banner' => 'next-step', 'custom_subject' => 'News', 'custom_content' => 'An update.'];
            $this->post(route('admin.email-blast.preview'), $payload)->assertOk()->assertSee(EmailBannerCatalog::find('next-step')['path']);
            $this->postJson(route('admin.email-blast.send-single'), $payload)->assertOk()->assertJsonPath('success', true);
            Mail::assertSent($class, fn ($mail) => $mail->hasTo($recipient->email)
                && $mail->viewData['emailBannerId'] === 'next-step'
                && str_contains($mail->render(), EmailBannerCatalog::find('next-step')['path']));
        }
    }

    public function test_verification_keeps_otp_delivery_and_uses_the_selected_banner(): void
    {
        Mail::fake();
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $recipient = User::factory()->unverified()->create();
        $payload = ['email_type' => 'verification', 'user_id' => $recipient->id, 'banner' => 'here-to-help'];
        $this->post(route('admin.email-blast.preview'), $payload)->assertOk()->assertSee(EmailBannerCatalog::find('here-to-help')['path']);
        $this->assertDatabaseCount('email_verification_codes', 0);
        $this->postJson(route('admin.email-blast.send-single'), $payload)->assertOk();
        Mail::assertSent(OtpVerificationMail::class, fn ($mail) => $mail->hasTo($recipient->email)
            && $mail->viewData['emailBannerId'] === 'here-to-help'
            && preg_match('/^[0-9]{6}$/', $mail->otp));
        $this->assertDatabaseCount('email_verification_codes', 1);
    }

    public function test_invalid_banner_is_rejected_before_preview_audience_or_delivery(): void
    {
        Mail::fake();
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $recipient = User::factory()->create();
        foreach (['https://example.test/tracker.jpg', '../secret', ['next-step']] as $invalid) {
            foreach (['preview', 'init', 'send-single', 'send'] as $route) {
                $this->postJson(route('admin.email-blast.'.$route), ['email_type' => 'welcome', 'target_user' => 'all', 'user_id' => $recipient->id, 'banner' => $invalid])
                    ->assertUnprocessable()->assertJsonValidationErrors('banner');
            }
        }
        Mail::assertNothingSent();
    }
}
