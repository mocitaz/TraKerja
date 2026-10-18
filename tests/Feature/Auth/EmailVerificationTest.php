<?php

namespace Tests\Feature\Auth;

use App\Mail\OtpVerificationMail;
use App\Mail\WelcomeMail;
use App\Models\EmailVerificationCode;
use App\Models\User;
use App\Services\EmailVerificationService;
use App\Services\TelegramService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Http::preventStrayRequests();
        Event::fake([Verified::class]);
    }

    private function issue(User $user): string
    {
        $user->sendEmailVerificationNotification();

        return Mail::sent(OtpVerificationMail::class)->last()->otp;
    }

    public function test_email_verification_screen_has_an_accessible_otp_form(): void
    {
        $user = User::factory()->unverified()->create();
        $this->actingAs($user)->get('/verify-email')->assertOk()
            ->assertSee('name="otp"', false)->assertSee('autocomplete="one-time-code"', false)
            ->assertSee('action="'.route('verification.confirm').'"', false);
    }

    public function test_email_can_be_verified_once_with_the_current_otp(): void
    {
        $user = User::factory()->unverified()->create();
        $otp = $this->issue($user);
        $this->actingAs($user)->post('/verify-email', ['otp' => $otp])
            ->assertRedirect(route('dashboard', absolute: false).'?verified=1');
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $this->assertNull(EmailVerificationCode::first()->code_hash);
        Event::assertDispatchedTimes(Verified::class, 1);
        Mail::assertSent(WelcomeMail::class, 1);
        $this->post('/verify-email', ['otp' => $otp])->assertRedirect();
        Event::assertDispatchedTimes(Verified::class, 1);
        Mail::assertSent(WelcomeMail::class, 1);
    }

    public function test_otp_is_hashed_and_hidden_and_legacy_fields_are_not_used(): void
    {
        $user = User::factory()->unverified()->create();
        $otp = $this->issue($user);
        $challenge = EmailVerificationCode::first();
        $this->assertMatchesRegularExpression('/^[0-9]{6}$/', $otp);
        $this->assertNotSame($otp, $challenge->code_hash);
        $this->assertTrue(Hash::check($otp, $challenge->code_hash));
        $this->assertArrayNotHasKey('code_hash', $challenge->toArray());
        $this->assertArrayNotHasKey('otp_code', $user->fresh()->toArray());
        $this->assertNull($user->fresh()->otp_code);
    }

    public function test_wrong_code_does_not_verify_and_is_not_flashed_to_session(): void
    {
        $user = User::factory()->unverified()->create();
        $this->issue($user);
        $this->actingAs($user)->from('/verify-email')->post('/verify-email', ['otp' => '000000'])
            ->assertSessionHasErrors('otp')->assertSessionMissing('_old_input.otp');
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
        $this->assertSame(1, EmailVerificationCode::first()->attempts);
        Event::assertNotDispatched(Verified::class);
    }

    public function test_code_expires_at_ten_minutes(): void
    {
        $user = User::factory()->unverified()->create();
        $otp = $this->issue($user);
        $this->travel(10)->minutes();
        $this->actingAs($user)->post('/verify-email', ['otp' => $otp])->assertSessionHasErrors('otp');
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_resend_cooldown_is_enforced_by_the_server_and_replaces_the_code(): void
    {
        $user = User::factory()->unverified()->create();
        $old = $this->issue($user);
        $this->actingAs($user)->post('/email/verification-notification')->assertSessionHasErrors('otp');
        Mail::assertSent(OtpVerificationMail::class, 1);
        $this->travel(60)->seconds();
        $this->post('/email/verification-notification')->assertSessionHasNoErrors();
        Mail::assertSent(OtpVerificationMail::class, 2);
        $new = Mail::sent(OtpVerificationMail::class)->last()->otp;
        $this->assertNotSame($old, $new);
        $this->assertTrue(Hash::check($new, EmailVerificationCode::first()->code_hash));
        $this->post('/verify-email', ['otp' => $old])->assertSessionHasErrors('otp');
        $this->post('/verify-email', ['otp' => $new])->assertSessionHasNoErrors();
    }

    public function test_only_five_guesses_are_allowed_per_code(): void
    {
        $user = User::factory()->unverified()->create();
        $otp = $this->issue($user);
        $this->actingAs($user);
        for ($i = 0; $i < 5; $i++) {
            $this->post('/verify-email', ['otp' => '000000'])->assertSessionHasErrors('otp');
        }
        $this->post('/verify-email', ['otp' => $otp])->assertSessionHasErrors('otp');
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_resending_does_not_reset_the_aggregate_attempt_limit(): void
    {
        $user = User::factory()->unverified()->create();
        $this->issue($user);
        $this->actingAs($user);
        for ($batch = 0; $batch < 2; $batch++) {
            for ($i = 0; $i < 5; $i++) {
                $this->post('/verify-email', ['otp' => '000000'])->assertSessionHasErrors('otp');
            }
            $this->travel(61)->seconds();
            $this->post('/email/verification-notification')->assertSessionHasNoErrors();
        }
        $otp = Mail::sent(OtpVerificationMail::class)->last()->otp;
        $this->post('/verify-email', ['otp' => $otp])->assertSessionHasErrors('otp');
        $this->travel(10)->minutes();
        $this->post('/email/verification-notification')->assertSessionHasNoErrors();
        $otp = Mail::sent(OtpVerificationMail::class)->last()->otp;
        $this->post('/verify-email', ['otp' => $otp])->assertSessionHasNoErrors();
    }

    public function test_hourly_send_limit_is_enforced_outside_http_too(): void
    {
        $user = User::factory()->unverified()->create();
        $this->actingAs($user);
        for ($i = 0; $i < 5; $i++) {
            $this->post('/email/verification-notification')->assertSessionHasNoErrors();
            $this->travel(61)->seconds();
        }
        $this->post('/email/verification-notification')->assertSessionHasErrors('otp');
        Mail::assertSent(OtpVerificationMail::class, 5);
        $this->travel(1)->hours();
        $this->post('/email/verification-notification')->assertSessionHasNoErrors();
    }

    public function test_codes_cannot_verify_another_account(): void
    {
        $owner = User::factory()->unverified()->create();
        $other = User::factory()->unverified()->create();
        $otp = $this->issue($owner);
        $this->actingAs($other)->post('/verify-email', ['otp' => $otp])->assertSessionHasErrors('otp');
        $this->assertFalse($owner->fresh()->hasVerifiedEmail());
        $this->assertFalse($other->fresh()->hasVerifiedEmail());
    }

    public function test_email_changes_invalidate_outstanding_codes(): void
    {
        $user = User::factory()->unverified()->create();
        $otp = $this->issue($user);
        $user->update(['email' => 'changed@example.test']);
        $this->assertNull(EmailVerificationCode::where('user_id', $user->id)->firstOrFail()->code_hash);
        $this->actingAs($user)->post('/verify-email', ['otp' => $otp])->assertSessionHasErrors('otp');
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_legacy_signed_links_never_verify_or_log_in_any_account(): void
    {
        $user = User::factory()->unverified()->create();
        $url = URL::temporarySignedRoute('verification.verify', now()->addHour(), ['id' => $user->id, 'hash' => sha1($user->email)]);
        $this->get($url)->assertRedirect(route('login'));
        $this->assertGuest();
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
        $other = User::factory()->unverified()->create();
        $this->actingAs($other)->get($url)->assertRedirect(route('verification.notice'));
        $this->assertAuthenticatedAs($other);
        Event::assertNotDispatched(Verified::class);
    }

    public function test_guests_cannot_submit_or_request_codes(): void
    {
        $this->post('/verify-email', ['otp' => '123456'])->assertRedirect(route('login'));
        $this->post('/email/verification-notification')->assertRedirect(route('login'));
        Mail::assertNothingSent();
    }

    public function test_expired_legacy_links_still_lead_to_the_new_flow(): void
    {
        $user = User::factory()->unverified()->create();
        $url = URL::temporarySignedRoute('verification.verify', now()->subMinute(), ['id' => $user->id, 'hash' => sha1($user->email)]);
        $this->get($url)->assertRedirect(route('login'));
        $this->assertGuest();
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_verified_users_do_not_receive_more_codes(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/email/verification-notification')->assertRedirect();
        $user->sendEmailVerificationNotification();
        Mail::assertNothingSent();
    }

    public function test_legacy_plaintext_codes_cannot_activate_accounts(): void
    {
        $user = User::factory()->unverified()->create();
        $user->forceFill(['otp_code' => '123456', 'otp_expires_at' => now()->addMinutes(10)])->save();
        $this->actingAs($user)->post('/verify-email', ['otp' => '123456'])->assertSessionHasErrors('otp');
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_profile_links_unverified_users_to_the_otp_form(): void
    {
        $user = User::factory()->unverified()->create();
        $this->actingAs($user)->get('/profile')->assertOk()
            ->assertSee('href="'.route('verification.notice').'"', false);
    }

    public function test_delivery_failure_preserves_existing_code_and_shows_an_error(): void
    {
        $user = User::factory()->unverified()->create();
        $otp = $this->issue($user);
        $hash = EmailVerificationCode::first()->code_hash;
        $this->travel(61)->seconds();
        Mail::shouldReceive('to')->once()->andThrow(new \RuntimeException('Synthetic delivery failure'));
        $this->actingAs($user)->post('/email/verification-notification')->assertSessionHasErrors('otp');
        $this->assertSame($hash, EmailVerificationCode::first()->code_hash);
        $this->assertTrue(Hash::check($otp, EmailVerificationCode::first()->code_hash));
    }

    public function test_registration_issues_an_otp_and_keeps_the_account_unverified(): void
    {
        $this->mock(TelegramService::class)->shouldReceive('sendNewUser')->andReturnNull();
        $this->post('/register', [
            'name' => 'OTP Test', 'email' => 'otp-registration@example.test',
            'password' => 'Password123!', 'password_confirmation' => 'Password123!',
        ])->assertRedirect(route('verification.notice'))->assertSessionHasNoErrors();
        $user = User::where('email', 'otp-registration@example.test')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertFalse($user->hasVerifiedEmail());
        Mail::assertSent(OtpVerificationMail::class, 1);
        $this->assertDatabaseHas('email_verification_codes', ['user_id' => $user->id, 'email' => $user->email]);
    }

    public function test_registration_delivery_failure_keeps_the_account_recoverable(): void
    {
        $this->mock(TelegramService::class)->shouldReceive('sendNewUser')->andReturnNull();
        $this->mock(EmailVerificationService::class)->shouldReceive('send')->once()->andThrow(new \RuntimeException('Synthetic delivery failure'));
        $this->post('/register', [
            'name' => 'OTP Retry', 'email' => 'otp-retry@example.test',
            'password' => 'Password123!', 'password_confirmation' => 'Password123!',
        ])->assertRedirect(route('verification.notice'))->assertSessionHasErrors('otp');
        $user = User::where('email', 'otp-retry@example.test')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertFalse($user->hasVerifiedEmail());
        Mail::assertNothingSent();
    }

    public function test_submission_ip_limit_applies_across_accounts(): void
    {
        $first = User::factory()->unverified()->create();
        $second = User::factory()->unverified()->create();
        $third = User::factory()->unverified()->create();
        foreach ([$first, $second] as $user) {
            $this->actingAs($user);
            for ($i = 0; $i < 15; $i++) {
                $this->post('/verify-email', ['otp' => 'invalid'])->assertSessionHasErrors('otp');
            }
        }
        $this->actingAs($third)->post('/verify-email', ['otp' => '123456'])->assertStatus(429);
    }

    public function test_sending_is_limited_by_ip_across_accounts(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $this->actingAs(User::factory()->unverified()->create())
                ->post('/email/verification-notification')->assertSessionHasNoErrors();
        }
        $this->actingAs(User::factory()->unverified()->create())
            ->post('/email/verification-notification')->assertStatus(429);
        Mail::assertSent(OtpVerificationMail::class, 6);
    }
}
