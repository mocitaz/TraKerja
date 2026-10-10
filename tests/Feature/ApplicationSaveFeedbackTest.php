<?php

namespace Tests\Feature;

use App\Livewire\JobApplicationForm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ApplicationSaveFeedbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_success_keeps_the_form_intact_until_the_client_finishes_closing(): void
    {
        Livewire::actingAs(User::factory()->create())->test(JobApplicationForm::class)
            ->set('company_name', 'Feedback Corp')->set('position', 'Designer')
            ->set('platform', 'LinkedIn')->set('isRemote', true)
            ->set('application_status', 'On Process')->set('recruitment_stage', 'Applied')
            ->set('application_date', today()->format('Y-m-d'))->call('save')
            ->assertHasNoErrors()->assertDispatched('application-saved')
            ->assertNotDispatched('close-modal')->assertSet('company_name', 'Feedback Corp');
        $this->assertDatabaseHas('job_applications', ['company_name' => 'Feedback Corp']);
    }

    public function test_invalid_form_does_not_signal_success_or_close(): void
    {
        Livewire::actingAs(User::factory()->create())->test(JobApplicationForm::class)
            ->set('isRemote', true)->call('save')->assertHasErrors('company_name')
            ->assertNotDispatched('application-saved')->assertNotDispatched('close-modal');
        $this->assertDatabaseCount('job_applications', 0);
    }
}
