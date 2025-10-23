<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Only add columns if they don't exist
            if (!Schema::hasColumn('users', 'registered_phase')) {
                $table->tinyInteger('registered_phase')->default(1)->after('email_verified_at');
            }
            
            if (!Schema::hasColumn('users', 'grandfathered_benefits')) {
                $table->json('grandfathered_benefits')->nullable()->after('registered_phase');
            }
            
            if (!Schema::hasColumn('users', 'cv_exports_this_month')) {
                $table->integer('cv_exports_this_month')->default(0)->after('grandfathered_benefits');
            }
            
            if (!Schema::hasColumn('users', 'last_export_reset')) {
                $table->date('last_export_reset')->nullable()->after('cv_exports_this_month');
            }
        });
        
        // Try to add index, skip if exists
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->index('registered_phase');
            });
        } catch (\Exception $e) {
            // Index might already exist, skip
        }
        
        // Set all existing users to Phase 1 (early adopters with benefits)
        DB::table('users')
            ->whereNull('registered_phase')
            ->orWhere('registered_phase', 0)
            ->update([
                'registered_phase' => 1,
                'grandfathered_benefits' => json_encode([
                    'cv_templates_3_free',
                    'premium_discount_50'
                ])
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['registered_phase']);
            $table->dropColumn([
                'registered_phase',
                'grandfathered_benefits',
                'cv_exports_this_month',
                'last_export_reset'
            ]);
        });
    }
};
