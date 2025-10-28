<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\JobApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use App\Mail\InterviewReminderMail;

class InterviewReminderEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_premium_user_receives_interview_reminder_email()
    {
        Mail::fake();
        $user = User::factory()->create([
            'is_premium' => true,
            'payment_status' => User::PAYMENT_STATUS_PAID,
        ]);
        $job = JobApplication::factory()->create([
            'user_id' => $user->id,
            'interview_date' => now()->addDay(),
        ]);
        $sent = $job->sendInterviewReminder();
        $this->assertTrue($sent);
        Mail::assertSent(InterviewReminderMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_free_user_does_not_receive_interview_reminder_email_when_premium_mode_enabled()
    {
        Mail::fake();
        \App\Models\Setting::set('monetization_enabled', true);
        $user = User::factory()->create([
            'is_premium' => false,
            'payment_status' => User::PAYMENT_STATUS_FREE,
        ]);
        $job = JobApplication::factory()->create([
            'user_id' => $user->id,
            'interview_date' => now()->addDay(),
        ]);
        $sent = $job->sendInterviewReminder();
        $this->assertFalse($sent);
        Mail::assertNotSent(InterviewReminderMail::class);
    }

    public function test_free_user_receives_interview_reminder_email_when_premium_mode_disabled()
    {
        Mail::fake();
        \App\Models\Setting::set('monetization_enabled', false);
        $user = User::factory()->create([
            'is_premium' => false,
            'payment_status' => User::PAYMENT_STATUS_FREE,
        ]);
        $job = JobApplication::factory()->create([
            'user_id' => $user->id,
            'interview_date' => now()->addDay(),
        ]);
        $sent = $job->sendInterviewReminder();
        $this->assertTrue($sent);
        Mail::assertSent(InterviewReminderMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }
}
