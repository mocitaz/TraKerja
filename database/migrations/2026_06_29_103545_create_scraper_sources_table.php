<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('scraper_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'LinkedIn Indonesia', 'JobStreet IT'
            $table->string('target_domain'); // e.g., 'linkedin.com', 'jobstreet.co.id'
            $table->string('seed_url', 1000); // Initial entry URL for listing links
            $table->boolean('is_active')->default(true);
            $table->json('selectors_config'); // Stores CSS/XPath mapping (title, company, body, etc.)
            
            // Scheduling and rate-limiting params
            $table->integer('frequency_minutes')->default(360); // 6 hours
            $table->integer('delay_between_requests_ms')->default(1500); // Politeness delay
            $table->integer('max_concurrency')->default(5); // Parallel limits per scraper
            
            $table->timestamp('last_run_at')->nullable();
            $table->timestamps();

            // Indexes for lookup optimization
            $table->index(['target_domain', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scraper_sources');
    }
};
