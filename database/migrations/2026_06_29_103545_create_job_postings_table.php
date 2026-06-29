<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scraper_source_id')->constrained('scraper_sources')->onDelete('cascade');
            $table->string('title');
            $table->string('company_name');
            $table->text('description');
            $table->string('raw_url', 1000);
            
            // Deduplication hash
            $table->string('unique_hash', 32)->unique();

            // State management: active, closed, reported_dead, archived
            $table->enum('status', ['active', 'closed', 'reported_dead', 'archived'])->default('active');
            
            // Crowdsourced feedback loops
            $table->integer('report_dead_count')->default(0);
            
            // Performance/health validation tracker
            $table->timestamp('last_validated_at')->nullable();
            $table->timestamps();

            // Optimized indexes for validation queries
            $table->index(['status', 'last_validated_at']);
            $table->index(['company_name', 'title']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};
