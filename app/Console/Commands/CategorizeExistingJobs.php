<?php

namespace App\Console\Commands;

use App\Models\JobPosting;
use Illuminate\Console\Command;

class CategorizeExistingJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:categorize-existing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Map existing scraped job postings in database to the 15 Sektor Ekonomi, target majors, and work types';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Scanning job_postings table for uncategorized postings...");

        $postings = JobPosting::whereNull('category_field')
            ->orWhereNull('category_major')
            ->orWhereNull('work_type')
            ->orWhereNull('tech_stack')
            ->get();

        $count = $postings->count();
        $this->comment("Found {$count} postings requiring categorization update.");

        if ($count === 0) {
            $this->info("All postings are already fully categorized!");
            return Command::SUCCESS;
        }

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        foreach ($postings as $posting) {
            $title = $posting->title;
            $description = $posting->description ?? '';
            $searchStr = strtolower($title . ' ' . $description);

            // 1. Categorize Field & Major using CategoryHelper
            $categoryResult = \App\Helpers\CategoryHelper::classify($title, $description);
            $field = $categoryResult['sektor'];
            $major = $categoryResult['jurusan'];

            // 3. Categorize Work Type
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

            $posting->update([
                'category_field' => $field,
                'category_major' => $major,
                'work_type' => $workType,
                'tech_stack' => $detectedStack,
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Categorization complete! Successfully updated {$count} postings.");

        return Command::SUCCESS;
    }
}
