<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        try {
            // Check if database is configured and accessible
            if (empty(env('DB_DATABASE')) || empty(env('DB_USERNAME'))) {
                return $default;
            }
            
            return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
                return Setting::where('key', $key)->value('value') ?? $default;
            });
        } catch (\Exception $e) {
            // Database might not be ready yet during installation
            return $default;
        }
    }

    /**
     * Set a setting value.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set(string $key, $value): void
    {
        Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting.{$key}");
    }

    /**
     * Get app name.
     *
     * @return string
     */
    public static function getAppName(): string
    {
        return self::get('app_name', config('app.name'));
    }

    /**
     * Get app logo.
     *
     * @return string|null
     */
    public static function getAppLogo(): ?string
    {
        return self::get('app_logo');
    }

    /**
     * Get app logo URL.
     *
     * @return string|null
     */
    public static function getAppLogoUrl(): ?string
    {
        $logo = self::getAppLogo();
        return $logo ? asset('storage/' . $logo) : null;
    }

    /**
     * Get currency symbol.
     *
     * @return string
     */
    public static function getCurrencySymbol(): string
    {
        return self::get('currency_symbol', '$');
    }

    /**
     * Get currency code.
     *
     * @return string
     */
    public static function getCurrencyCode(): string
    {
        return self::get('currency_code', 'USD');
    }

    /**
     * Get currency position (before or after amount).
     *
     * @return string
     */
    public static function getCurrencyPosition(): string
    {
        return self::get('currency_position', 'before');
    }

    /**
     * Format amount with currency.
     *
     * @param float $amount
     * @param int $decimals
     * @return string
     */
    public static function formatCurrency(float $amount, int $decimals = 2): string
    {
        $symbol = self::getCurrencySymbol();
        $position = self::getCurrencyPosition();
        $formattedAmount = number_format($amount, $decimals);
        
        return $position === 'before' 
            ? $symbol . $formattedAmount 
            : $formattedAmount . $symbol;
    }

    /**
     * Clear all settings cache.
     *
     * @return void
     */
    public static function clearCache(): void
    {
        $settings = Setting::pluck('key')->toArray();
        foreach ($settings as $key) {
            Cache::forget("setting.{$key}");
        }
    }
}
