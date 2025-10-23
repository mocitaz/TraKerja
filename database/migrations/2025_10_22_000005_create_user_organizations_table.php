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
        Schema::create('user_organizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('organization_name');
            $table->string('role'); // Position/jabatan
            $table->string('location')->nullable(); // Domisili organisasi
            $table->date('start_date');
            $table->date('end_date')->nullable(); // Nullable untuk yang masih aktif
            $table->boolean('is_current')->default(false); // Masih aktif
            $table->text('description')->nullable(); // Responsibilities and achievements
            
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
        Schema::dropIfExists('user_organizations');
    }
};
