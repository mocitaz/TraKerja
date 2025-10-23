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
        Schema::create('user_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('skill_name');
            $table->string('category')->nullable(); // Technical, Soft Skills, Language, etc.
            $table->string('proficiency_level')->nullable(); // Beginner, Intermediate, Advanced, Expert
            $table->integer('years_of_experience')->nullable(); // Lama pengalaman dengan skill ini
            
            $table->integer('display_order')->default(0); // Untuk sorting CV
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['user_id', 'category']);
            $table->index(['user_id', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_skills');
    }
};
