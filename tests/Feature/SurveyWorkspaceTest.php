<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\SatisfactionSurvey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SurveyWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    private function ratings(): array
    {
        return array_fill_keys(['q1_overall', 'q2_navigation', 'q3_speed', 'q4_cv_builder', 'q5_ai_analyzer', 'q6_job_tracker', 'q7_cover_letter', 'q8_interviews', 'q9_premium', 'q10_recommend', 'q11_design', 'q12_cv_templates'], 4);
    }

    public function test_survey_has_three_sections_real_inputs_and_optional_deferral(): void
    {
        Setting::set('survey_enabled', true);
        $this->actingAs(User::factory()->create());
        $response = $this->get('/survey')->assertOk()->assertSee('Help shape TraKerja')->assertSee('surveyWorkspace', false)
            ->assertSee('Remind me in 7 days')->assertSee('Your survey is optional')->assertDontSee('Temporary Limited Access')
            ->assertDontSee('sidebar')->assertDontSee('Net Promoter Score');
        foreach ($this->ratings() as $field => $rating) {
            $response->assertSee('name="'.$field.'"', false);
        }
        $this->assertDatabaseCount('satisfaction_surveys', 0);
    }

    public function test_submission_displays_completion_for_owner_and_never_edits_an_existing_response(): void
    {
        Setting::set('survey_enabled', true);
        $user = User::factory()->create();
        $this->actingAs($user)->post('/survey', $this->ratings() + ['feedback' => 'Make keyboard navigation easier.'])->assertRedirect(route('survey.show'));
        $this->assertDatabaseHas('satisfaction_surveys', ['user_id' => $user->id, 'q1_overall' => 4, 'feedback' => 'Make keyboard navigation easier.']);
        $this->get('/survey')->assertOk()->assertSee('Thanks for sharing')->assertSee('Your feedback has been saved')
            ->assertSee(route('dashboard'), false)->assertDontSee('name="q1_overall"', false);
        $this->post('/survey', array_fill_keys(array_keys($this->ratings()), 1))->assertRedirect(route('tracker'));
        $this->assertDatabaseCount('satisfaction_surveys', 1);
        $this->assertDatabaseHas('satisfaction_surveys', ['user_id' => $user->id, 'q1_overall' => 4]);
        $this->actingAs(User::factory()->create())->get('/survey')->assertSee('Help shape TraKerja')->assertDontSee('Thanks for sharing');
    }

    public function test_invalid_answers_preserve_feedback_without_saving(): void
    {
        Setting::set('survey_enabled', true);
        $this->actingAs(User::factory()->create());
        $this->from('/survey')->post('/survey', ['q1_overall' => 6, 'feedback' => 'Keep this draft'])->assertRedirect('/survey')
            ->assertSessionHasErrors(['q1_overall', 'q2_navigation']);
        $this->get('/survey')->assertOk()->assertSee('Keep this draft')->assertSee('Check your answers');
        $this->assertDatabaseCount('satisfaction_surveys', 0);
    }
    public function test_written_feedback_can_be_omitted(): void
    {
        Setting::set('survey_enabled', true);
        $user = User::factory()->create();
        $this->actingAs($user)->post('/survey', $this->ratings())->assertRedirect(route('survey.show'));
        $this->assertDatabaseHas('satisfaction_surveys', ['user_id' => $user->id, 'feedback' => null]);
    }

}
