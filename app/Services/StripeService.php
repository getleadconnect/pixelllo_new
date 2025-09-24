<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;

class StripeService
{
    private $secretKey;
    private $publishableKey;
    private $apiBase = 'https://api.stripe.com/v1';

    public function __construct()
    {
        $this->secretKey = config('stripe.secret_key');
        $this->publishableKey = config('stripe.publishable_key');
    }

    /**
     * Create a Stripe Checkout Session
     */
    public function createCheckoutSession($params)
    {
        try {
            $endpoint = '/checkout/sessions';

            // Build form data
            $formData = [
                'mode' => 'payment',
                'success_url' => $params['success_url'],
                'cancel_url' => $params['cancel_url'],
            ];

            // Add line items
            if (isset($params['line_items'])) {
                foreach ($params['line_items'] as $index => $item) {
                    $formData["line_items[$index][price_data][currency]"] = $item['price_data']['currency'];
                    $formData["line_items[$index][price_data][product_data][name]"] = $item['price_data']['product_data']['name'];
                    $formData["line_items[$index][price_data][product_data][description]"] = $item['price_data']['product_data']['description'] ?? '';
                    $formData["line_items[$index][price_data][unit_amount]"] = $item['price_data']['unit_amount'];
                    $formData["line_items[$index][quantity]"] = $item['quantity'];
                }
            }

            // Add payment method types
            if (isset($params['payment_method_types'])) {
                foreach ($params['payment_method_types'] as $index => $type) {
                    $formData["payment_method_types[$index]"] = $type;
                }
            }

            // Add metadata
            if (isset($params['metadata'])) {
                foreach ($params['metadata'] as $key => $value) {
                    $formData["metadata[$key]"] = $value;
                }
            }

            $response = $this->makeRequest('POST', $endpoint, $formData);

            return $response;

        } catch (Exception $e) {
            Log::error('Stripe createCheckoutSession error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Retrieve a Stripe Checkout Session
     */
    public function retrieveSession($sessionId)
    {
        try {
            $endpoint = '/checkout/sessions/' . $sessionId;
            return $this->makeRequest('GET', $endpoint);
        } catch (Exception $e) {
            Log::error('Stripe retrieveSession error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Make HTTP request to Stripe API
     */
    private function makeRequest($method, $endpoint, $data = [])
    {
        $url = $this->apiBase . $endpoint;

        // Check if we can use curl
        if (function_exists('curl_init')) {
            return $this->makeCurlRequest($method, $url, $data);
        } else {
            return $this->makeStreamRequest($method, $url, $data);
        }
    }

    /**
     * Make request using curl
     */
    private function makeCurlRequest($method, $url, $data = [])
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->secretKey . ':');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Stripe-Version: 2023-10-16',
            'Content-Type: application/x-www-form-urlencoded',
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception('Curl error: ' . $error);
        }

        $result = json_decode($response, true);

        if ($httpCode >= 400) {
            $errorMessage = isset($result['error']['message']) ? $result['error']['message'] : 'Stripe API error';
            throw new Exception($errorMessage);
        }

        return $result;
    }

    /**
     * Make request using file_get_contents (fallback when curl is not available)
     */
    private function makeStreamRequest($method, $url, $data = [])
    {
        $auth = base64_encode($this->secretKey . ':');

        $options = [
            'http' => [
                'method' => $method,
                'header' => [
                    'Authorization: Basic ' . $auth,
                    'Stripe-Version: 2023-10-16',
                    'Content-Type: application/x-www-form-urlencoded',
                ],
                'ignore_errors' => true,
            ],
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
            ],
        ];

        if ($method === 'POST' && !empty($data)) {
            $options['http']['content'] = http_build_query($data);
        }

        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            throw new Exception('Failed to connect to Stripe API');
        }

        // Get HTTP response code
        $httpCode = 200;
        if (isset($http_response_header)) {
            foreach ($http_response_header as $header) {
                if (preg_match('/^HTTP\/\d\.\d\s+(\d+)/', $header, $matches)) {
                    $httpCode = (int)$matches[1];
                    break;
                }
            }
        }

        $result = json_decode($response, true);

        if ($httpCode >= 400) {
            $errorMessage = isset($result['error']['message']) ? $result['error']['message'] : 'Stripe API error';
            throw new Exception($errorMessage);
        }

        return $result;
    }

    /**
     * Get checkout URL from session
     */
    public function getCheckoutUrl($session)
    {
        return isset($session['url']) ? $session['url'] : null;
    }

    /**
     * Check if payment is successful
     */
    public function isPaymentSuccessful($session)
    {
        return isset($session['payment_status']) && $session['payment_status'] === 'paid';
    }
}