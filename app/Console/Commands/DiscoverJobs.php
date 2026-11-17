<?php

namespace App\Console\Commands;

use App\JobEngine\Ingest\DiscoveryRunner;
use App\Jobs\DiscoverSourceJob;
use App\Models\JobSource;
use Illuminate\Console\Command;

/**
 * php artisan jobs:discover                      all active sources that have an adapter
 * php artisan jobs:discover dealls kalibrr --limit=50
 * php artisan jobs:discover --queue              dispatch to the "ingest" queue instead
 */
class DiscoverJobs extends Command
{
    protected $signature = 'jobs:discover {sources?* : Source slugs (default: all active with an adapter)}
        {--limit=100 : Max vacancies per source in this run}
        {--queue : Dispatch to the ingest queue instead of running here}
        {--refresh : Re-read and rewrite vacancies already stored}';

    protected $description = 'Discover vacancies from job sources into vacancies / vacancy_listings';

    public function handle(DiscoveryRunner $runner): int
    {
        $query = JobSource::query()->whereIn('slug', array_keys(DiscoveryRunner::ADAPTERS));
        $slugs = $this->argument('sources');
        $slugs ? $query->whereIn('slug', $slugs) : $query->where('is_active', true);
        $sources = $query->orderByRaw("CASE region_scope WHEN 'id' THEN 0 ELSE 1 END")->get();

        if ($sources->isEmpty()) {
            $this->warn('Tidak ada sumber yang cocok. Jalankan dulu: php artisan db:seed --class=JobEngineReferenceSeeder');

            return self::FAILURE;
        }

        $limit = (int) $this->option('limit');
        foreach ($sources as $source) {
            if ($this->option('queue')) {
                DiscoverSourceJob::dispatch($source->id, $limit);
                $this->line("{$source->slug}: dikirim ke antrean ingest");

                continue;
            }

            $this->info("== {$source->slug}");
            $run = $runner->run($source, $limit, 'manual', refresh: (bool) $this->option('refresh'), progress: function (array $c) {
                $this->output->write(sprintf("\r   ditemukan %d · baru %d · berubah %d · sama %d · gagal %d",
                    $c['found'], $c['new'], $c['updated'], $c['unchanged'], $c['failed'] + $c['skipped']));
            });
            $this->newLine();
            $this->line(sprintf('   %s dalam %ds, %d request%s', $run->status, $run->finished_at->diffInSeconds($run->started_at, true),
                $run->requests, $run->error_summary ? "\n   ".str_replace("\n", "\n   ", $run->error_summary) : ''));
        }

        return self::SUCCESS;
    }
}
