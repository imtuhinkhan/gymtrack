<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PwaSetting extends Model
{
    protected $fillable = [
        'app_name',
        'short_name',
        'description',
        'theme_color',
        'background_color',
        'display',
        'orientation',
        'start_url',
        'scope',
        'icon_192',
        'icon_512',
        'splash_icon',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    /**
     * Get the current PWA settings (singleton pattern)
     */
    public static function getCurrent(): self
    {
        try {
            // Check if database is configured and accessible
            if (empty(env('DB_DATABASE')) || empty(env('DB_USERNAME'))) {
                return static::getDefault();
            }
            
            return static::first() ?? static::create([
                'app_name' => 'Gym Management',
                'short_name' => 'GymApp',
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
            // Database might not be ready yet during installation
            return static::getDefault();
        }
    }
    
    /**
     * Get default PWA settings for installation
     */
    public static function getDefault(): self
    {
        $instance = new static();
        $instance->fill([
            'app_name' => config('app.name', 'Gym Management'),
            'short_name' => 'GymApp',
            'description' => 'Professional Gym Management System',
            'theme_color' => '#3B82F6',
            'background_color' => '#FFFFFF',
            'display' => 'standalone',
            'orientation' => 'portrait',
            'start_url' => '/',
            'scope' => '/',
            'is_enabled' => true,
        ]);
        return $instance;
    }
}
