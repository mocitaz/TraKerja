<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ScraperSource;
use App\Models\JobPosting;
use App\Services\DeadLinkDetector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DeadLinkDetectorTest extends TestCase
{
    use RefreshDatabase;

    protected ScraperSource $source;

    protected function setUp(): void
    {
        parent::setUp();

        $this->source = ScraperSource::create([
            'name' => 'LinkedIn Indonesia',
            'target_domain' => 'linkedin.com',
            'seed_url' => 'https://linkedin.com/jobs',
            'selectors_config' => ['title' => '.job-title'],
        ]);
    }

    public function test_detector_detects_closed_listings_via_404_status()
    {
        Http::fake([
            'https://linkedin.com/jobs/view/1' => Http::response('', 404),
        ]);

        $posting = JobPosting::create([
            'scraper_source_id' => $this->source->id,
            'title' => 'Backend Developer',
            'company_name' => 'GoTo',
            'description' => 'Sample description',
            'raw_url' => 'https://linkedin.com/jobs/view/1',
            'unique_hash' => md5('https://linkedin.com/jobs/view/1'),
            'status' => 'active',
        ]);

        $detector = new DeadLinkDetector();
        $this->assertEquals('closed', $detector->validate($posting));
    }

    public function test_detector_detects_closed_listings_via_html_footprints()
    {
        Http::fake([
            'https://linkedin.com/jobs/view/2' => Http::response('<html><body>This job has expired and is no longer accepting applications.</body></html>', 200),
        ]);

        $posting = JobPosting::create([
            'scraper_source_id' => $this->source->id,
            'title' => 'Frontend Developer',
            'company_name' => 'Shopee',
            'description' => 'Sample description',
            'raw_url' => 'https://linkedin.com/jobs/view/2',
            'unique_hash' => md5('https://linkedin.com/jobs/view/2'),
            'status' => 'active',
        ]);

        $detector = new DeadLinkDetector();
        $this->assertEquals('closed', $detector->validate($posting));
    }

    public function test_detector_identifies_active_listings_without_footprints()
    {
        Http::fake([
            'https://linkedin.com/jobs/view/3' => Http::response('<html><body>Apply now for this wonderful open opportunity!</body></html>', 200),
        ]);

        $posting = JobPosting::create([
            'scraper_source_id' => $this->source->id,
            'title' => 'DevOps Engineer',
            'company_name' => 'Bukalapak',
            'description' => 'Sample description',
            'raw_url' => 'https://linkedin.com/jobs/view/3',
            'unique_hash' => md5('https://linkedin.com/jobs/view/3'),
            'status' => 'active',
        ]);

        $detector = new DeadLinkDetector();
        $this->assertEquals('active', $detector->validate($posting));
    }

    public function test_console_command_updates_database_statuses()
    {
        Http::fake([
            'https://linkedin.com/jobs/view/10' => Http::response('', 404),
            'https://linkedin.com/jobs/view/11' => Http::response('<html><body>Apply now!</body></html>', 200),
        ]);

        $closedJob = JobPosting::create([
            'scraper_source_id' => $this->source->id,
            'title' => 'Android Developer',
            'company_name' => 'Company A',
            'description' => 'Desc A',
            'raw_url' => 'https://linkedin.com/jobs/view/10',
            'unique_hash' => md5('https://linkedin.com/jobs/view/10'),
            'status' => 'active',
        ]);

        $activeJob = JobPosting::create([
            'scraper_source_id' => $this->source->id,
            'title' => 'iOS Developer',
            'company_name' => 'Company B',
            'description' => 'Desc B',
            'raw_url' => 'https://linkedin.com/jobs/view/11',
            'unique_hash' => md5('https://linkedin.com/jobs/view/11'),
            'status' => 'active',
        ]);

        $this->artisan('jobs:validate-dead-links --limit=5')
            ->assertExitCode(0);

        $this->assertEquals('closed', $closedJob->fresh()->status);
        $this->assertEquals('active', $activeJob->fresh()->status);
    }
}
