<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\AutoBid;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Show the homepage.
     *
     * @return \Illuminate\View\View
     */

public function __construct()
{
    date_default_timezone_set('Asia/Kolkata');
}

    public function index()
    {
        // Get featured auctions (ensure they're active and not ended)
        $featuredAuctions = Auction::where('status', 'active')
            ->where('status', 'active')
            ->where('endTime', '>', now()) // Make sure they haven't ended yet
            ->with(['category', 'bids'])
            ->take(4)
            ->get();

        // Removed endingSoonAuctions query as we no longer need this section

        // Get upcoming auctions
        $upcomingAuctions = Auction::where('status', 'upcoming')
            ->with(['category'])
            ->orderBy('startTime', 'asc')
            ->take(6)
            ->get();

        // Get recently added auctions
        $recentAuctions = Auction::where('status', 'active')
            ->with(['category', 'bids'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Get popular categories
        $popularCategories = DB::table('categories')
            ->leftJoin('auctions', 'categories.id', '=', 'auctions.category_id')
            ->select('categories.id', 'categories.name', 'categories.slug', 'categories.description', 'categories.image', DB::raw('count(auctions.id) as auction_count'))
            ->groupBy('categories.id', 'categories.name', 'categories.slug', 'categories.description', 'categories.image')
            ->orderBy('auction_count', 'desc')
            ->take(5)
            ->get();

        // Get recent winners - any auction with a winner
        $recentWinners = Auction::whereNotNull('winner_id')
            ->with(['winner', 'category'])
            ->orderBy('endTime', 'desc')
            ->take(6)
            ->get();

        // Get all categories for the filter
        $categories = \App\Models\Category::orderBy('name')->get();

        // Get ended auctions for the ended tab
        $endedAuctions = Auction::where('status', 'ended')
            ->orWhere('endTime', '<', now())
            ->with(['category', 'bids'])
            ->orderBy('endTime', 'desc')
            ->take(8)
            ->get();

        // Get slider images
        $sliderImages = $this->getSliderImages();

        return view('home', compact(
            'featuredAuctions',
            'upcomingAuctions',
            'recentAuctions',
            'popularCategories',
            'recentWinners',
            'categories',
            'endedAuctions',
            'sliderImages'
        ));
    }

    /**
     * Show the auctions page.
     *
     * @return \Illuminate\View\View
     */
    public function auctions()
    {
        // Start with base query
        $auct = Auction::with(['category', 'bids']);

        // Apply status filter
        if (request('status')) {
            switch(request('status')) {
                case 'live':
                    // Only active auctions
                    $auct->where('status', 'active')
                         ->where('endTime', '>', now());
                    break;
                case 'ending-soon':
                    // Active auctions ending within 24 hours
                    $auct->where('status', 'active')
                         ->where('endTime', '>', now())
                         ->where('endTime', '<', now()->addHours(24));
                    break;
                case 'upcoming':
                    // Upcoming auctions
                    $auct->where('status', 'upcoming');
                    break;
                case 'all':
                default:
                    // Show all active auctions by default
                    $auct->where('status', 'active');
                    break;
            }
        } else {
            // Default to active auctions
            $auct->where('status', 'active');
        }

        // Apply category filter
        if (request('category') && request('category') != 'all') {
            $auct->whereHas('category', function ($query) {
                $query->where('slug', request('category'));
            });
        }

        // Apply search filter
        if (request('search')) {
            $search = request('search');
            $auct->where(function($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Apply sorting - MODIFIED TO PRIORITIZE ENDING SOON
        if(request('sort')) {
            switch(request('sort')) {
                case 'price-low':
                    $auct->orderBy('currentPrice', 'ASC');
                    break;
                case 'price-high':
                    $auct->orderBy('currentPrice', 'DESC');
                    break;
                case 'featured':
                    $auct->orderBy('featured', 'DESC')->orderBy('endTime', 'ASC');
                    break;
                case 'bids':
                    $auct->withCount('bids')->orderBy('bids_count', 'DESC');
                    break;
                case 'newest':
                    $auct->orderBy('created_at', 'DESC');
                    break;
                case 'ending-soon':
                default:
                    // Sort by ending soon first (using CASE to prioritize auctions ending within 24 hours)
                    $auct->orderByRaw("CASE
                        WHEN status = 'active' AND endTime > NOW() AND endTime <= DATE_ADD(NOW(), INTERVAL 24 HOUR) THEN 0
                        WHEN status = 'active' THEN 1
                        ELSE 2
                    END")
                    ->orderBy('endTime', 'ASC');
                    break;
            }
        } else {
            // Default sorting - ending soon first, then other active auctions
            $auct->orderByRaw("CASE
                WHEN status = 'active' AND endTime > NOW() AND endTime <= DATE_ADD(NOW(), INTERVAL 24 HOUR) THEN 0
                WHEN status = 'active' THEN 1
                ELSE 2
            END")
            ->orderBy('featured', 'DESC')
            ->orderBy('endTime', 'ASC');
        }

        $auctions = $auct->paginate(12)->withQueryString();

        // Get all categories
        $categories = DB::table('categories')
            ->select('id', 'name', 'slug')
            ->get();

        return view('auctions', compact('auctions', 'categories'));
    }

    /**
     * Show the categories page.
     *
     * @return \Illuminate\View\View
     */
    public function categories()
    {
        // Get featured categories with auction counts
        $featuredCategories = DB::table('categories')
            ->leftJoin('auctions', 'categories.id', '=', 'auctions.category_id')
            ->select('categories.id', 'categories.name', 'categories.slug', 'categories.description', 'categories.image', 'categories.featured',
                    DB::raw('count(auctions.id) as auction_count'),
                    DB::raw('count(CASE WHEN auctions.status = \'active\' THEN 1 END) as active_auction_count'))
            ->where('categories.featured', true)
            ->groupBy('categories.id', 'categories.name', 'categories.slug', 'categories.description', 'categories.image', 'categories.featured')
            ->orderBy('auction_count', 'desc')
            ->get();

        // Get all categories with auction counts
        $categories = DB::table('categories')
            ->leftJoin('auctions', 'categories.id', '=', 'auctions.category_id')
            ->select('categories.id', 'categories.name', 'categories.slug', 'categories.description', 'categories.image', 'categories.featured',
                    DB::raw('count(auctions.id) as auction_count'),
                    DB::raw('count(CASE WHEN auctions.status = \'active\' THEN 1 END) as active_auction_count'))
            ->groupBy('categories.id', 'categories.name', 'categories.slug', 'categories.description', 'categories.image', 'categories.featured')
            ->orderBy('featured', 'desc')
            ->orderBy('auction_count', 'desc')
            ->get();

        return view('categories', compact('categories', 'featuredCategories'));
    }

    /**
     * Show the how it works page.
     *
     * @return \Illuminate\View\View
     */
    public function howItWorks()
    {
        return view('how-it-works');
    }

    /**
     * Show the winners page.
     *
     * @return \Illuminate\View\View
     */
    public function winners()
    {
        // Start with base query for winners - check for any auction with a winner
        $query = Auction::whereNotNull('winner_id')
            ->with(['winner', 'category', 'bids']);

        // Apply category filter
        if (request('category') && request('category') != 'all') {
            $query->whereHas('category', function ($q) {
                $q->where('slug', request('category'));
            });
        }

        // Apply time period filter
        if (request('period')) {
            $now = now();
            switch(request('period')) {
                case 'today':
                    $query->whereDate('endTime', '=', $now->toDateString());
                    break;
                case 'this-week':
                    $query->whereBetween('endTime', [
                        $now->copy()->startOfWeek()->toDateTimeString(),
                        $now->copy()->endOfWeek()->toDateTimeString()
                    ]);
                    break;
                case 'last-week':
                    $lastWeek = $now->copy()->subWeek();
                    $query->whereBetween('endTime', [
                        $lastWeek->startOfWeek()->toDateTimeString(),
                        $lastWeek->endOfWeek()->toDateTimeString()
                    ]);
                    break;
                case 'this-month':
                    $query->whereMonth('endTime', '=', $now->month)
                          ->whereYear('endTime', '=', $now->year);
                    break;
                case 'last-month':
                    $lastMonth = $now->copy()->subMonth();
                    $query->whereMonth('endTime', '=', $lastMonth->month)
                          ->whereYear('endTime', '=', $lastMonth->year);
                    break;
                case 'last-3-months':
                    $query->where('endTime', '>=', $now->copy()->subMonths(3)->toDateTimeString());
                    break;
                case 'last-6-months':
                    $query->where('endTime', '>=', $now->copy()->subMonths(6)->toDateTimeString());
                    break;
                case 'this-year':
                    $query->whereYear('endTime', '=', $now->year);
                    break;
                case 'all':
                default:
                    // No time filter
                    break;
            }
        }

        // Apply search if provided
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhereHas('winner', function($wq) use ($search) {
                      $wq->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Sort by end time (most recent first)
        $query->orderBy('endTime', 'desc');

        // Get paginated results
        $winners = $query->paginate(12)->withQueryString();

        // Get all categories for filter dropdown
        $categories = DB::table('categories')
            ->select('id', 'name', 'slug')
            ->orderBy('name', 'asc')
            ->get();

        // Calculate statistics for the winners page
        // Happy Winners Count - Total number of auctions with winners
        $happyWinnersCount = Auction::whereNotNull('winner_id')->count();

        // Total Savings - Sum of (retailPrice - currentPrice) for all won auctions
        $totalSavings = Auction::whereNotNull('winner_id')
            ->sum(DB::raw('retailPrice - currentPrice'));

        // Average Savings Percentage - Average percentage saved across all won auctions
        $avgSavingsQuery = Auction::whereNotNull('winner_id')
            ->where('retailPrice', '>', 0)
            ->selectRaw('AVG((retailPrice - currentPrice) / retailPrice * 100) as avg_savings')
            ->first();
        $avgSavingsPercentage = $avgSavingsQuery->avg_savings ?? 0;

        return view('winners', compact('winners', 'categories', 'happyWinnersCount', 'totalSavings', 'avgSavingsPercentage'));
    }

    /**
     * Show the about us page.
     *
     * @return \Illuminate\View\View
     */
    public function about()
    {
        return view('info.about');
    }

    /**
     * Show the terms of service page.
     *
     * @return \Illuminate\View\View
     */
    public function terms()
    {
        return view('info.terms');
    }

    /**
     * Show the privacy policy page.
     *
     * @return \Illuminate\View\View
     */
    public function privacy()
    {
        return view('info.privacy');
    }

    /**
     * Show the shipping and returns page.
     *
     * @return \Illuminate\View\View
     */
    public function shipping()
    {
        return view('info.shipping');
    }

    /**
     * Show the FAQ page.
     *
     * @return \Illuminate\View\View
     */
    public function faq()
    {
        return view('info.faq');
    }

    /**
     * Show the help center page.
     *
     * @param string|null $topic
     * @return \Illuminate\View\View
     */
    public function help($topic = null)
    {
        if ($topic) {
            // Check if a topic view exists
            $viewPath = 'info.help.' . $topic;
            if (view()->exists($viewPath)) {
                return view($viewPath);
            }

            // If topic doesn't exist, redirect to the main help page
            return redirect()->route('help')->with('error', 'The requested help topic could not be found.');
        }

        return view('info.help');
    }

    /**
     * Show the sitemap page.
     *
     * @return \Illuminate\View\View
     */
    public function sitemap()
    {
        return view('info.sitemap');
    }

    /**
     * Show detailed auction page.
     *
     * @param string $id
     * @return \Illuminate\View\View
     */
    public function auctionDetail($id)
    {
        $auction = Auction::with(['category', 'winner', 'bids' => function($query) {
            $query->with('user')->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        // Get the total number of bids for this auction
        $totalBids = $auction->bids->count();

        // Get the recent bidders (limit to 10)
        $recentBidders = $auction->bids->take(10);

        // Calculate percentage of time elapsed
        $timeProgress = 0;
        $timeLeft = '';
        $now = now();

        // Override status based on endTime comparison
        if ($auction->endTime && ($auction->endTime < $now)) {
            $auction->status = 'ended';

            // Check if auction has ended but no winner is set yet
            if (!$auction->winner_id && $auction->bids->count() > 0) {
                // Get the latest bid (first in the collection since it's ordered by created_at desc)
                $latestBid = $auction->bids->first();

                // Update the auction with the winner
                $auction->winner_id = $latestBid->user_id;
                $auction->save();

                // Reload the auction with updated data
                return redirect()->route('auction.detail', $auction->id);
            }
        }

        // Calculate time remaining for active/upcoming auctions
        if ($auction->endTime && $auction->startTime) {
            // Check if current time has exceeded end time
            if ($now >= $auction->endTime) {
                // Auction time has expired - show ENDED
                $auction->status = 'ended';
                $timeProgress = 100;
                $timeLeft = 'ENDED';
            } elseif ($now >= $auction->startTime && $now < $auction->endTime) {
                // Auction is currently running - show countdown
                // Calculate total duration from start to end
                $totalDuration = $auction->startTime->diffInSeconds($auction->endTime);

                // Calculate elapsed time from start to now
                $elapsed = $auction->startTime->diffInSeconds($now);

                // Calculate progress (0-100%)
                if ($totalDuration > 0) {
                    $timeProgress = min(100, max(0, ($elapsed / $totalDuration) * 100));
                }

                // Calculate time remaining (from now to endTime)
                $secondsLeft = $now->diffInSeconds($auction->endTime);
                $timeLeft = $this->formatTimeRemaining($secondsLeft);
            } elseif ($now < $auction->startTime) {
                // Auction hasn't started yet
                $auction->status = 'upcoming';
                $timeProgress = 0;
                // Show countdown to start time
                $secondsUntilStart = $now->diffInSeconds($auction->startTime);
                $timeLeft = 'Starts in ' . $this->formatTimeRemaining($secondsUntilStart);
            }
        }

        // Calculate savings percentage
        $savingsPercentage = 0;
        if ($auction->retailPrice > 0 && $auction->currentPrice > 0) {
            $savingsPercentage = 100 - (($auction->currentPrice / $auction->retailPrice) * 100);
        }

        // Get similar auctions from the same category
        $similarAuctions = Auction::where('category_id', $auction->category_id)
            ->where('id', '!=', $auction->id)
            ->where('status', 'active')
            ->take(4)
            ->get();

        return view('auction-detail', compact(
            'auction',
            'totalBids',
            'recentBidders',
            'timeProgress',
            'timeLeft',
            'savingsPercentage',
            'similarAuctions'
        ));
    }

    /**
     * Place a bid on an auction.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function placeBid(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'auction_id' => 'required|exists:auctions,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get the authenticated user
            $user = Auth::user();

            // Find the auction
            $auction = Auction::findOrFail($request->auction_id);

            // Check if auction is active
            if ($auction->status !== 'active') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This auction is not active.'
                ], 422);
            }

            // Check if auction has ended
            if (now() > $auction->endTime) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This auction has ended.'
                ], 422);
            }

            // Check if user has enough bid balance
            if ($user->bid_balance < 1) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient bid credits'
                ], 403);
            }

            // Start a database transaction
            DB::beginTransaction();

            try {
                // Decrement user's bid balance
                $user->bid_balance -= 1;
                $user->save();

                // Calculate new price
                $newPrice = $auction->currentPrice + $auction->bidIncrement;

                // Update auction price
                $auction->currentPrice = $newPrice;

                // Extend auction end time
                $auction->endTime = Carbon::now()->addSeconds($auction->extensionTime);
                $auction->save();

                // Create bid record
                $bid = new Bid();
                $bid->user_id = $user->id;
                $bid->auction_id = $auction->id;
                $bid->amount = $newPrice;
                $bid->save();

                // Commit the transaction
                DB::commit();

                // Return success response
                return response()->json([
                    'status' => 'success',
                    'message' => 'Bid placed successfully',
                    'data' => [
                        'auction' => $auction,
                        'bid' => $bid,
                        'userBidCredits' => $user->bid_balance
                    ]
                ]);
            } catch (\Exception $e) {
                // Something went wrong, rollback the transaction
                DB::rollBack();

                // Throw the exception to be caught by the outer try-catch
                throw $e;
            }
        } catch (\Exception $e) {
            // Return error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while placing your bid.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set up auto-bidding for an auction.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autoBid(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'auction_id' => 'required|exists:auctions,id',
            'max_bids' => 'required|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            $auctionId = $request->auction_id;
            $maxBids = $request->max_bids;

            // Check if user has enough bid balance
            if ($user->bid_balance < $maxBids) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient bid credits. You need at least ' . $maxBids . ' bid credits to set up auto-bidding with this configuration.'
                ], 403);
            }

            // Check if auction exists and is active
            $auction = Auction::findOrFail($auctionId);

            if ($auction->status !== 'active') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Auto-bidding can only be set up for active auctions.'
                ], 422);
            }

            // Check if auto-bid already exists for this user and auction
            $existingAutoBid = AutoBid::where('user_id', $user->id)
                ->where('auction_id', $auctionId)
                ->first();

            if ($existingAutoBid) {
                // Update existing auto-bid
                $existingAutoBid->max_bids = $maxBids;
                $existingAutoBid->bids_left = $maxBids;
                $existingAutoBid->is_active = true;
                $existingAutoBid->save();

                $autobid = $existingAutoBid;
            } else {
                // Create new auto-bid
                $autobid = new AutoBid();
                $autobid->user_id = $user->id;
                $autobid->auction_id = $auctionId;
                $autobid->max_bids = $maxBids;
                $autobid->bids_left = $maxBids;
                $autobid->is_active = true;
                $autobid->save();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Auto-bidder set up successfully',
                'data' => $autobid
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while setting up auto-bidding',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get slider images for display
     */
    private function getSliderImages()
    {
        $sliderData = $this->getSliderData();
        
        // Filter only active slides and format for display
        $activeSlides = array_filter($sliderData, function($slide) {
            return $slide['active'] ?? false;
        });

        // Sort by order
        usort($activeSlides, function($a, $b) {
            return ($a['order'] ?? 0) - ($b['order'] ?? 0);
        });

        return array_map(function($slide, $index) {
            return [
                'index' => $index,
                'image' => $slide['image'],
                'url' => asset('storage/' . $slide['image']),
                'title' => $slide['title'] ?? '',
                'subtitle' => $slide['subtitle'] ?? '',
                'button_text' => $slide['button_text'] ?? '',
                'button_link' => $slide['button_link'] ?? '',
                'active' => $slide['active'] ?? true,
                'order' => $slide['order'] ?? $index + 1,
            ];
        }, $activeSlides, array_keys($activeSlides));
    }

    /**
     * Get slider data from storage
     */
    private function getSliderData()
    {
        $configPath = storage_path('app/slider-config.json');

        if (File::exists($configPath)) {
            $content = File::get($configPath);
            return json_decode($content, true) ?? [];
        }

        return [];
    }

    /**
     * Format seconds into readable time remaining string
     *
     * @param int|float $seconds
     * @return string
     */
    private function formatTimeRemaining($seconds)
    {
        // Convert to integer to avoid float precision issues
        $seconds = (int) $seconds;

        if ($seconds <= 0) {
            return 'ENDED';
        }

        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        $parts = [];

        if ($days > 0) {
            $parts[] = $days . 'd';
        }
        if ($hours > 0 || $days > 0) {
            $parts[] = $hours . 'h';
        }
        if ($minutes > 0 || $hours > 0 || $days > 0) {
            $parts[] = $minutes . 'm';
        }
        $parts[] = $secs . 's';

        return implode(' ', $parts);
    }
}
