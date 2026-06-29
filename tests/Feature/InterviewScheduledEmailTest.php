<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\JobApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use App\Mail\InterviewScheduledMail;
use Livewire\Livewire;

class InterviewScheduledEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_livewire_sends_scheduled_email_on_new_job_with_interview_for_premium_user()
    {
        Mail::fake();
        $user = User::factory()->create([
            'is_premium' => true,
            'payment_status' => User::PAYMENT_STATUS_PAID,
        ]);
        
        $this->actingAs($user);
        
        Livewire::test(\App\Livewire\JobApplicationForm::class)
            ->set('company_name', 'TechCorp')
            ->set('position', 'Developer')
            ->set('platform', 'LinkedIn')
            ->set('application_date', now()->format('Y-m-d'))
            ->set('isRemote', true)
            ->set('application_status', 'Interview')
            ->set('recruitment_stage', 'HR - Interview')
            ->set('interview_date', now()->addDay()->format('Y-m-d\TH:i'))
            ->call('save')
            ->assertHasNoErrors();
            
        Mail::assertSent(InterviewScheduledMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email) && $mail->jobApplication->company_name === 'TechCorp';
        });
    }

    public function test_livewire_does_not_send_scheduled_email_for_free_user_when_premium_mode_enabled()
    {
        Mail::fake();
        \App\Models\Setting::set('monetization_enabled', true);
        
        $user = User::factory()->create([
            'is_premium' => false,
            'payment_status' => User::PAYMENT_STATUS_FREE,
        ]);
        
        $this->actingAs($user);
        
        Livewire::test(\App\Livewire\JobApplicationForm::class)
            ->set('company_name', 'TechCorp')
            ->set('position', 'Developer')
            ->set('platform', 'LinkedIn')
            ->set('application_date', now()->format('Y-m-d'))
            ->set('isRemote', true)
            ->set('application_status', 'Interview')
            ->set('recruitment_stage', 'HR - Interview')
            ->set('interview_date', now()->addDay()->format('Y-m-d\TH:i'))
            ->call('save')
            ->assertHasNoErrors();
            
        Mail::assertNotSent(InterviewScheduledMail::class);
    }

    public function test_livewire_sends_scheduled_email_for_free_user_when_premium_mode_disabled()
    {
        Mail::fake();
        \App\Models\Setting::set('monetization_enabled', false);
        
        $user = User::factory()->create([
            'is_premium' => false,
            'payment_status' => User::PAYMENT_STATUS_FREE,
        ]);
        
        $this->actingAs($user);
        
        Livewire::test(\App\Livewire\JobApplicationForm::class)
            ->set('company_name', 'TechCorp')
            ->set('position', 'Developer')
            ->set('platform', 'LinkedIn')
            ->set('application_date', now()->format('Y-m-d'))
            ->set('isRemote', true)
            ->set('application_status', 'Interview')
            ->set('recruitment_stage', 'HR - Interview')
            ->set('interview_date', now()->addDay()->format('Y-m-d\TH:i'))
            ->call('save')
            ->assertHasNoErrors();
            
        Mail::assertSent(InterviewScheduledMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_livewire_sends_email_when_interview_date_is_rescheduled()
    {
        Mail::fake();
        $user = User::factory()->create([
            'is_premium' => true,
            'payment_status' => User::PAYMENT_STATUS_PAID,
        ]);
        
        $job = JobApplication::factory()->create([
            'user_id' => $user->id,
            'company_name' => 'OldCorp',
            'position' => 'Senior Developer',
            'platform' => 'Indeed',
            'application_status' => 'Interview',
            'recruitment_stage' => 'HR - Interview',
            'interview_date' => now()->addDay(),
        ]);

        $this->actingAs($user);

        Livewire::test(\App\Livewire\JobApplicationForm::class)
            ->call('editJob', $job->id)
            ->set('interview_date', now()->addDays(3)->format('Y-m-d\TH:i'))
            ->call('save')
            ->assertHasNoErrors();

        Mail::assertSent(InterviewScheduledMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email) && $mail->jobApplication->company_name === 'OldCorp';
        });
    }

    public function test_livewire_does_not_send_email_when_interview_date_is_unchanged_on_update()
    {
        Mail::fake();
        $user = User::factory()->create([
            'is_premium' => true,
            'payment_status' => User::PAYMENT_STATUS_PAID,
        ]);
        
        $interviewDate = now()->addDay();
        
        $job = JobApplication::factory()->create([
            'user_id' => $user->id,
            'company_name' => 'OldCorp',
            'position' => 'Senior Developer',
            'platform' => 'Indeed',
            'application_status' => 'Interview',
            'recruitment_stage' => 'HR - Interview',
            'interview_date' => $interviewDate,
        ]);

        $this->actingAs($user);

        Livewire::test(\App\Livewire\JobApplicationForm::class)
            ->call('editJob', $job->id)
            ->set('notes', 'Some updated notes') // Change other field, keep interview_date identical
            ->set('interview_date', $interviewDate->format('Y-m-d\TH:i'))
            ->call('save')
            ->assertHasNoErrors();

        Mail::assertNotSent(InterviewScheduledMail::class);
    }
}
