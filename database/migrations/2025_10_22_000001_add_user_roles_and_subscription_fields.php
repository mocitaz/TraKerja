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
            // Role management for admin dashboard
            $table->string('role')->default('user')->after('email'); // user, admin
            
            // Subscription & Premium features
            $table->boolean('is_premium')->default(false)->after('role');
            $table->timestamp('premium_purchased_at')->nullable()->after('is_premium');
            $table->string('payment_status')->default('free')->after('premium_purchased_at'); // free, paid, expired
            
            // Index untuk query performa
            $table->index('role');
            $table->index('is_premium');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['is_premium']);
            $table->dropColumn(['role', 'is_premium', 'premium_purchased_at', 'payment_status']);
        });
    }
};
