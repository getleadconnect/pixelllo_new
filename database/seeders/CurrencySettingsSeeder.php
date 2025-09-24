<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class CurrencySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default currency settings
        Setting::set('default_currency', 'USD', 'string', 'Default site currency');
        Setting::set('show_currency_selector', true, 'boolean', 'Show currency selector to users');
        Setting::set('remember_user_choice', true, 'boolean', 'Remember user currency preference');
        
        // Default exchange rates
        $defaultRates = [
            'USD' => 1.00,
            'AED' => 3.67,
            'RM' => 4.50,
        ];
        
        Setting::setCurrencyRates($defaultRates);

        // Supported currencies configuration
        $supportedCurrencies = [
            'USD' => [
                'name' => 'US Dollar',
                'symbol' => '$',
                'symbol_position' => 'before',
                'decimal_places' => 2,
            ],
            'AED' => [
                'name' => 'UAE Dirham',
                'symbol' => 'د.إ',
                'symbol_position' => 'before',
                'decimal_places' => 2,
            ],
            'RM' => [
                'name' => 'Malaysian Ringgit',
                'symbol' => 'RM',
                'symbol_position' => 'before',
                'decimal_places' => 2,
            ],
        ];
        
        Setting::set('supported_currencies', $supportedCurrencies, 'json', 'Supported currencies configuration');
        
        echo "Currency settings seeded successfully!\n";
        echo "- Default currency: USD\n";
        echo "- Supported currencies: USD, AED, RM\n";
        echo "- Exchange rates: 1 USD = 3.67 AED, 1 USD = 4.50 RM\n";
    }
}