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
            // OTP fields for email verification
            $table->string('otp_code', 6)->nullable()->after('email_verified_at');
            $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
            
            // Email notification preferences (premium feature)
            $table->boolean('email_notifications_enabled')->default(true)->after('otp_expires_at');
            $table->boolean('notify_goal_reminders')->default(true)->after('email_notifications_enabled');
            $table->boolean('notify_interview_reminders')->default(true)->after('notify_goal_reminders');
            $table->boolean('notify_goal_achieved')->default(true)->after('notify_interview_reminders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'otp_code',
                'otp_expires_at',
                'email_notifications_enabled',
                'notify_goal_reminders',
                'notify_interview_reminders',
                'notify_goal_achieved',
            ]);
        });
    }
};
