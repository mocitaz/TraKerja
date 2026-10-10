<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ai_photos', function (Blueprint $table) {
            $table->uuid('request_key')->nullable();
            $table->string('status')->default('completed');
            $table->string('credit_state')->default('none');
            $table->string('source_path')->nullable();
            $table->json('request_payload')->nullable();
            $table->text('error_message')->nullable();
            $table->uuid('processing_token')->nullable();
            $table->timestamp('processing_expires_at')->nullable();
            $table->unique(['user_id', 'request_key']);
            $table->index(['status', 'processing_expires_at']);
        });
    }

    public function down(): void
    {
        Schema::table('ai_photos', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'request_key']);
            $table->dropIndex(['status', 'processing_expires_at']);
            $table->dropColumn(['request_key', 'status', 'credit_state', 'source_path', 'request_payload', 'error_message', 'processing_token', 'processing_expires_at']);
        });
    }
};
