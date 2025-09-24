<?php

namespace App\Http\Controllers;

use App\Helpers\CurrencyHelper;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * Switch currency
     */
    public function switch(Request $request)
    {
        $request->validate([
            'currency' => 'required|string|size:3',
        ]);

        $currency = strtoupper($request->currency);
        
        if (CurrencyHelper::setCurrency($currency)) {
            return response()->json([
                'success' => true,
                'currency' => $currency,
                'symbol' => CurrencyHelper::getSymbol($currency),
                'name' => CurrencyHelper::getName($currency),
                'message' => 'Currency changed to ' . CurrencyHelper::getName($currency)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unsupported currency'
        ], 400);
    }

    /**
     * Get current currency info
     */
    public function current()
    {
        $currency = CurrencyHelper::getCurrentCurrency();
        
        return response()->json([
            'currency' => $currency,
            'symbol' => CurrencyHelper::getSymbol($currency),
            'name' => CurrencyHelper::getName($currency),
            'exchange_rate' => CurrencyHelper::getExchangeRate($currency),
            'supported_currencies' => CurrencyHelper::getSupportedCurrencies()
        ]);
    }

    /**
     * Convert amount between currencies
     */
    public function convert(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'from' => 'required|string|size:3',
            'to' => 'required|string|size:3',
        ]);

        $fromCurrency = strtoupper($request->from);
        $toCurrency = strtoupper($request->to);
        $amount = $request->amount;

        if (!CurrencyHelper::isSupported($fromCurrency) || !CurrencyHelper::isSupported($toCurrency)) {
            return response()->json([
                'success' => false,
                'message' => 'Unsupported currency'
            ], 400);
        }

        $convertedAmount = CurrencyHelper::convert($amount, $fromCurrency, $toCurrency);
        $formattedAmount = CurrencyHelper::format($convertedAmount, $toCurrency);

        return response()->json([
            'success' => true,
            'original_amount' => $amount,
            'converted_amount' => $convertedAmount,
            'formatted_amount' => $formattedAmount,
            'from_currency' => $fromCurrency,
            'to_currency' => $toCurrency,
            'exchange_rate' => CurrencyHelper::getExchangeRate($toCurrency) / CurrencyHelper::getExchangeRate($fromCurrency)
        ]);
    }
}