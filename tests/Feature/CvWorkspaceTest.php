<?php

namespace Tests\Feature;

use App\Livewire\CvBuilder\MainBuilder;
use App\Livewire\CvBuilder\SkillsForm;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CvWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_builder_and_generator_have_a_working_draft_flow_without_requiring_experience(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get(route('cv.builder'))->assertOk()->assertSee('Your details')->assertSee('Import an existing PDF');
        $this->get(route('cv.generator'))->assertOk()->assertSee('Your story starts here.')->assertDontSee('95%')->assertDontSee('ATS GOLD');
        $user->skills()->create(['skill_name' => 'Real TypeScript', 'category' => 'Technical', 'proficiency_level' => 'Intermediate']);
        $this->get(route('cv.generator'))->assertOk()->assertDontSee('Your story starts here.');
        $this->get(route('cv-builder.preview.get', ['canvas' => 1]))->assertOk()->assertSee('Real TypeScript')->assertDontSee('cv-generator-layout');
    }

    public function test_contact_details_save_only_the_current_profile_and_validate_links(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $other->profile()->create(['headline' => 'Do not change']);
        Livewire::actingAs($owner)->test(MainBuilder::class)
            ->set('headline', 'Actual engineer')->set('bio', 'Actual introduction')->set('website_url', 'javascript:alert(1)')
            ->call('saveDetails')->assertHasErrors('website_url')
            ->set('website_url', 'https://example.test/portfolio')->call('saveDetails')->assertHasNoErrors();
        $this->assertSame('Do not change', $other->profile()->first()->headline);
        $this->assertDatabaseHas('user_profiles', ['user_id' => $owner->id, 'headline' => 'Actual engineer']);
        $this->actingAs($owner)->get(route('cv-builder.preview.get', ['canvas' => 1]))->assertSee('Actual introduction')->assertDontSee('Do not change');
    }

    public function test_canvas_preview_preserves_template_access_and_ownership(): void
    {
        Setting::set('monetization_enabled', true, 'boolean');
        $owner = User::factory()->create(['is_premium' => false]);
        $other = User::factory()->create();
        $other->skills()->create(['skill_name' => 'Secret foreign skill', 'category' => 'Technical', 'proficiency_level' => 'Expert']);
        $this->actingAs($owner)->get(route('cv-builder.preview.get', ['canvas' => 1, 'template' => 'creative']))->assertRedirect();
        $this->get(route('cv-builder.preview.get', ['canvas' => 1, 'template' => 'minimal']))->assertOk()->assertDontSee('Secret foreign skill');
        $this->get(route('cv-builder.preview.get', ['canvas' => 1, 'template' => 'missing']))->assertRedirect();
        $this->assertDatabaseCount('cv_templates', 0);
    }

    public function test_section_counts_refresh_after_cv_mutations(): void
    {
        $user = User::factory()->create();
        $workspace = Livewire::actingAs($user)->test(MainBuilder::class);
        Livewire::test(SkillsForm::class)->set('skill_name', 'TypeScript')->call('save')->assertHasNoErrors()->assertDispatched('cv-data-updated');
        $workspace->dispatch('cv-data-updated')->assertSeeHtml('cv-section-count">1');
        $workspace->call('selectSection', 'skills')->assertSet('section', 'skills');
    }

    public function test_review_and_canvas_use_valid_layout_settings_without_spending_export_allowance(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        foreach (['minimal', 'professional', 'creative', 'elegant'] as $template) {
            $this->post(route('cv-builder.preview'), ['template' => $template])->assertOk()->assertSee('Review your CV')->assertSee('Download PDF');
            $this->get(route('cv-builder.preview.get', ['canvas' => 1, 'template' => $template, 'margin' => '22mm', 'font_size' => '10.5pt']))
                ->assertOk()->assertSee('padding: 22mm', false);
        }
        $this->get(route('cv-builder.preview.get', ['canvas' => 1, 'margin' => 'INVALID_CSS', 'font_size' => 'INVALID_CSS']))->assertOk()->assertDontSee('INVALID_CSS');
        $this->assertSame(0, $user->fresh()->cv_generated_this_month);
    }
}
