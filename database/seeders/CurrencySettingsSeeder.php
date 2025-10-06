<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class CurrencySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default currency settings
        $defaultSettings = [
            [
                'key' => 'currency_code',
                'value' => 'USD',
                'type' => 'string',
                'description' => 'Default currency code for the application'
            ],
            [
                'key' => 'currency_symbol',
                'value' => '$',
                'type' => 'string',
                'description' => 'Default currency symbol for the application'
            ],
            [
                'key' => 'currency_position',
                'value' => 'before',
                'type' => 'string',
                'description' => 'Position of currency symbol relative to amount (before or after)'
            ]
        ];

        foreach ($defaultSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}