<?php

namespace App\Services;

use App\Models\JobPosting;

class DeadLinkDetector
{
    protected array $deadFootprints = [
        'no longer accepting applications',
        'lowongan kerja ini sudah ditutup',
        'job has expired',
        'halaman tidak ditemukan',
        'this listing is closed',
        'lowongan tidak lagi aktif',
        'tidak aktif',
        'expired',
        'closed',
    ];

    /**
     * Validate status of a job posting URL.
     * Returns: 'active', 'closed', or 'escalate' (if blocked or requires dynamic JS verification).
     */
    public function validate(JobPosting $posting): string
    {
        try {
            $response = app(JobPageFetcher::class)->get($posting->raw_url);

            if ($response->status() === 404) {
                return 'closed';
            }

            if ($response->status() === 403) {
                // Escalation required (blocked by anti-bot proxies)
                return 'escalate';
            }

            $html = strtolower($response->body());

            foreach ($this->deadFootprints as $footprint) {
                if (str_contains($html, $footprint)) {
                    return 'closed';
                }
            }

            return 'active';
        } catch (\Exception $e) {
            // Connection errors/timeouts trigger escalation
            return 'escalate';
        }
    }
}
