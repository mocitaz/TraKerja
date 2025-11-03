<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Track CV generated count this month
            if (!Schema::hasColumn('users', 'cv_generated_this_month')) {
                $table->integer('cv_generated_this_month')->default(0)->after('cv_exports_this_month');
            }
            
            if (!Schema::hasColumn('users', 'last_cv_generation_reset')) {
                $table->date('last_cv_generation_reset')->nullable()->after('cv_generated_this_month');
            }
            
            // Track AI Analyzer usage this month
            if (!Schema::hasColumn('users', 'ai_analyzer_count_this_month')) {
                $table->integer('ai_analyzer_count_this_month')->default(0)->after('ai_analyzer_trial_used_at');
            }
            
            if (!Schema::hasColumn('users', 'last_ai_analyzer_reset')) {
                $table->date('last_ai_analyzer_reset')->nullable()->after('ai_analyzer_count_this_month');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'cv_generated_this_month',
                'last_cv_generation_reset',
                'ai_analyzer_count_this_month',
                'last_ai_analyzer_reset'
            ]);
        });
    }
};
