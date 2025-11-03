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
            // Track verification reminder emails
            $table->timestamp('last_verification_reminder_sent_at')->nullable()->after('email_verified_at');
            $table->integer('verification_reminder_count')->default(0)->after('last_verification_reminder_sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'last_verification_reminder_sent_at',
                'verification_reminder_count',
            ]);
        });
    }
};
