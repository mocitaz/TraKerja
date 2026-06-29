<?php

namespace App\Services;

use App\Models\JobPosting;
use Illuminate\Support\Facades\Http;

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
            // 1. Lightweight HEAD request check
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ])
            ->timeout(10)
            ->head($posting->raw_url);

            if ($response->status() === 404) {
                return 'closed';
            }

            // 2. GET request for lightweight footprint checking
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ])
            ->timeout(12)
            ->get($posting->raw_url);

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
