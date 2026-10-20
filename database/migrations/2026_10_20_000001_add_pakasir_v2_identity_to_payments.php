<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('pakasir_txn_id', 100)->nullable()->unique();
            $table->boolean('pakasir_is_sandbox')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropUnique(['pakasir_txn_id']);
            $table->dropColumn(['pakasir_txn_id', 'pakasir_is_sandbox']);
        });
    }
};
