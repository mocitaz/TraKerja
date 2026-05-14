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
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->default('pakasir');
            $table->string('transaction_id')->index(); // the pakasir transaction id, not unique since same transaction can have multiple events
            $table->string('reference_id')->index()->nullable(); // the TraKerja order_id
            $table->string('event_type')->nullable(); // e.g. payment_success
            $table->json('payload')->nullable();
            $table->string('status')->default('received'); // received, processed, skipped, failed
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // To prevent processing the same EXACT webhook twice (idempotency key),
            // Pakasir sometimes sends 'reference' and 'transaction_id' and 'status'
            // We can add an idempotency key if we need, but for now transaction_id + status check in controller is enough.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_logs');
    }
};
