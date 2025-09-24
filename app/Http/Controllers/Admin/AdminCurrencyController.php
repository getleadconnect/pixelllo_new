<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\CurrencyHelper;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminCurrencyController extends Controller
{
    /**
     * Display currency management page
     */
    public function index()
    {
        $currencies = CurrencyHelper::getSupportedCurrencies();
        $defaultCurrency = Setting::get('default_currency', 'USD');
        $currentCurrency = CurrencyHelper::getCurrentCurrency();
        
        // Get exchange rates from database
        $exchangeRates = Setting::getCurrencyRates();
        
        // Merge exchange rates into currency config
        foreach ($currencies as $code => &$config) {
            $config['exchange_rate'] = $exchangeRates[$code] ?? 1.0;
        }
        
        return view('admin.settings.currencies', compact('currencies', 'defaultCurrency', 'currentCurrency'));
    }

    /**
     * Update exchange rates
     */
    public function updateExchangeRates(Request $request)
    {
        $request->validate([
            'rates' => 'required|array',
            'rates.*' => 'required|numeric|min:0.01',
        ]);

        // Update exchange rates in database
        $rates = [];
        foreach ($request->rates as $currency => $rate) {
            $rates[$currency] = (float) $rate;
        }
        
        Setting::setCurrencyRates($rates);

        return redirect()->route('admin.settings.currencies')->with('success', 'Exchange rates updated successfully');
    }

    /**
     * Update currency settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'default_currency' => 'required|string|size:3',
            'show_currency_selector' => 'boolean',
            'remember_user_choice' => 'boolean',
        ]);

        // Update settings in database
        Setting::set('default_currency', $request->default_currency);
        Setting::set('show_currency_selector', $request->has('show_currency_selector'), 'boolean');
        Setting::set('remember_user_choice', $request->has('remember_user_choice'), 'boolean');

        return redirect()->route('admin.settings.currencies')->with('success', 'Currency settings updated successfully');
    }

    /**
     * Add new currency
     */
    public function addCurrency(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:3',
            'name' => 'required|string|max:100',
            'symbol' => 'required|string|max:10',
            'symbol_position' => 'required|in:before,after',
            'decimal_places' => 'required|integer|min:0|max:4',
            'exchange_rate' => 'required|numeric|min:0.01',
        ]);

        $code = strtoupper($request->code);
        
        // Get current supported currencies
        $currencies = Setting::get('supported_currencies', []);
        
        // Check if currency already exists
        if (isset($currencies[$code])) {
            return redirect()->back()->with('error', 'Currency already exists');
        }
        
        // Add new currency
        $currencies[$code] = [
            'name' => $request->name,
            'symbol' => $request->symbol,
            'symbol_position' => $request->symbol_position,
            'decimal_places' => (int) $request->decimal_places,
        ];
        
        Setting::set('supported_currencies', $currencies, 'json');
        
        // Add exchange rate
        $rates = Setting::getCurrencyRates();
        $rates[$code] = (float) $request->exchange_rate;
        Setting::setCurrencyRates($rates);

        return redirect()->route('admin.settings.currencies')->with('success', 'Currency added successfully');
    }

    /**
     * Remove currency
     */
    public function removeCurrency($currency)
    {
        $defaultCurrency = Setting::get('default_currency', 'USD');
        
        // Don't allow removing USD or the default currency
        if ($currency === 'USD' || $currency === $defaultCurrency) {
            return redirect()->back()->with('error', 'Cannot remove the base currency or default currency');
        }

        // Remove from supported currencies
        $currencies = Setting::get('supported_currencies', []);
        if (isset($currencies[$currency])) {
            unset($currencies[$currency]);
            Setting::set('supported_currencies', $currencies, 'json');
        }
        
        // Remove from exchange rates
        $rates = Setting::getCurrencyRates();
        if (isset($rates[$currency])) {
            unset($rates[$currency]);
            Setting::setCurrencyRates($rates);
        }

        return redirect()->route('admin.settings.currencies')->with('success', 'Currency removed successfully');
    }
}