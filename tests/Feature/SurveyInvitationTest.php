<?php
namespace Tests\Feature;

use App\Models\{User, Setting, SatisfactionSurvey};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SurveyInvitationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::set('survey_enabled', true);
    }

    private function oldUser(array $attributes = []): User
    {
        return User::factory()->create(array_merge(['created_at' => now()->subDays(4)], $attributes));
    }

    public function test_eligible_user_can_continue_tasks_and_payment_with_optional_invitation(): void
    {
        $this->actingAs($this->oldUser());
        $this->get(route('tracker'))->assertOk()->assertSee('data-survey-invitation', false)
            ->assertSee('aria-controls="workspace-mobile-menu"', false)->assertSee('id="workspace-navigation"', false);
        $this->get(route('payment.premium'))->assertOk()->assertDontSee('data-survey-invitation', false);
        $this->get(route('auth.google'))->assertRedirect();
        $this->assertStringNotContainsString('/survey', $this->get(route('auth.google'))->headers->get('Location'));
        $this->get(route('auth.google.callback'))->assertRedirect(route('login'));
        $this->get(route('survey.show'))->assertOk()->assertViewIs('survey');
    }

    public function test_invitation_is_not_shown_to_new_admin_completed_or_disabled_accounts(): void
    {
        $this->actingAs(User::factory()->create())->get(route('tracker'))->assertDontSee('data-survey-invitation', false);
        $this->actingAs($this->oldUser(['role' => 'admin']))->get(route('survey.show'))->assertRedirect(route('admin.index'));
        $user = $this->oldUser();
        $ratings = array_fill_keys(['q1_overall','q2_navigation','q3_speed','q4_cv_builder','q5_ai_analyzer','q6_job_tracker','q7_cover_letter','q8_interviews','q9_premium','q10_recommend','q11_design','q12_cv_templates'], 4);
        SatisfactionSurvey::create(['user_id' => $user->id] + $ratings);
        $this->actingAs($user)->get(route('tracker'))->assertOk()->assertDontSee('data-survey-invitation', false);
        Setting::set('survey_enabled', false);
        $this->actingAs($this->oldUser())->get(route('tracker'))->assertOk()->assertDontSee('data-survey-invitation', false);
    }

    public function test_dismissal_defers_for_seven_days_and_is_scoped_to_account(): void
    {
        $this->actingAs($this->oldUser());
        $this->post('/survey/dismiss')->assertRedirect(route('tracker'));
        $this->get(route('tracker'))->assertOk()->assertDontSee('data-survey-invitation', false);
        $this->travel(7)->days();
        $this->get(route('tracker'))->assertOk()->assertSee('data-survey-invitation', false);
        $this->post('/survey/dismiss');
        $this->actingAs($this->oldUser())->get(route('tracker'))->assertSee('data-survey-invitation', false);
    }
}
