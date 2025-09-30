<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Branch Management
            ['name' => 'view_branches', 'group' => 'branches', 'description' => 'View branch information'],
            ['name' => 'create_branches', 'group' => 'branches', 'description' => 'Create new branches'],
            ['name' => 'edit_branches', 'group' => 'branches', 'description' => 'Edit branch information'],
            ['name' => 'delete_branches', 'group' => 'branches', 'description' => 'Delete branches'],

            // Member Management
            ['name' => 'view_members', 'group' => 'members', 'description' => 'View member information'],
            ['name' => 'create_members', 'group' => 'members', 'description' => 'Create new members'],
            ['name' => 'edit_members', 'group' => 'members', 'description' => 'Edit member information'],
            ['name' => 'delete_members', 'group' => 'members', 'description' => 'Delete members'],
            ['name' => 'assign_packages', 'group' => 'members', 'description' => 'Assign packages to members'],

            // Trainer Management
            ['name' => 'view_trainers', 'group' => 'trainers', 'description' => 'View trainer information'],
            ['name' => 'create_trainers', 'group' => 'trainers', 'description' => 'Create new trainers'],
            ['name' => 'edit_trainers', 'group' => 'trainers', 'description' => 'Edit trainer information'],
            ['name' => 'delete_trainers', 'group' => 'trainers', 'description' => 'Delete trainers'],

            // Package Management
            ['name' => 'view_packages', 'group' => 'packages', 'description' => 'View package information'],
            ['name' => 'create_packages', 'group' => 'packages', 'description' => 'Create new packages'],
            ['name' => 'edit_packages', 'group' => 'packages', 'description' => 'Edit package information'],
            ['name' => 'delete_packages', 'group' => 'packages', 'description' => 'Delete packages'],

            // Subscription Management
            ['name' => 'view_subscriptions', 'group' => 'subscriptions', 'description' => 'View subscription information'],
            ['name' => 'create_subscriptions', 'group' => 'subscriptions', 'description' => 'Create new subscriptions'],
            ['name' => 'edit_subscriptions', 'group' => 'subscriptions', 'description' => 'Edit subscription information'],
            ['name' => 'delete_subscriptions', 'group' => 'subscriptions', 'description' => 'Delete subscriptions'],

            // Payment Management
            ['name' => 'view_payments', 'group' => 'payments', 'description' => 'View payment information'],
            ['name' => 'create_payments', 'group' => 'payments', 'description' => 'Create new payments'],
            ['name' => 'edit_payments', 'group' => 'payments', 'description' => 'Edit payment information'],
            ['name' => 'delete_payments', 'group' => 'payments', 'description' => 'Delete payments'],
            ['name' => 'mark_payments_paid', 'group' => 'payments', 'description' => 'Mark payments as paid'],
            ['name' => 'export_payments', 'group' => 'payments', 'description' => 'Export payment data'],

            // Attendance Management
            ['name' => 'view_attendance', 'group' => 'attendance', 'description' => 'View attendance records'],
            ['name' => 'create_attendance', 'group' => 'attendance', 'description' => 'Create attendance records'],
            ['name' => 'edit_attendance', 'group' => 'attendance', 'description' => 'Edit attendance records'],
            ['name' => 'delete_attendance', 'group' => 'attendance', 'description' => 'Delete attendance records'],
            ['name' => 'manual_attendance', 'group' => 'attendance', 'description' => 'Manual attendance entry'],
            ['name' => 'view_attendance_stats', 'group' => 'attendance', 'description' => 'View attendance statistics'],

            // Workout Routine Management
            ['name' => 'view_workout_routines', 'group' => 'workout_routines', 'description' => 'View workout routine information'],
            ['name' => 'create_workout_routines', 'group' => 'workout_routines', 'description' => 'Create new workout routines'],
            ['name' => 'edit_workout_routines', 'group' => 'workout_routines', 'description' => 'Edit workout routine information'],
            ['name' => 'delete_workout_routines', 'group' => 'workout_routines', 'description' => 'Delete workout routines'],

            // Notice Management
            ['name' => 'view_notices', 'group' => 'notices', 'description' => 'View notices'],
            ['name' => 'create_notices', 'group' => 'notices', 'description' => 'Create new notices'],
            ['name' => 'edit_notices', 'group' => 'notices', 'description' => 'Edit notice information'],
            ['name' => 'delete_notices', 'group' => 'notices', 'description' => 'Delete notices'],

            // Gallery Management
            ['name' => 'view_gallery', 'group' => 'gallery', 'description' => 'View gallery items'],
            ['name' => 'create_gallery', 'group' => 'gallery', 'description' => 'Create new gallery items'],
            ['name' => 'edit_gallery', 'group' => 'gallery', 'description' => 'Edit gallery items'],
            ['name' => 'delete_gallery', 'group' => 'gallery', 'description' => 'Delete gallery items'],

            // Inquiry Management
            ['name' => 'view_inquiries', 'group' => 'inquiries', 'description' => 'View inquiries'],
            ['name' => 'create_inquiries', 'group' => 'inquiries', 'description' => 'Create new inquiries'],
            ['name' => 'edit_inquiries', 'group' => 'inquiries', 'description' => 'Edit inquiry information'],
            ['name' => 'delete_inquiries', 'group' => 'inquiries', 'description' => 'Delete inquiries'],

            // Settings Management
            ['name' => 'view_settings', 'group' => 'settings', 'description' => 'View system settings'],
            ['name' => 'edit_settings', 'group' => 'settings', 'description' => 'Edit system settings'],
            ['name' => 'manage_logo', 'group' => 'settings', 'description' => 'Manage application logo'],
            ['name' => 'clear_cache', 'group' => 'settings', 'description' => 'Clear system cache'],
            ['name' => 'backup_database', 'group' => 'settings', 'description' => 'Backup database'],
            ['name' => 'restore_database', 'group' => 'settings', 'description' => 'Restore database'],

            // Role Management
            ['name' => 'view_roles', 'group' => 'roles', 'description' => 'View roles'],
            ['name' => 'create_roles', 'group' => 'roles', 'description' => 'Create new roles'],
            ['name' => 'edit_roles', 'group' => 'roles', 'description' => 'Edit role information'],
            ['name' => 'delete_roles', 'group' => 'roles', 'description' => 'Delete roles'],

            // Permission Management
            ['name' => 'view_permissions', 'group' => 'permissions', 'description' => 'View permissions'],
            ['name' => 'create_permissions', 'group' => 'permissions', 'description' => 'Create new permissions'],
            ['name' => 'edit_permissions', 'group' => 'permissions', 'description' => 'Edit permission information'],
            ['name' => 'delete_permissions', 'group' => 'permissions', 'description' => 'Delete permissions'],

            // User Management
            ['name' => 'view_users', 'group' => 'users', 'description' => 'View users'],
            ['name' => 'create_users', 'group' => 'users', 'description' => 'Create new users'],
            ['name' => 'edit_users', 'group' => 'users', 'description' => 'Edit user information'],
            ['name' => 'delete_users', 'group' => 'users', 'description' => 'Delete users'],
            ['name' => 'change_user_password', 'group' => 'users', 'description' => 'Change user passwords'],
            ['name' => 'toggle_user_status', 'group' => 'users', 'description' => 'Toggle user status'],

            // Reports Management
            ['name' => 'view_reports', 'group' => 'reports', 'description' => 'View reports'],
            ['name' => 'view_revenue_reports', 'group' => 'reports', 'description' => 'View revenue reports'],
            ['name' => 'view_member_growth_reports', 'group' => 'reports', 'description' => 'View member growth reports'],
            ['name' => 'view_attendance_reports', 'group' => 'reports', 'description' => 'View attendance reports'],
            ['name' => 'export_reports', 'group' => 'reports', 'description' => 'Export reports'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                [
                    'group' => $permission['group'],
                    'description' => $permission['description']
                ]
            );
        }
    }
}
