<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;
use App\Models\Setting;

class CurrencyHelper
{
    /**
     * Get the current selected currency
     */
    public static function getCurrentCurrency()
    {
        $remember = Setting::get('remember_user_choice', true);
        
        if ($remember) {
            return Session::get('selected_currency', Setting::get('default_currency', 'USD'));
        }
        
        return Setting::get('default_currency', 'USD');
    }

    /**
     * Set the current currency
     */
    public static function setCurrency($currency)
    {
        if (self::isSupported($currency)) {
            Session::put('selected_currency', $currency);
            return true;
        }
        return false;
    }

    /**
     * Check if a currency is supported
     */
    public static function isSupported($currency)
    {
        $supported = Setting::get('supported_currencies', []);
        return array_key_exists($currency, $supported);
    }

    /**
     * Get all supported currencies
     */
    public static function getSupportedCurrencies()
    {
        return Setting::get('supported_currencies', []);
    }

    /**
     * Get currency configuration
     */
    public static function getCurrencyConfig($currency = null)
    {
        if ($currency === null) {
            $currency = self::getCurrentCurrency();
        }

        $supported = self::getSupportedCurrencies();
        $config = $supported[$currency] ?? null;
        
        if ($config) {
            // Get exchange rate from database
            $rates = Setting::getCurrencyRates();
            $config['exchange_rate'] = $rates[$currency] ?? 1.0;
        }

        return $config;
    }

    /**
     * Convert amount from USD to target currency
     */
    public static function convertFromUSD($amount, $targetCurrency = null)
    {
        if ($targetCurrency === null) {
            $targetCurrency = self::getCurrentCurrency();
        }

        $rates = Setting::getCurrencyRates();
        $rate = $rates[$targetCurrency] ?? 1.0;

        return $amount * $rate;
    }

    /**
     * Convert amount to USD from source currency
     */
    public static function convertToUSD($amount, $sourceCurrency = null)
    {
        if ($sourceCurrency === null) {
            $sourceCurrency = self::getCurrentCurrency();
        }

        $rates = Setting::getCurrencyRates();
        $rate = $rates[$sourceCurrency] ?? 1.0;

        return $amount / $rate;
    }

    /**
     * Convert between two currencies
     */
    public static function convert($amount, $fromCurrency, $toCurrency)
    {
        $usdAmount = self::convertToUSD($amount, $fromCurrency);
        return self::convertFromUSD($usdAmount, $toCurrency);
    }

    /**
     * Format amount with currency symbol
     */
    public static function format($amount, $currency = null, $includeSymbol = true)
    {
        if ($currency === null) {
            $currency = self::getCurrentCurrency();
        }

        $config = self::getCurrencyConfig($currency);
        if (!$config) {
            return number_format($amount, 2);
        }

        $formattedAmount = number_format($amount, $config['decimal_places']);

        if (!$includeSymbol) {
            return $formattedAmount;
        }

        $symbol = $config['symbol'];
        $position = $config['symbol_position'];

        if ($position === 'before') {
            return $symbol . $formattedAmount;
        } else {
            return $formattedAmount . $symbol;
        }
    }

    /**
     * Format and convert amount from USD
     */
    public static function formatFromUSD($usdAmount, $targetCurrency = null)
    {
        if ($targetCurrency === null) {
            $targetCurrency = self::getCurrentCurrency();
        }

        $convertedAmount = self::convertFromUSD($usdAmount, $targetCurrency);
        return self::format($convertedAmount, $targetCurrency);
    }

    /**
     * Get currency symbol
     */
    public static function getSymbol($currency = null)
    {
        if ($currency === null) {
            $currency = self::getCurrentCurrency();
        }

        $config = self::getCurrencyConfig($currency);
        return $config['symbol'] ?? '$';
    }

    /**
     * Get currency name
     */
    public static function getName($currency = null)
    {
        if ($currency === null) {
            $currency = self::getCurrentCurrency();
        }

        $config = self::getCurrencyConfig($currency);
        return $config['name'] ?? 'Unknown Currency';
    }

    /**
     * Get exchange rate
     */
    public static function getExchangeRate($currency = null)
    {
        if ($currency === null) {
            $currency = self::getCurrentCurrency();
        }

        $rates = Setting::getCurrencyRates();
        return $rates[$currency] ?? 1.0;
    }
}