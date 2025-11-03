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
            // AI Analyzer free trial tracking
            $table->boolean('has_used_ai_analyzer_trial')->default(false)->after('verification_reminder_count');
            $table->timestamp('ai_analyzer_trial_used_at')->nullable()->after('has_used_ai_analyzer_trial');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'has_used_ai_analyzer_trial',
                'ai_analyzer_trial_used_at',
            ]);
        });
    }
};
