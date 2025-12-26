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

            // Get the payment intent ID and retrieve transaction details
            $paymentIntentId = $session['payment_intent'] ?? null;
            $transactionId = null;
            
            if ($paymentIntentId) {
                try {
                    $paymentIntent = $stripeService->retrievePaymentIntent($paymentIntentId);
                    // Get the charge ID (transaction ID) from the payment intent
                    if (!empty($paymentIntent['charges']['data'])) {
                        $transactionId = $paymentIntent['charges']['data'][0]['id'] ?? null;
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to retrieve transaction ID: ' . $e->getMessage());
                }
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
                'stripe_session_id' => $sessionId
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