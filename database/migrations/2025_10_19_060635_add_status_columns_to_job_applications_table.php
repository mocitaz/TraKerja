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
        Schema::table('job_applications', function (Blueprint $table) {
            $table->string('application_status')->default('On Process')->after('status');
            $table->string('recruitment_stage')->default('Applied')->after('application_status');
            $table->string('career_level')->default('Full Time')->after('recruitment_stage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn(['application_status', 'recruitment_stage', 'career_level']);
        });
    }
};
