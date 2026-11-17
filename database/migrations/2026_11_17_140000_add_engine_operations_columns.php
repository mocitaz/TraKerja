<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fase 4 (verification and operations): adaptive discovery schedule and health reason per
 * source, plus the weekly QA sample that a person checks in the admin panel.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_sources', function (Blueprint $table) {
            $table->unsignedInteger('discovery_interval_minutes')->default(720)->after('daily_request_budget');
            $table->timestamp('next_discovery_at')->nullable()->after('discovery_interval_minutes');
            $table->string('health_reason')->nullable()->after('health');
        });

        Schema::create('vacancy_qa_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vacancy_id')->constrained()->cascadeOnDelete();
            $table->string('sample', 20);                 // e.g. 2026-W47
            $table->string('verdict', 12)->nullable();    // correct, wrong_status, wrong_data, duplicate
            $table->text('note')->nullable();
            $table->foreignId('checked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();
            $table->unique(['vacancy_id', 'sample']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacancy_qa_checks');
        $columns = array_values(array_filter(['discovery_interval_minutes', 'next_discovery_at', 'health_reason'], fn ($c) => Schema::hasColumn('job_sources', $c)));
        if ($columns) {
            Schema::table('job_sources', fn (Blueprint $table) => $table->dropColumn($columns));
        }
    }
};
