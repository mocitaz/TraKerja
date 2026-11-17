<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * TraKerja 2.0, Fase 6: CV-to-job match (embeddings), job alerts, closed-job notices, feed API clients.
 */
return new class extends Migration
{
    public function up(): void
    {
        // One embedding per vacancy and per user profile, packed float32. text_hash says when to re-embed.
        Schema::create('vacancy_embeddings', function (Blueprint $table) {
            $table->foreignId('vacancy_id')->primary()->constrained('vacancies')->cascadeOnDelete();
            $table->string('model', 64);
            $table->char('text_hash', 64);
            $table->binary('vector');
            $table->timestamps();
        });
        Schema::create('user_embeddings', function (Blueprint $table) {
            $table->foreignId('user_id')->primary()->constrained()->cascadeOnDelete();
            $table->string('model', 64);
            $table->char('text_hash', 64);
            $table->binary('vector');
            $table->timestamps();
        });

        // Saved-search alerts reuse alert_channel / alert_frequency / last_alerted_at from 2026_11_17_120100.
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notify_job_alerts')->default(true)->after('notify_goal_achieved');
            $table->boolean('notify_job_closed')->default(true)->after('notify_job_alerts');
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        // Read-only feed clients (e.g. the asep Telegram bot). Only the key's hash is stored.
        Schema::create('feed_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->char('key_hash', 64)->unique();
            $table->string('key_prefix', 12);
            $table->unsignedInteger('per_minute')->default(60);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_used_at')->nullable();
            $table->unsignedBigInteger('requests')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feed_clients');
        Schema::dropIfExists('notifications');
        Schema::table('users', function (Blueprint $table) {
            foreach (['notify_job_alerts', 'notify_job_closed'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
        Schema::dropIfExists('user_embeddings');
        Schema::dropIfExists('vacancy_embeddings');
    }
};
