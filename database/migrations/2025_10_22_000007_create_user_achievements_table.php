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
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('title');
            $table->string('issuer')->nullable(); // Organization/company yang memberi
            $table->date('date')->nullable();
            $table->text('description')->nullable();
            $table->string('certificate_url')->nullable(); // Link ke sertifikat
            
            $table->integer('display_order')->default(0); // Untuk sorting CV
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['user_id', 'display_order']);
            $table->index(['user_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_achievements');
    }
};
