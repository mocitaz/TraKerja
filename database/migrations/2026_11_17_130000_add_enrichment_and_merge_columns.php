<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fase 3 (data intelligence): keep the cleaned description per listing for AI and dedup,
 * record what the AI filled in, and remember which vacancy a duplicate was merged into.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vacancy_listings', function (Blueprint $table) {
            $table->text('description')->nullable()->after('raw_salary'); // PII already removed, max ~8k chars
            $table->char('apply_url_hash', 64)->nullable()->after('apply_url')->index(); // exact cross-platform matches
        });

        Schema::table('vacancies', function (Blueprint $table) {
            $table->timestamp('enriched_at')->nullable()->after('quality_score');
            $table->json('ai_filled')->nullable()->after('enriched_at');   // fields the AI filled, for auditing
            $table->foreignId('merged_into_id')->nullable()->after('closed_reason')->constrained('vacancies')->nullOnDelete();
            $table->index(['enriched_at', 'status']);
        });
    }

    public function down(): void
    {
        if (Schema::hasIndex('vacancies', ['enriched_at', 'status'])) {
            Schema::table('vacancies', fn (Blueprint $table) => $table->dropIndex(['enriched_at', 'status']));
        }
        if (Schema::hasColumn('vacancies', 'merged_into_id')) {
            Schema::table('vacancies', fn (Blueprint $table) => $table->dropConstrainedForeignId('merged_into_id'));
        }
        Schema::table('vacancies', function (Blueprint $table) {
            $table->dropColumn(array_values(array_filter(['enriched_at', 'ai_filled'], fn ($c) => Schema::hasColumn('vacancies', $c))));
        });
        if (Schema::hasIndex('vacancy_listings', ['apply_url_hash'])) {
            Schema::table('vacancy_listings', fn (Blueprint $table) => $table->dropIndex(['apply_url_hash']));
        }
        Schema::table('vacancy_listings', function (Blueprint $table) {
            $table->dropColumn(array_values(array_filter(['description', 'apply_url_hash'], fn ($c) => Schema::hasColumn('vacancy_listings', $c))));
        });
    }
};
