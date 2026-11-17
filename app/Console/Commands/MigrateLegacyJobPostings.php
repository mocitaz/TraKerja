<?php

namespace App\Console\Commands;

use App\JobEngine\Ingest\IngestService;
use App\JobEngine\ScrapedJob;
use App\Models\JobSource;
use App\Models\VacancyListing;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Copies the old Laravel scraper's job_postings into vacancies / vacancy_listings
 * (TraKerja 2.0 Blueprint, Fase 1) and points existing bookmarks at the new vacancies.
 * Idempotent: rows already migrated are skipped. job_postings itself is left untouched.
 */
class MigrateLegacyJobPostings extends Command
{
    protected $signature = 'jobs:migrate-legacy {--dry-run : Count only}';

    protected $description = 'Copy legacy job_postings into the new vacancy tables and relink bookmarks';

    public function handle(IngestService $ingest): int
    {
        $total = DB::table('job_postings')->count();
        $this->info("job_postings: {$total} baris");
        if ($total === 0 || $this->option('dry-run')) {
            return self::SUCCESS;
        }

        $sectors = DB::table('taxonomy_sectors')->pluck('id', 'name');
        $sources = [];
        $migrated = 0;
        $skipped = 0;

        DB::table('job_postings')->orderBy('id')->lazyById(500)->each(function ($row) use ($ingest, $sectors, &$sources, &$migrated, &$skipped) {
            $source = $sources[$row->scraper_source_id] ??= $this->legacySource($row->scraper_source_id);
            $job = new ScrapedJob(
                source: $source->slug,
                url: $row->raw_url,
                externalId: 'legacy-'.$row->id,
                title: $row->title,
                company: $row->company_name,
                locationText: $row->location,
                city: $row->location,
                country: 'ID',
                workMode: match (strtolower((string) $row->work_type)) { 'remote' => 'remote', 'hybrid' => 'hybrid', 'onsite' => 'onsite', default => null },
                description: $row->description,
                isOpen: $row->status === 'closed' ? false : null,
                extractionMethod: 'parser',
                raw: ['legacy_job_posting_id' => $row->id, 'category_field' => $row->category_field, 'tech_stack' => $row->tech_stack],
            );

            if ($ingest->ingest($source, $job) === 'skipped') {
                $skipped++;

                return;
            }
            $migrated++;

            $listing = VacancyListing::where('job_source_id', $source->id)->where('external_id', 'legacy-'.$row->id)->first();
            if ($listing?->vacancy_id) {
                DB::table('vacancies')->where('id', $listing->vacancy_id)->update([
                    'sector_id' => $sectors[$row->category_field] ?? null,
                    'skills' => $row->tech_stack,
                    'first_seen_at' => $row->created_at,
                ]);
                DB::table('job_bookmarks')->where('job_posting_id', $row->id)->whereNull('vacancy_id')->update(['vacancy_id' => $listing->vacancy_id]);
            }
        });

        $this->info("Dimigrasi: {$migrated}, dilewati (tanpa judul/perusahaan): {$skipped}");

        return self::SUCCESS;
    }

    private function legacySource(?int $scraperSourceId): JobSource
    {
        $old = $scraperSourceId ? DB::table('scraper_sources')->find($scraperSourceId) : null;

        return JobSource::firstOrCreate(['slug' => 'legacy-'.($scraperSourceId ?? 'unknown')], [
            'name' => 'Legacy: '.($old->name ?? 'unknown'),
            'kind' => 'html',
            'tier' => 'C',
            'region_scope' => 'id',
            'base_url' => $old->seed_url ?? null,
            'is_active' => false,
        ]);
    }
}
