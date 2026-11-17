<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AuditJobDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_audit_counts_duplicates_across_both_job_stores(): void
    {
        $jobstreet = DB::table('scraper_sources')->insertGetId(['name' => 'JobStreet', 'target_domain' => 'jobstreet.co.id', 'seed_url' => 'https://jobstreet.co.id', 'is_active' => true, 'selectors_config' => '{}']);
        $glints = DB::table('scraper_sources')->insertGetId(['name' => 'Glints', 'target_domain' => 'glints.com', 'seed_url' => 'https://glints.com', 'is_active' => true, 'selectors_config' => '{}']);

        foreach ([
            [$jobstreet, 'Senior Frontend Engineer', 'PT Kredivo Group'],
            [$glints, 'Senior Frontend Engineer', 'Kredivo Group'], // same job, other platform, "PT" stripped
            [$jobstreet, 'Data Analyst', 'Bank Jago'],
        ] as $i => [$source, $title, $company]) {
            DB::table('job_postings')->insert([
                'scraper_source_id' => $source, 'title' => $title, 'company_name' => $company, 'description' => '',
                'raw_url' => "https://example.com/{$i}", 'unique_hash' => md5((string) $i), 'status' => 'active',
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        Schema::create('lowongan', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('perusahaan')->nullable();
            $table->string('sumber_platform')->nullable();
            $table->string('status_kirim')->default('belum');
            $table->timestamp('created_at')->useCurrent();
        });
        DB::table('lowongan')->insert([
            ['judul' => 'Admin Gudang', 'perusahaan' => 'PT Sumber Alfaria Trijaya Tbk', 'sumber_platform' => 'karir', 'status_kirim' => 'terkirim'],
            ['judul' => 'admin gudang', 'perusahaan' => 'Sumber Alfaria Trijaya', 'sumber_platform' => 'karir', 'status_kirim' => 'belum'],
        ]);

        Artisan::call('jobs:audit', ['--json' => true]);
        $report = json_decode(Artisan::output(), true);

        $this->assertSame(3, $report['laravel_job_postings']['total']);
        $this->assertSame(['JobStreet' => 2, 'Glints' => 1], $report['laravel_job_postings']['by_source']);
        $this->assertSame(1, $report['laravel_job_postings']['duplicates']['extra_rows']);
        $this->assertSame(1, $report['laravel_job_postings']['duplicates']['cross_source_groups']);

        $this->assertTrue($report['asep_lowongan']['exists']);
        $this->assertSame(['terkirim' => 1, 'belum' => 1], $report['asep_lowongan']['by_status_kirim']);
        $this->assertSame(1, $report['asep_lowongan']['duplicates']['extra_rows']);
        $this->assertSame(0, $report['asep_lowongan']['duplicates']['cross_source_groups']);
        $this->assertFalse($report['asep_grup_telegram']['exists']);
    }
}
