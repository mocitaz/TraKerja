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
        // First, fix any existing invalid payment_status values
        DB::table('users')
            ->whereNotIn('payment_status', ['free', 'paid', 'expired'])
            ->orWhereNull('payment_status')
            ->update(['payment_status' => 'free']);

        // Modify the column to use ENUM with valid values
        DB::statement("ALTER TABLE users MODIFY COLUMN payment_status ENUM('free', 'paid', 'expired') NOT NULL DEFAULT 'free'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to string column
        DB::statement("ALTER TABLE users MODIFY COLUMN payment_status VARCHAR(255) NOT NULL DEFAULT 'free'");
    }
};
