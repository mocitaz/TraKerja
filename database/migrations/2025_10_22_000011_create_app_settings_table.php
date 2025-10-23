<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g., 'premium_price', 'feature_cv_templates'
            $table->string('group')->index(); // pricing, features, general
            $table->string('type'); // number, boolean, string, json
            $table->text('value'); // Stored as text, cast based on type
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // Extra config like min/max for numbers
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default settings
        DB::table('app_settings')->insert([
            // Pricing Settings
            [
                'key' => 'premium_price',
                'group' => 'pricing',
                'type' => 'number',
                'value' => '199000',
                'description' => 'Harga premium membership (satu kali bayar, lifetime access)',
                'metadata' => json_encode(['min' => 50000, 'max' => 999000, 'currency' => 'IDR']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'discount_percentage',
                'group' => 'pricing',
                'type' => 'number',
                'value' => '0',
                'description' => 'Diskon percentage untuk special promotion (0-100)',
                'metadata' => json_encode(['min' => 0, 'max' => 100]),
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'discount_code',
                'group' => 'pricing',
                'type' => 'string',
                'value' => '',
                'description' => 'Kode diskon untuk special promotion',
                'metadata' => null,
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Feature Flags - Profile Management (All FREE by default)
            [
                'key' => 'feature_profile_experiences',
                'group' => 'features',
                'type' => 'string',
                'value' => 'free',
                'description' => 'Akses fitur Work Experiences (free/premium/disabled)',
                'metadata' => json_encode(['options' => ['free', 'premium', 'disabled']]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'feature_profile_education',
                'group' => 'features',
                'type' => 'string',
                'value' => 'free',
                'description' => 'Akses fitur Education (free/premium/disabled)',
                'metadata' => json_encode(['options' => ['free', 'premium', 'disabled']]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'feature_profile_skills',
                'group' => 'features',
                'type' => 'string',
                'value' => 'free',
                'description' => 'Akses fitur Skills (free/premium/disabled)',
                'metadata' => json_encode(['options' => ['free', 'premium', 'disabled']]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'feature_profile_organizations',
                'group' => 'features',
                'type' => 'string',
                'value' => 'free',
                'description' => 'Akses fitur Organizations (free/premium/disabled)',
                'metadata' => json_encode(['options' => ['free', 'premium', 'disabled']]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'feature_profile_achievements',
                'group' => 'features',
                'type' => 'string',
                'value' => 'free',
                'description' => 'Akses fitur Achievements (free/premium/disabled)',
                'metadata' => json_encode(['options' => ['free', 'premium', 'disabled']]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'feature_profile_projects',
                'group' => 'features',
                'type' => 'string',
                'value' => 'free',
                'description' => 'Akses fitur Projects (free/premium/disabled)',
                'metadata' => json_encode(['options' => ['free', 'premium', 'disabled']]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Feature Flags - CV Generator
            [
                'key' => 'feature_cv_basic',
                'group' => 'features',
                'type' => 'string',
                'value' => 'free',
                'description' => 'Akses CV basic (Modern Blue template dengan watermark)',
                'metadata' => json_encode(['options' => ['free', 'premium', 'disabled']]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'feature_cv_premium_templates',
                'group' => 'features',
                'type' => 'string',
                'value' => 'premium',
                'description' => 'Akses CV premium templates (Professional, Minimal, Creative, Classic)',
                'metadata' => json_encode(['options' => ['free', 'premium', 'disabled']]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'feature_cv_customization',
                'group' => 'features',
                'type' => 'string',
                'value' => 'premium',
                'description' => 'Akses CV customization (colors, fonts, layout)',
                'metadata' => json_encode(['options' => ['free', 'premium', 'disabled']]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'feature_cv_no_watermark',
                'group' => 'features',
                'type' => 'string',
                'value' => 'premium',
                'description' => 'Export CV tanpa watermark',
                'metadata' => json_encode(['options' => ['free', 'premium', 'disabled']]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Limits for Free Users
            [
                'key' => 'limit_job_applications',
                'group' => 'limits',
                'type' => 'number',
                'value' => '0', // 0 = unlimited
                'description' => 'Maksimal job applications untuk free users (0 = unlimited)',
                'metadata' => json_encode(['min' => 0, 'max' => 1000]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'limit_experiences',
                'group' => 'limits',
                'type' => 'number',
                'value' => '0',
                'description' => 'Maksimal work experiences untuk free users (0 = unlimited)',
                'metadata' => json_encode(['min' => 0, 'max' => 50]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'limit_skills',
                'group' => 'limits',
                'type' => 'number',
                'value' => '0',
                'description' => 'Maksimal skills untuk free users (0 = unlimited)',
                'metadata' => json_encode(['min' => 0, 'max' => 100]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'limit_cv_exports_per_month',
                'group' => 'limits',
                'type' => 'number',
                'value' => '0',
                'description' => 'Maksimal CV exports per bulan untuk free users (0 = unlimited)',
                'metadata' => json_encode(['min' => 0, 'max' => 100]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // General Settings
            [
                'key' => 'maintenance_mode',
                'group' => 'general',
                'type' => 'boolean',
                'value' => '0',
                'description' => 'Enable maintenance mode',
                'metadata' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'registration_enabled',
                'group' => 'general',
                'type' => 'boolean',
                'value' => '1',
                'description' => 'Allow new user registrations',
                'metadata' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
