<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BidPackage;
use App\Models\BidPurchaseHistory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\StripeService;

class StripeController extends Controller
{
    /**
     * Create a Stripe Checkout session for bid package purchase.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createCheckoutSession(Request $request)
    {
        // Validate the request
        $request->validate([
            'package_id' => 'required|exists:bid_packages,id'
        ]);

        // Get authenticated user
        $user = Auth::user();

        // Get the bid package
        $package = BidPackage::where('id', $request->package_id)
            ->where('isActive', true)
            ->first();

        if (!$package) {
            return redirect()->route('dashboard.buy-bids')->with('error', 'Invalid package selected.');
        }

        // Calculate prices (assuming package price is in dollars)
        $subtotal = $package->price;
        //$tax = $subtotal * 0.08; // 8% tax
        //$total = $subtotal + $tax;

        $total = $subtotal;


        try {
            // Check if Stripe is configured
            if (!config('stripe.secret_key')) {
                return redirect()->route('dashboard.buy-bids')
                    ->with('error', 'Payment gateway is not configured. Please contact support.');
            }

            // Use our custom Stripe service
            $stripeService = new StripeService();

            // Create Stripe Checkout session
            $session = $stripeService->createCheckoutSession([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'AED',
                        'product_data' => [
                            'name' => $package->name,
                            'description' => $package->bidAmount . ' Bid Credits',
                        ],
                        'unit_amount' => round($total * 100), // Stripe expects amount in cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('stripe.cancel'),
                'metadata' => [
                    'user_id' => $user->id,
                    'package_id' => $package->id,
                    'bid_amount' => $package->bidAmount,
                ]
            ]);

            // Store session ID in session for verification
            session(['stripe_session_id' => $session['id']]);

            // Get checkout URL
            $checkoutUrl = $stripeService->getCheckoutUrl($session);

            if (!$checkoutUrl) {
                throw new \Exception('Failed to create checkout session');
            }

            // Redirect to Stripe Checkout
            return redirect($checkoutUrl);

        } catch (\Exception $e) {
            Log::error('Stripe checkout error: ' . $e->getMessage());
            return redirect()->route('dashboard.purchase-bids', $package->id)
                ->with('error', 'Unable to process payment. Please try again later.');
        }
    }

    /**
     * Handle successful payment from Stripe.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('dashboard.buy-bids')
                ->with('error', 'Invalid payment session.');
        }

        try {
            // Check if Stripe is configured
            if (!config('stripe.secret_key')) {
                return redirect()->route('dashboard.buy-bids')
                    ->with('error', 'Payment gateway is not configured.');
            }

            // Use our custom Stripe service
            $stripeService = new StripeService();

            // Retrieve the session from Stripe
            $session = $stripeService->retrieveSession($sessionId);

            // Verify payment was successful
            if (!$stripeService->isPaymentSuccessful($session)) {
                return redirect()->route('dashboard.buy-bids')
                    ->with('error', 'Payment was not successful.');
            }

            // Get transaction ID from payment intent
            $transactionId = null;
            $paymentIntentId = null;

            // Get payment intent ID from session
            if (isset($session['payment_intent'])) {
                // Extract payment intent ID (could be string or array)
                if (is_array($session['payment_intent'])) {
                    $paymentIntentId = $session['payment_intent']['id'] ?? null;
                } else {
                    $paymentIntentId = $session['payment_intent'];
                }

                // Always retrieve payment intent with charges to ensure we get the transaction ID
                if ($paymentIntentId) {
                    try {
                        Log::info('Retrieving payment intent with charges', ['payment_intent_id' => $paymentIntentId]);

                        $paymentIntent = $stripeService->retrievePaymentIntent($paymentIntentId);

                        Log::info('Payment intent retrieved', [
                            'payment_intent_id' => $paymentIntentId,
                            'has_charges' => isset($paymentIntent['charges']),
                            'has_latest_charge' => isset($paymentIntent['latest_charge']),
                            'charges_count' => isset($paymentIntent['charges']['data']) ? count($paymentIntent['charges']['data']) : 0
                        ]);

                        // Try to get transaction ID from charges
                        if (!empty($paymentIntent['charges']['data'])) {
                            $transactionId = $paymentIntent['charges']['data'][0]['id'] ?? null;
                        }

                        // Fallback: try latest_charge if charges array is empty
                        if (!$transactionId && isset($paymentIntent['latest_charge'])) {
                            $transactionId = is_string($paymentIntent['latest_charge'])
                                ? $paymentIntent['latest_charge']
                                : ($paymentIntent['latest_charge']['id'] ?? null);
                        }

                        if ($transactionId) {
                            Log::info('Transaction ID extracted successfully', ['transaction_id' => $transactionId]);
                        } else {
                            Log::warning('Could not extract transaction ID from payment intent', [
                                'payment_intent_structure' => array_keys($paymentIntent ?? [])
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to retrieve payment intent', [
                            'error' => $e->getMessage(),
                            'payment_intent_id' => $paymentIntentId,
                            'trace' => $e->getTraceAsString()
                        ]);
                    }
                }
            } else {
                Log::warning('No payment intent in session', [
                    'session_id' => $sessionId,
                    'session_keys' => array_keys($session ?? [])
                ]);
            }

            // Get metadata
            $userId = $session['metadata']['user_id'] ?? null;
            $packageId = $session['metadata']['package_id'] ?? null;
            $bidAmount = $session['metadata']['bid_amount'] ?? null;

            if (!$userId || !$packageId || !$bidAmount) {
                return redirect()->route('dashboard.buy-bids')
                    ->with('error', 'Invalid payment session data.');
            }

            // Get user and package
            $user = User::findOrFail($userId);
            $package = BidPackage::findOrFail($packageId);

            // Check if this payment has already been processed
            // (to prevent duplicate credits from refresh)
            $processedKey = 'stripe_processed_' . $sessionId;
            if (session($processedKey)) {
                return redirect()->route('dashboard')
                    ->with('info', 'This payment has already been processed.');
            }

            // Add bid credits to user's balance
            $oldBalance = $user->bid_balance;
            $user->bid_balance += $bidAmount;
            $user->save();

            // Create bid purchase history record
            BidPurchaseHistory::create([
                'user_id' => $user->id,
                'bid_amount' => $bidAmount,
                'bid_price' => $package->price,
                'description' => $package->name . ' - ' . $bidAmount . ' Bid Credits',
                'stripe_session_id' => $sessionId,
                'stripe_transaction_id' => $transactionId
            ]);

            // Mark as processed
            session([$processedKey => true]);

            // Log the transaction (you might want to create a transactions table)
            Log::info('Stripe payment successful', [
                'user_id' => $userId,
                'package_id' => $packageId,
                'bid_amount' => $bidAmount,
                'old_balance' => $oldBalance,
                'new_balance' => $user->bid_balance,
                'stripe_session_id' => $sessionId,
                'stripe_transaction_id' => $transactionId
            ]);

            // Clear stripe session
            session()->forget('stripe_session_id');

            // Redirect with success message
            return redirect()->route('dashboard')
                ->with('success', 'Payment successful! ' . $bidAmount . ' bid credits have been added to your account.')
                ->with('package_details', [
                    'bid_amount' => $bidAmount,
                    'new_balance' => $user->bid_balance
                ]);

        } catch (\Exception $e) {
            Log::error('Stripe success handler error: ' . $e->getMessage());
            return redirect()->route('dashboard.buy-bids')
                ->with('error', 'An error occurred while processing your payment.');
        }
    }

    /**
     * Handle cancelled payment from Stripe.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Request $request)
    {
        // Clear stripe session
        session()->forget('stripe_session_id');

        return redirect()->route('dashboard.buy-bids')
            ->with('info', 'Payment was cancelled. You can try again when you\'re ready.');
    }

}