<?php

namespace App\Console\Commands;

use App\JobEngine\Ingest\DiscoveryRunner;
use App\Jobs\DiscoverSourceJob;
use App\Models\JobSource;
use Illuminate\Console\Command;

/**
 * Runs every 15 minutes from the scheduler: queues discovery for each active source whose
 * adaptive interval has passed. Does nothing unless JOB_ENGINE_SCHEDULE_ENABLED=true.
 */
class JobEngineTick extends Command
{
    protected $signature = 'jobs:tick {--force : Run even when the schedule is disabled}';

    protected $description = 'Queue discovery for job sources that are due';

    public function handle(): int
    {
        if (! config('job_sources.schedule_enabled') && ! $this->option('force')) {
            $this->line('Jadwal mesin data nonaktif (JOB_ENGINE_SCHEDULE_ENABLED=false).');

            return self::SUCCESS;
        }

        $due = JobSource::query()->where('is_active', true)
            ->whereIn('slug', array_keys(DiscoveryRunner::ADAPTERS))
            ->where(fn ($q) => $q->whereNull('next_discovery_at')->orWhere('next_discovery_at', '<=', now()))
            ->get();

        foreach ($due as $source) {
            DiscoverSourceJob::dispatch($source->id, 300);
        }
        $this->line("{$due->count()} sumber dikirim ke antrean ingest.");

        return self::SUCCESS;
    }
}
