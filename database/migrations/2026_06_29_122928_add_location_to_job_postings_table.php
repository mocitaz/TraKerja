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
        Schema::table('job_postings', function (Blueprint $table) {
            $table->string('location')->nullable()->after('category_major');
            $table->index('location');
        });

        // Auto-backfill locations for already scraped job postings using title/description scanning
        \App\Models\JobPosting::chunk(100, function ($postings) {
            foreach ($postings as $posting) {
                $classification = \App\Helpers\LocationHelper::classify('', $posting->title, $posting->description);
                $posting->update([
                    'location' => $classification['city']
                ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropIndex(['location']);
            $table->dropColumn('location');
        });
    }
};
