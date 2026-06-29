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

        // 1. Categorize Field & Major using unified CategoryHelper
        $categoryResult = \App\Helpers\CategoryHelper::classify($title, $description);
        $field = $categoryResult['sektor'];
        $major = $categoryResult['jurusan'];

        // 3. Categorize Work Type
        $searchStr = strtolower($title . ' ' . $description);
        $workType = 'Onsite';
        if (str_contains($searchStr, 'remote') || str_contains($searchStr, 'wfh') || str_contains($searchStr, 'work from home') || str_contains($searchStr, 'dirumah')) {
            $workType = 'Remote';
        } elseif (str_contains($searchStr, 'hybrid') || str_contains($searchStr, 'wfo/wfh')) {
            $workType = 'Hybrid';
        }

        // 4. Extract Tech Stack
        $techStackKeywords = [
            'React', 'Vue', 'Angular', 'Svelte', 'JavaScript', 'TypeScript', 'Node.js', 'Express',
            'PHP', 'Laravel', 'Symfony', 'Golang', 'Python', 'Django', 'Flask', 'Ruby', 'Rails',
            'Java', 'Kotlin', 'Swift', 'Flutter', 'React Native', 'MySQL', 'PostgreSQL', 
            'MongoDB', 'Redis', 'Docker', 'Kubernetes', 'AWS', 'Git', 'CI/CD', 
            'DevOps', 'QA', 'Selenium', 'Cypress', 'Figma', 'Tailwind', 'Bootstrap'
        ];
        
        $detectedStack = [];
        foreach ($techStackKeywords as $tech) {
            $pattern = '/\b' . preg_quote(strtolower($tech), '/') . '\b/i';
            if (str_contains($tech, '.')) {
                $pattern = '/' . preg_quote(strtolower($tech), '/') . '/i';
            }
            if (preg_match($pattern, $searchStr)) {
                $detectedStack[] = $tech;
            }
        }
        $detectedStack = array_values(array_unique($detectedStack));

        $existing = JobPosting::where('scraper_source_id', $this->sourceId)
            ->where('title', $title)
            ->where('company_name', $company)
            ->first();

        if ($existing) {
            $existing->update([
                'description' => $description,
                'category_field' => $field,
                'category_major' => $major,
                'work_type' => $workType,
                'tech_stack' => $detectedStack,
                'raw_url' => $this->url,
                'status' => ($this->payload['isClosed'] ?? false) ? 'closed' : 'active',
                'last_validated_at' => now(),
            ]);
            $posting = $existing;
        } else {
            $posting = JobPosting::updateOrCreate(
                ['unique_hash' => $hash],
                [
                    'scraper_source_id' => $this->sourceId,
                    'title' => $title,
                    'company_name' => $company,
                    'description' => $description,
                    'category_field' => $field,
                    'category_major' => $major,
                    'work_type' => $workType,
                    'tech_stack' => $detectedStack,
                    'raw_url' => $this->url,
                    'status' => ($this->payload['isClosed'] ?? false) ? 'closed' : 'active',
                    'last_validated_at' => now(),
                ]
            );
        }

        if ($posting->wasRecentlyCreated) {
            $cacheKey = 'targeted_scraping_progress';
            if (\Illuminate\Support\Facades\Cache::has($cacheKey)) {
                $progress = \Illuminate\Support\Facades\Cache::get($cacheKey);
                if (
                    ($progress['status'] ?? '') === 'RUNNING' &&
                    $progress['sector'] === $field &&
                    $progress['major'] === $major
                ) {
                    $progress['current']++;
                    if ($progress['current'] >= $progress['target']) {
                        $progress['status'] = 'COMPLETED';
                        $progress['completed_at'] = now()->toDateTimeString();
                        \App\Jobs\ScrapeJobDetailsJob::logToLiveBuffer("Targeted Ingestion: Target kuota " . $progress['target'] . " loker baru untuk " . $progress['major'] . " telah TERPENUHI!", 'success');
                    } else {
                        \App\Jobs\ScrapeJobDetailsJob::logToLiveBuffer("Targeted Ingestion: Menemukan loker baru (" . $progress['current'] . "/" . $progress['target'] . ") untuk " . $progress['major']);
                    }
                    \Illuminate\Support\Facades\Cache::put($cacheKey, $progress, now()->addHours(6));
                }
            }
        }
    }
}
