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
        Schema::create('satisfaction_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // 12 Rating questions (1-5 scale)
            $table->unsignedTinyInteger('q1_overall');
            $table->unsignedTinyInteger('q2_navigation');
            $table->unsignedTinyInteger('q3_speed');
            $table->unsignedTinyInteger('q4_cv_builder');
            $table->unsignedTinyInteger('q5_ai_analyzer');
            $table->unsignedTinyInteger('q6_job_tracker');
            $table->unsignedTinyInteger('q7_cover_letter');
            $table->unsignedTinyInteger('q8_interviews');
            $table->unsignedTinyInteger('q9_premium');
            $table->unsignedTinyInteger('q10_recommend');
            $table->unsignedTinyInteger('q11_design');
            $table->unsignedTinyInteger('q12_cv_templates');
            
            $table->text('feedback')->nullable(); // Suggestions / feedback text
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('satisfaction_surveys');
    }
};
