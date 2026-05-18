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
        Schema::create('email_blast_logs', function (Blueprint $table) {
            $table->id();
            $table->string('email_type');
            $table->string('target_audience');
            $table->integer('total_target');
            $table->integer('success_count');
            $table->integer('failed_count');
            $table->json('failed_details')->nullable(); // JSON to store failed emails and errors
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_blast_logs');
    }
};
