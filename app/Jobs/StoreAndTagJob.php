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
        $description = $this->payload['description'] ?? '';
        
        $hash = md5($this->url . $company . $title);

        // 1. Categorize Field (Bidang)
        $searchStr = strtolower($title . ' ' . $description);
        $field = 'Software Engineer';
        
        if (str_contains($searchStr, 'backend') || str_contains($searchStr, 'php') || str_contains($searchStr, 'laravel') || str_contains($searchStr, 'golang') || str_contains($searchStr, 'python') || str_contains($searchStr, 'node') || str_contains($searchStr, 'java') || str_contains($searchStr, 'api')) {
            $field = 'Backend Developer';
        } elseif (str_contains($searchStr, 'frontend') || str_contains($searchStr, 'vue') || str_contains($searchStr, 'react') || str_contains($searchStr, 'angular') || str_contains($searchStr, 'css') || str_contains($searchStr, 'html') || str_contains($searchStr, 'javascript') || str_contains($searchStr, 'tail')) {
            $field = 'Frontend Developer';
        } elseif (str_contains($searchStr, 'full stack') || str_contains($searchStr, 'fullstack') || str_contains($searchStr, 'web developer')) {
            $field = 'Fullstack Developer';
        } elseif (str_contains($searchStr, 'mobile') || str_contains($searchStr, 'android') || str_contains($searchStr, 'ios') || str_contains($searchStr, 'flutter') || str_contains($searchStr, 'react native')) {
            $field = 'Mobile Developer';
        } elseif (str_contains($searchStr, 'devops') || str_contains($searchStr, 'cloud') || str_contains($searchStr, 'aws') || str_contains($searchStr, 'kubernetes') || str_contains($searchStr, 'sysadmin') || str_contains($searchStr, 'system admin')) {
            $field = 'DevOps / Cloud';
        } elseif (str_contains($searchStr, 'qa') || str_contains($searchStr, 'tester') || str_contains($searchStr, 'quality assurance') || str_contains($searchStr, 'testing')) {
            $field = 'QA / Testing';
        } elseif (str_contains($searchStr, 'data') || str_contains($searchStr, 'analysis') || str_contains($searchStr, 'analytics') || str_contains($searchStr, 'machine learning') || str_contains($searchStr, 'ai')) {
            $field = 'Data & AI';
        }

        // 2. Categorize Major (Jurusan)
        $major = 'Semua Jurusan IT';
        if (str_contains($searchStr, 'informatika') || str_contains($searchStr, 'computer science') || str_contains($searchStr, 'it') || str_contains($searchStr, 'rpl') || str_contains($searchStr, 'rekayasa perangkat lunak')) {
            $major = 'Teknik Informatika';
        } elseif (str_contains($searchStr, 'sistem informasi') || str_contains($searchStr, 'information system')) {
            $major = 'Sistem Informasi';
        } elseif (str_contains($searchStr, 'matematika') || str_contains($searchStr, 'statistika') || str_contains($searchStr, 'mathematics') || str_contains($searchStr, 'statistics')) {
            $major = 'Matematika / Statistika';
        } elseif (str_contains($searchStr, 'teknik elektro') || str_contains($searchStr, 'electrical engineering')) {
            $major = 'Teknik Elektro';
        }

        // 3. Categorize Work Type
        $workType = 'Onsite';
        if (str_contains($searchStr, 'remote') || str_contains($searchStr, 'wfh') || str_contains($searchStr, 'work from home') || str_contains($searchStr, 'dirumah')) {
            $workType = 'Remote';
        } elseif (str_contains($searchStr, 'hybrid') || str_contains($searchStr, 'wfo/wfh')) {
            $workType = 'Hybrid';
        }

        JobPosting::updateOrCreate(
            ['unique_hash' => $hash],
            [
                'scraper_source_id' => $this->sourceId,
                'title' => $title,
                'company_name' => $company,
                'description' => $description,
                'category_field' => $field,
                'category_major' => $major,
                'work_type' => $workType,
                'raw_url' => $this->url,
                'status' => ($this->payload['isClosed'] ?? false) ? 'closed' : 'active',
                'last_validated_at' => now(),
            ]
        );
    }
}
