<?php

namespace Tests\Feature;

use App\JobEngine\Ingest\DiscoveryRunner;
use App\Models\JobSource;
use App\Models\Vacancy;
use Database\Seeders\JobEngineReferenceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class JobEngineAtsSourcesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        $this->seed(JobEngineReferenceSeeder::class);
        JobSource::query()->update(['min_delay_ms' => 0]);
        Http::preventStrayRequests();
    }

    private function discover(string $slug, array $boards): void
    {
        $source = JobSource::where('slug', $slug)->firstOrFail();
        $source->update(['config' => array_merge($source->config ?? [], ['boards' => $boards])]);
        app(DiscoveryRunner::class)->run($source, 20);
    }

    public function test_greenhouse_keeps_only_indonesian_roles_from_regional_boards(): void
    {
        Http::fake([
            '*/robots.txt' => Http::response('', 404),
            'boards-api.greenhouse.io/*' => Http::response(['jobs' => [
                ['id' => 1, 'title' => 'Account Executive', 'absolute_url' => 'https://job-boards.greenhouse.io/xendit/jobs/1', 'location' => ['name' => 'Bangkok, Thailand; Jakarta, Indonesia; Singapore, Singapore'], 'first_published' => '2026-11-10T00:00:00Z', 'company_name' => 'Xendit'],
                ['id' => 2, 'title' => 'Sales Lead', 'absolute_url' => 'https://job-boards.greenhouse.io/xendit/jobs/2', 'location' => ['name' => 'Bangkok, Thailand'], 'company_name' => 'Xendit'],
                ['id' => 3, 'title' => 'Engineer', 'absolute_url' => 'https://job-boards.greenhouse.io/xendit/jobs/3', 'location' => ['name' => 'United States'], 'company_name' => 'Xendit'],
            ]]),
        ]);

        $this->discover('greenhouse', ['xendit']);

        $vacancy = Vacancy::sole();
        $this->assertSame('Account Executive', $vacancy->title);
        $this->assertSame('DKI Jakarta', $vacancy->region?->name, 'the Indonesian segment of a multi-country location is used');
        $this->assertSame('ID', $vacancy->country);
    }

    public function test_workable_maps_indonesian_jobs(): void
    {
        Http::fake([
            '*/robots.txt' => Http::response('', 404),
            'apply.workable.com/*' => Http::response(['name' => 'Ajaib', 'jobs' => [
                ['title' => 'AVP, Strategy and Projects', 'shortcode' => 'ABC123', 'url' => 'https://apply.workable.com/ajaib/j/ABC123', 'application_url' => 'https://apply.workable.com/ajaib/j/ABC123/apply', 'city' => 'Jakarta', 'country' => 'Indonesia', 'employment_type' => 'Full-time', 'telecommuting' => false, 'published_on' => '2026-11-12', 'description' => '<p>Minimal S1, gaji Rp 30 juta - 40 juta</p>'],
                ['title' => 'Analyst', 'shortcode' => 'XYZ', 'url' => 'https://apply.workable.com/ajaib/j/XYZ', 'city' => 'Singapore', 'country' => 'Singapore', 'employment_type' => 'Full-time', 'telecommuting' => false],
            ]]),
        ]);

        $this->discover('workable', ['ajaib']);

        $vacancy = Vacancy::sole();
        $this->assertSame(['Ajaib', 'full_time', 's1', 30000000, 40000000], [
            $vacancy->company->name, $vacancy->employment_type, $vacancy->education_min, $vacancy->salary_min, $vacancy->salary_max,
        ]);
        $this->assertSame('manager', $vacancy->seniority, 'AVP counts as manager level');
    }

    public function test_smartrecruiters_maps_indonesian_jobs(): void
    {
        Http::fake([
            '*/robots.txt' => Http::response('', 404),
            'api.smartrecruiters.com/*' => Http::response(['content' => [
                ['id' => '744000', 'name' => 'Backend Engineer - Golang', 'company' => ['name' => 'Stockbit'], 'releasedDate' => '2026-11-14T08:00:00.000Z', 'location' => ['city' => 'Kecamatan Setiabudi', 'region' => 'Jakarta', 'country' => 'id', 'remote' => false], 'typeOfEmployment' => ['label' => 'Full-time']],
            ]]),
        ]);

        $this->discover('smartrecruiters', ['stockbit']);

        $vacancy = Vacancy::sole();
        $this->assertSame(['Stockbit', 'ID', 'full_time'], [$vacancy->company->name, $vacancy->country, $vacancy->employment_type]);
        $this->assertSame('https://jobs.smartrecruiters.com/stockbit/744000', $vacancy->listings()->first()->url);
        $this->assertNotNull($vacancy->region_id, '"Jakarta" region resolves');
    }
}
