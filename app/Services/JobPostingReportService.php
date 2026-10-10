<?php

namespace App\Services;

use App\Jobs\VerifyReportedJobPosting;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class JobPostingReportService
{
    public function report(User $user, int $postingId): bool
    {
        $inserted = DB::transaction(function () use ($user, $postingId) {
            $posting = JobPosting::whereKey($postingId)->lockForUpdate()->firstOrFail();
            if ($posting->status !== 'active') {
                return false;
            }
            $inserted = DB::table('job_posting_reports')->insertOrIgnore([
                'job_posting_id' => $posting->id, 'user_id' => $user->id,
                'created_at' => now(), 'updated_at' => now(),
            ]);
            if ($inserted) {
                $posting->increment('report_dead_count');
            }
            return (bool) $inserted;
        });
        if ($inserted) {
            VerifyReportedJobPosting::dispatch($postingId)->afterCommit();
        }
        return $inserted;
    }
}
