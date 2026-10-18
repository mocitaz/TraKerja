<?php

namespace Tests\Feature;

use App\Livewire\JobFollowUp;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class JobDetailWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_detail_renders_owned_data_interview_and_existing_action_routes(): void
    {
        $owner = User::factory()->create();
        $job = JobApplication::factory()->create([
            'user_id' => $owner->id, 'company_name' => "O'Brien & Partners",
            'position' => 'Backend Engineer', 'application_status' => 'On Process',
            'recruitment_stage' => 'HR - Interview', 'application_date' => now()->subDays(20),
            'interview_date' => now()->addDays(3), 'interview_location' => 'https://meet.example.test/interview',
            'notes' => "First line\nSecond line", 'platform_link' => 'https://example.test/opportunity',
        ]);
        $this->actingAs($owner)->get(route('jobs.show', $job))->assertOk()
            ->assertSee('Backend Engineer')->assertSee("O'Brien & Partners")
            ->assertSee('Application progress')->assertSee('Interview schedule')->assertSee('WIB')
            ->assertSee('First line')->assertSee('Second line')
            ->assertSee(route('jobs.toggle-archive', $job), false)
            ->assertSee(route('jobs.destroy', $job), false)
            ->assertSee('https://meet.example.test/interview', false)
            ->assertSeeLivewire(JobFollowUp::class);
        $this->actingAs(User::factory()->create())->get(route('jobs.show', $job))->assertForbidden();
    }

    public function test_completed_application_has_no_follow_up_and_unsafe_listing_is_not_linked(): void
    {
        $owner = User::factory()->create();
        $job = JobApplication::factory()->create([
            'user_id' => $owner->id, 'application_status' => 'Rejected', 'recruitment_stage' => 'Applied',
            'application_date' => now()->subDays(30), 'interview_date' => null,
            'platform_link' => 'javascript:alert(1)', 'notes' => '<script>alert(1)</script>',
        ]);
        $this->actingAs($owner)->get(route('jobs.show', $job))->assertOk()
            ->assertSee('Recorded outcome')->assertSee('Rejected')->assertSee('No selection schedule recorded yet.')
            ->assertSee('No valid original listing link recorded.')
            ->assertDontSee('href="javascript:', false)->assertDontSee('<script>alert(1)</script>', false)
            ->assertDontSee('Ready to check in?');
    }

    public function test_assessment_detail_uses_the_actual_stage_and_online_platform(): void
    {
        $owner = User::factory()->create();
        $job = JobApplication::factory()->create([
            'user_id' => $owner->id, 'application_status' => 'On Process',
            'recruitment_stage' => 'Assessment Test', 'interview_date' => now()->addDay(),
            'interview_type' => 'Online', 'interview_platform' => 'Assessment portal',
        ]);

        $this->actingAs($owner)->get(route('jobs.show', $job))->assertOk()
            ->assertSee('Assessment schedule')->assertSee('Assessment portal')
            ->assertDontSee('Upcoming interview');
    }

    public function test_detail_follow_up_uses_owned_eligible_application_and_resets_draft(): void
    {
        Http::fake(['*' => Http::response(['result' => 'A polite follow-up draft'])]);
        $owner = User::factory()->create();
        $job = JobApplication::factory()->create(['user_id' => $owner->id, 'application_status' => 'On Process', 'recruitment_stage' => 'Applied', 'application_date' => now()->subDays(20)]);
        $view = Livewire::actingAs($owner)->test(JobFollowUp::class, ['jobId' => $job->id])
            ->call('generateFollowUp', $job->id)->assertSet('showFollowUpModal', true)
            ->assertSet('followUpDraft', 'A polite follow-up draft');
        Http::assertSentCount(1);
        $view->call('closeFollowUpModal')->assertSet('followUpDraft', '')->assertSet('showFollowUpModal', false);
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        Livewire::actingAs(User::factory()->create())->test(JobFollowUp::class, ['jobId' => $job->id]);
    }
}
