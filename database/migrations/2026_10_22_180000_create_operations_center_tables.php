<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operation_incidents', function (Blueprint $table) {
            $table->id();
            $table->string('title', 160);
            $table->text('description');
            $table->string('severity', 20)->index();
            $table->string('status', 20)->default('open')->index();
            $table->string('service', 30)->nullable()->index();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('affected_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
        Schema::create('operation_restrictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('scope', 20);
            $table->text('reason');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('incident_id')->nullable()->constrained('operation_incidents')->nullOnDelete();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->foreignId('released_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('release_reason')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'scope', 'released_at']);
        });
        Schema::create('operation_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_id')->nullable()->constrained('operation_incidents')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 40);
            $table->text('note');
            $table->json('changes')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['user_id', 'created_at']);
        });
        Schema::create('operation_service_checks', function (Blueprint $table) {
            $table->id();
            $table->string('service', 30)->index();
            $table->string('status', 20);
            $table->unsignedInteger('duration_ms')->nullable();
            $table->string('summary', 240);
            $table->foreignId('checked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operation_service_checks');
        Schema::dropIfExists('operation_events');
        Schema::dropIfExists('operation_restrictions');
        Schema::dropIfExists('operation_incidents');
    }
};
