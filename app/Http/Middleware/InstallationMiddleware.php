<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class InstallationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the system is already installed
        if ($this->isInstalled()) {
            // If trying to access installation routes, redirect to home
            if ($request->is('install*')) {
                return redirect()->route('login')->with('info', 'System is already installed.');
            }
        } else {
            // If system is not installed and not accessing installation routes, redirect to installation
            if (!$request->is('install*')) {
                return redirect()->route('install.index');
            }
        }

        return $next($request);
    }

    /**
     * Check if the system is already installed
     */
    private function isInstalled(): bool
    {
        try {
            // Check if .env file exists
            if (!file_exists(base_path('.env'))) {
                return false;
            }
            
            // Check if database credentials are configured
            if (empty(env('DB_DATABASE')) || empty(env('DB_USERNAME'))) {
                return false;
            }
            
            // Check if installation flag exists
            $hasInstallationFlag = DB::table('settings')->where('key', 'installed')->exists();
            if (!$hasInstallationFlag) {
                return false;
            }
            
            // Check if admin user exists (mandatory for installation)
            $hasAdminUser = \App\Models\User::whereHas('roles', function($query) {
                $query->where('name', 'admin');
            })->exists();
            
            if (!$hasAdminUser) {
                \Log::warning('Installation flag exists but no admin user found - marking as not installed');
                return false;
            }
            
            return true;
        } catch (\Exception $e) {
            // If database connection fails, assume not installed
            return false;
        }
    }
}