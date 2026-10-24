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

    public function test_position_suggestions_keep_livewire_morph_markers_outside_options(): void
    {
        $user = User::factory()->create();
        \App\Models\JobApplication::factory()->create([
            'user_id' => $user->id,
            'position' => 'Product Designer',
        ]);

        $html = Livewire::actingAs($user)->test(JobApplicationForm::class)->html();
        $document = new \DOMDocument;
        @$document->loadHTML($html);
        $xpath = new \DOMXPath($document);

        $this->assertSame(1, $xpath->query('//datalist/option')->length);
        $this->assertSame(0, $xpath->query('//datalist/option//comment()')->length,
            'Unclosed options swallow Livewire block markers and break subsequent form updates.');
    }

    public function test_repeated_saves_reset_location_and_switch_city_options_without_reloading(): void
    {
        $user = User::factory()->create();
        $form = Livewire::actingAs($user)->test(JobApplicationForm::class);

        foreach ([
            ['DKI Jakarta', 'Jakarta Selatan'],
            ['Jawa Barat', 'Kota Bandung'],
            ['Jawa Timur', 'Kota Surabaya'],
        ] as [$province, $city]) {
            $form->dispatch('resetFormForNewJob')
                ->assertSet('selectedProvince', '')->assertSet('selectedCity', '')
                ->set('company_name', 'Company '.$province)->set('position', 'Designer '.$city)
                ->set('platform', 'LinkedIn')->set('selectedProvince', $province)
                ->set('selectedCity', $city);

            $otherProvince = $province === 'DKI Jakarta' ? 'Jawa Barat' : 'DKI Jakarta';
            $form->set('selectedProvince', $otherProvince)->assertSet('selectedCity', '')
                ->set('selectedProvince', $province)->assertSet('selectedCity', '')
                ->set('selectedCity', $city)->call('save')
                ->assertHasNoErrors()->assertDispatched('application-saved');

            $this->assertDatabaseHas('job_applications', [
                'user_id' => $user->id,
                'company_name' => 'Company '.$province,
                'location' => $city.', '.$province,
            ]);
        }

        $this->assertDatabaseCount('job_applications', 3);
    }
}
