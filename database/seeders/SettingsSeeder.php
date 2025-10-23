<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // System Settings
            [
                'key' => 'monetization_phase',
                'value' => '1',
                'type' => 'integer',
                'category' => 'system',
                'description' => 'Current monetization phase: 1=Free All, 2=Soft Premium, 3=Full Premium'
            ],
            
            // Pricing Settings
            [
                'key' => 'premium_price',
                'value' => '199000',
                'type' => 'integer',
                'category' => 'pricing',
                'description' => 'Premium package price (one-time payment in IDR)'
            ],
            [
                'key' => 'premium_discount_active',
                'value' => 'false',
                'type' => 'boolean',
                'category' => 'pricing',
                'description' => 'Is premium discount currently active?'
            ],
            [
                'key' => 'premium_discount_percent',
                'value' => '0',
                'type' => 'integer',
                'category' => 'pricing',
                'description' => 'Premium discount percentage (0-100)'
            ],
            [
                'key' => 'premium_discount_code',
                'value' => '',
                'type' => 'string',
                'category' => 'pricing',
                'description' => 'Premium discount promo code'
            ],
            
            // Feature Access Configuration (JSON)
            [
                'key' => 'feature_access',
                'value' => json_encode([
                    'phase_1' => [
                        // Phase 1: Everything FREE
                        'all_features' => 'free',
                        'cv_templates' => 5,
                        'cv_customization' => 'free',
                        'cv_watermark' => false,
                        'cv_exports' => 'unlimited',
                        'saved_configs' => 'unlimited',
                        'advanced_analytics' => 'free',
                        'interview_reminders' => 'free',
                        'calendar_export' => 'free'
                    ],
                    'phase_2' => [
                        // Phase 2: Core FREE, Advanced PREMIUM
                        'cv_templates_free' => 1,
                        'cv_templates_premium' => 5,
                        'cv_customization' => 'premium',
                        'cv_watermark_free' => true,
                        'cv_watermark_premium' => false,
                        'cv_exports_free' => 'unlimited',
                        'cv_exports_premium' => 'unlimited',
                        'saved_configs_free' => 1,
                        'saved_configs_premium' => 'unlimited',
                        'advanced_analytics' => 'free',
                        'interview_reminders' => 'free',
                        'calendar_export' => 'free'
                    ],
                    'phase_3' => [
                        // Phase 3: FREE with limits, PREMIUM unlimited
                        'cv_templates_free' => 1,
                        'cv_templates_premium' => 5,
                        'cv_customization' => 'premium',
                        'cv_watermark_free' => true,
                        'cv_watermark_premium' => false,
                        'cv_exports_free' => 3,
                        'cv_exports_premium' => 'unlimited',
                        'saved_configs_free' => 1,
                        'saved_configs_premium' => 'unlimited',
                        'advanced_analytics' => 'premium',
                        'interview_reminders' => 'premium',
                        'calendar_export' => 'premium'
                    ]
                ]),
                'type' => 'json',
                'category' => 'features',
                'description' => 'Feature access control configuration per monetization phase'
            ],
            
            // Free Tier Limits
            [
                'key' => 'free_cv_exports_per_month',
                'value' => '0',
                'type' => 'integer',
                'category' => 'limits',
                'description' => 'Free tier CV exports per month (0 = unlimited, used in phase 3)'
            ],
            [
                'key' => 'free_cv_templates_count',
                'value' => '1',
                'type' => 'integer',
                'category' => 'limits',
                'description' => 'Number of CV templates for free users (used in phase 2 & 3)'
            ],
            [
                'key' => 'free_saved_cv_configs',
                'value' => '1',
                'type' => 'integer',
                'category' => 'limits',
                'description' => 'Max saved CV configurations for free users (used in phase 2 & 3)'
            ],
            
            // General Settings
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'type' => 'boolean',
                'category' => 'system',
                'description' => 'Is application in maintenance mode?'
            ],
            [
                'key' => 'registration_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'category' => 'system',
                'description' => 'Is user registration currently enabled?'
            ]
        ];
        
        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
        
        $this->command->info('✅ Settings seeded successfully!');
        $this->command->info('📊 Current Phase: ' . Setting::get('monetization_phase'));
        $this->command->info('💰 Premium Price: Rp ' . number_format(Setting::get('premium_price'), 0, ',', '.'));
    }
}
