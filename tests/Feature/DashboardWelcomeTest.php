<?php

namespace Tests\Feature;

use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardWelcomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_companion_uses_only_the_owners_next_interview_and_escapes_company_names(): void
    {
        $owner = User::factory()->create();
        JobApplication::factory()->create(['user_id' => $owner->id, 'company_name' => '<script>owner</script>', 'application_status' => 'On Process', 'recruitment_stage' => 'HR - Interview', 'interview_date' => now()->addDays(2)]);
        JobApplication::factory()->create(['company_name' => 'Other private interview', 'interview_date' => now()->addDay()]);
        $this->actingAs($owner)->get(route('dashboard'))->assertOk()
            ->assertSee('Your next interview is with')->assertSee('&lt;script&gt;owner&lt;/script&gt;', false)
            ->assertDontSee('<script>owner</script>', false)->assertDontSee('Other private interview')
            ->assertSee(route('cv.builder'), false)->assertSee(route('interviews'), false)
            ->assertSee(route('ai-studio.index'), false)->assertSee('Pause welcome animations', false);
    }

    public function test_empty_dashboard_offers_preparation_without_inventing_a_schedule(): void
    {
        $this->actingAs(User::factory()->create())->get(route('dashboard'))->assertOk()
            ->assertSee('Working on your next application?')->assertDontSee('Your next interview is with')
            ->assertSee('Meet your TraKerja companion')->assertSee('No applications yet');
    }
}
