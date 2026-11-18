<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // The admin's conversation with Nara Admin (private to that admin).
        Schema::create('admin_nara_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role', 10);
            $table->longText('content');
            $table->string('page', 60)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['user_id', 'id']);
        });

        // Changes Nara proposes; nothing runs until the admin approves it.
        Schema::create('admin_nara_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type', 40);
            $table->json('params');
            $table->string('summary', 300);
            $table->string('status', 12)->default('pending'); // pending, executed, rejected, expired, failed
            $table->json('result')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'status']);
        });

        // Every tool Nara ran for an admin: who asked what data, when.
        Schema::create('admin_nara_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('tool', 60);
            $table->json('args')->nullable();
            $table->boolean('ok')->default(true);
            $table->unsignedInteger('rows')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_nara_audits');
        Schema::dropIfExists('admin_nara_actions');
        Schema::dropIfExists('admin_nara_messages');
    }
};
