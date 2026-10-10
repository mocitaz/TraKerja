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
        $source = ScraperSource::create(['name' => 'LinkedIn Indonesia', 'target_domain' => 'linkedin.com', 'seed_url' => 'https://linkedin.com/jobs', 'selectors_config' => []]);
        JobPosting::create(['scraper_source_id' => $source->id, 'title' => 'Engineer', 'company_name' => 'Company', 'description' => 'Description', 'raw_url' => 'https://linkedin.com/jobs/1', 'unique_hash' => md5('job1'), 'status' => 'active', 'last_validated_at' => '2026-10-08 09:30:00']);
        $component = Livewire::actingAs(User::factory()->create())->test(ExploreJobs::class);
        $component->assertSee('Source:')->assertSee('LinkedIn Indonesia')->assertSee('08 Oct 2026 · 09:30')->assertSee('HTTP')->assertSee('not a guarantee that applications are still open');
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
