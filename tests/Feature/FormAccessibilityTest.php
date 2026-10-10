<?php

namespace Tests\Feature;

use App\Livewire\JobApplicationForm;
use App\Models\User;
use DOMDocument;
use DOMXPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FormAccessibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_application_field_has_an_associated_label_across_location_and_interview_states(): void
    {
        $user = User::factory()->create();
        $form = Livewire::actingAs($user)->test(JobApplicationForm::class);
        $this->assertFieldsHaveLabels($form->html());
        $form->set('isInternational', true)->set('recruitment_stage', 'HR - Interview');
        $this->assertFieldsHaveLabels($form->html());
        $second = Livewire::test(JobApplicationForm::class);
        $xpath = $this->dom($form->html().$second->html());
        $ids = [];
        foreach ($xpath->query('//*[@id]') as $element) {
            $this->assertNotContains($element->getAttribute('id'), $ids, 'IDs must remain unique when two forms are rendered.');
            $ids[] = $element->getAttribute('id');
        }
    }

    public function test_sidebar_controls_have_accessible_names_and_no_nested_interactive_elements(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $xpath = $this->dom(view('layouts.sidebar')->render());
        foreach ($xpath->query('//a | //button') as $control) {
            // Expanded-only text is hidden from the accessibility tree when collapsed.
            $this->assertNotSame('', trim($control->getAttribute('aria-label')), 'Collapsed controls need a persistent accessible name.');
        }
        $this->assertSame(0, $xpath->query('//a//button | //a//form')->length);
        foreach ($xpath->query('//button[@aria-controls]') as $control) {
            $this->assertSame(1, $xpath->query('//*[@id="'.$control->getAttribute('aria-controls').'"]')->length);
            $this->assertContains($control->getAttribute('aria-expanded'), ['true', 'false']);
        }
    }

    private function assertFieldsHaveLabels(string $html): void
    {
        $xpath = $this->dom($html);
        foreach ($xpath->query('//input[not(@type="hidden")] | //select | //textarea') as $field) {
            $id = $field->getAttribute('id');
            $this->assertNotSame('', $id, 'Every field needs a unique label target.');
            $labels = $xpath->query('//label[@for="'.$id.'"]');
            $this->assertSame(1, $labels->length, 'Every field needs one associated label: '.$id);
            $this->assertNotSame('', trim($labels->item(0)->textContent));
        }
    }

    private function dom(string $html): DOMXPath
    {
        $document = new DOMDocument;
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML('<!DOCTYPE html><html><body>'.$html.'</body></html>');
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        return new DOMXPath($document);
    }
}
