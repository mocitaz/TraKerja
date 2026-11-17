<?php

namespace Tests\Feature;

use App\Livewire\ExploreJobs;
use App\Livewire\JobApplicationForm;
use App\Models\JobPosting;
use App\Models\ScraperSource;
use App\Models\User;
use DOMDocument;
use DOMXPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UiLanguageReadabilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_sidebar_uses_readable_tokens_and_indonesian_names(): void
    {
        $this->actingAs(User::factory()->create());
        app()->setLocale('id');
        $html = view('layouts.sidebar')->render();
        $xpath = $this->dom($html);
        foreach ($xpath->query('//nav/a') as $link) {
            $this->assertStringContainsString('text-sm', $link->getAttribute('class'));
            $this->assertStringContainsString('text-ui-muted', $link->getAttribute('class'));
        }
        $this->assertStringContainsString('Pantau Lamaran', $html);
        $this->assertStringContainsString('Ringkasan', $html);
        $this->assertStringNotContainsString('Close navigation', $html);
    }

    public function test_form_labels_and_disabled_fields_stay_readable_and_status_values_stay_canonical(): void
    {
        $component = Livewire::actingAs(User::factory()->create())->test(JobApplicationForm::class);
        $xpath = $this->dom($component->html());
        foreach ($xpath->query('//label[not(@class="sr-only")]') as $label) {
            $this->assertStringContainsString('text-xs', $label->getAttribute('class'));
        }
        $disabled = $xpath->query('//select[@disabled]')->item(0);
        $this->assertStringNotContainsString('opacity-', $disabled->getAttribute('class'));
        $this->assertStringContainsString('text-ui-disabled', $disabled->getAttribute('class'));
        $component->assertSee('Application status')->assertSee('On Process')->assertSee('Applied');
        $this->assertSame('On Process', $xpath->query('//option[@value="On Process"]')->item(0)->textContent);
        $this->assertSame('Applied', $xpath->query('//option[@value="Applied"]')->item(0)->textContent);
    }

    public function test_explore_shows_source_validation_time_and_limits_of_http_check(): void
    {
        $this->seed(\Database\Seeders\JobEngineReferenceSeeder::class);
        app(\App\JobEngine\Ingest\IngestService::class)->ingest(\App\Models\JobSource::where('slug', 'dealls')->first(), new \App\JobEngine\ScrapedJob(
            source: 'dealls', url: 'https://dealls.com/loker/1', externalId: '1', title: 'Engineer', company: 'Company', city: 'Bekasi', extractionMethod: 'jsonld',
        ));
        \App\Models\Vacancy::query()->update(['last_verified_at' => '2026-10-08 02:30:00']);
        $component = Livewire::actingAs(User::factory()->create())->test(ExploreJobs::class);
        $component->assertSee('Check the original listing before applying.')->assertSee('Latest page check')->assertSee('08 Oct 2026')
            ->assertSee('An HTTP check confirms page availability, not whether the employer is still hiring.');
        $component->assertDontSee('anti-ghosting')->assertDontSee('real-time')->assertDontSee('thousands')->assertDontSee('Verified');
    }

    private function dom(string $html): DOMXPath
    {
        $document = new DOMDocument;
        libxml_use_internal_errors(true);
        $document->loadHTML('<!DOCTYPE html><html><body>'.$html.'</body></html>');
        libxml_clear_errors();
        return new DOMXPath($document);
    }
}
