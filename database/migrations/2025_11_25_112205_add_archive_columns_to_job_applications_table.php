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
            $table->boolean('is_archived')->default(false)->after('is_pinned');
            $table->timestamp('archived_at')->nullable()->after('is_archived');
        });

        // Archive existing jobs that should be archived
        // This handles old data that existed before the archive feature was implemented
        \DB::table('job_applications')
            ->where(function ($query) {
                $query->where('application_status', 'Declined')
                      ->orWhere('recruitment_stage', 'Not Processed');
            })
            ->where('is_archived', false)
            ->update([
                'is_archived' => true,
                'archived_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn(['is_archived', 'archived_at']);
        });
    }
};
