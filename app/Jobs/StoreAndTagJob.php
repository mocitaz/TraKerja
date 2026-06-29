<?php

namespace App\Jobs;

use App\Models\JobPosting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreAndTagJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $payload;
    public string $url;
    public int $sourceId;

    public function __construct(array $payload, string $url, int $sourceId)
    {
        $this->payload = $payload;
        $this->url = $url;
        $this->sourceId = $sourceId;
        $this->queue = 'processing';
    }

    public function handle()
    {
        $company = $this->payload['company'] ?? 'Unknown Company';
        $title = $this->payload['title'] ?? 'Job Opportunity';
        
        $hash = md5($this->url . $company . $title);

        JobPosting::updateOrCreate(
            ['unique_hash' => $hash],
            [
                'scraper_source_id' => $this->sourceId,
                'title' => $title,
                'company_name' => $company,
                'description' => $this->payload['description'] ?? '',
                'raw_url' => $this->url,
                'status' => ($this->payload['isClosed'] ?? false) ? 'closed' : 'active',
                'last_validated_at' => now(),
            ]
        );
    }
}
