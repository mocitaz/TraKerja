<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Structured interview practice with Nara: the session lives in the database, not only in the model's memory. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nara_interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vacancy_id')->nullable()->constrained()->nullOnDelete();
            $table->string('role', 120);
            $table->string('company', 120)->nullable();
            $table->unsignedTinyInteger('total')->default(5);
            $table->json('plan')->nullable();      // question types, in order
            $table->json('answers')->nullable();   // [{n, type, question, score, strength, improvement, excerpt}]
            $table->string('status', 10)->default('active'); // active, done
            $table->timestamps();
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nara_interviews');
    }
};
