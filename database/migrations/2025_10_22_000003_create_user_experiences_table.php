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
        Schema::create('user_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('company_name');
            $table->string('role'); // Job title/position
            $table->string('location')->nullable(); // Domisili perusahaan
            $table->date('start_date');
            $table->date('end_date')->nullable(); // Nullable untuk current job
            $table->boolean('is_current')->default(false); // Masih bekerja di sini
            $table->text('description')->nullable(); // Job description/responsibilities
            
            $table->integer('display_order')->default(0); // Untuk sorting CV
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['user_id', 'display_order']);
            $table->index(['user_id', 'start_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_experiences');
    }
};
