<?php

namespace App\Jobs;

use App\JobEngine\Ingest\DiscoveryRunner;
use App\Models\JobSource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

/** One discovery pass for one source on the "ingest" queue; never two at once for the same source. */
class DiscoverSourceJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public int $timeout = 3600;

    public int $tries = 1;

    public function __construct(public int $sourceId, public int $limit = 200)
    {
        $this->onQueue('ingest');
    }

    public function uniqueId(): string
    {
        return 'discover-'.$this->sourceId;
    }

    public function handle(DiscoveryRunner $runner): void
    {
        $source = JobSource::find($this->sourceId);
        if ($source && $source->is_active) {
            $runner->run($source, $this->limit, 'schedule');
        }
    }
}
