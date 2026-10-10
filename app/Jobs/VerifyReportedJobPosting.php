<?php

namespace App\Jobs;

use App\Models\JobPosting;
use App\Services\JobPageFetcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VerifyReportedJobPosting implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $postingId)
    {
        $this->onQueue('discovery');
    }

    public function handle(JobPageFetcher $fetcher): void
    {
        $posting = JobPosting::find($this->postingId);
        if (!$posting || $posting->status !== 'active') {
            return;
        }
        try {
            $response = $fetcher->get($posting->raw_url);
            $closed = in_array($response->status(), [404, 410], true);
            if (!$closed && !$response->successful()) {
                return;
            }
            // A 200 page can contain hidden expiry templates; leave those decisions to moderation.
            DB::transaction(function () use ($closed) {
                $locked = JobPosting::whereKey($this->postingId)->lockForUpdate()->first();
                if ($locked && $locked->status === 'active') {
                    $locked->update(['last_validated_at' => now()] + ($closed ? ['status' => 'closed'] : []));
                }
            });
        } catch (\Exception $exception) {
            Log::warning('Reported listing verification failed', ['job_posting_id' => $this->postingId, 'exception' => get_class($exception)]);
        }
    }
}
