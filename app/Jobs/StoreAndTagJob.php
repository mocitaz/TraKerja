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
                'tech_stack' => $detectedStack,
                'raw_url' => $this->url,
                'status' => ($this->payload['isClosed'] ?? false) ? 'closed' : 'active',
                'last_validated_at' => now(),
            ]
        );
    }
}
