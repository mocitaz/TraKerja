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
        Schema::create('user_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('project_name');
            $table->string('role')->nullable(); // Your role in the project
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_ongoing')->default(false);
            $table->text('description')->nullable();
            $table->string('project_url')->nullable(); // Link to live project/demo
            $table->string('repository_url')->nullable(); // GitHub/GitLab link
            $table->json('technologies')->nullable(); // Array of technologies used
            
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
        Schema::dropIfExists('user_projects');
    }
};
