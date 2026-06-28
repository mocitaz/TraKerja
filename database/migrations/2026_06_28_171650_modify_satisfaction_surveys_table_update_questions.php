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
        Schema::table('satisfaction_surveys', function (Blueprint $table) {
            $table->dropColumn('q8_summary');
            $table->unsignedTinyInteger('q8_interviews')->after('q7_cover_letter');
            $table->unsignedTinyInteger('q12_cv_templates')->after('q11_design');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('satisfaction_surveys', function (Blueprint $table) {
            $table->dropColumn('q12_cv_templates');
            $table->dropColumn('q8_interviews');
            $table->unsignedTinyInteger('q8_summary')->after('q7_cover_letter');
        });
    }
};
