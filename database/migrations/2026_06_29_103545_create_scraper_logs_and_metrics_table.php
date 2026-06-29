<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('scraper_logs_and_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scraper_source_id')->constrained('scraper_sources')->onDelete('cascade');
            $table->timestamp('session_started_at');
            $table->timestamp('session_ended_at')->nullable();
            
            // Ingestion figures
            $table->integer('discovered_links_count')->default(0);
            $table->integer('successfully_scraped_count')->default(0);
            $table->integer('failed_scraped_count')->default(0);
            
            // Network & Proxy Metrics
            $table->string('proxy_ip_used')->nullable();
            $table->integer('bandwidth_bytes_consumed')->default(0);
            $table->decimal('estimated_cost_usd', 8, 4)->default(0.0000);
            
            // Health log details
            $table->string('status')->default('success'); // success, warnings, failed
            $table->text('error_summary')->nullable(); // Aggregated error stack trace if session crashes

            // Indexes for dashboard chart rendering
            $table->index(['scraper_source_id', 'session_started_at'], 'scraper_session_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scraper_logs_and_metrics');
    }
};
