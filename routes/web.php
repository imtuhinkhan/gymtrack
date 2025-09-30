<?php

use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Branch\DashboardController as BranchDashboardController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\Trainer\DashboardController as TrainerDashboardController;
use App\Http\Controllers\Receptionist\DashboardController as ReceptionistDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\PreInstallController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Pre-installation routes (no middleware)
Route::get('/', [PreInstallController::class, 'index'])->name('pre-install.index');
Route::post('/pre-install/create-env', [PreInstallController::class, 'createEnv'])->name('pre-install.create-env');

// Installation routes (no middleware - accessible during installation)
Route::prefix('install')->name('install.')->group(function () {
    Route::get('/', [InstallController::class, 'index'])->name('index');
    Route::get('/check-requirements', [InstallController::class, 'checkRequirements'])->name('check-requirements');
    Route::post('/check-database', [InstallController::class, 'checkDatabase'])->name('check-database');
    Route::post('/install', [InstallController::class, 'install'])->name('install');
});

// Apply installation middleware to all other routes
Route::middleware('installation')->group(function () {

    // Authentication routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login']);
    });

    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    // Protected routes
    Route::middleware('auth')->group(function () {
        // Dashboard routes based on role
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Admin routes
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
            
            // Branch management
            Route::resource('branches', BranchController::class)->middleware('permission:view_branches');
            Route::post('branches/{branch}/toggle-status', [BranchController::class, 'toggleStatus'])->name('branches.toggle-status')->middleware('permission:edit_branches');
                    
            // Member management (renamed from customers)
            Route::resource('members', \App\Http\Controllers\Admin\MemberController::class)->middleware('permission:view_members');
            Route::post('members/{member}/toggle-status', [\App\Http\Controllers\Admin\MemberController::class, 'toggleStatus'])->name('members.toggle-status')->middleware('permission:edit_members');
            Route::post('members/{member}/assign-package', [\App\Http\Controllers\Admin\MemberController::class, 'assignPackage'])->name('members.assign-package')->middleware('permission:assign_packages');
                    
            // Trainer management
            Route::resource('trainers', \App\Http\Controllers\Admin\TrainerController::class)->middleware('permission:view_trainers');
            Route::post('trainers/{trainer}/toggle-status', [\App\Http\Controllers\Admin\TrainerController::class, 'toggleStatus'])->name('trainers.toggle-status')->middleware('permission:edit_trainers');
                    
            // Package management
            Route::resource('packages', \App\Http\Controllers\Admin\PackageController::class)->middleware('permission:view_packages');
            Route::post('packages/{package}/toggle-status', [\App\Http\Controllers\Admin\PackageController::class, 'toggleStatus'])->name('packages.toggle-status')->middleware('permission:edit_packages');
            Route::post('packages/{package}/duplicate', [\App\Http\Controllers\Admin\PackageController::class, 'duplicate'])->name('packages.duplicate')->middleware('permission:create_packages');
                    
            // Payment management
            Route::resource('payments', \App\Http\Controllers\Admin\PaymentController::class)->middleware('permission:view_payments');
            Route::post('payments/{payment}/mark-paid', [\App\Http\Controllers\Admin\PaymentController::class, 'markPaid'])->name('payments.mark-paid')->middleware('permission:mark_payments_paid');
            Route::get('payments/export', [\App\Http\Controllers\Admin\PaymentController::class, 'export'])->name('payments.export')->middleware('permission:export_payments');
                    
            // Attendance management
            Route::get('attendance/manual', [\App\Http\Controllers\Admin\AttendanceController::class, 'manualEntry'])->name('attendance.manual')->middleware('permission:manual_attendance');
            Route::post('attendance/manual', [\App\Http\Controllers\Admin\AttendanceController::class, 'storeManual'])->name('attendance.store-manual')->middleware('permission:manual_attendance');
            Route::get('attendance/statistics', [\App\Http\Controllers\Admin\AttendanceController::class, 'statistics'])->name('attendance.statistics')->middleware('permission:view_attendance_stats');
            Route::post('attendance/bulk', [\App\Http\Controllers\Admin\AttendanceController::class, 'markBulk'])->name('attendance.bulk')->middleware('permission:create_attendance');
            Route::resource('attendance', \App\Http\Controllers\Admin\AttendanceController::class)->middleware('permission:view_attendance');
                    
            // Workout routine management
            Route::resource('workout-routines', \App\Http\Controllers\Admin\WorkoutRoutineController::class)->middleware('permission:view_workout_routines');
            Route::post('workout-routines/{workoutRoutine}/duplicate', [\App\Http\Controllers\Admin\WorkoutRoutineController::class, 'duplicate'])->name('workout-routines.duplicate')->middleware('permission:create_workout_routines');
            Route::post('workout-routines/{workoutRoutine}/toggle-status', [\App\Http\Controllers\Admin\WorkoutRoutineController::class, 'toggleStatus'])->name('workout-routines.toggle-status')->middleware('permission:edit_workout_routines');
                    
            // Reports
            Route::prefix('reports')->name('reports.')->middleware('permission:view_reports')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\ReportsController::class, 'index'])->name('index');
                Route::get('/revenue', [\App\Http\Controllers\Admin\ReportsController::class, 'revenue'])->name('revenue');
                Route::get('/member-growth', [\App\Http\Controllers\Admin\ReportsController::class, 'memberGrowth'])->name('member-growth');
                Route::get('/attendance', [\App\Http\Controllers\Admin\ReportsController::class, 'attendance'])->name('attendance');
                Route::post('/export', [\App\Http\Controllers\Admin\ReportsController::class, 'export'])->name('export')->middleware('permission:export_reports');
            });
                    
            // Role Management
            Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)->middleware([
                'index' => 'permission:view_roles',
                'create' => 'permission:create_roles',
                'store' => 'permission:create_roles',
                'show' => 'permission:view_roles',
                'edit' => 'permission:edit_roles',
                'update' => 'permission:edit_roles',
                'destroy' => 'permission:delete_roles'
            ]);

            // Permission Management
            Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class)->middleware('permission:view_permissions');

            // User Management
            Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->middleware('permission:view_users');
            Route::get('users/{user}/change-password', [\App\Http\Controllers\Admin\UserController::class, 'changePassword'])->name('users.change-password')->middleware('permission:change_user_password');
            Route::put('users/{user}/change-password', [\App\Http\Controllers\Admin\UserController::class, 'updatePassword'])->name('users.update-password')->middleware('permission:change_user_password');
            Route::post('users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status')->middleware('permission:toggle_user_status');

            // Settings
            Route::prefix('settings')->name('settings.')->middleware('permission:view_settings')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('index');
                Route::post('/general', [\App\Http\Controllers\Admin\SettingsController::class, 'updateGeneral'])->name('general')->middleware('permission:edit_settings');
                Route::post('/email', [\App\Http\Controllers\Admin\SettingsController::class, 'updateEmail'])->name('email')->middleware('permission:edit_settings');
                Route::post('/system', [\App\Http\Controllers\Admin\SettingsController::class, 'updateSystem'])->name('system')->middleware('permission:edit_settings');
                Route::post('/logo', [\App\Http\Controllers\Admin\SettingsController::class, 'updateLogo'])->name('logo')->middleware('permission:manage_logo');
                Route::post('/clear-cache', [\App\Http\Controllers\Admin\SettingsController::class, 'clearCache'])->name('clear-cache')->middleware('permission:clear_cache');
            });

            // PWA Settings
            Route::prefix('pwa-settings')->name('pwa-settings.')->middleware('permission:view_settings')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\PwaSettingsController::class, 'index'])->name('index');
                Route::put('/', [\App\Http\Controllers\Admin\PwaSettingsController::class, 'update'])->name('update')->middleware('permission:edit_settings');
                Route::get('/manifest', [\App\Http\Controllers\Admin\PwaSettingsController::class, 'manifest'])->name('manifest');
            });

            // Database Backup & Restore
            Route::prefix('backup')->name('backup.')->middleware('permission:backup_database')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\BackupController::class, 'index'])->name('index');
                Route::post('/create', [\App\Http\Controllers\Admin\BackupController::class, 'create'])->name('create');
                Route::post('/restore', [\App\Http\Controllers\Admin\BackupController::class, 'restore'])->name('restore');
                Route::get('/download/{filename}', [\App\Http\Controllers\Admin\BackupController::class, 'download'])->name('download');
                Route::delete('/delete/{filename}', [\App\Http\Controllers\Admin\BackupController::class, 'delete'])->name('delete');
            });
        });

        // Profile routes (available to all authenticated users)
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('index');
            Route::put('/', [ProfileController::class, 'update'])->name('update');
            Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
        });

        // Branch Manager routes
        Route::middleware('role:branch_manager')->prefix('branch')->name('branch.')->group(function () {
            Route::get('/dashboard', [BranchDashboardController::class, 'index'])->name('dashboard');
        });

        // Trainer routes
        Route::middleware('role:trainer')->prefix('trainer')->name('trainer.')->group(function () {
            Route::get('/dashboard', [TrainerDashboardController::class, 'index'])->name('dashboard');
            
            // Trainer's members (only their assigned members)
            Route::get('/members', [\App\Http\Controllers\Trainer\MemberController::class, 'index'])->name('members.index');
            Route::get('/members/{member}', [\App\Http\Controllers\Trainer\MemberController::class, 'show'])->name('members.show');
            
            // Trainer's workout routines (only for their members)
            Route::resource('workout-routines', \App\Http\Controllers\Trainer\WorkoutRoutineController::class);
            
            // Trainer's attendance (only for their members)
            Route::get('/attendance', [\App\Http\Controllers\Trainer\AttendanceController::class, 'index'])->name('attendance.index');
            Route::post('/attendance/manual', [\App\Http\Controllers\Trainer\AttendanceController::class, 'storeManual'])->name('attendance.store-manual');
            
            // Trainer's reports (only for their members)
            Route::get('/reports', [\App\Http\Controllers\Trainer\ReportsController::class, 'index'])->name('reports.index');
        });

        // Receptionist routes
        Route::middleware('role:receptionist')->prefix('receptionist')->name('receptionist.')->group(function () {
            Route::get('/dashboard', [ReceptionistDashboardController::class, 'index'])->name('dashboard');
        });

        // Customer routes
        Route::middleware('role:customer')->prefix('customer')->name('customer.')->group(function () {
            Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
            Route::get('/workouts', [CustomerDashboardController::class, 'workouts'])->name('workouts');
            Route::get('/workouts/{routine}', [CustomerDashboardController::class, 'workoutDetails'])->name('workout-details');
            Route::get('/attendance', [CustomerDashboardController::class, 'attendance'])->name('attendance');
            Route::get('/membership', [CustomerDashboardController::class, 'membership'])->name('membership');
            Route::post('/clock-in', [CustomerDashboardController::class, 'clockIn'])->name('clock-in');
            Route::post('/clock-out', [CustomerDashboardController::class, 'clockOut'])->name('clock-out');
            Route::get('/payments', [CustomerDashboardController::class, 'payments'])->name('payments');
            Route::get('/payments/{payment}/invoice', [CustomerDashboardController::class, 'downloadInvoice'])->name('payments.invoice');
        });
    });

    // PWA Routes (outside auth middleware)
    Route::get('/manifest.json', [\App\Http\Controllers\Admin\PwaSettingsController::class, 'manifest'])->name('pwa.manifest');
});