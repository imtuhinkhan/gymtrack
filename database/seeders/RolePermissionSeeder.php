<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Branch;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // First, run the PermissionSeeder to create all permissions
        $this->call(PermissionSeeder::class);

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $branchManagerRole = Role::firstOrCreate(['name' => 'branch_manager']);
        $trainerRole = Role::firstOrCreate(['name' => 'trainer']);
        $receptionistRole = Role::firstOrCreate(['name' => 'receptionist']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // Assign permissions to Admin role (all permissions)
        $adminRole->givePermissionTo(Permission::all());

        // Assign permissions to Branch Manager role
        $branchManagerPermissions = [
            'view_branches', 'edit_branches',
            'view_members', 'create_members', 'edit_members', 'assign_packages',
            'view_trainers', 'create_trainers', 'edit_trainers',
            'view_packages', 'create_packages', 'edit_packages',
            'view_subscriptions', 'create_subscriptions', 'edit_subscriptions',
            'view_payments', 'create_payments', 'edit_payments', 'mark_payments_paid',
            'view_attendance', 'create_attendance', 'edit_attendance', 'manual_attendance', 'view_attendance_stats',
            'view_workout_routines', 'create_workout_routines', 'edit_workout_routines',
            'view_notices', 'create_notices', 'edit_notices',
            'view_gallery', 'create_gallery', 'edit_gallery',
            'view_inquiries', 'create_inquiries', 'edit_inquiries',
            'view_reports', 'view_revenue_reports', 'view_member_growth_reports', 'view_attendance_reports'
        ];
        $branchManagerRole->givePermissionTo($branchManagerPermissions);

        // Assign permissions to Trainer role
        $trainerPermissions = [
            'view_members', 'edit_members',
            'view_packages',
            'view_subscriptions',
            'view_payments',
            'view_attendance', 'create_attendance', 'edit_attendance',
            'view_workout_routines', 'create_workout_routines', 'edit_workout_routines',
            'view_notices',
            'view_gallery',
            'view_inquiries', 'create_inquiries',
            'view_reports', 'view_attendance_reports'
        ];
        $trainerRole->givePermissionTo($trainerPermissions);

        // Assign permissions to Receptionist role
        $receptionistPermissions = [
            'view_members', 'create_members', 'edit_members',
            'view_packages',
            'view_subscriptions', 'create_subscriptions', 'edit_subscriptions',
            'view_payments', 'create_payments', 'edit_payments', 'mark_payments_paid',
            'view_attendance', 'create_attendance', 'edit_attendance', 'manual_attendance',
            'view_notices',
            'view_gallery',
            'view_inquiries', 'create_inquiries', 'edit_inquiries'
        ];
        $receptionistRole->givePermissionTo($receptionistPermissions);

        // Assign permissions to Customer role
        $customerPermissions = [
            'view_packages',
            'view_subscriptions',
            'view_payments',
            'view_attendance',
            'view_workout_routines',
            'view_notices',
            'view_gallery',
            'create_inquiries'
        ];
        $customerRole->givePermissionTo($customerPermissions);

        // Create demo users
        // $this->createDemoUsers();
        
        // Create default settings
        $this->createDefaultSettings();
    }

    private function createDemoUsers()
    {
        // Create a sample branch
        $branch = Branch::firstOrCreate(
            ['email' => 'main@example.com'],
            [
                'name' => 'Main Branch',
                'address' => '123 Main St',
                'city' => 'New York',
                'state' => 'NY',
                'postal_code' => '10001',
                'country' => 'USA',
                'phone' => '123-456-7890',
                'opening_time' => '06:00',
                'closing_time' => '22:00',
                'working_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
                'is_active' => true,
            ]
        );

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );
        $admin->assignRole('admin');

        // Create branch manager user
        $branchManager = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Branch Manager',
                'password' => bcrypt('password'),
                'branch_id' => $branch->id,
            ]
        );
        $branchManager->assignRole('branch_manager');

        // Create trainer user
        $trainer = User::firstOrCreate(
            ['email' => 'trainer@example.com'],
            [
                'name' => 'Trainer One',
                'password' => bcrypt('password'),
                'branch_id' => $branch->id,
            ]
        );
        $trainer->assignRole('trainer');

        // Create receptionist user
        $receptionist = User::firstOrCreate(
            ['email' => 'receptionist@example.com'],
            [
                'name' => 'Receptionist One',
                'password' => bcrypt('password'),
                'branch_id' => $branch->id,
            ]
        );
        $receptionist->assignRole('receptionist');

        // Create customer user
        $customer = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer One',
                'password' => bcrypt('password'),
                'branch_id' => $branch->id,
            ]
        );
        $customer->assignRole('customer');
    }
    
    /**
     * Create default application settings.
     */
    private function createDefaultSettings()
    {
        $defaultSettings = [
            'app_name' => 'Gym Management System',
            'app_url' => config('app.url'),
            'app_timezone' => 'UTC',
            'app_currency' => 'USD',
            'app_locale' => 'en',
        ];
        
        foreach ($defaultSettings as $key => $value) {
            \App\Models\Setting::firstOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
        
        $this->command->info('Default settings created successfully!');
    }
}