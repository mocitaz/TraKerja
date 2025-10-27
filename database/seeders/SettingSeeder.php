<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'monetization_enabled',
                'value' => 'false',
                'type' => 'boolean',
                'category' => 'monetization',
                'description' => 'Enable monetization features'
            ],
            [
                'key' => 'premium_price',
                'value' => '199000',
                'type' => 'integer',
                'category' => 'monetization',
                'description' => 'Premium subscription price in IDR'
            ],
            [
                'key' => 'premium_discount_active',
                'value' => 'false',
                'type' => 'boolean',
                'category' => 'monetization',
                'description' => 'Enable premium discount'
            ],
            [
                'key' => 'premium_discount_percent',
                'value' => '0',
                'type' => 'integer',
                'category' => 'monetization',
                'description' => 'Premium discount percentage'
            ],
            [
                'key' => 'app_name',
                'value' => 'TraKerja',
                'type' => 'string',
                'category' => 'general',
                'description' => 'Application name'
            ],
            [
                'key' => 'app_version',
                'value' => '1.0.0',
                'type' => 'string',
                'category' => 'general',
                'description' => 'Application version'
            ]
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}