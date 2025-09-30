<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update general settings.
     */
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'app_timezone' => 'required|string',
            'app_currency' => 'required|string|max:3',
            'app_locale' => 'required|string|max:5',
        ]);

        $settings = [
            'app_name' => $request->app_name,
            'app_url' => $request->app_url,
            'app_timezone' => $request->app_timezone,
            'app_currency' => $request->app_currency,
            'app_locale' => $request->app_locale,
        ];

        foreach ($settings as $key => $value) {
            SettingsService::set($key, $value);
        }

        return back()->with('success', 'General settings updated successfully.');
    }

    /**
     * Update email settings.
     */
    public function updateEmail(Request $request)
    {
        $request->validate([
            'mail_driver' => 'required|string',
            'mail_host' => 'required|string',
            'mail_port' => 'required|integer',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);

        $settings = [
            'mail_driver' => $request->mail_driver,
            'mail_host' => $request->mail_host,
            'mail_port' => $request->mail_port,
            'mail_username' => $request->mail_username,
            'mail_password' => $request->mail_password,
            'mail_encryption' => $request->mail_encryption,
            'mail_from_address' => $request->mail_from_address,
            'mail_from_name' => $request->mail_from_name,
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return back()->with('success', 'Email settings updated successfully.');
    }

    /**
     * Update notification settings.
     */
    public function updateNotifications(Request $request)
    {
        $request->validate([
            'notify_expiring_subscriptions' => 'boolean',
            'notify_overdue_payments' => 'boolean',
            'notify_new_members' => 'boolean',
            'notify_low_attendance' => 'boolean',
            'notification_email' => 'required|email',
        ]);

        $settings = [
            'notify_expiring_subscriptions' => $request->boolean('notify_expiring_subscriptions'),
            'notify_overdue_payments' => $request->boolean('notify_overdue_payments'),
            'notify_new_members' => $request->boolean('notify_new_members'),
            'notify_low_attendance' => $request->boolean('notify_low_attendance'),
            'notification_email' => $request->notification_email,
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return back()->with('success', 'Notification settings updated successfully.');
    }

    /**
     * Update system settings.
     */
    public function updateSystem(Request $request)
    {
        $request->validate([
            'maintenance_mode' => 'boolean',
            'debug_mode' => 'boolean',
            'session_lifetime' => 'required|integer|min:1',
            'password_min_length' => 'required|integer|min:6',
            'max_login_attempts' => 'required|integer|min:1',
        ]);

        $settings = [
            'maintenance_mode' => $request->boolean('maintenance_mode'),
            'debug_mode' => $request->boolean('debug_mode'),
            'session_lifetime' => $request->session_lifetime,
            'password_min_length' => $request->password_min_length,
            'max_login_attempts' => $request->max_login_attempts,
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return back()->with('success', 'System settings updated successfully.');
    }

    /**
     * Update logo.
     */
    public function updateLogo(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            SettingsService::set('app_logo', $logoPath);
        }

        return back()->with('success', 'Logo updated successfully.');
    }


    /**
     * Clear cache.
     */
    public function clearCache()
    {
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('view:clear');
        \Artisan::call('route:clear');
        SettingsService::clearCache();

        return back()->with('success', 'Cache cleared successfully.');
    }

    /**
     * Backup database.
     */
    public function backupDatabase()
    {
        // Implementation for database backup
        return back()->with('success', 'Database backup functionality will be implemented.');
    }

    /**
     * Restore database.
     */
    public function restoreDatabase(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql',
        ]);

        // Implementation for database restore
        return back()->with('success', 'Database restore functionality will be implemented.');
    }
}
