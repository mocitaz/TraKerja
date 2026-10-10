<?php

namespace Tests\Feature;

use App\Livewire\DashboardNextMoves;
use App\Models\JobApplication;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardNextMoveTest extends TestCase
{
    use RefreshDatabase;

    public function test_actions_use_owned_active_records_and_existing_follow_up_rules(): void
    {
        $owner = User::factory()->create();
        $eligible = JobApplication::factory()->create(['user_id' => $owner->id, 'company_name' => 'Follow up company', 'application_status' => 'On Process', 'recruitment_stage' => 'Applied', 'application_date' => today()->subDays(14), 'interview_date' => null]);
        JobApplication::factory()->create(['user_id' => $owner->id, 'company_name' => 'Interview company', 'application_status' => 'On Process', 'recruitment_stage' => 'HR - Interview', 'application_date' => today(), 'interview_date' => now()->addDay()]);
        foreach (['Offering', 'Not Processed'] as $stage) {
            JobApplication::factory()->create(['user_id' => $owner->id, 'application_status' => 'On Process', 'recruitment_stage' => $stage, 'application_date' => today()->subDays(30)]);
        }
        JobApplication::factory()->create(['application_status' => 'On Process', 'recruitment_stage' => 'Applied', 'company_name' => 'Private other owner', 'application_date' => today()->subDays(40)]);
        $response = $this->actingAs($owner)->get(route('dashboard'))->assertOk()->assertSee('Your next move')->assertSee('Time to follow up')
            ->assertSee('1 application ready to review')->assertSee('Interview company')->assertSee(route('jobs.show', $eligible), false)
            ->assertDontSee('Private other owner')->assertDontSee('Your tools')->assertDontSee('Your next three conversations.');
        $response->assertViewHas('cvEssentials', fn ($items) => count($items) === 4 && ! in_array(true, $items, true));
        $this->assertLessThan(strpos($response->getContent(), 'Recent applications'), strpos($response->getContent(), 'Your next move'));
    }

    public function test_mobile_priority_moves_from_interview_to_follow_up_after_preparation(): void
    {
        $owner = User::factory()->create();
        $interview = JobApplication::factory()->create(['user_id' => $owner->id, 'application_status' => 'On Process', 'recruitment_stage' => 'HR - Interview', 'application_date' => today(), 'interview_date' => now()->addDay(), 'is_archived' => false]);
        JobApplication::factory()->create(['user_id' => $owner->id, 'application_status' => 'On Process', 'recruitment_stage' => 'Applied', 'application_date' => today()->subDays(20), 'interview_date' => null, 'is_archived' => false]);
        $this->actingAs($owner);
        $component = Livewire::test(DashboardNextMoves::class)
            ->assertViewHas('primaryMove', 'interview');
        $component->call('complete', 'interview', $interview->id)
            ->assertViewHas('primaryMove', 'followup')
            ->assertDispatched('next-move-updated');
    }

    public function test_empty_state_and_cv_progress_change_with_saved_data(): void
    {
        $owner = User::factory()->create();
        $this->actingAs($owner)->get(route('dashboard'))->assertOk()->assertSee('No upcoming interviews')->assertSee('Nothing due for a follow-up')->assertSee('Start your CV');
        UserProfile::create(['user_id' => $owner->id, 'full_name' => 'Example Owner', 'phone_number' => '123', 'domicile' => 'Bandung', 'bio' => 'Developer']);
        $owner->skills()->create(['skill_name' => 'PHP', 'display_order' => 0]);
        $this->get(route('dashboard'))->assertOk()->assertSee('Continue your CV')->assertSee('3 of 4 essentials added')
            ->assertViewHas('cvEssentials', fn ($items) => $items['Contact details'] && $items['Summary'] && $items['Skills'] && ! $items['Experience or education']);
    }
}
