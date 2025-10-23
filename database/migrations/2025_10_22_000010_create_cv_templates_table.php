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
        Schema::create('cv_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('name'); // Template name (e.g., "Professional Blue", "Modern Minimal")
            $table->string('template_key')->nullable(); // Reference to template file/component
            
            // Customization Settings (JSON)
            $table->json('settings')->nullable(); // Colors, fonts, layout preferences
            
            // Content Selection
            $table->json('sections')->nullable(); // Which sections to include in CV
            
            // Premium Feature
            $table->boolean('is_premium_template')->default(false);
            
            $table->boolean('is_default')->default(false); // User's default template
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['user_id', 'is_default']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_templates');
    }
};
