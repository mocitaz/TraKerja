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
            $table->dateTime('interview_date')->nullable()->after('application_date');
            $table->text('interview_notes')->nullable()->after('interview_date');
            $table->string('interview_type', 50)->nullable()->after('interview_notes'); // 'Phone', 'Video', 'In-person', 'Panel'
            $table->string('interview_location')->nullable()->after('interview_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn(['interview_date', 'interview_notes', 'interview_type', 'interview_location']);
        });
    }
};
