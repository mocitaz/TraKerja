<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GoldenSetCommandsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    public function test_collector_drafts_labels_from_json_ld_and_api_sources(): void
    {
        $jsonLd = json_encode([
            '@context' => 'https://schema.org', '@type' => 'JobPosting',
            'title' => 'Staff Admin Gudang',
            'identifier' => ['@type' => 'PropertyValue', 'value' => 'abc123'],
            'datePosted' => '2026-11-10', 'validThrough' => '2026-12-10',
            'employmentType' => ['CONTRACTOR'],
            'hiringOrganization' => ['name' => 'Sumber Rejeki', 'sameAs' => 'https://www.sumberrejeki.co.id'],
            'jobLocation' => ['@type' => 'Place', 'address' => ['addressLocality' => 'Bekasi', 'addressRegion' => 'Jawa Barat', 'addressCountry' => 'ID']],
            'baseSalary' => ['currency' => 'IDR', 'value' => ['minValue' => 4500000, 'maxValue' => 5200000, 'unitText' => 'MONTH']],
        ]);

        Http::fake([
            'dealls.com/sitemap-dynamic.xml' => Http::response('<urlset><url><loc>https://dealls.com/loker/staff-admin-gudang~sumber-rejeki</loc></url></urlset>'),
            'dealls.com/loker/*' => Http::response('<html><script type="application/ld+json">'.$jsonLd.'</script></html>'),
            'www.arbeitnow.com/*' => Http::response(['data' => [[
                'slug' => 'qa-intern-berlin', 'url' => 'https://www.arbeitnow.com/jobs/qa-intern-berlin',
                'title' => 'QA Intern', 'company_name' => 'Acme', 'location' => 'Berlin', 'remote' => false,
                'job_types' => ['internship'], 'created_at' => 1763337600,
            ]]]),
        ]);

        $this->artisan('jobs:golden-collect', ['--source' => ['dealls', 'arbeitnow'], '--delay' => 0])->assertSuccessful();

        $dealls = json_decode(Storage::disk('local')->get('golden/labels/dealls.json'), true);
        $this->assertCount(1, $dealls);
        $this->assertFalse($dealls[0]['reviewed']);
        $this->assertSame([
            'title' => 'Staff Admin Gudang', 'company' => 'Sumber Rejeki', 'company_domain' => 'sumberrejeki.co.id',
            'city' => 'Bekasi', 'province' => 'Jawa Barat', 'employment_type' => 'contract',
            'salary_min' => 4500000, 'salary_max' => 5200000, 'salary_period' => 'month',
            'date_posted' => '2026-11-10', 'valid_through' => '2026-12-10',
        ], array_intersect_key($dealls[0]['label'], array_flip([
            'title', 'company', 'company_domain', 'city', 'province', 'employment_type',
            'salary_min', 'salary_max', 'salary_period', 'date_posted', 'valid_through',
        ])));
        $this->assertTrue(Storage::disk('local')->exists('golden/raw/dealls/dealls-abc123.json'));

        $arbeitnow = json_decode(Storage::disk('local')->get('golden/labels/arbeitnow.json'), true);
        $this->assertSame('internship', $arbeitnow[0]['label']['employment_type']);
        $this->assertTrue($arbeitnow[0]['label']['is_internship']);

        Http::assertSentCount(3);
        Http::assertSent(fn ($request) => str_starts_with($request->header('User-Agent')[0] ?? '', 'TraKerjaBot/'));

        // A second run must not choke on its own _duplicates.json.
        $this->artisan('jobs:golden-collect', ['--source' => ['arbeitnow'], '--delay' => 0])->assertSuccessful();
    }

    public function test_check_reports_review_progress_and_invalid_values(): void
    {
        Storage::disk('local')->put('golden/labels/dealls.json', json_encode([
            ['id' => 'dealls-1', 'reviewed' => true, 'label' => ['title' => 'A', 'company' => 'B', 'employment_type' => 'full_time', 'is_internship' => false, 'work_mode' => 'onsite']],
            ['id' => 'dealls-2', 'reviewed' => true, 'label' => ['title' => 'C', 'company' => null, 'employment_type' => 'permanent', 'is_internship' => false]],
            ['id' => 'dealls-3', 'reviewed' => false, 'label' => ['title' => 'D', 'company' => 'E', 'employment_type' => null, 'is_internship' => null]],
        ]));

        Artisan::call('jobs:golden-check', ['--json' => true]);
        $report = json_decode(Artisan::output(), true);

        $this->assertSame(3, $report['total']);
        $this->assertSame(2, $report['reviewed']);
        $this->assertSame(['samples' => 3, 'reviewed' => 2], $report['per_source']['dealls']);
        $this->assertSame(67, $report['field_coverage_pct']['company']);
        $this->assertContains('dealls-2: company kosong padahal reviewed=true', $report['problems']);
        $this->assertContains("dealls-2: employment_type='permanent' tidak ada di enum", $report['problems']);
        $this->assertCount(2, $report['problems']);
    }
}
