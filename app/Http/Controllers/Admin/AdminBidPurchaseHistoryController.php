<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BidPurchaseHistory;
use Illuminate\Http\Request;

class AdminBidPurchaseHistoryController extends Controller
{
    /**
     * Display a listing of bid package purchase histories.
     */
    public function index()
    {
        $histories = BidPurchaseHistory::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Calculate statistics
        $totalBids = $histories->sum('bid_amount');
        $totalAmount = $histories->sum('bid_price');
        
        // Today's statistics
        $today = now()->startOfDay();
        $todaysHistories = $histories->where('created_at', '>=', $today);
        $todaysBids = $todaysHistories->sum('bid_amount');
        $todaysAmount = $todaysHistories->sum('bid_price');
        
        // Get current year for dynamic month options
        $currentYear = date('Y');
        $currentMonth = date('m');
        
        $statistics = [
            'total_bids' => $totalBids,
            'total_amount' => $totalAmount,
            'todays_bids' => $todaysBids,
            'todays_amount' => $todaysAmount
        ];
            
        return view('admin.bid-purchase-histories.index', compact('histories', 'currentYear', 'currentMonth', 'statistics'));
    }
}
