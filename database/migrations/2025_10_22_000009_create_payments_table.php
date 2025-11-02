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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Order & Transaction Info
            $table->string('order_id')->unique(); // Our internal order ID
            $table->string('yukk_transaction_code')->nullable()->unique(); // YUKK transaction code
            $table->string('yukk_token')->nullable(); // Token for YUKK session
            
            // Payment Details
            $table->integer('amount'); // Amount in IDR
            $table->string('payment_channel_code')->nullable(); // VA_BCA, QRIS, OVO, etc
            $table->string('payment_channel_name')->nullable(); // Virtual Account BCA, QRIS, etc
            $table->string('payment_category')->nullable(); // BANK_TRANSFER, E_WALLET, QRIS, etc
            
            // VA Specific (if using Virtual Account)
            $table->string('va_number')->nullable(); // VA number for customer to pay
            $table->string('va_account_id')->nullable(); // Last 8 digit for fixed VA
            $table->timestamp('va_expired_at')->nullable(); // VA expiry time
            
            // Customer Info
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            
            // Status & Timestamps
            $table->enum('status', ['PENDING', 'WAITING', 'SUCCESS', 'FAILED', 'CANCELED', 'EXPIRED'])->default('PENDING');
            $table->timestamp('request_at')->nullable(); // When payment was requested
            $table->timestamp('paid_at')->nullable(); // When payment was completed
            $table->timestamp('expired_at')->nullable(); // Session expiry time
            
            // URLs
            $table->text('redirect_url')->nullable(); // URL to YUKK payment page
            $table->text('callback_url')->nullable(); // URL to return after payment
            $table->text('notification_url')->nullable(); // Webhook URL
            
            // Additional Info
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable(); // Store additional data as JSON
            $table->json('webhook_data')->nullable(); // Store webhook payload
            
            $table->timestamps();
            $table->softDeletes(); // Soft delete for record keeping
            
            // Indexes
            $table->index('user_id');
            $table->index('order_id');
            $table->index('yukk_transaction_code');
            $table->index('status');
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
