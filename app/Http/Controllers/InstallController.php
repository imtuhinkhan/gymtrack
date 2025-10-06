<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class InstallController extends Controller
{
    public function index()
    {
        // Check if already installed
        if ($this->isInstalled()) {
            return redirect()->route('login')->with('info', 'System is already installed.');
        }

        return view('install.index');
    }

    public function checkRequirements()
    {
        $requirements = [
            'PHP Version >= 8.2' => version_compare(PHP_VERSION, '8.2.0', '>='),
            'PDO Extension' => extension_loaded('pdo'),
            'PDO MySQL Extension' => extension_loaded('pdo_mysql'),
            'OpenSSL Extension' => extension_loaded('openssl'),
            'Tokenizer Extension' => extension_loaded('tokenizer'),
            'XML Extension' => extension_loaded('xml'),
            'Ctype Extension' => extension_loaded('ctype'),
            'JSON Extension' => extension_loaded('json'),
            'BCMath Extension' => extension_loaded('bcmath'),
            'Fileinfo Extension' => extension_loaded('fileinfo'),
            'GD Extension' => extension_loaded('gd'),
            'Mbstring Extension' => extension_loaded('mbstring'),
        ];

        $allPassed = collect($requirements)->every(function ($passed) {
            return $passed;
        });

        return response()->json([
            'requirements' => $requirements,
            'all_passed' => $allPassed
        ]);
    }

    public function checkDatabase(Request $request)
    {
        $request->validate([
            'db_host' => 'required|string',
            'db_port' => 'required|integer',
            'db_name' => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'nullable|string',
        ]);

        try {
            $connection = new \PDO(
                "mysql:host={$request->db_host};port={$request->db_port};dbname={$request->db_name}",
                $request->db_username,
                $request->db_password,
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );

            return response()->json([
                'success' => true,
                'message' => 'Database connection successful!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database connection failed: ' . $e->getMessage()
            ], 400);
        }
    }

    public function install(Request $request)
    {
        // Log the incoming request for debugging
        \Log::info('Installation request received', [
            'method' => $request->method(),
            'content_type' => $request->header('Content-Type'),
            'has_csrf_token' => $request->has('_token'),
            'data_keys' => array_keys($request->all())
        ]);

        try {
            $request->validate([
                'app_name' => 'required|string|max:255',
                'app_url' => 'required|url',
                'admin_name' => 'required|string|max:255|min:2',
                'admin_email' => 'required|email|max:255',
                'admin_password' => 'required|string|min:8',
                'admin_password_confirmation' => 'required|string|min:8',
                'db_host' => 'required|string',
                'db_port' => 'required|integer|min:1|max:65535',
                'db_name' => 'required|string|min:1',
                'db_username' => 'required|string|min:1',
                'db_password' => 'nullable|string',
            ], [
                'admin_name.required' => 'Admin name is required',
                'admin_name.min' => 'Admin name must be at least 2 characters',
                'admin_email.required' => 'Admin email is required',
                'admin_email.email' => 'Admin email must be a valid email address',
                'admin_password.required' => 'Admin password is required',
                'admin_password.min' => 'Admin password must be at least 8 characters',
                'admin_password_confirmation.required' => 'Password confirmation is required',
                'admin_password_confirmation.min' => 'Password confirmation must be at least 8 characters',
            ]);

            // Manual password confirmation validation
            if ($request->admin_password !== $request->admin_password_confirmation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password confirmation does not match',
                    'errors' => ['admin_password_confirmation' => ['Password confirmation does not match']]
                ], 422);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            // Update .env file
            $this->updateEnvFile($request);

            // Clear all caches
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            // Test database connection after .env update
            $this->testDatabaseConnection($request);

            // Ensure database configuration is properly set for migrations
            config([
                'database.connections.mysql.host' => $request->db_host,
                'database.connections.mysql.port' => $request->db_port,
                'database.connections.mysql.database' => $request->db_name,
                'database.connections.mysql.username' => $request->db_username,
                'database.connections.mysql.password' => $request->db_password,
            ]);
            
            // Purge and reconnect to ensure fresh connection
            DB::purge('mysql');
            
            // Verify the configuration is correct
            $currentDb = config('database.connections.mysql.database');
            if ($currentDb !== $request->db_name) {
                throw new \Exception("Database configuration mismatch. Expected: {$request->db_name}, Got: {$currentDb}");
            }

            // Run migrations with fresh database
            Artisan::call('migrate:fresh', ['--force' => true, '--seed' => false]);

            // Run seeders to create initial data
            Artisan::call('db:seed', ['--force' => true]);

            // Create admin user (mandatory step)
            $this->createAdminUser($request);

            // Verify admin user was created successfully
            $adminUser = User::where('email', $request->admin_email)->first();
            if (!$adminUser || !$adminUser->hasRole('admin')) {
                throw new \Exception('Failed to create admin user - installation cannot proceed without an admin account');
            }

            // Create installation flag
            $this->createInstallationFlag();

            // Create default PWA settings
            $this->createDefaultPwaSettings($request);

            // Clear caches again after installation (don't cache config to avoid env() issues)
            Artisan::call('route:cache');

            return response()->json([
                'success' => true,
                'message' => 'Installation completed successfully!',
                'redirect_url' => route('login')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Installation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function isInstalled()
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
            $hasAdminUser = User::whereHas('roles', function($query) {
                $query->where('name', 'admin');
            })->exists();
            
            if (!$hasAdminUser) {
                \Log::warning('Installation flag exists but no admin user found - marking as not installed');
                return false;
            }
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function updateEnvFile($request)
    {
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        $updates = [
            'APP_NAME' => '"' . $request->app_name . '"',
            'APP_URL' => $request->app_url,
            'DB_HOST' => $request->db_host,
            'DB_PORT' => $request->db_port,
            'DB_DATABASE' => $request->db_name,
            'DB_USERNAME' => $request->db_username,
            'DB_PASSWORD' => $request->db_password,
            'CACHE_STORE' => 'file',
            'SESSION_DRIVER' => 'file',
            'QUEUE_CONNECTION' => 'sync',
        ];

        foreach ($updates as $key => $value) {
            // More robust regex pattern that handles various formats
            $pattern = "/^{$key}\s*=\s*.*$/m";
            $replacement = "{$key}={$value}";
            
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                // If the key doesn't exist, append it
                $envContent .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envPath, $envContent);
        
        // Verify the update worked
        $updatedContent = file_get_contents($envPath);
        if (!str_contains($updatedContent, "DB_DATABASE={$request->db_name}")) {
            throw new \Exception('Failed to update .env file with database configuration');
        }
        
        // Force reload the .env file
        if (file_exists($envPath)) {
            $dotenv = \Dotenv\Dotenv::createImmutable(base_path());
            $dotenv->load();
        }
    }

    private function createAdminUser($request)
    {
        try {
            // Check if admin user already exists
            $existingAdmin = User::where('email', $request->admin_email)->first();
            if ($existingAdmin) {
                throw new \Exception('Admin user with this email already exists');
            }

            // Create admin user
            $admin = User::create([
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'password' => Hash::make($request->admin_password),
                'email_verified_at' => now(),
            ]);

            if (!$admin) {
                throw new \Exception('Failed to create admin user');
            }

            // Assign admin role
            $admin->assignRole('admin');

            // Verify admin role was assigned
            if (!$admin->hasRole('admin')) {
                throw new \Exception('Failed to assign admin role to user');
            }

            // Log successful admin creation
            \Log::info('Admin user created successfully during installation', [
                'admin_id' => $admin->id,
                'admin_email' => $admin->email,
                'admin_name' => $admin->name
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to create admin user during installation', [
                'error' => $e->getMessage(),
                'admin_email' => $request->admin_email ?? 'unknown'
            ]);
            throw new \Exception('Admin user creation failed: ' . $e->getMessage());
        }
    }

    private function createInstallationFlag()
    {
        DB::table('settings')->insert([
            'key' => 'installed',
            'value' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function testDatabaseConnection($request)
    {
        try {
            // Test with PDO first
            $connection = new \PDO(
                "mysql:host={$request->db_host};port={$request->db_port};dbname={$request->db_name}",
                $request->db_username,
                $request->db_password,
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
            
            // Also test with Laravel's database connection
            config([
                'database.connections.mysql.host' => $request->db_host,
                'database.connections.mysql.port' => $request->db_port,
                'database.connections.mysql.database' => $request->db_name,
                'database.connections.mysql.username' => $request->db_username,
                'database.connections.mysql.password' => $request->db_password,
            ]);
            
            DB::purge('mysql');
            DB::connection('mysql')->getPdo();
            
        } catch (\Exception $e) {
            throw new \Exception('Database connection failed after .env update: ' . $e->getMessage());
        }
    }

    private function createDefaultPwaSettings($request)
    {
        try {
            \App\Models\PwaSetting::create([
                'app_name' => $request->app_name,
                'short_name' => substr($request->app_name, 0, 12),
                'description' => 'Professional Gym Management System',
                'theme_color' => '#3B82F6',
                'background_color' => '#FFFFFF',
                'display' => 'standalone',
                'orientation' => 'portrait',
                'start_url' => '/',
                'scope' => '/',
                'is_enabled' => true,
            ]);
        } catch (\Exception $e) {
            // PWA settings creation is optional, don't fail installation
            \Log::warning('Failed to create default PWA settings: ' . $e->getMessage());
        }
    }
}
