<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Promotions for several social platforms: one caption and one "posted" mark per platform. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_promotions', function (Blueprint $table) {
            $table->json('captions')->nullable()->after('caption');
            $table->json('posted_on')->nullable()->after('posted_at');
        });
    }

    public function down(): void
    {
        Schema::table('job_promotions', function (Blueprint $table) {
            foreach (['captions', 'posted_on'] as $column) {
                if (Schema::hasColumn('job_promotions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
