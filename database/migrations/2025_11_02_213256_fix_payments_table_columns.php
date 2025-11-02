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
        Schema::table('payments', function (Blueprint $table) {
            // Check and add columns if they don't exist
            if (!Schema::hasColumn('payments', 'payment_channel_code')) {
                $table->string('payment_channel_code')->nullable()->after('amount');
            }
            
            if (!Schema::hasColumn('payments', 'payment_channel_name')) {
                $table->string('payment_channel_name')->nullable()->after('payment_channel_code');
            }
            
            if (!Schema::hasColumn('payments', 'payment_category')) {
                $table->string('payment_category')->nullable()->after('payment_channel_name');
            }
            
            if (!Schema::hasColumn('payments', 'va_number')) {
                $table->string('va_number')->nullable()->after('payment_category');
            }
            
            if (!Schema::hasColumn('payments', 'va_account_id')) {
                $table->string('va_account_id')->nullable()->after('va_number');
            }
            
            if (!Schema::hasColumn('payments', 'va_expired_at')) {
                $table->timestamp('va_expired_at')->nullable()->after('va_account_id');
            }
            
            if (!Schema::hasColumn('payments', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('va_expired_at');
            }
            
            if (!Schema::hasColumn('payments', 'customer_email')) {
                $table->string('customer_email')->nullable()->after('customer_name');
            }
            
            if (!Schema::hasColumn('payments', 'customer_phone')) {
                $table->string('customer_phone')->nullable()->after('customer_email');
            }
            
            if (!Schema::hasColumn('payments', 'status')) {
                $table->enum('status', ['PENDING', 'WAITING', 'SUCCESS', 'FAILED', 'CANCELED', 'EXPIRED'])->default('PENDING')->after('customer_phone');
            }
            
            if (!Schema::hasColumn('payments', 'request_at')) {
                $table->timestamp('request_at')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('payments', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('request_at');
            }
            
            if (!Schema::hasColumn('payments', 'expired_at')) {
                $table->timestamp('expired_at')->nullable()->after('paid_at');
            }
            
            if (!Schema::hasColumn('payments', 'redirect_url')) {
                $table->text('redirect_url')->nullable()->after('expired_at');
            }
            
            if (!Schema::hasColumn('payments', 'callback_url')) {
                $table->text('callback_url')->nullable()->after('redirect_url');
            }
            
            if (!Schema::hasColumn('payments', 'notification_url')) {
                $table->text('notification_url')->nullable()->after('callback_url');
            }
            
            if (!Schema::hasColumn('payments', 'notes')) {
                $table->text('notes')->nullable()->after('notification_url');
            }
            
            if (!Schema::hasColumn('payments', 'metadata')) {
                $table->json('metadata')->nullable()->after('notes');
            }
            
            if (!Schema::hasColumn('payments', 'webhook_data')) {
                $table->json('webhook_data')->nullable()->after('metadata');
            }
            
            if (!Schema::hasColumn('payments', 'yukk_transaction_code')) {
                $table->string('yukk_transaction_code')->nullable()->unique()->after('order_id');
            }
            
            if (!Schema::hasColumn('payments', 'yukk_token')) {
                $table->string('yukk_token')->nullable()->after('yukk_transaction_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop columns in down() to avoid data loss
        // If needed, create a separate migration to drop columns
    }
};
