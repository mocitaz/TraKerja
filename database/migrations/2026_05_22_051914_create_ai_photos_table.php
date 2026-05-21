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
        Schema::create('ai_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // remove_bg, enhance
            $table->string('style_used')->nullable();
            $table->string('background_used')->nullable();
            $table->string('mode')->nullable(); // portrait, headshot
            $table->string('original_photo_name')->nullable();
            $table->string('result_url')->nullable();
            $table->json('photo_analysis')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_photos');
    }
};
