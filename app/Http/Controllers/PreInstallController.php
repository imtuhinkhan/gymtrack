<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PreInstallController extends Controller
{
    /**
     * Show the pre-installation page
     */
    public function index()
    {
        // Check if .env file exists
        if (file_exists(base_path('.env'))) {
            return redirect()->route('install.index');
        }
        
        return view('pre-install.index');
    }
    
    /**
     * Create initial .env file
     */
    public function createEnv(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
        ]);
        
        try {
            // Create minimal .env file
            $envContent = $this->generateEnvContent($request);
            
            // Write .env file
            File::put(base_path('.env'), $envContent);
            
            // Generate app key
            \Artisan::call('key:generate');
            
            // Clear config cache
            \Artisan::call('config:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'Environment file created successfully!',
                'redirect_url' => route('install.index')
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create environment file: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Generate .env file content
     */
    private function generateEnvContent($request)
    {
        return "APP_NAME=\"{$request->app_name}\"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL={$request->app_url}

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync

CACHE_STORE=file
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=\"hello@example.com\"
MAIL_FROM_NAME=\"{$request->app_name}\"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME=\"{$request->app_name}\"";
    }
}