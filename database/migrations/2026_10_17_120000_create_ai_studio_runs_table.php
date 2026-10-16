<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ai_studio_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->uuid('request_key');
            $table->string('task', 30);
            $table->string('title');
            $table->string('status', 20)->default('queued');
            $table->string('credit_state', 20)->default('none');
            $table->longText('payload');
            $table->string('source_path')->nullable();
            $table->string('source_name')->nullable();
            $table->unsignedBigInteger('result_id')->nullable();
            $table->string('model')->nullable();
            $table->string('error_code', 40)->nullable();
            $table->string('error_message')->nullable();
            $table->uuid('processing_token')->nullable();
            $table->timestamp('processing_expires_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'request_key']);
            $table->index(['status', 'created_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('ai_studio_runs'); }
};
