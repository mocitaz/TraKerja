<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Job-data engine schema (TraKerja 2.0 Blueprint, Fase 1).
 *
 * Naming: the canonical job is a "vacancy" because `jobs` is Laravel's queue table.
 * Enumerated values are stored as short strings and validated in PHP (App\JobEngine\Enums),
 * so adding a value never needs an ALTER TABLE on MySQL.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_sources', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 50)->unique();
            $table->string('name', 100);
            $table->string('kind', 20);            // api, ats, rss, jsonld, next_data, html, partner, direct
            $table->char('tier', 1);               // A-D, see config/job_sources.php
            $table->string('region_scope', 10)->default('id'); // id, remote, global, mixed
            $table->string('base_url')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('robots_allowed')->nullable();
            $table->timestamp('robots_checked_at')->nullable();
            $table->unsignedInteger('min_delay_ms')->default(2000);
            $table->unsignedInteger('daily_request_budget')->default(2000);
            $table->json('config')->nullable();
            $table->string('health', 12)->default('ok'); // ok, degraded, failing, paused
            $table->timestamp('last_success_at')->nullable();
            $table->timestamps();
        });

        Schema::create('crawl_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_source_id')->nullable()->constrained('job_sources')->nullOnDelete();
            $table->string('run_type', 12);         // discover, verify
            $table->string('trigger', 12)->default('manual');
            $table->timestamp('started_at');
            $table->timestamp('finished_at')->nullable();
            $table->string('status', 12)->default('running'); // running, success, partial, failed
            $table->unsignedInteger('found')->default(0);
            $table->unsignedInteger('new_listings')->default(0);
            $table->unsignedInteger('updated_listings')->default(0);
            $table->unsignedInteger('unchanged_listings')->default(0);
            $table->unsignedInteger('failed_items')->default(0);
            $table->unsignedInteger('requests')->default(0);
            $table->unsignedInteger('ai_tokens')->default(0);
            $table->decimal('cost_usd', 10, 6)->default(0);
            $table->text('error_summary')->nullable();
            $table->string('worker_host', 100)->nullable();
            $table->timestamps();
            $table->index(['job_source_id', 'started_at']);
        });

        Schema::create('taxonomy_sectors', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 120)->unique();
            $table->string('name');
            $table->unsignedSmallInteger('sort')->default(0);
        });

        Schema::create('taxonomy_majors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sector_id')->constrained('taxonomy_sectors')->cascadeOnDelete();
            $table->string('slug', 150)->unique();
            $table->string('name');
            $table->json('keywords')->nullable();
        });

        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->string('level', 10);            // province, city
            $table->string('name', 120);
            $table->string('slug', 150)->unique();
            $table->json('aliases')->nullable();
            $table->index(['level', 'name']);
        });

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('slug', 200)->unique();
            $table->string('name_normalized', 200)->index();
            $table->string('domain', 150)->nullable()->unique();
            $table->string('logo_url')->nullable();
            $table->string('website_url')->nullable();
            $table->foreignId('sector_id')->nullable()->constrained('taxonomy_sectors')->nullOnDelete();
            $table->foreignId('hq_region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });

        Schema::create('company_aliases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('alias_normalized', 200)->unique();
            $table->string('origin', 40)->default('auto');
            $table->timestamps();
        });

        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->string('slug');
            $table->foreignId('company_id')->constrained();
            $table->string('title');
            $table->string('title_normalized')->index();
            $table->foreignId('sector_id')->nullable()->constrained('taxonomy_sectors')->nullOnDelete();
            $table->foreignId('major_id')->nullable()->constrained('taxonomy_majors')->nullOnDelete();
            $table->string('employment_type', 20)->nullable();
            $table->string('work_mode', 10)->nullable();
            $table->string('seniority', 10)->nullable();
            $table->string('education_min', 10)->nullable();
            $table->boolean('is_internship')->default(false);
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->string('location_text')->nullable();
            $table->char('country', 2)->nullable();
            $table->unsignedBigInteger('salary_min')->nullable();
            $table->unsignedBigInteger('salary_max')->nullable();
            $table->char('salary_currency', 3)->nullable();
            $table->string('salary_period', 6)->nullable(); // hour, day, week, month, year
            $table->text('summary')->nullable();
            $table->json('skills')->nullable();
            $table->date('date_posted')->nullable();
            $table->date('valid_through')->nullable();
            $table->string('status', 10)->default('active'); // active, suspect, closed, archived
            $table->timestamp('closed_at')->nullable();
            $table->string('closed_reason', 40)->nullable();
            $table->unsignedBigInteger('primary_listing_id')->nullable(); // FK-free: listings point back here
            $table->unsignedTinyInteger('listings_count')->default(0);
            $table->timestamp('first_seen_at')->nullable();
            $table->timestamp('last_seen_open_at')->nullable();
            $table->timestamp('last_verified_at')->nullable();
            $table->decimal('extraction_confidence', 3, 2)->nullable();
            $table->unsignedTinyInteger('quality_score')->default(0);
            $table->string('scam_flag', 10)->default('none'); // none, suspected, confirmed
            $table->timestamps();
            $table->index(['status', 'date_posted']);
            $table->index(['status', 'sector_id', 'region_id']);
            $table->index(['company_id', 'status']);
        });

        Schema::create('vacancy_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_source_id')->constrained('job_sources');
            $table->foreignId('vacancy_id')->nullable()->constrained()->nullOnDelete();
            $table->string('external_id', 191)->nullable();
            $table->text('url');
            $table->char('url_hash', 64)->unique();
            $table->text('apply_url')->nullable();
            $table->string('raw_title')->nullable();
            $table->string('raw_company')->nullable();
            $table->string('raw_location')->nullable();
            $table->string('raw_salary')->nullable();
            $table->json('raw_payload')->nullable();
            $table->char('content_hash', 64)->nullable();
            $table->unsignedBigInteger('simhash')->nullable()->index();
            $table->string('status', 10)->default('active');
            $table->string('match_method', 10)->default('new'); // new, exact, fuzzy, manual
            $table->decimal('match_score', 4, 3)->nullable();
            $table->string('extraction_method', 10)->nullable(); // api, jsonld, next_data, parser, ai
            $table->string('etag')->nullable();
            $table->string('last_modified')->nullable();
            $table->timestamp('first_seen_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamp('next_check_at')->nullable();
            $table->unsignedTinyInteger('consecutive_misses')->default(0);
            $table->timestamps();
            $table->unique(['job_source_id', 'external_id']);
            $table->index(['status', 'next_check_at']);
        });

        Schema::create('vacancy_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vacancy_listing_id')->constrained()->cascadeOnDelete();
            $table->timestamp('checked_at');
            $table->unsignedSmallInteger('http_status')->nullable();
            $table->text('final_url')->nullable();
            $table->unsignedInteger('latency_ms')->nullable();
            $table->json('signals')->nullable();
            $table->string('verdict', 10); // open, suspect, closed, blocked, error
            $table->index(['vacancy_listing_id', 'checked_at']);
        });

        Schema::create('dedup_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vacancy_listing_id')->constrained()->cascadeOnDelete();
            $table->foreignId('candidate_vacancy_id')->constrained('vacancies')->cascadeOnDelete();
            $table->decimal('score', 4, 3);
            $table->json('score_detail')->nullable();
            $table->string('status', 10)->default('pending'); // pending, merged, separate
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('vacancy_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vacancy_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('reason', 12); // closed, scam, wrong_info, duplicate
            $table->text('note')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            $table->unique(['vacancy_id', 'user_id', 'reason']);
        });

        Schema::create('ai_calls', function (Blueprint $table) {
            $table->id();
            $table->string('purpose', 20);   // extract, classify, dedup_judge, selector_suggest, embed
            $table->string('model', 50);
            $table->string('service_tier', 10)->nullable();
            $table->foreignId('vacancy_listing_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('crawl_run_id')->nullable()->constrained()->nullOnDelete();
            $table->char('input_hash', 64);
            $table->unsignedInteger('prompt_tokens')->default(0);
            $table->unsignedInteger('cached_tokens')->default(0);
            $table->unsignedInteger('output_tokens')->default(0);
            $table->decimal('cost_usd', 10, 7)->default(0);
            $table->unsignedInteger('latency_ms')->default(0);
            $table->string('status', 16);    // ok, invalid_output, error, budget_skipped
            $table->json('response')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['purpose', 'created_at']);
            $table->index(['input_hash', 'purpose']);
        });
    }

    public function down(): void
    {
        foreach (['ai_calls', 'vacancy_reports', 'dedup_reviews', 'vacancy_checks', 'vacancy_listings', 'vacancies',
            'company_aliases', 'companies', 'regions', 'taxonomy_majors', 'taxonomy_sectors', 'crawl_runs', 'job_sources'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
