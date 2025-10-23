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
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('users', 'cv_exports_this_month')) {
                $table->integer('cv_exports_this_month')->default(0)->after('is_premium');
            }
            if (!Schema::hasColumn('users', 'cv_exports_reset_at')) {
                $table->timestamp('cv_exports_reset_at')->nullable()->after('cv_exports_this_month');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cv_exports_this_month', 'cv_exports_reset_at']);
        });
    }
};
