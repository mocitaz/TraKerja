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
        Schema::create('user_educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('institution_name'); // Nama kampus/universitas
            $table->string('degree'); // S1, S2, D3, etc.
            $table->string('major'); // Jurusan
            $table->decimal('gpa', 3, 2)->nullable(); // IPK (0.00 - 4.00)
            $table->string('location')->nullable(); // Domisili kampus
            $table->date('start_date');
            $table->date('end_date')->nullable(); // Nullable untuk yang masih kuliah
            $table->boolean('is_current')->default(false); // Masih kuliah
            $table->text('description')->nullable(); // Achievement, activities, etc.
            
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
        Schema::dropIfExists('user_educations');
    }
};
