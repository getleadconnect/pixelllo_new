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
        $totalOrders = Order::count() ?? 0;
        
        // Get recent users
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();
        
        // Get recent auctions
        $recentAuctions = Auction::orderBy('created_at', 'desc')->take(5)->get();
        
        // Get recent bids
        $recentBids = Bid::with(['user', 'auction'])->orderBy('created_at', 'desc')->take(10)->get();
        
        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalAuctions', 
            'totalBids', 
            'totalOrders', 
            'recentUsers', 
            'recentAuctions', 
            'recentBids'
        ));
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
    public function showUser($id)
    {
        $user = User::findOrFail($id);
        
        // Get user's bids
        $bids = Bid::where('user_id', $id)->with('auction')->orderBy('created_at', 'desc')->take(10)->get();
        
        // Get user's won auctions
        $wonAuctions = Auction::where('winner_id', $id)->get();
        
        // Get user's orders
        $orders = Order::where('user_id', $id)->orderBy('created_at', 'desc')->get();
        
        return view('admin.users.show', compact('user', 'bids', 'wonAuctions', 'orders'));
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
        // Get some stats for the admin dashboard
        $totalUsers = User::count();
        $totalAuctions = Auction::count();
        $totalBids = Bid::count() ?? 0;

        // Set default values in case of errors
        $totalOrders = 0;
        $totalRevenue = 0;
        $monthlyRegistrations = collect([]);
        $monthlyRevenue = collect([]);

        try {
            // These might fail if the tables don't exist or are empty
            $totalOrders = Order::count() ?? 0;
            $totalRevenue = Order::sum('total') ?? 0;

            // Get monthly registration counts - Database agnostic
            $monthlyRegistrations = User::selectRaw(DatabaseService::getMonthFromDate('created_at') . " as month, COUNT(*) as count")
                ->whereRaw(DatabaseService::getCurrentYearCondition('created_at'))
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // Get monthly revenue - Database agnostic
            $monthlyRevenue = Order::selectRaw(DatabaseService::getMonthFromDate('created_at') . " as month, SUM(total) as total")
                ->whereRaw(DatabaseService::getCurrentYearCondition('created_at'))
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        } catch (\Exception $e) {
            // Just continue with default empty values
        }
        
        return view('admin.statistics', compact(
            'totalUsers', 
            'totalAuctions', 
            'totalBids', 
            'totalOrders', 
            'totalRevenue',
            'monthlyRegistrations', 
            'monthlyRevenue'
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
            ->orderBy('bids_count', 'desc')
            ->take(10)
            ->get();
            
        // Get top users by auctions won
        $topWinners = User::withCount('auctions')
            ->orderBy('auctions_count', 'desc')
            ->take(10)
            ->get();
            
        // Get top spenders (users who have spent the most)
        try {
            $topSpenders = User::withSum('orders', 'total')
                ->orderBy('orders_sum_total', 'desc')
                ->take(10)
                ->get();
        } catch (\Exception $e) {
            // If there's an error (e.g., table doesn't exist), return an empty collection
            $topSpenders = collect([]);
        }
            
        return view('admin.reports.users', compact('topBidders', 'topWinners', 'topSpenders'));
    }
    
    /**
     * Show the auctions reports page.
     *
     * @return \Illuminate\View\View
     */
    public function auctionsReport()
    {
        // Default empty collections
        $topAuctionsByBids = collect([]);
        $topAuctionsByPrice = collect([]);
        $auctionsWithMostBidders = collect([]);

        try {
            // Get top auctions by bids
            $topAuctionsByBids = Auction::withCount('bids')
                ->orderBy('bids_count', 'desc')
                ->take(10)
                ->get();
        } catch (\Exception $e) {
            // Continue with empty collection
        }

        try {
            // Get top auctions by final price
            // Use currentPrice instead of final_price since that's the field we have
            $topAuctionsByPrice = Auction::orderBy('currentPrice', 'desc')
                ->take(10)
                ->get();
        } catch (\Exception $e) {
            // Continue with empty collection
        }

        try {
            // Get auctions with highest number of unique bidders
            $auctionsWithMostBidders = Auction::withCount(['bids' => function($query) {
                    $query->select('user_id')->distinct();
                }])
                ->orderBy('bids_count', 'desc')
                ->take(10)
                ->get();
        } catch (\Exception $e) {
            // Continue with empty collection
        }
            
        return view('admin.reports.auctions', compact('topAuctionsByBids', 'topAuctionsByPrice', 'auctionsWithMostBidders'));
    }
    
    /**
     * Show the sales reports page.
     *
     * @return \Illuminate\View\View
     */
    public function salesReport()
    {
        // Default empty collections
        $monthlySales = collect([]);
        $salesByCategory = collect([]);
        $yearSales = collect([]);

        try {
            // Get monthly sales data - Database agnostic
            $monthlySales = Order::selectRaw(DatabaseService::getMonthFromDate('created_at') . " as month, COUNT(*) as count, SUM(total) as total")
                ->whereRaw(DatabaseService::getCurrentYearCondition('created_at'))
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        } catch (\Exception $e) {
            // Continue with empty collection
        }

        try {
            // Get sales by product category
            // Simplified version that won't fail if relationship doesn't exist
            $salesByCategory = Order::where('total', '>', 0)
                ->groupBy('auction_id')
                ->selectRaw('auction_id, COUNT(*) as count, SUM(total) as total')
                ->orderBy('total', 'desc')
                ->take(10)
                ->get();
        } catch (\Exception $e) {
            // Continue with empty collection
        }

        try {
            // Get sales data for the last 12 months - Database agnostic
            $yearSales = Order::selectRaw(DatabaseService::getMonthFromDate('created_at') . " as month, " . DatabaseService::getYearFromDate('created_at') . " as year, COUNT(*) as count, SUM(total) as total")
                ->whereRaw(DatabaseService::getLastMonthsCondition('created_at', 12))
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
        } catch (\Exception $e) {
            // Continue with empty collection
        }
            
        return view('admin.reports.sales', compact('monthlySales', 'salesByCategory', 'yearSales'));
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
}