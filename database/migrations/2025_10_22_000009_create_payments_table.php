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
            
            // Payment Details
            $table->string('transaction_id')->unique(); // External payment ID from gateway
            $table->string('order_id')->unique(); // Internal order ID
            $table->decimal('amount', 10, 2); // Harga pembelian
            $table->string('currency', 3)->default('IDR');
            
            // Payment Gateway Info
            $table->string('payment_gateway'); // midtrans, xendit, paypal, stripe, etc.
            $table->string('payment_method')->nullable(); // credit_card, bank_transfer, e-wallet, etc.
            $table->string('payment_channel')->nullable(); // Specific channel (BCA, Gopay, etc.)
            
            // Status
            $table->string('status')->default('pending'); // pending, success, failed, expired, cancelled
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            
            // Additional Data
            $table->json('payment_details')->nullable(); // Raw response from payment gateway
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['user_id', 'status']);
            $table->index('transaction_id');
            $table->index('order_id');
            $table->index('status');
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
