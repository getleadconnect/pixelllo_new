<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\BidPackage;
use App\Models\Bid;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use App\Services\DatabaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Get dashboard statistics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboard()
    {
        // Get counts
        $userCount = User::where('role', 'customer')->count();
        $auctionCount = Auction::count();
        $bidCount = Bid::count();
        $orderCount = Order::count();

        // Get active auctions
        $activeAuctions = Auction::where('status', 'active')
            ->with('category')
            ->orderBy('endTime', 'asc')
            ->take(5)
            ->get();

        // Get recent bids
        $recentBids = Bid::with(['user', 'auction'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get recent orders
        $recentOrders = Order::with(['user', 'auction'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get auction stats by status
        $auctionStats = DB::table('auctions')
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Get order stats by status
        $orderStats = DB::table('orders')
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Calculate revenue
        $totalRevenue = Order::sum('amount');
        $revenueThisMonth = Order::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');

        return response()->json([
            'counts' => [
                'users' => $userCount,
                'auctions' => $auctionCount,
                'bids' => $bidCount,
                'orders' => $orderCount,
            ],
            'activeAuctions' => $activeAuctions,
            'recentBids' => $recentBids,
            'recentOrders' => $recentOrders,
            'auctionStats' => $auctionStats,
            'orderStats' => $orderStats,
            'revenue' => [
                'total' => $totalRevenue,
                'thisMonth' => $revenueThisMonth,
            ],
        ]);
    }

    /**
     * List all users
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Apply filters
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        if ($request->has('active')) {
            $query->where('active', $request->active === 'true');
        }

        // Apply search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('firstName', 'like', "%{$search}%")
                  ->orWhere('lastName', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sortField = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        $allowedSortFields = ['firstName', 'lastName', 'email', 'created_at', 'bidBalance'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Paginate results
        $perPage = $request->get('per_page', 15);
        return $query->paginate($perPage);
    }

    /**
     * Create a new user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createUser(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phoneNumber' => 'nullable|string|max:20',
            'dateOfBirth' => 'nullable|date',
            'role' => 'required|in:customer,admin',
            'bidBalance' => 'nullable|integer|min:0',
        ]);

        $user = User::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phoneNumber' => $request->phoneNumber,
            'dateOfBirth' => $request->dateOfBirth,
            'role' => $request->role,
            'bidBalance' => $request->bidBalance ?? 0,
            'active' => true,
        ]);

        return response()->json($user, 201);
    }

    /**
     * Show user details
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showUser(string $id)
    {
        $user = User::with(['bids.auction', 'orders.auction'])->findOrFail($id);
        return response()->json($user);
    }

    /**
     * Update user details
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'firstName' => 'sometimes|required|string|max:255',
            'lastName' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'phoneNumber' => 'nullable|string|max:20',
            'dateOfBirth' => 'nullable|date',
            'role' => 'sometimes|required|in:customer,admin',
            'bidBalance' => 'sometimes|required|integer|min:0',
            'active' => 'sometimes|required|boolean',
            'password' => 'nullable|string|min:8',
        ]);

        // Handle password separately
        if ($request->has('password') && !empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        $user->fill($request->except('password'));
        $user->save();

        return response()->json($user);
    }

    /**
     * Delete a user
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUser(string $id)
    {
        $user = User::findOrFail($id);

        // Check for bids and orders
        if ($user->bids()->count() > 0 || $user->orders()->count() > 0) {
            // Instead of deleting, deactivate the user
            $user->active = false;
            $user->save();
            return response()->json(['message' => 'User has bids or orders and cannot be deleted. User has been deactivated instead.']);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    /**
     * List all orders
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function orders(Request $request)
    {
        $query = Order::with(['user', 'auction']);

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Apply date range filter
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // Apply sorting
        $sortField = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        $allowedSortFields = ['created_at', 'status', 'amount'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Paginate results
        $perPage = $request->get('per_page', 15);
        return $query->paginate($perPage);
    }

    /**
     * Show order details
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showOrder(string $id)
    {
        $order = Order::with(['user', 'auction'])->findOrFail($id);
        return response()->json($order);
    }

    /**
     * Update order status
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOrderStatus(Request $request, string $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order->status = $request->status;

        // If order is shipped, add tracking number
        if ($request->status === 'shipped' && $request->has('trackingNumber')) {
            $request->validate([
                'trackingNumber' => 'required|string'
            ]);

            $order->trackingNumber = $request->trackingNumber;
        }

        $order->save();

        return response()->json($order);
    }

    /**
     * Get general statistics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistics()
    {
        // Get user counts
        $userStats = [
            'total' => User::count(),
            'customers' => User::where('role', 'customer')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'active' => User::where('active', true)->count(),
            'inactive' => User::where('active', false)->count(),
            'registeredThisMonth' => User::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count(),
        ];

        // Get auction counts
        $auctionStats = [
            'total' => Auction::count(),
            'upcoming' => Auction::where('status', 'upcoming')->count(),
            'active' => Auction::where('status', 'active')->count(),
            'ended' => Auction::where('status', 'ended')->count(),
            'cancelled' => Auction::where('status', 'cancelled')->count(),
            'featured' => Auction::where('featured', true)->count(),
        ];

        // Get bid counts
        $bidStats = [
            'total' => Bid::count(),
            'autobid' => Bid::where('autobid', true)->count(),
            'bidsToday' => Bid::whereDate('created_at', Carbon::today())->count(),
            'averageBidsPerAuction' => Auction::whereHas('bids')->withCount('bids')->avg('bids_count') ?? 0,
        ];

        // Get order counts
        $orderStats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'totalValue' => Order::sum('amount'),
            'averageValue' => Order::avg('amount') ?? 0,
        ];

        return response()->json([
            'users' => $userStats,
            'auctions' => $auctionStats,
            'bids' => $bidStats,
            'orders' => $orderStats,
        ]);
    }

    /**
     * Get sales report
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function salesReport(Request $request)
    {
        // Validate date range
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'group_by' => 'required|in:day,week,month'
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // Group by format
        $groupFormat = [
            'day' => '%Y-%m-%d',
            'week' => '%Y-%u', // ISO week number
            'month' => '%Y-%m',
        ][$request->group_by];

        // Get bid package sales
        // Implementation depends on your payment tracking system

        // Get auction orders
        $dbDateFormat = DatabaseService::getDateFormat('created_at', $groupFormat);
        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw("{$dbDateFormat} as period"),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as revenue')
            )
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();

        // Get total for the period
        $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return response()->json([
            'orders' => $orders,
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'period' => [
                'start' => $startDate->toDateString(),
                'end' => $endDate->toDateString(),
                'group_by' => $request->group_by
            ]
        ]);
    }

    /**
     * Get users report
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function usersReport(Request $request)
    {
        // Validate date range
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'group_by' => 'required|in:day,week,month'
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // Group by format
        $groupFormat = [
            'day' => '%Y-%m-%d',
            'week' => '%Y-%u', // ISO week number
            'month' => '%Y-%m',
        ][$request->group_by];

        // Get new users over time
        $dbDateFormat = DatabaseService::getDateFormat('created_at', $groupFormat);
        $newUsers = User::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw("{$dbDateFormat} as period"),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();

        // Get total new users for the period
        $totalNewUsers = User::whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Get active users (users who placed a bid during the period)
        $activeUsers = User::whereHas('bids', function($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })->count();

        // Get top bidders during the period
        $topBidders = User::withCount(['bids' => function($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }])
        ->having('bids_count', '>', 0)
        ->orderBy('bids_count', 'desc')
        ->take(10)
        ->get(['id', 'firstName', 'lastName', 'email', 'bids_count']);

        return response()->json([
            'new_users' => $newUsers,
            'total_new_users' => $totalNewUsers,
            'active_users' => $activeUsers,
            'top_bidders' => $topBidders,
            'period' => [
                'start' => $startDate->toDateString(),
                'end' => $endDate->toDateString(),
                'group_by' => $request->group_by
            ]
        ]);
    }

    /**
     * Get auctions report
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function auctionsReport(Request $request)
    {
        // Validate date range
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // Get auctions that ended within the date range
        $auctions = Auction::where('status', 'ended')
            ->whereBetween('endTime', [$startDate, $endDate])
            ->with('category')
            ->withCount('bids')
            ->get();

        // Get auctions by category
        $auctionsByCategory = DB::table('auctions')
            ->join('categories', 'auctions.category_id', '=', 'categories.id')
            ->where('auctions.status', 'ended')
            ->whereBetween('auctions.endTime', [$startDate, $endDate])
            ->select('categories.name', DB::raw('count(*) as count'))
            ->groupBy('categories.name')
            ->get();

        // Get average bids per auction
        $avgBidsPerAuction = $auctions->avg('bids_count') ?? 0;

        // Get auctions with most bids
        $mostPopularAuctions = $auctions->sortByDesc('bids_count')->take(10)->values();

        // Total auction revenue (final price)
        $totalAuctionRevenue = $auctions->sum('currentPrice');

        return response()->json([
            'total_auctions' => $auctions->count(),
            'auctions_by_category' => $auctionsByCategory,
            'avg_bids_per_auction' => $avgBidsPerAuction,
            'most_popular_auctions' => $mostPopularAuctions,
            'total_auction_revenue' => $totalAuctionRevenue,
            'period' => [
                'start' => $startDate->toDateString(),
                'end' => $endDate->toDateString(),
            ]
        ]);
    }
}
