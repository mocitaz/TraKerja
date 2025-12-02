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
            // Check if premium_purchased_at exists, if not add after payment_status or is_premium
            if (Schema::hasColumn('users', 'premium_purchased_at')) {
                $table->timestamp('premium_until')->nullable()->after('premium_purchased_at');
            } elseif (Schema::hasColumn('users', 'payment_status')) {
                $table->timestamp('premium_until')->nullable()->after('payment_status');
            } elseif (Schema::hasColumn('users', 'is_premium')) {
                $table->timestamp('premium_until')->nullable()->after('is_premium');
            } else {
                // Fallback: add at the end
                $table->timestamp('premium_until')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('premium_until');
        });
    }
};

