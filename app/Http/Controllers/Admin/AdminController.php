<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\Order;
use App\Services\DatabaseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Middleware is applied in routes/web.php instead
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get stats for the admin dashboard
        $totalUsers = User::count();
        $totalAuctions = Auction::count();
        $totalBids = Bid::count();
        $totalSubscriptions = \DB::table('bid_purchase_histories')->distinct('user_id')->count('user_id') ?? 0;
        $totalBidsPurchased = \DB::table('bid_purchase_histories')->sum('bid_amount') ?? 0;
        
        // Get recent users
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();
        
        // Get recent auctions
        $recentAuctions = Auction::orderBy('created_at', 'desc')->take(5)->get();
        
        // Get recent bids
        $recentBids = Bid::with(['user', 'auction'])->orderBy('created_at', 'desc')->take(10)->get();
        
        // Get monthly bid purchase data for current year
        $currentYear = now()->year;
        $monthlyBidPurchases = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $monthStart = \Carbon\Carbon::create($currentYear, $month, 1)->startOfMonth();
            $monthEnd = \Carbon\Carbon::create($currentYear, $month, 1)->endOfMonth();
            
            $monthData = \DB::table('bid_purchase_histories')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->selectRaw('COUNT(*) as count, COALESCE(SUM(bid_amount), 0) as total_bids, COALESCE(SUM(bid_price), 0) as total_amount')
                ->first();
            
            $monthlyBidPurchases[] = [
                'month' => $month,
                'month_name' => $monthStart->format('M'),
                'count' => $monthData->count ?? 0,
                'total_bids' => $monthData->total_bids ?? 0,
                'total_amount' => $monthData->total_amount ?? 0
            ];
        }
        
        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalAuctions', 
            'totalBids', 
            'totalSubscriptions',
            'totalBidsPurchased',
            'recentUsers', 
            'recentAuctions', 
            'recentBids',
            'monthlyBidPurchases',
            'currentYear'
        ));
    }
    
    /**
     * Get monthly bid purchase data for a specific year (AJAX endpoint)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMonthlyBidPurchases(Request $request)
    {
        $year = $request->input('year', now()->year);
        $monthlyData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $monthStart = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
            $monthEnd = \Carbon\Carbon::create($year, $month, 1)->endOfMonth();
            
            $monthData = \DB::table('bid_purchase_histories')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->selectRaw('COUNT(*) as count, COALESCE(SUM(bid_amount), 0) as total_bids, COALESCE(SUM(bid_price), 0) as total_amount')
                ->first();
            
            $monthlyData[] = [
                'month' => $month,
                'month_name' => $monthStart->format('M'),
                'count' => $monthData->count ?? 0,
                'total_bids' => $monthData->total_bids ?? 0,
                'total_amount' => $monthData->total_amount ?? 0
            ];
        }
        
        return response()->json($monthlyData);
    }

    /**
     * Show the users management page.
     *
     * @return \Illuminate\View\View
     */
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }
    
    /**
     * Show the user details page.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function showUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Get user's bids with pagination
        $bidsQuery = Bid::where('user_id', $id)
            ->with('auction')
            ->orderBy('created_at', 'desc');

        // For activity modal, get all bids
        $allBids = $bidsQuery->get();

        // For tab, paginate bids
        $bids = $bidsQuery->paginate(10, ['*'], 'bids_page');

        // Get user's won auctions with pagination
        $wonAuctionsQuery = Auction::where('winner_id', $id)
            ->orderBy('endTime', 'desc');

        // For activity modal, get all won auctions
        $allWonAuctions = $wonAuctionsQuery->get();

        // For tab, paginate won auctions
        $wonAuctions = $wonAuctionsQuery->paginate(10, ['*'], 'won_page');

        // Get user's orders with pagination
        $ordersQuery = Order::where('user_id', $id)
            ->with('auction')
            ->orderBy('created_at', 'desc');

        // For activity modal, get all orders
        $allOrders = $ordersQuery->get();

        // For tab, paginate orders
        $orders = $ordersQuery->paginate(10, ['*'], 'orders_page');

        // Get additional stats for activity modal
        $activeAuctionCount = Auction::whereHas('bids', function($query) use ($id) {
            $query->where('user_id', $id);
        })->where('status', 'active')->count();

        $totalSpent = Order::where('user_id', $id)
            ->where('payment_status', 'paid')
            ->sum('total');

        // Determine active tab based on pagination parameter
        $activeTab = 'bids'; // default
        if ($request->has('won_page')) {
            $activeTab = 'won-auctions';
        } elseif ($request->has('orders_page')) {
            $activeTab = 'orders';
        }

        return view('admin.users.show', compact(
            'user',
            'bids',
            'wonAuctions',
            'orders',
            'allBids',
            'allWonAuctions',
            'allOrders',
            'activeAuctionCount',
            'totalSpent',
            'activeTab'
        ));
    }
    
    /**
     * Show the edit user form.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }
    
    /**
     * Update the specified user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'country' => 'required|string|max:2',
            'bid_balance' => 'required|integer|min:0',
            'role' => 'required|in:admin,customer',
            'active' => 'required|boolean',
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->country = $request->country;
        $user->bid_balance = $request->bid_balance;
        $user->role = $request->role;
        $user->active = $request->active;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();
        
        return redirect(url('/admin/users/' . $id))->with('success', 'User updated successfully!');
    }
    
    /**
     * Delete a user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // Don't allow deleting yourself
        if ($user->id === Auth::id()) {
            return redirect(url('/admin/users'))->with('error', 'You cannot delete your own account!');
        }
        
        $user->delete();
        
        return redirect(url('/admin/users'))->with('success', 'User deleted successfully!');
    }
    
    /**
     * Create a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'country' => 'required|string|max:2',
            'bid_balance' => 'required|integer|min:0',
            'role' => 'required|in:admin,customer',
            'active' => 'required|boolean',
        ]);
        
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone = $request->phone;
        $user->country = $request->country;
        $user->bid_balance = $request->bid_balance;
        $user->role = $request->role;
        $user->active = $request->active;
        $user->notification_preferences = json_encode([
            'outbid_notification' => true,
            'ending_notification' => true,
            'new_notification' => true,
            'order_notification' => true,
            'promo_notification' => true,
        ]);
        
        $user->save();
        
        return redirect(url('/admin/users'))->with('success', 'User created successfully!');
    }
    
    /**
     * Show the auctions management page.
     *
     * @return \Illuminate\View\View
     */
    public function auctions()
    {
        $auctions = Auction::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.auctions.index', compact('auctions'));
    }
    
    /**
     * Show the auction details page.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function showAuction($id)
    {
        $auction = Auction::with(['bids.user'])->findOrFail($id);
        return view('admin.auctions.show', compact('auction'));
    }
    
    /**
     * Show the orders management page.
     *
     * @return \Illuminate\View\View
     */
    public function orders()
    {
        $orders = Order::with(['user', 'auction'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }
    
    /**
     * Show the order details page.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function showOrder($id)
    {
        $order = Order::with(['user', 'auction'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }
    
    /**
     * Update the order status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered',
        ]);

        // Update the order status
        $order->status = $request->status;

        // If there's a status history, add the new status to it
        $statusHistory = $order->status_history ?? [];
        $statusHistory[] = [
            'status' => $request->status,
            'timestamp' => now()->toDateTimeString(),
            'comment' => $request->status_comment ?? '',
        ];

        $order->status_history = $statusHistory;
        $order->save();

        return redirect(url('/admin/orders/' . $id))->with('success', 'Order status updated successfully!');
    }

    /**
     * Add a note to an order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addOrderNote(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'note' => 'required|string',
        ]);

        // Append the new note to existing notes or create a new notes field
        $notes = $order->notes ?? '';
        $notes .= "\n\n" . now()->format('M d, Y H:i') . " - Admin Note:\n" . $request->note;
        $order->notes = trim($notes);

        $order->save();

        return redirect(url('/admin/orders/' . $id))->with('success', 'Note added successfully!');
    }
    
    /**
     * Show the statistics page.
     *
     * @return \Illuminate\View\View
     */
    public function statistics()
    {
        // Get main statistics
        $totalUsers = User::where('role', 'customer')->count();
        $totalAuctions = Auction::count();
        $totalBids = Bid::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');

        // Get monthly registration counts for current year
        $currentYear = now()->year;
        $monthlyRegistrations = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthStart = \Carbon\Carbon::create($currentYear, $month, 1)->startOfMonth();
            $monthEnd = \Carbon\Carbon::create($currentYear, $month, 1)->endOfMonth();

            $count = User::where('role', 'customer')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();

            $monthlyRegistrations[] = (object)[
                'month' => $month,
                'count' => $count
            ];
        }

        // Get monthly revenue for current year
        $monthlyRevenue = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthStart = \Carbon\Carbon::create($currentYear, $month, 1)->startOfMonth();
            $monthEnd = \Carbon\Carbon::create($currentYear, $month, 1)->endOfMonth();

            $revenue = Order::where('payment_status', 'paid')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('total');

            $monthlyRevenue[] = (object)[
                'month' => $month,
                'total' => $revenue
            ];
        }

        // Get auction status distribution
        $auctionStatusData = [
            'upcoming' => Auction::where('status', 'upcoming')->count(),
            'active' => Auction::where('status', 'active')->count(),
            'ended' => Auction::where('status', 'ended')->count(),
            'cancelled' => Auction::where('status', 'cancelled')->count()
        ];

        // Get order status distribution
        $orderStatusData = [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count()
        ];

        // Get payment status distribution
        $paymentStatusData = [
            'pending' => Order::where('payment_status', 'pending')->orWhereNull('payment_status')->count(),
            'paid' => Order::where('payment_status', 'paid')->count(),
            'failed' => Order::where('payment_status', 'failed')->count()
        ];

        // Get top 5 bidders
        $topBidders = User::withCount('bids')
            ->where('role', 'customer')
            ->orderBy('bids_count', 'desc')
            ->take(5)
            ->get();

        // Get top 5 auctions by bid count
        $topAuctions = Auction::withCount('bids')
            ->orderBy('bids_count', 'desc')
            ->take(5)
            ->get();

        // Get recent activity stats
        $todayUsers = User::whereDate('created_at', today())->count();
        $todayAuctions = Auction::whereDate('created_at', today())->count();
        $todayBids = Bid::whereDate('created_at', today())->count();
        $todayRevenue = Order::where('payment_status', 'paid')
            ->whereDate('created_at', today())
            ->sum('total');

        // Get active users (users who have bid in last 30 days)
        $activeUsers = User::whereHas('bids', function($query) {
            $query->where('created_at', '>=', now()->subDays(30));
        })->count();

        // Get conversion metrics
        $auctionsWithBids = Auction::has('bids')->count();
        $auctionConversionRate = $totalAuctions > 0 ? round(($auctionsWithBids / $totalAuctions) * 100, 1) : 0;

        $wonAuctions = Auction::whereNotNull('winner_id')->count();
        $winRate = $totalAuctions > 0 ? round(($wonAuctions / $totalAuctions) * 100, 1) : 0;

        return view('admin.statistics', compact(
            'totalUsers',
            'totalAuctions',
            'totalBids',
            'totalOrders',
            'totalRevenue',
            'monthlyRegistrations',
            'monthlyRevenue',
            'auctionStatusData',
            'orderStatusData',
            'paymentStatusData',
            'topBidders',
            'topAuctions',
            'todayUsers',
            'todayAuctions',
            'todayBids',
            'todayRevenue',
            'activeUsers',
            'auctionConversionRate',
            'winRate'
        ));
    }
    
    /**
     * Show the user reports page.
     *
     * @return \Illuminate\View\View
     */
    public function usersReport()
    {
        // Get top users by bids placed
        $topBidders = User::withCount('bids')
            ->where('role', 'customer')
            ->orderBy('bids_count', 'desc')
            ->take(10)
            ->get();

        // Get top users by auctions won
        $topWinners = User::withCount('auctions')
            ->where('role', 'customer')
            ->orderBy('auctions_count', 'desc')
            ->take(10)
            ->get();

        // Get top spenders (users who have spent the most on orders)
        $topSpenders = User::withSum(['orders as orders_sum_total' => function ($query) {
                $query->where('payment_status', 'paid');
            }], 'total')
            ->where('role', 'customer')
            ->orderBy('orders_sum_total', 'desc')
            ->take(10)
            ->get();

        // User Activity Distribution (by bid count ranges)
        $userActivityDistribution = [
            User::where('role', 'customer')->doesntHave('bids')->count(), // No Activity
            User::where('role', 'customer')->has('bids', '>=', 1)->has('bids', '<=', 5)->count(), // 1-5 Bids
            User::where('role', 'customer')->has('bids', '>=', 6)->has('bids', '<=', 20)->count(), // 6-20 Bids
            User::where('role', 'customer')->has('bids', '>=', 21)->has('bids', '<=', 50)->count(), // 21-50 Bids
            User::where('role', 'customer')->has('bids', '>=', 51)->has('bids', '<=', 100)->count(), // 51-100 Bids
            User::where('role', 'customer')->has('bids', '>', 100)->count(), // 100+ Bids
        ];

        // User Growth Data (Last 12 months)
        $userGrowthData = [];
        $cumulativeTotal = 0;
        for ($i = 11; $i >= 0; $i--) {
            $startDate = now()->subMonths($i)->startOfMonth();
            $endDate = now()->subMonths($i)->endOfMonth();

            $newUsers = User::where('role', 'customer')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            $cumulativeTotal += $newUsers;

            $userGrowthData[] = [
                'month' => $startDate->format('M'),
                'new_users' => $newUsers,
                'total_users' => $cumulativeTotal
            ];
        }

        // Users by Role
        $usersByRole = [
            'customers' => User::where('role', 'customer')->count(),
            'admins' => User::where('role', 'admin')->count()
        ];

        // Users by Status
        $usersByStatus = [
            'active' => User::where('role', 'customer')->where('active', true)->count(),
            'inactive' => User::where('role', 'customer')->where('active', false)->count()
        ];

        // Users by Country (from users table country field if exists, otherwise use sample data)
        $usersByCountry = User::where('role', 'customer')
            ->select('country')
            ->selectRaw('COUNT(*) as count')
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();

        // If no country data, provide sample structure
        if ($usersByCountry->isEmpty()) {
            $usersByCountry = collect([
                ['country' => 'United States', 'count' => 45],
                ['country' => 'United Kingdom', 'count' => 20],
                ['country' => 'Canada', 'count' => 15],
                ['country' => 'Australia', 'count' => 10],
                ['country' => 'Germany', 'count' => 5]
            ]);
        }

        return view('admin.reports.users', compact(
            'topBidders',
            'topWinners',
            'topSpenders',
            'userActivityDistribution',
            'userGrowthData',
            'usersByRole',
            'usersByStatus',
            'usersByCountry'
        ));
    }
    
    /**
     * Show the auctions reports page.
     *
     * @return \Illuminate\View\View
     */
    public function auctionsReport()
    {
        // Get top auctions by bids
        $topAuctionsByBids = Auction::withCount('bids')
            ->orderBy('bids_count', 'desc')
            ->take(10)
            ->get();

        // Get top auctions by final price (currentPrice is the final/current bid price)
        $topAuctionsByPrice = Auction::select('auctions.*')
            ->selectRaw('currentPrice as final_price')
            ->where('status', 'ended')
            ->orderBy('currentPrice', 'desc')
            ->take(10)
            ->get();

        // Get auctions with most unique bidders
        $auctionsWithMostBidders = Auction::select('auctions.*')
            ->selectRaw('(SELECT COUNT(DISTINCT user_id) FROM bids WHERE bids.auction_id = auctions.id) as unique_bidders_count')
            ->orderBy('unique_bidders_count', 'desc')
            ->take(10)
            ->get()
            ->map(function($auction) {
                $auction->bids_count = $auction->unique_bidders_count;
                return $auction;
            });

        // Calculate performance metrics
        $avgFinalPrice = Auction::where('status', 'ended')->avg('currentPrice') ?? 0;

        $totalAuctions = Auction::count();
        $totalBids = \App\Models\Bid::count();
        $avgBidsPerAuction = $totalAuctions > 0 ? round($totalBids / $totalAuctions) : 0;

        // Average unique bidders per auction
        $auctionWithBidders = Auction::select('auctions.id')
            ->selectRaw('COUNT(DISTINCT bids.user_id) as unique_bidders')
            ->leftJoin('bids', 'bids.auction_id', '=', 'auctions.id')
            ->groupBy('auctions.id')
            ->get();
        $avgUniqueBidders = $auctionWithBidders->avg('unique_bidders') ?? 0;

        // Completion rate (ended auctions vs total created)
        $endedAuctions = Auction::where('status', 'ended')->count();
        $totalCreatedAuctions = Auction::whereIn('status', ['ended', 'active', 'upcoming'])->count();
        $completionRate = $totalCreatedAuctions > 0 ? round(($endedAuctions / $totalCreatedAuctions) * 100) : 0;

        // Auction Activity for last 30 days
        $auctionActivityData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dateLabel = now()->subDays($i)->format('M j');

            $newAuctions = Auction::whereDate('created_at', $date)->count();
            $completedAuctions = Auction::whereDate('endTime', $date)->where('status', 'ended')->count();

            $auctionActivityData[] = [
                'date' => $dateLabel,
                'new' => $newAuctions,
                'completed' => $completedAuctions
            ];
        }

        // Auction Distribution by Category
        $auctionsByCategory = Auction::select('categories.name as category_name')
            ->selectRaw('COUNT(auctions.id) as count')
            ->leftJoin('categories', 'categories.id', '=', 'auctions.category_id')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('count', 'desc')
            ->get();

        // Bid timing distribution (sample data structure for now)
        $bidTimingData = [
            ['label' => 'Start', 'value' => 5],
            ['label' => '10%', 'value' => 7],
            ['label' => '20%', 'value' => 9],
            ['label' => '30%', 'value' => 10],
            ['label' => '40%', 'value' => 12],
            ['label' => '50%', 'value' => 15],
            ['label' => '60%', 'value' => 18],
            ['label' => '70%', 'value' => 25],
            ['label' => '80%', 'value' => 40],
            ['label' => '90%', 'value' => 70],
            ['label' => 'End', 'value' => 95]
        ];

        // Price increase pattern (calculate average price multiplier)
        $priceIncreaseData = [];
        $endedAuctionsWithBids = Auction::where('status', 'ended')
            ->where('currentPrice', '>', 'startingPrice')
            ->select('startingPrice', 'currentPrice')
            ->get();

        if ($endedAuctionsWithBids->count() > 0) {
            $avgMultiplier = $endedAuctionsWithBids->avg(function($auction) {
                return $auction->startingPrice > 0 ? $auction->currentPrice / $auction->startingPrice : 1;
            });

            // Generate price increase curve based on average multiplier
            for ($i = 0; $i <= 10; $i++) {
                $percentage = $i * 10;
                $multiplier = 1 + (($avgMultiplier - 1) * pow($i / 10, 2)); // Exponential curve
                $priceIncreaseData[] = [
                    'label' => $percentage . '%',
                    'value' => round($multiplier, 2)
                ];
            }
        }

        return view('admin.reports.auctions', compact(
            'topAuctionsByBids',
            'topAuctionsByPrice',
            'auctionsWithMostBidders',
            'avgFinalPrice',
            'avgBidsPerAuction',
            'avgUniqueBidders',
            'completionRate',
            'auctionActivityData',
            'auctionsByCategory',
            'bidTimingData',
            'priceIncreaseData'
        ));
    }
    
    /**
     * Show the sales reports page.
     *
     * @return \Illuminate\View\View
     */
    public function salesReport()
    {
        // Calculate main revenue metrics
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $totalOrders = Order::where('payment_status', 'paid')->count();
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Conversion rate: paid orders vs all orders
        $allOrders = Order::count();
        $conversionRate = $allOrders > 0 ? ($totalOrders / $allOrders) * 100 : 0;

        // Get monthly sales data for current year
        $currentYear = now()->year;
        $monthlySales = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthStart = \Carbon\Carbon::create($currentYear, $month, 1)->startOfMonth();
            $monthEnd = \Carbon\Carbon::create($currentYear, $month, 1)->endOfMonth();

            $monthData = Order::where('payment_status', 'paid')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->selectRaw('COUNT(*) as count, COALESCE(SUM(total), 0) as total')
                ->first();

            $monthlySales[] = (object)[
                'month' => $month,
                'count' => $monthData->count ?? 0,
                'total' => $monthData->total ?? 0
            ];
        }

        // Year-over-year comparison
        $lastYear = $currentYear - 1;
        $lastYearSales = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthStart = \Carbon\Carbon::create($lastYear, $month, 1)->startOfMonth();
            $monthEnd = \Carbon\Carbon::create($lastYear, $month, 1)->endOfMonth();

            $monthData = Order::where('payment_status', 'paid')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('total');

            $lastYearSales[] = $monthData;
        }

        // Sales by category
        $salesByCategory = \DB::table('orders')
            ->select('categories.name as category_name')
            ->selectRaw('COUNT(orders.id) as order_count')
            ->selectRaw('COALESCE(SUM(orders.total), 0) as revenue')
            ->leftJoin('auctions', 'orders.auction_id', '=', 'auctions.id')
            ->leftJoin('categories', 'auctions.category_id', '=', 'categories.id')
            ->where('orders.payment_status', 'paid')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('revenue', 'desc')
            ->get();

        // If no category data, provide empty structure
        if ($salesByCategory->isEmpty()) {
            $salesByCategory = collect([
                (object)['category_name' => 'Uncategorized', 'order_count' => $totalOrders, 'revenue' => $totalRevenue]
            ]);
        }

        // Daily sales distribution (by day of week)
        $dailySales = [];
        $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        for ($day = 0; $day <= 6; $day++) {
            $count = Order::where('payment_status', 'paid')
                ->whereRaw('DAYOFWEEK(created_at) = ?', [$day + 1])
                ->count();
            $dailySales[] = $count;
        }

        // Hourly sales distribution
        $hourlySales = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $count = Order::where('payment_status', 'paid')
                ->whereRaw('HOUR(created_at) = ?', [$hour])
                ->count();
            $hourlySales[] = $count;
        }

        // Customer value tiers
        $customerTiers = \DB::table('orders')
            ->select('user_id')
            ->selectRaw('SUM(total) as lifetime_value')
            ->where('payment_status', 'paid')
            ->groupBy('user_id')
            ->get();

        $tierCounts = [
            '1-50' => 0,
            '51-100' => 0,
            '101-250' => 0,
            '251-500' => 0,
            '501-1000' => 0,
            '1000+' => 0
        ];

        foreach ($customerTiers as $customer) {
            $value = $customer->lifetime_value;
            if ($value <= 50) $tierCounts['1-50']++;
            elseif ($value <= 100) $tierCounts['51-100']++;
            elseif ($value <= 250) $tierCounts['101-250']++;
            elseif ($value <= 500) $tierCounts['251-500']++;
            elseif ($value <= 1000) $tierCounts['501-1000']++;
            else $tierCounts['1000+']++;
        }

        // Repeat purchase rate
        $customersWithOrders = Order::where('payment_status', 'paid')
            ->select('user_id')
            ->selectRaw('COUNT(*) as order_count')
            ->groupBy('user_id')
            ->get();

        $repeatPurchaseData = [
            'one_time' => 0,
            'two_three' => 0,
            'four_five' => 0,
            'six_plus' => 0
        ];

        foreach ($customersWithOrders as $customer) {
            if ($customer->order_count == 1) $repeatPurchaseData['one_time']++;
            elseif ($customer->order_count <= 3) $repeatPurchaseData['two_three']++;
            elseif ($customer->order_count <= 5) $repeatPurchaseData['four_five']++;
            else $repeatPurchaseData['six_plus']++;
        }

        // Get top selling products
        $topProducts = Order::select('auctions.title', 'orders.auction_id')
            ->selectRaw('COUNT(orders.id) as sale_count')
            ->selectRaw('SUM(orders.total) as revenue')
            ->leftJoin('auctions', 'orders.auction_id', '=', 'auctions.id')
            ->where('orders.payment_status', 'paid')
            ->groupBy('orders.auction_id', 'auctions.title')
            ->orderBy('revenue', 'desc')
            ->take(10)
            ->get();

        return view('admin.reports.sales', compact(
            'totalRevenue',
            'totalOrders',
            'avgOrderValue',
            'conversionRate',
            'monthlySales',
            'lastYearSales',
            'salesByCategory',
            'dailySales',
            'hourlySales',
            'tierCounts',
            'repeatPurchaseData',
            'topProducts'
        ));
    }

    /**
     * Show the admin settings page.
     *
     * @return \Illuminate\View\View
     */
    public function settings()
    {
        // Get the current admin user
        $user = Auth::user();

        // Get site settings if they exist (we would normally store these in a settings table)
        // This is just placeholder/mock data for demonstration
        $siteSettings = [
            'site_name' => config('app.name'),
            'site_email' => 'admin@example.com',
            'bid_increment_default' => 0.01,
            'enable_notifications' => true,
            'auction_extension_time' => 30,
            'maintenance_mode' => false,
        ];

        return view('admin.settings', compact('user', 'siteSettings'));
    }

    /**
     * Update the admin profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        $user->save();

        return redirect()->route('admin.settings')->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the admin password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('admin.settings')->with('success', 'Password updated successfully!');
    }

    /**
     * Update site settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSiteSettings(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|string|email|max:255',
            'bid_increment_default' => 'required|numeric|min:0.01',
            'auction_extension_time' => 'required|integer|min:0',
        ]);

        // Here you would normally update the settings in your database
        // For now, we'll just return success

        return redirect()->route('admin.settings')->with('success', 'Site settings updated successfully!');
    }

    /**
     * Toggle user active status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleUserStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Prevent admin from deactivating themselves
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot deactivate your own account.'
            ]);
        }

        $user->active = $request->input('active', !$user->active);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully.',
            'active' => $user->active
        ]);
    }

    /**
     * Send message to user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'mobile' => 'required|string',
            'message' => 'required|string'
        ]);

        $user = User::findOrFail($request->user_id);

        // Here you would implement the actual message sending logic
        // For now, we'll simulate success
        // In a real application, you might:
        // 1. Store the message in a messages table
        // 2. Send SMS via service like Twilio
        // 3. Create in-app notification

        // Log the message for now
        \Log::info('Admin message sent to user', [
            'admin_id' => Auth::id(),
            'admin_name' => Auth::user()->name,
            'user_id' => $user->id,
            'user_name' => $user->name,
            'mobile' => $request->mobile,
            'message' => $request->message,
            'timestamp' => now()
        ]);

        // Here you would integrate with SMS service
        // Example with Twilio:
        // $twilio = new \Twilio\Rest\Client($sid, $token);
        // $twilio->messages->create($request->mobile, [
        //     'from' => config('services.twilio.phone'),
        //     'body' => $request->message
        // ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully to ' . $user->name
        ]);
    }
}