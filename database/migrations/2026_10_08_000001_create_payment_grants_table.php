<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_grants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->string('benefit');
            $table->unsignedInteger('quantity');
            $table->timestamp('granted_at');
            $table->unique(['payment_id', 'benefit']);
        });

        // paid_at also covers old settlements downgraded by the former webhook handler.
        DB::table('payments')->where(function ($query) {
            $query->where('status', 'SUCCESS')->orWhereNotNull('paid_at');
        })->orderBy('id')->chunkById(500, function ($payments) {
            $rows = $payments->map(fn ($payment) => [
                'payment_id' => $payment->id,
                'benefit' => 'legacy_settlement',
                'quantity' => 0,
                'granted_at' => $payment->paid_at ?? $payment->updated_at ?? now(),
            ])->all();
            DB::table('payment_grants')->insert($rows);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_grants');
    }
};
