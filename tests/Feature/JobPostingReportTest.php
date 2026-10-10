<?php

namespace Tests\Feature;

use App\Livewire\ExploreJobs;
use App\Models\JobPosting;
use App\Models\ScraperSource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class JobPostingReportTest extends TestCase
{
    use RefreshDatabase;

    private function posting(): JobPosting
    {
        $source = ScraperSource::create(['name' => 'LinkedIn', 'target_domain' => 'linkedin.com', 'seed_url' => 'https://linkedin.com/jobs', 'selectors_config' => []]);
        return JobPosting::create(['scraper_source_id' => $source->id, 'title' => 'Engineer', 'company_name' => 'Example', 'description' => 'Vacancy', 'raw_url' => 'https://linkedin.com/jobs/1', 'unique_hash' => md5('report'), 'status' => 'active']);
    }

    public function test_repeated_report_is_counted_once(): void
    {
        Queue::fake();
        $posting = $this->posting();
        Livewire::actingAs(User::factory()->create())->test(ExploreJobs::class)
            ->call('reportExpired', $posting->id)->call('reportExpired', $posting->id)->call('reportExpired', $posting->id);
        $this->assertSame(1, $posting->fresh()->report_dead_count);
        $this->assertSame('active', $posting->fresh()->status);
    }

    public function test_distinct_reports_request_verification_without_closing(): void
    {
        Queue::fake();
        $posting = $this->posting();
        foreach (range(1, 3) as $i) {
            Livewire::actingAs(User::factory()->create())->test(ExploreJobs::class)->call('reportExpired', $posting->id);
        }
        $this->assertSame(3, $posting->fresh()->report_dead_count);
        $this->assertSame('active', $posting->fresh()->status);
        Queue::assertPushed(\App\Jobs\VerifyReportedJobPosting::class, 3);
    }

    public function test_report_ledger_has_database_unique_constraint(): void
    {
        Queue::fake();
        $posting = $this->posting();
        $user = User::factory()->create();
        app(\App\Services\JobPostingReportService::class)->report($user, $posting->id);
        $this->expectException(\Illuminate\Database\QueryException::class);
        \Illuminate\Support\Facades\DB::table('job_posting_reports')->insert(['user_id' => $user->id, 'job_posting_id' => $posting->id]);
    }

    public function test_verified_missing_page_closes_listing_but_blocked_page_does_not(): void
    {
        $posting = $this->posting();
        $fetcher = $this->createMock(\App\Services\JobPageFetcher::class);
        $fetcher->method('get')->willReturnOnConsecutiveCalls(
            new \Illuminate\Http\Client\Response(new \GuzzleHttp\Psr7\Response(403)),
            new \Illuminate\Http\Client\Response(new \GuzzleHttp\Psr7\Response(404))
        );
        $job = new \App\Jobs\VerifyReportedJobPosting($posting->id);
        $job->handle($fetcher);
        $this->assertSame('active', $posting->fresh()->status);
        $this->assertNull($posting->fresh()->last_validated_at);
        $job->handle($fetcher);
        $this->assertSame('closed', $posting->fresh()->status);
        $this->assertNotNull($posting->fresh()->last_validated_at);
    }

    public function test_guest_cannot_record_a_report(): void
    {
        Queue::fake();
        $posting = $this->posting();
        try {
            (new ExploreJobs)->reportExpired($posting->id);
            $this->fail('Guest report must be denied');
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $exception) {
            $this->assertSame(401, $exception->getStatusCode());
        }
        $this->assertSame(0, $posting->fresh()->report_dead_count);
        Queue::assertNothingPushed();
    }

    public function test_active_page_with_expired_script_message_is_not_closed(): void
    {
        $posting = $this->posting();
        $fetcher = $this->createMock(\App\Services\JobPageFetcher::class);
        $fetcher->method('get')->willReturn(new \Illuminate\Http\Client\Response(new \GuzzleHttp\Psr7\Response(200, [], '<script>const expired = "no longer accepting applications";</script><h1>Active vacancy</h1>')));
        (new \App\Jobs\VerifyReportedJobPosting($posting->id))->handle($fetcher);
        $this->assertSame('active', $posting->fresh()->status);
    }
}
