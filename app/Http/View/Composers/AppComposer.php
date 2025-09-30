<?php

namespace App\Http\View\Composers;

use App\Services\SettingsService;
use App\Models\PwaSetting;
use Illuminate\View\View;

class AppComposer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $pwaSettings = null;
        $appName = config('app.name', 'Gym Management System');
        $appLogo = null;
        $appLogoUrl = null;
        
        try {
            // Check if database is configured and accessible
            if (!empty(env('DB_DATABASE')) && !empty(env('DB_USERNAME'))) {
                $pwaSettings = PwaSetting::getCurrent();
            }
        } catch (\Exception $e) {
            // Database might not be ready yet during installation
            \Log::info('AppComposer: Database not ready, using defaults');
        }
        
        try {
            $appName = SettingsService::getAppName();
            $appLogo = SettingsService::getAppLogo();
            $appLogoUrl = SettingsService::getAppLogoUrl();
        } catch (\Exception $e) {
            // Settings service might not be ready during installation
            \Log::info('AppComposer: Settings service not ready, using defaults');
        }

        $view->with([
            'appName' => $appName,
            'appLogo' => $appLogo,
            'appLogoUrl' => $appLogoUrl,
            'pwaSettings' => $pwaSettings,
        ]);
    }
}
