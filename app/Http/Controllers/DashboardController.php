<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\Order;
use App\Models\AutoBid;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // Using middleware in the controller constructor is not needed
    // as the routes are already protected with middleware in web.php

    /**
     * Show the user dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get authenticated user
        $user = Auth::user();

        // Get user's statistics
        $totalBids = Bid::where('user_id', $user->id)->count();
        $wonAuctions = Auction::where('winner_id', $user->id)->count();

        // Calculate total savings (retail price - winning bid amount)
        $savings = 0;
        $userWonAuctions = Auction::where('winner_id', $user->id)->get();
        foreach ($userWonAuctions as $auction) {
            $winningBid = Bid::where('auction_id', $auction->id)
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($winningBid && $auction->retailPrice) {
                $savings += ($auction->retailPrice - $winningBid->amount);
            }
        }

        // Get count of active auctions the user is participating in
        $activeAuctions = Auction::whereHas('bids', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'active')->count();

        // Get user's recent activity (bids, wins, watchlist additions)
        $recentBids = Bid::where('user_id', $user->id)
            ->with('auction')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentWins = Auction::where('winner_id', $user->id)
            ->orderBy('endTime', 'desc')
            ->take(3)
            ->get();

        // Recent watchlist additions
        $recentWatchlist = $user->watchlist()
            ->withPivot('created_at')
            ->orderBy('user_watchlist.created_at', 'desc')
            ->take(3)
            ->get();

        // Quick stats for sidebar
        $activeBidsCount = Bid::where('user_id', $user->id)
            ->whereHas('auction', function ($query) {
                $query->where('status', 'active');
            })
            ->count();

        $watchlistCount = $user->watchlist()->count();
        $winsCount = Auction::where('winner_id', $user->id)->count();

        return view('dashboard.index', compact(
            'user',
            'totalBids',
            'wonAuctions',
            'savings',
            'activeAuctions',
            'recentBids',
            'recentWins',
            'recentWatchlist',
            'activeBidsCount',
            'watchlistCount',
            'winsCount'
        ));
    }

    /**
     * Show the user's active auctions.
     *
     * @return \Illuminate\View\View
     */
    public function activeAuctions()
    {
        // Get authenticated user
        $user = Auth::user();

         // Quick stats for sidebar
        $activeBidsCount = Bid::where('user_id', $user->id)
            ->whereHas('auction', function ($query) {
                $query->where('status', 'active');
            })
            ->count();

        $watchlistCount = $user->watchlist()->count();
        $winsCount = Auction::where('winner_id', $user->id)->count();

        // Get total counts for tabs
        $activeAuctionsCount = Auction::whereHas('bids', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'active')->count();

        $wonAuctionsCount = Auction::where('winner_id', $user->id)->count();

        $lostAuctionsCount = Auction::whereHas('bids', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('status', 'ended')
        ->where(function ($query) use ($user) {
            $query->whereNull('winner_id')
                ->orWhere('winner_id', '!=', $user->id);
        })->count();

        // Get user's active auctions with pagination
        $activeAuctions = Auction::whereHas('bids', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('status', 'active')
          ->orderBy('endTime', 'asc')
          ->paginate(10, ['*'], 'active_page');

        // Get user's won auctions with pagination
        $wonAuctions = Auction::where('winner_id', $user->id)
            ->orderBy('endTime', 'desc')
            ->paginate(10, ['*'], 'won_page');

        // Get user's lost auctions with pagination
        $lostAuctions = Auction::whereHas('bids', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('status', 'ended')
        ->where(function ($query) use ($user) {
            $query->whereNull('winner_id')
                ->orWhere('winner_id', '!=', $user->id);
        })
        ->orderBy('endTime', 'desc')
        ->paginate(10, ['*'], 'lost_page');

        // Get count of user's bids for each active auction
        $userBidCounts = [];
        foreach ($activeAuctions as $auction) {
            $userBidCounts[$auction->id] = Bid::where('auction_id', $auction->id)
                ->where('user_id', $user->id)
                ->count();
        }

        // Get user's watchlist auction IDs
        $watchlistIds = $user->watchlist()->pluck('auctions.id')->toArray();

        return view('dashboard.auctions', compact(
            'user',
            'activeAuctions',
            'wonAuctions',
            'lostAuctions',
            'activeAuctionsCount',
            'wonAuctionsCount',
            'lostAuctionsCount',
            'userBidCounts',
            'watchlistIds',
            'activeBidsCount',
            'watchlistCount',
            'winsCount'
        ));
    }

    /**
     * Show the user's watchlist.
     *
     * @return \Illuminate\View\View
     */
    public function watchlist()
    {
        // Get authenticated user
        $user = Auth::user();

        // Quick stats for sidebar
        $activeBidsCount = Bid::where('user_id', $user->id)
            ->whereHas('auction', function ($query) {
                $query->where('status', 'active');
            })
            ->count();

        $watchlistCount = $user->watchlist()->count();
        $winsCount = Auction::where('winner_id', $user->id)->count();

        // Get total watchlist count for display
        $totalWatchlistCount = $user->watchlist()->count();

        // Get user's watchlist with auction details and pagination
        $watchlist = $user->watchlist()
            ->with('bids') // Load bids relationship
            ->orderBy('pivot_created_at', 'desc') // Order by when added to watchlist
            ->paginate(10); // 12 items per page for better grid layout

        // Get bid counts for each auction
        $bidCounts = [];
        foreach ($watchlist as $auction) {
            $bidCounts[$auction->id] = $auction->bids->count();
        }

        return view('dashboard.watchlist', compact(
            'user',
            'watchlist',
            'bidCounts',
            'activeBidsCount',
            'watchlistCount',
            'winsCount',
            'totalWatchlistCount'
        ));
    }

    /**
     * Show the user's won auctions.
     *
     * @return \Illuminate\View\View
     */
    public function wins(Request $request)
    {
        // Get authenticated user
        $user = Auth::user();


        // Quick stats for sidebar
        $activeBidsCount = Bid::where('user_id', $user->id)
            ->whereHas('auction', function ($query) {
                $query->where('status', 'active');
            })
            ->count();

        $watchlistCount = $user->watchlist()->count();
        $winsCount = Auction::where('winner_id', $user->id)->count();

        // Get pending wins query
        $pendingWinsQuery = Auction::where('winner_id', $user->id)
            ->with(['category', 'order' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->whereDoesntHave('order', function($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->whereIn('status', ['processing', 'shipped', 'delivered']);
            })
            ->orderBy('endTime', 'desc');

        // Get completed wins query
        $completedWinsQuery = Auction::where('winner_id', $user->id)
            ->with(['category', 'order' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->whereHas('order', function($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->whereIn('status', ['processing', 'shipped', 'delivered']);
            })
            ->orderBy('endTime', 'desc');

        // Get counts for tab badges
        $pendingWinsCount = $pendingWinsQuery->count();
        $completedWinsCount = $completedWinsQuery->count();

        // Paginate both queries with different page parameters
        $pendingWins = $pendingWinsQuery->paginate(10, ['*'], 'pending_page');
        $completedWins = $completedWinsQuery->paginate(10, ['*'], 'completed_page');

        // Get bid information for all wins on current pages
        $winDetails = [];
        $allWins = $pendingWins->concat($completedWins);

        foreach ($allWins as $auction) {
            $bidsUsed = Bid::where('auction_id', $auction->id)
                ->where('user_id', $user->id)
                ->count();

            $finalBid = Bid::where('auction_id', $auction->id)
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();

            $winDetails[$auction->id] = [
                'bidsUsed' => $bidsUsed,
                'finalBid' => $finalBid ? $finalBid->amount : 0,
                'savings' => $auction->retailPrice && $finalBid ?
                    round((($auction->retailPrice - $finalBid->amount) / $auction->retailPrice) * 100) : 0
            ];
        }

        return view('dashboard.wins', compact(
            'user',
            'pendingWins',
            'completedWins',
            'pendingWinsCount',
            'completedWinsCount',
            'winDetails',
            'activeBidsCount',
            'watchlistCount',
            'winsCount'
        ));
    }

    /**
     * Show the user's bid history.
     *
     * @return \Illuminate\View\View
     */
    public function bidHistory(Request $request)
    {
        // Get authenticated user
        $user = Auth::user();

        // Quick stats for sidebar
        $activeBidsCount = Bid::where('user_id', $user->id)
            ->whereHas('auction', function ($query) {
                $query->where('status', 'active');
            })
            ->count();

        $watchlistCount = $user->watchlist()->count();
        $winsCount = Auction::where('winner_id', $user->id)->count();

        // Get filter parameters
        $dateRange = $request->input('dateRange', 30); // Default to 30 days
        $bidStatus = $request->input('bidStatus', 'all'); // Default to all bids

        // Base query for user's bids
        $query = Bid::where('user_id', $user->id)
            ->with('auction')
            ->orderBy('created_at', 'desc');

        // Apply date filter
        if ($dateRange) {
            $query->where('created_at', '>=', now()->subDays($dateRange));
        }

        // Apply bid status filter
        if ($bidStatus !== 'all') {
            if ($bidStatus === 'won') {
                $query->whereHas('auction', function($q) use ($user) {
                    $q->where('winner_id', $user->id);
                });
            } elseif ($bidStatus === 'active') {
                $query->whereHas('auction', function($q) {
                    $q->where('status', 'active');
                });
            } elseif ($bidStatus === 'lost') {
                $query->whereHas('auction', function($q) use ($user) {
                    $q->where('status', 'ended')
                      ->where(function($query) use ($user) {
                          $query->whereNull('winner_id')
                                ->orWhere('winner_id', '!=', $user->id);
                      });
                });
            }
        }

        // Get paginated results
        $bidHistory = $query->paginate(10);

        // Get status for each bid
        $bidStatuses = [];
        foreach ($bidHistory as $bid) {
            if ($bid->auction->winner_id === $user->id) {
                $bidStatuses[$bid->id] = 'won';
            } elseif ($bid->auction->status === 'active') {
                // Check if this is the current highest bid
                $highestBid = Bid::where('auction_id', $bid->auction_id)
                    ->orderBy('amount', 'desc')
                    ->first();

                if ($highestBid && $highestBid->id === $bid->id) {
                    $bidStatuses[$bid->id] = 'active';
                } else {
                    $bidStatuses[$bid->id] = 'outbid';
                }
            } else {
                $bidStatuses[$bid->id] = 'lost';
            }
        }

        return view('dashboard.bid-history', compact('user', 'bidHistory', 'bidStatuses', 'dateRange', 'bidStatus','activeBidsCount','watchlistCount','winsCount'));
    }

    /**
     * Show the user's orders.
     *
     * @return \Illuminate\View\View
     */
    public function orders(Request $request)
    {
        // Get authenticated user
        $user = Auth::user();

         // Quick stats for sidebar
        $activeBidsCount = Bid::where('user_id', $user->id)
            ->whereHas('auction', function ($query) {
                $query->where('status', 'active');
            })
            ->count();

        $watchlistCount = $user->watchlist()->count();
        $winsCount = Auction::where('winner_id', $user->id)->count();

        // Get filter parameters
        $status = $request->input('orderStatus', 'all'); // Default to all orders

        // Base query for user's orders
        $query = Order::where('user_id', $user->id)
            ->with('auction')
            ->orderBy('created_at', 'desc');

        // Apply status filter
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Get paginated results
        $orders = $query->paginate(10);

        // Get auction details for each order
        foreach ($orders as $order) {
            $winningBid = Bid::where('auction_id', $order->auction_id)
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();

            $order->winningBid = $winningBid ? $winningBid->amount : null;
        }

        return view('dashboard.orders', compact('user', 'orders', 'status','activeBidsCount','watchlistCount','winsCount'));
    }

    /**
     * Show order detail page
     */
    public function showOrder($orderId)
    {
        $user = Auth::user();

        // Get the order with validation
        $order = Order::with(['auction', 'user'])
            ->where('id', $orderId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Quick stats for sidebar
        $activeBidsCount = Bid::where('user_id', $user->id)
            ->whereHas('auction', function ($query) {
                $query->where('status', 'active');
            })
            ->count();

        $watchlistCount = $user->watchlist()->count();
        $winsCount = Auction::where('winner_id', $user->id)->count();

        return view('dashboard.order-detail', compact('user', 'order', 'activeBidsCount', 'watchlistCount', 'winsCount'));
    }

    /**
     * Process order payment with Stripe
     */
    public function processOrderPayment(Request $request, $orderId)
    {
        $user = Auth::user();

        // Get the order
        $order = Order::where('id', $orderId)
            ->where('user_id', $user->id)
            ->where('payment_status', '!=', 'paid')
            ->firstOrFail();

        // Check if Stripe is configured
        if (!config('stripe.secret_key')) {
            return redirect()->route('dashboard.orders')
                ->with('error', 'Payment gateway is not configured. Please contact support.');
        }

        try {
            // Use our custom Stripe service
            $stripeService = new \App\Services\StripeService();

            // Create Stripe Checkout session for order
            $session = $stripeService->createCheckoutSession([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $order->auction->title,
                            'description' => 'Order #' . $order->id . ' - Won Auction',
                        ],
                        'unit_amount' => round($order->total * 100), // Stripe expects amount in cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('dashboard.order.payment.success') . '?session_id={CHECKOUT_SESSION_ID}&order_id=' . $order->id,
                'cancel_url' => route('dashboard.order.payment.cancel', $order->id),
                'metadata' => [
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'type' => 'order_payment',
                ]
            ]);

            // Store session ID for verification
            session(['stripe_order_session_' . $order->id => $session['id']]);

            // Get checkout URL
            $checkoutUrl = $stripeService->getCheckoutUrl($session);

            if (!$checkoutUrl) {
                throw new \Exception('Failed to create checkout session');
            }

            // Redirect to Stripe Checkout
            return redirect($checkoutUrl);

        } catch (\Exception $e) {
            Log::error('Stripe order payment error: ' . $e->getMessage());
            return redirect()->route('dashboard.order.show', $order->id)
                ->with('error', 'Unable to process payment. Please try again later.');
        }
    }

    /**
     * Handle successful order payment
     */
    public function orderPaymentSuccess(Request $request)
    {
        $sessionId = $request->get('session_id');
        $orderId = $request->get('order_id');

        if (!$sessionId || !$orderId) {
            return redirect()->route('dashboard.orders')
                ->with('error', 'Invalid payment session.');
        }

        try {
            $user = Auth::user();

            // Get the order
            $order = Order::where('id', $orderId)
                ->where('user_id', $user->id)
                ->firstOrFail();

            // Check if already paid
            if ($order->payment_status === 'paid') {
                return redirect()->route('dashboard.orders')
                    ->with('info', 'This order has already been paid.');
            }

            // Use our custom Stripe service
            $stripeService = new \App\Services\StripeService();

            // Retrieve the session from Stripe
            $session = $stripeService->retrieveSession($sessionId);

            // Verify payment was successful
            if (!$stripeService->isPaymentSuccessful($session)) {
                return redirect()->route('dashboard.order.show', $order->id)
                    ->with('error', 'Payment was not successful.');
            }

            // Update order with payment details
            $order->transaction_id = $session['payment_intent'] ?? $sessionId;
            $order->paymentMethod = 'stripe';
            $order->payment_details = [
                'stripe_session_id' => $sessionId,
                'payment_intent' => $session['payment_intent'] ?? null,
                'amount_paid' => $session['amount_total'] / 100, // Convert from cents
                'currency' => $session['currency'] ?? 'usd',
                'payment_date' => now()->toIso8601String(),
            ];
            $order->payment_status = 'paid';
            $order->status = 'processing'; // Move order to processing after payment
            $order->save();

            // Log the transaction
            Log::info('Order payment successful', [
                'order_id' => $order->id,
                'user_id' => $user->id,
                'transaction_id' => $order->transaction_id,
                'amount' => $order->total,
            ]);

            // Clear session
            session()->forget('stripe_order_session_' . $order->id);

            return redirect()->route('dashboard.orders')
                ->with('success', 'Payment successful! Your order #' . $order->id . ' is now being processed.');

        } catch (\Exception $e) {
            Log::error('Order payment success handler error: ' . $e->getMessage());
            return redirect()->route('dashboard.orders')
                ->with('error', 'An error occurred while processing your payment.');
        }
    }

    /**
     * Handle cancelled order payment
     */
    public function orderPaymentCancel($orderId)
    {
        // Clear session
        session()->forget('stripe_order_session_' . $orderId);

        return redirect()->route('dashboard.order.show', $orderId)
            ->with('info', 'Payment was cancelled. You can try again when you\'re ready.');
    }

    /**
     * Show the user's account settings.
     *
     * @return \Illuminate\View\View
     */
    public function settings()
    {
        // Get authenticated user
        $user = Auth::user();

         // Quick stats for sidebar
        $activeBidsCount = Bid::where('user_id', $user->id)
            ->whereHas('auction', function ($query) {
                $query->where('status', 'active');
            })
            ->count();

        $watchlistCount = $user->watchlist()->count();
        $winsCount = Auction::where('winner_id', $user->id)->count();

        // Get user's notification preferences
        $notificationPreferences = $user->notification_preferences ?? [
            'outbid' => true,
            'ending' => true,
            'new' => true,
            'order' => true,
            'promo' => false
        ];

        return view('dashboard.settings', compact('user', 'notificationPreferences','activeBidsCount','watchlistCount','winsCount'));
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'country' => 'required|string|max:2',
        ]);

        // Update user profile
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->country = $request->country;
        $user->save();

        return redirect(url('/dashboard/settings'))->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the user's avatar image.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAvatar(Request $request)
    {
        // Validate the request
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
        ]);

        $user = Auth::user();

        // Delete old avatar if exists
        if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
            Storage::delete('public/' . $user->avatar);
        }

        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');

        // Update user record
        $user->avatar = str_replace('public/', '', $path);
        $user->save();

        // Return JSON response for AJAX request
        return response()->json([
            'success' => true,
            'message' => 'Avatar updated successfully!',
            'avatar_url' => asset('storage/' . $user->avatar),
            'user_name' => $user->name
        ]);
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        // Validate the request
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Update user password
        $user = Auth::user();
        
        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect(url('/dashboard/settings'))->with('success', 'Password updated successfully!');
    }

    /**
     * Update the user's notification preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateNotifications(Request $request)
    {
        // Update user notification preferences
        $user = Auth::user();

        // Get notification preferences from request
        $preferences = [
            'outbid' => $request->has('outbid_notification'),
            'ending' => $request->has('ending_notification'),
            'new' => $request->has('new_notification'),
            'order' => $request->has('order_notification'),
            'promo' => $request->has('promo_notification')
        ];

        // Update user notification preferences
        $user->notification_preferences = $preferences;
        $user->save();

        return redirect(url('/dashboard/settings'))->with('success', 'Notification preferences updated successfully!');
    }

    /**
     * Add an auction to the user's watchlist.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addToWatchlist(Request $request)
    {
        // Validate the request
        $request->validate([
            'auction_id' => 'required|exists:auctions,id',
        ]);

        // Add auction to user's watchlist
        $user = Auth::user();

        // Check if auction is already in watchlist
        if (!$user->watchlist()->where('auction_id', $request->auction_id)->exists()) {
            $user->watchlist()->attach($request->auction_id);
            $message = 'Auction added to watchlist!';
            $success = true;
        } else {
            $message = 'Auction is already in your watchlist!';
            $success = false;
        }

        // Return JSON response for AJAX requests
        if ($request->wantsJson()) {
            return response()->json([
                'success' => $success,
                'message' => $message
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove an auction from the user's watchlist.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeFromWatchlist(Request $request)
    {
        // Validate the request
        $request->validate([
            'auction_id' => 'required|exists:auctions,id',
        ]);

        // Remove auction from user's watchlist
        $user = Auth::user();

        // Check if auction is in watchlist
        if ($user->watchlist()->where('auction_id', $request->auction_id)->exists()) {
            $user->watchlist()->detach($request->auction_id);
            $message = 'Auction removed from watchlist!';
            $success = true;
        } else {
            $message = 'Auction was not in your watchlist!';
            $success = false;
        }

        // Return JSON response for AJAX requests
        if ($request->wantsJson()) {
            return response()->json([
                'success' => $success,
                'message' => $message
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Show the bid packages page for purchasing bids.
     *
     * @return \Illuminate\View\View
     */
    public function buyBids()
    {
        // Get authenticated user
        $user = Auth::user();

        // Get active bid packages
        $bidPackages = \App\Models\BidPackage::where('isActive', true)
            ->orderBy('bidAmount', 'asc')
            ->get();

        return view('dashboard.buy-bids', compact('user', 'bidPackages'));
    }

    /**
     * Show the bid package purchase page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function purchaseBids(Request $request)
    {
        // Get authenticated user
        $user = Auth::user();

        // Get package ID from request
        $packageId = $request->input('package_id');

        // Get the bid package
        $package = \App\Models\BidPackage::where('id', $packageId)
            ->where('isActive', true)
            ->first();

        if (!$package) {
            return redirect()->route('dashboard.buy-bids')->with('error', 'Invalid package selected.');
        }

        // Calculate price details
        $pricePerBid = $package->bidAmount > 0 ? $package->price / $package->bidAmount : 0;
        $subtotal = $package->price;
        $tax = 0; // Can be calculated based on user location
        $total = $subtotal + $tax;

        return view('dashboard.purchase-bids', compact('user', 'package', 'pricePerBid', 'subtotal', 'tax', 'total'));
    }

    /**
     * Process the bid package purchase (testing purpose - no actual payment).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processPurchase(Request $request)
    {
        // Validate the request
        $request->validate([
            'package_id' => 'required|exists:bid_packages,id'
        ]);

        // Get authenticated user
        $user = Auth::user();

        // Get the bid package
        $package = \App\Models\BidPackage::where('id', $request->package_id)
            ->where('isActive', true)
            ->first();

        if (!$package) {
            return redirect()->route('dashboard.buy-bids')->with('error', 'Invalid package selected.');
        }

        // Add bid credits to user's balance (testing purpose - no actual payment processing)
        $oldBalance = $user->bid_balance;
        $user->bid_balance += $package->bidAmount;
        $user->save();

        // Redirect with success message
        return redirect()->route('dashboard')
            ->with('success', 'Your purchase has been successfully completed. Thank you!')
            ->with('package_details', [
                'bid_amount' => $package->bidAmount,
                'new_balance' => $user->bid_balance
            ]);
    }

    /**
     * Show checkout page for won auction
     */
    public function checkout($auctionId)
    {
        $user = Auth::user();

        // Get the auction with winner validation
        $auction = Auction::with(['category'])
            ->where('id', $auctionId)
            ->where('winner_id', $user->id)
            ->firstOrFail();

        // Check if order already exists
        $existingOrder = Order::where('auction_id', $auctionId)
            ->where('user_id', $user->id)
            ->first();

        if ($existingOrder) {
            return redirect()->route('dashboard.wins')
                ->with('info', 'You have already placed an order for this auction.');
        }

        // Calculate order details
        $subtotal = $auction->currentPrice;
        $shippingCost = 10.00; // Fixed shipping cost, can be made dynamic
        $taxRate = 0.08; // 8% tax, can be made configurable
        $tax = $subtotal * $taxRate;
        $total = $subtotal + $shippingCost + $tax;

        return view('dashboard.checkout', compact(
            'user',
            'auction',
            'subtotal',
            'shippingCost',
            'tax',
            'total'
        ));
    }

    /**
     * Process checkout and create order
     */
    public function processCheckout(Request $request, $auctionId)
    {
        $user = Auth::user();

        // Validate the auction and winner
        $auction = Auction::where('id', $auctionId)
            ->where('winner_id', $user->id)
            ->firstOrFail();

        // Check if order already exists
        $existingOrder = Order::where('auction_id', $auctionId)
            ->where('user_id', $user->id)
            ->first();

        if ($existingOrder) {
            return redirect()->route('dashboard.wins')
                ->with('error', 'Order already exists for this auction.');
        }

        // Validate request
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'payment_method' => 'required|in:credit_card,paypal,bank_transfer'
        ]);

        // Calculate order totals
        $subtotal = $auction->currentPrice;
        $shippingCost = 10.00;
        $taxRate = 0.08;
        $tax = $subtotal * $taxRate;
        $total = $subtotal + $shippingCost + $tax;

        // Prepare shipping address
        $shippingAddress = [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => $request->country
        ];

        // Create the order
        $order = Order::create([
            'user_id' => $user->id,
            'auction_id' => $auctionId,
            'amount' => $total, // For backward compatibility
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'tax' => $tax,
            'total' => $total,
            'status' => 'pending',
            'shippingAddress' => $shippingAddress,
            'paymentMethod' => $request->payment_method,
            'payment_status' => 'pending',
            'transaction_id' => 'TXN-' . strtoupper(uniqid()),
            'notes' => $request->notes ?? null
        ]);

        // For testing, automatically mark as processing
        // In production, this would be done after payment confirmation
        if ($request->payment_method === 'credit_card') {
            $order->update([
                'status' => 'processing',
                'payment_status' => 'completed'
            ]);
        }

        return redirect()->route('dashboard.wins')
            ->with('success', 'Your order has been successfully placed! Order ID: ' . $order->id);
    }

    /**
     * Submit a review for an auction
     */
    public function submitReview(Request $request)
    {
        // Validate the request
        $request->validate([
            'auction_id' => 'required|exists:auctions,id',
            'order_id' => 'nullable|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        // Check if user is the winner of this auction
        $auction = Auction::findOrFail($request->auction_id);
        if ($auction->winner_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You can only review auctions you have won.'
            ], 403);
        }

        // Check if review already exists
        $existingReview = Review::where('user_id', $user->id)
            ->where('auction_id', $request->auction_id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this product.'
            ], 400);
        }

        // Create the review
        try {
            $review = Review::create([
                'user_id' => $user->id,
                'auction_id' => $request->auction_id,
                'order_id' => $request->order_id,
                'rating' => $request->rating,
                'title' => $request->title,
                'comment' => $request->comment,
                'is_verified' => true, // Since they are the winner
                'is_published' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your review!',
                'review' => $review
            ]);

        } catch (\Exception $e) {
            Log::error('Review submission error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit review. Please try again.'
            ], 500);
        }
    }
}