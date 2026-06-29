<?php

namespace App\Console\Commands;

use App\Jobs\DiscoverLinksJob;
use Illuminate\Console\Command;

class RunScraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:run-scraper {--sync : Run the scraping jobs synchronously for immediate terminal output} {--force : Force run bypassing scheduling isDue checks}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trigger the scraping ingestion pipeline for active job portals';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Initiating TraKerja Ingestion Pipeline...");
        
        $force = $this->option('force') || $this->option('sync');

        if ($this->option('sync')) {
            $this->comment("Executing DiscoverLinksJob synchronously...");
            DiscoverLinksJob::dispatchSync($force);
            $this->info("Synchronous crawl cycle dispatched successfully.");
        } else {
            $this->comment("Dispatching DiscoverLinksJob to background queue worker...");
            DiscoverLinksJob::dispatch($force);
            $this->info("Job successfully pushed to the discovery queue.");
        }

        return Command::SUCCESS;
    }
}
