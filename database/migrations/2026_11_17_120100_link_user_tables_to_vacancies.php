<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lets user-owned records point at canonical vacancies (TraKerja 2.0 Blueprint, Fase 1).
 * The old job_posting_id columns stay until Explore switches over in Fase 5.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->foreignId('vacancy_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
        });

        Schema::table('job_bookmarks', function (Blueprint $table) {
            $table->foreignId('vacancy_id')->nullable()->after('job_posting_id')->constrained()->cascadeOnDelete();
            // New bookmarks point at vacancies only; the legacy column becomes optional.
            $table->unsignedBigInteger('job_posting_id')->nullable()->change();
            $table->unique(['user_id', 'vacancy_id']);
        });

        Schema::table('saved_job_searches', function (Blueprint $table) {
            $table->string('alert_channel', 10)->default('none')->after('fingerprint');   // none, email, telegram
            $table->string('alert_frequency', 10)->default('daily')->after('alert_channel'); // instant, daily, weekly
            $table->timestamp('last_alerted_at')->nullable()->after('alert_frequency');
        });
    }

    /** Defensive: every step checks first, so a half-finished rollback can be run again. */
    public function down(): void
    {
        $columns = array_values(array_filter(['alert_channel', 'alert_frequency', 'last_alerted_at'], fn ($c) => Schema::hasColumn('saved_job_searches', $c)));
        if ($columns) {
            Schema::table('saved_job_searches', fn (Blueprint $table) => $table->dropColumn($columns));
        }
        if (Schema::hasIndex('job_bookmarks', ['user_id', 'vacancy_id'], 'unique')) {
            Schema::table('job_bookmarks', fn (Blueprint $table) => $table->dropUnique(['user_id', 'vacancy_id']));
        }
        // job_posting_id stays nullable: bookmarks created after this migration may not have one.
        if (Schema::hasColumn('job_bookmarks', 'vacancy_id')) {
            Schema::table('job_bookmarks', fn (Blueprint $table) => $table->dropConstrainedForeignId('vacancy_id'));
        }
        if (Schema::hasColumn('job_applications', 'vacancy_id')) {
            Schema::table('job_applications', fn (Blueprint $table) => $table->dropConstrainedForeignId('vacancy_id'));
        }
    }
};
