<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    |
    | This option defines the default currency for the application.
    |
    */
    'default' => env('DEFAULT_CURRENCY', 'USD'),

    /*
    |--------------------------------------------------------------------------
    | Supported Currencies
    |--------------------------------------------------------------------------
    |
    | This array contains all currencies supported by the application.
    | Each currency includes symbol, name, and exchange rate to USD.
    |
    */
    'supported' => [
        'USD' => [
            'name' => 'US Dollar',
            'symbol' => '$',
            'symbol_position' => 'before', // before or after
            'decimal_places' => 2,
            'exchange_rate' => 1.00, // Base currency
        ],
        'AED' => [
            'name' => 'UAE Dirham',
            'symbol' => 'د.إ',
            'symbol_position' => 'before',
            'decimal_places' => 2,
            'exchange_rate' => 3.67, // 1 USD = 3.67 AED (approximate)
        ],
        'RM' => [
            'name' => 'Malaysian Ringgit',
            'symbol' => 'RM',
            'symbol_position' => 'before',
            'decimal_places' => 2,
            'exchange_rate' => 4.50, // 1 USD = 4.50 RM (approximate)
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Exchange Rate API
    |--------------------------------------------------------------------------
    |
    | Configuration for automatic exchange rate updates.
    | You can use services like exchangerate-api.com, fixer.io, etc.
    |
    */
    'exchange_rate_api' => [
        'enabled' => env('EXCHANGE_RATE_API_ENABLED', false),
        'provider' => env('EXCHANGE_RATE_PROVIDER', 'exchangerate-api'),
        'api_key' => env('EXCHANGE_RATE_API_KEY'),
        'update_frequency' => 'daily', // daily, hourly, manual
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency Display Settings
    |--------------------------------------------------------------------------
    |
    | Settings for how currencies are displayed throughout the application.
    |
    */
    'display' => [
        'show_currency_selector' => true,
        'remember_user_choice' => true,
        'session_key' => 'selected_currency',
    ],
];