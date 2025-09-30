<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class SamplePackagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Basic Membership',
                'description' => 'Perfect for beginners starting their fitness journey. Access to all basic equipment and facilities.',
                'price' => 29.99,
                'duration_type' => 'months',
                'duration_value' => 1,
                'max_visits' => null, // unlimited
                'includes_trainer' => false,
                'includes_locker' => false,
                'includes_towel' => false,
                'features' => [
                    'Access to cardio equipment',
                    'Access to weight training area',
                    'Locker room access',
                    'Free WiFi',
                    'Group fitness classes'
                ],
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Premium Membership',
                'description' => 'Most popular choice with all premium features including personal training sessions.',
                'price' => 59.99,
                'duration_type' => 'months',
                'duration_value' => 1,
                'max_visits' => null, // unlimited
                'includes_trainer' => true,
                'includes_locker' => true,
                'includes_towel' => true,
                'features' => [
                    'Everything in Basic',
                    'Personal training sessions (2/month)',
                    'Premium locker access',
                    'Towel service',
                    'Nutrition consultation',
                    'Priority booking for classes'
                ],
                'is_popular' => true,
                'is_active' => true,
            ],
            [
                'name' => 'VIP Membership',
                'description' => 'Ultimate experience with unlimited personal training and exclusive amenities.',
                'price' => 99.99,
                'duration_type' => 'months',
                'duration_value' => 1,
                'max_visits' => null, // unlimited
                'includes_trainer' => true,
                'includes_locker' => true,
                'includes_towel' => true,
                'features' => [
                    'Everything in Premium',
                    'Unlimited personal training',
                    'VIP lounge access',
                    'Spa and sauna access',
                    'Guest passes (2/month)',
                    '24/7 access',
                    'Concierge service'
                ],
                'is_popular' => false,
                'is_active' => true,
            ],
        ];

        foreach ($packages as $packageData) {
            Package::firstOrCreate(
                ['name' => $packageData['name']],
                $packageData
            );
        }
    }
}
