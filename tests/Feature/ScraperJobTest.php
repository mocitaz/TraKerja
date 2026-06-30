<?php

namespace Tests\Feature;

use App\Models\ScraperSource;
use App\Jobs\DiscoverLinksJob;
use App\Jobs\DiscoverPlatformKeywordJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class ScraperJobTest extends TestCase
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
            'is_active' => true,
        ]);
    }

    public function test_discover_links_job_dispatches_staggered_platform_keyword_jobs(): void
    {
        Bus::fake([DiscoverPlatformKeywordJob::class]);

        // Dispatch main master job
        DiscoverLinksJob::dispatch(true);

        // Assert that DiscoverPlatformKeywordJob was dispatched
        Bus::assertDispatched(DiscoverPlatformKeywordJob::class, function ($job) {
            return $job->source->id === $this->source->id;
        });

        // Verify staggering delays
        $dispatchedJobs = Bus::dispatched(DiscoverPlatformKeywordJob::class);
        $this->assertGreaterThan(0, count($dispatchedJobs));

        $firstDelay = $dispatchedJobs[0]->delay;
        $expectedDelay = 0;
        foreach ($dispatchedJobs as $dispatchedJob) {
            $diffInSeconds = (int) round($firstDelay->diffInSeconds($dispatchedJob->delay, false));
            $this->assertEquals($expectedDelay, $diffInSeconds);
            $expectedDelay += 10;
        }
    }
}
