<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Nara conversations survive logout and devices (they used to live in the session), and each reply can be rated
 * so the quality of Nara can be measured.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nara_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role', 10); // user, model
            $table->mediumText('content');
            $table->string('mode', 12)->nullable(); // career, interview, writing
            $table->boolean('has_jobs')->default(false); // the reply showed job cards
            $table->tinyInteger('rating')->nullable(); // 1 helpful, -1 not helpful
            $table->timestamp('rated_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'id']);
            $table->index(['rated_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nara_messages');
    }
};
