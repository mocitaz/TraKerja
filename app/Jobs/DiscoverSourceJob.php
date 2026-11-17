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

    /** A run takes 1–5 minutes; keep this below the queue's retry_after (960 s, config/queue.php). */
    public int $timeout = 900;

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

    /** Called once when the job finally fails: a clear Telegram alert instead of a bare stack trace. */
    public function failed(?\Throwable $e): void
    {
        $source = JobSource::find($this->sourceId);
        $timeout = $e instanceof \Illuminate\Queue\MaxAttemptsExceededException || $e instanceof \Illuminate\Queue\TimeoutExceededException;
        app(\App\JobEngine\Ingest\EngineAlerts::class)->notify(\App\JobEngine\Ingest\EngineAlerts::WARNING, 'Scraper run failed: '.($source?->name ?? "source #{$this->sourceId}"), [
            'Source' => $source ? "{$source->name} ({$source->slug}, {$source->kind})" : null,
            'Error' => $e ? class_basename($e).': '.\Illuminate\Support\Str::limit($e->getMessage(), 160) : null,
            'Meaning' => $timeout ? 'the run took longer than the queue allows or the worker restarted mid-run' : null,
            'Next' => 'the source is retried on its next scheduled discovery',
        ], 'discover-failed:'.$this->sourceId, 180);
    }
}
