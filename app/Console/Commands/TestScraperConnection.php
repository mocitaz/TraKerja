<?php

namespace App\Console\Commands;

use App\Models\ScraperSource;
use Illuminate\Console\Command;

class TestScraperConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:test-connection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test scraper discovery and puppeteer connections for LinkedIn, JobStreet, and Kalibrr';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("==============================================");
        $this->info("      Scraper Connection Diagnostic Tool      ");
        $this->info("==============================================");

        $sources = ScraperSource::all();

        if ($sources->isEmpty()) {
            $this->error("No scraper sources found in database. Please run db:seed first.");
            return Command::FAILURE;
        }

        foreach ($sources as $source) {
            $this->info("Testing Source: {$source->name} ({$source->target_domain})");
            $this->line("Seed URL: {$source->seed_url}");
            
            $startTime = microtime(true);
            
            // Execute the actual discovery method to verify link parsing
            $urls = $source->executeDiscovery();
            
            $duration = round((microtime(true) - $startTime) * 1000);

            if (!empty($urls)) {
                $this->info(" -> [SUCCESS] Connection established! Found " . count($urls) . " job links.");
                $this->comment(" -> Latency: {$duration} ms");
                $this->line(" -> Sample link: " . head($urls));
            } else {
                $this->error(" -> [FAILED] Connection failed or page returned 0 links.");
                $this->comment(" -> Latency: {$duration} ms");
                $this->line(" -> Tip: Check scraper-node.log if Puppeteer failed, or check your internet connection.");
            }
            $this->newLine();
        }

        $this->info("==============================================");
        return Command::SUCCESS;
    }
}
