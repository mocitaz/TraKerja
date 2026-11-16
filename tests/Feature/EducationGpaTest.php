<?php

namespace Tests\Feature;

use App\Livewire\CvBuilder\EducationForm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EducationGpaTest extends TestCase
{
    use RefreshDatabase;

    public function test_free_text_gpa_is_saved_as_typed(): void
    {
        $user = User::factory()->create();

        foreach (['3.8/4.0', '3,75', '85/100', ' 3.85 '] as $gpa) {
            Livewire::actingAs($user)->test(EducationForm::class)
                ->set('institution_name', 'Universitas Indonesia')
                ->set('degree', 'Bachelor')
                ->set('major', 'Computer Science')
                ->set('start_date', '2020-08-01')
                ->set('end_date', '2024-07-01')
                ->set('gpa', $gpa)
                ->call('save')
                ->assertHasNoErrors();

            $this->assertSame(trim($gpa), $user->educations()->latest('id')->first()->gpa);
        }
    }
}
