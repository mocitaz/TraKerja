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
        if (!Schema::hasColumn('job_postings', 'location')) {
            Schema::table('job_postings', function (Blueprint $table) {
                $table->string('location')->nullable()->after('category_major');
                $table->index('location');
            });
        }

        // Auto-backfill locations for already scraped job postings using title/description scanning
        // Query builder instead of the old JobPosting model, which was removed with the legacy scraper.
        \Illuminate\Support\Facades\DB::table('job_postings')->select('id', 'title', 'description')->orderBy('id')->chunkById(100, function ($postings) {
            foreach ($postings as $posting) {
                $classification = \App\Helpers\LocationHelper::classify('', $posting->title, $posting->description);
                \Illuminate\Support\Facades\DB::table('job_postings')->where('id', $posting->id)->update(['location' => $classification['city']]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('job_postings', 'location')) {
            Schema::table('job_postings', function (Blueprint $table) {
                try {
                    $table->dropIndex(['location']);
                } catch (\Exception $e) {}
                $table->dropColumn('location');
            });
        }
    }
};
