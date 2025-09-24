<?php

use App\Helpers\CurrencyHelper;

/**
 * Get an icon class for a category based on its name
 *
 * @param string $categoryName
 * @return string
 */
function getCategoryIcon($categoryName) {
    $name = strtolower($categoryName);
    
    $iconMap = [
        'electronics' => 'fa-laptop',
        'computers' => 'fa-desktop',
        'smartphones' => 'fa-mobile-alt',
        'phones' => 'fa-mobile-alt',
        'tablets' => 'fa-tablet-alt',
        'gadgets' => 'fa-microchip',
        'accessories' => 'fa-headphones',
        'jewelry' => 'fa-gem',
        'watches' => 'fa-clock',
        'home' => 'fa-home',
        'home & kitchen' => 'fa-home',
        'home & garden' => 'fa-house-user',
        'kitchen' => 'fa-utensils',
        'appliances' => 'fa-blender',
        'fashion' => 'fa-tshirt',
        'clothing' => 'fa-tshirt',
        'shoes' => 'fa-shoe-prints',
        'handbags' => 'fa-shopping-bag',
        'gaming' => 'fa-gamepad',
        'video games' => 'fa-gamepad',
        'toys' => 'fa-puzzle-piece',
        'sports' => 'fa-basketball-ball',
        'fitness' => 'fa-dumbbell',
        'outdoors' => 'fa-mountain',
        'beauty' => 'fa-spa',
        'health' => 'fa-heartbeat',
        'personal care' => 'fa-pump-soap',
        'books' => 'fa-book',
        'music' => 'fa-music',
        'movies' => 'fa-film',
        'art' => 'fa-paint-brush',
        'collectibles' => 'fa-trophy',
        'antiques' => 'fa-landmark',
        'automotive' => 'fa-car',
        'tools' => 'fa-tools',
        'travel' => 'fa-plane',
        'pet supplies' => 'fa-paw',
        'baby' => 'fa-baby',
        'photography' => 'fa-camera',
        'cameras' => 'fa-camera',
        'audio' => 'fa-headphones',
        'musical instruments' => 'fa-guitar',
        'gift cards' => 'fa-gift',
        'vouchers' => 'fa-ticket-alt'
    ];
    
    // Search for exact match
    if (isset($iconMap[$name])) {
        return $iconMap[$name];
    }
    
    // Search for partial match
    foreach ($iconMap as $key => $value) {
        if (strpos($name, $key) !== false) {
            return $value;
        }
    }
    
    // Default icon if no match is found
    return 'fa-tag';
}

/**
 * Format a price with currency symbol
 */
function formatPrice($price, $currency = null)
{
    // If no currency specified, use current session currency
    if ($currency === null) {
        $currency = CurrencyHelper::getCurrentCurrency();
    }
    
    // Convert from USD if needed
    if ($currency !== 'USD') {
        $price = CurrencyHelper::convertFromUSD($price, $currency);
    }
    
    return CurrencyHelper::format($price, $currency);
}

/**
 * Get current currency code
 */
function currentCurrency()
{
    return CurrencyHelper::getCurrentCurrency();
}

/**
 * Get current currency symbol
 */
function currencySymbol($currency = null)
{
    return CurrencyHelper::getSymbol($currency);
}

/**
 * Convert price from USD to target currency
 */
function convertPrice($usdPrice, $targetCurrency = null)
{
    return CurrencyHelper::convertFromUSD($usdPrice, $targetCurrency);
}