<?php

namespace App\Helpers;

use App\Services\SettingsService;

class CurrencyHelper
{
    /**
     * Format amount with currency symbol.
     *
     * @param float $amount
     * @param int $decimals
     * @return string
     */
    public static function format(float $amount, int $decimals = 2): string
    {
        return SettingsService::formatCurrency($amount, $decimals);
    }

    /**
     * Get currency symbol.
     *
     * @return string
     */
    public static function symbol(): string
    {
        return SettingsService::getCurrencySymbol();
    }

    /**
     * Get currency code.
     *
     * @return string
     */
    public static function code(): string
    {
        return SettingsService::getCurrencyCode();
    }

    /**
     * Get available currencies.
     *
     * @return array
     */
    public static function getAvailableCurrencies(): array
    {
        return [
            'USD' => ['symbol' => '$', 'name' => 'US Dollar'],
            'EUR' => ['symbol' => '€', 'name' => 'Euro'],
            'GBP' => ['symbol' => '£', 'name' => 'British Pound'],
            'JPY' => ['symbol' => '¥', 'name' => 'Japanese Yen'],
            'CAD' => ['symbol' => 'C$', 'name' => 'Canadian Dollar'],
            'AUD' => ['symbol' => 'A$', 'name' => 'Australian Dollar'],
            'CHF' => ['symbol' => 'CHF', 'name' => 'Swiss Franc'],
            'CNY' => ['symbol' => '¥', 'name' => 'Chinese Yuan'],
            'INR' => ['symbol' => '₹', 'name' => 'Indian Rupee'],
            'BDT' => ['symbol' => '৳', 'name' => 'Bangladeshi Taka'],
            'BRL' => ['symbol' => 'R$', 'name' => 'Brazilian Real'],
            'RUB' => ['symbol' => '₽', 'name' => 'Russian Ruble'],
            'KRW' => ['symbol' => '₩', 'name' => 'South Korean Won'],
            'SGD' => ['symbol' => 'S$', 'name' => 'Singapore Dollar'],
            'HKD' => ['symbol' => 'HK$', 'name' => 'Hong Kong Dollar'],
            'NZD' => ['symbol' => 'NZ$', 'name' => 'New Zealand Dollar'],
            'MXN' => ['symbol' => '$', 'name' => 'Mexican Peso'],
            'ZAR' => ['symbol' => 'R', 'name' => 'South African Rand'],
            'SEK' => ['symbol' => 'kr', 'name' => 'Swedish Krona'],
            'NOK' => ['symbol' => 'kr', 'name' => 'Norwegian Krone'],
            'DKK' => ['symbol' => 'kr', 'name' => 'Danish Krone'],
        ];
    }
}
