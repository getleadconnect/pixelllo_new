<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\AutoBid;
use App\Models\User;
use App\Models\BidPackage;
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
    public function index()
    {
        // Get featured auctions (ensure they're active and not ended)
        $featuredAuctions = Auction::where('status', 'active')
            ->where('endTime', '>', now()) // Make sure they haven't ended yet
            ->with(['category', 'bids'])
            ->take(4)
            ->get();

        // Removed endingSoonAuctions query as we no longer need this section

        // Get upcoming auctions (exclude auctions that have already ended)
        $upcomingAuctions = Auction::where('status', 'upcoming')
            ->where('endTime', '>', now()) // Exclude auctions that have ended
            ->where('startTime', '>', now()) // Only show auctions that haven't started yet
            ->with(['category'])
            ->orderBy('startTime', 'asc')
            ->take(6)
            ->get();

        // Get recently added auctions (only active and not ended)
        $recentAuctions = Auction::where('status', 'active')
            ->where('endTime', '>', now()) // Exclude auctions that have ended
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

        // Check if any filter is applied
        $hasFilter = request('status') || (request('category') && request('category') != 'all') || request('search');

        // Apply status filter only if explicitly provided
        if (request('status')) {
            switch(request('status')) {
                case 'active':
                    // Only active auctions (not ending soon)
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
                case 'ended':
                    // Ended/closed auctions
                    $auct->where(function($query) {
                        $query->where('status', 'ended')
                              ->orWhere('endTime', '<=', now());
                    });
                    break;
                case 'all':
                default:
                    // Show all auctions (no status filter)
                    break;
            }
        }
        // If no filter is applied, show all auctions by default
        // No status restriction on first load

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

        // Apply sorting
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
                    // Custom ordering for ending soon
                    $auct->orderByRaw("CASE
                        WHEN status = 'active' AND endTime > NOW() AND endTime <= DATE_ADD(NOW(), INTERVAL 24 HOUR) THEN 0
                        WHEN status = 'active' AND endTime > NOW() THEN 1
                        WHEN status = 'upcoming' THEN 2
                        WHEN status = 'ended' OR endTime <= NOW() THEN 3
                        ELSE 4
                    END")
                    ->orderBy('endTime', 'ASC');
                    break;
            }
        } else {
            // Default sorting: Active (ending soon first), Active, Upcoming, Ended
            $auct->orderByRaw("CASE
                WHEN status = 'active' AND endTime > NOW() AND endTime <= DATE_ADD(NOW(), INTERVAL 24 HOUR) THEN 0
                WHEN status = 'active' AND endTime > NOW() THEN 1
                WHEN status = 'upcoming' THEN 2
                WHEN status = 'ended' OR endTime <= NOW() THEN 3
                ELSE 4
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
        $bidPackages=BidPackage::where('isActive',1)->get();
        return view('how-it-works', compact('bidPackages'));
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

        // Initialize time variables
        $timeProgress = 0;
        $timeLeft = '';
        $now = now();

        // Calculate time remaining and auction status
        if ($auction->endTime && $auction->startTime) {
            // Calculate final end time dynamically:
            // Count how many bids were placed after original endTime
            // Each bid after endTime extends the auction by extensionTime seconds
            $extensionSeconds = $auction->extensionTime ?? 0;
            $originalEndTime = $auction->endTime;

            // Count bids placed after original endTime
            $bidsAfterEndTime = $auction->bids()
                ->where('created_at', '>=', $originalEndTime)
                ->count();

            // Calculate final end time: endTime + (extensionTime * bidsAfterEndTime)
            $finalEndTime = $originalEndTime->copy()->addSeconds($extensionSeconds * $bidsAfterEndTime);

            // Calculate seconds remaining to finalEndTime (for countdown display)
            $secondsToFinalEndTime = max(0, $finalEndTime->diffInSeconds($now, false));

            // Check if auction has ended:
            // The auction ends when current time >= finalEndTime
            if ($now >= $finalEndTime) {
                // AUCTION ENDED
                $timeProgress = 100;
                $timeLeft = 'ENDED';

                // Update auction status to ended
                if ($auction->status !== 'ended') {
                    $auction->status = 'ended';
                    $auction->save();
                }

                // Auto-assign winner if not set
                if (!$auction->winner_id && $auction->bids->count() > 0) {
                    $latestBid = $auction->bids->first();
                    $auction->winner_id = $latestBid->user_id;
                    $auction->save();
                }
            } elseif ($now < $auction->startTime) {
                // UPCOMING - Auction hasn't started yet
                $timeProgress = 0;
                $secondsUntilStart = $now->diffInSeconds($auction->startTime);
                $timeLeft = 'Starts in ' . $this->formatTimeRemaining($secondsUntilStart);

                if ($auction->status !== 'upcoming') {
                    $auction->status = 'upcoming';
                    $auction->save();
                }
            } else {
                // ACTIVE - Auction is running
                // Calculate progress bar (based on original duration)
                $totalDuration = $auction->startTime->diffInSeconds($originalEndTime);
                $elapsed = $auction->startTime->diffInSeconds($now);

                if ($totalDuration > 0) {
                    $timeProgress = min(100, max(0, ($elapsed / $totalDuration) * 100));
                }

                // Show countdown to finalEndTime
                $timeLeft = $this->formatTimeRemaining($secondsToFinalEndTime);

                // Update auction status to active
                if ($auction->status !== 'active' && $auction->status !== 'ended') {
                    $auction->status = 'active';
                    $auction->save();
                }
            }
        }

        // Calculate savings percentage
        $savingsPercentage = 0;
        if ($auction->retailPrice > 0 && $auction->currentPrice > 0) {
            $savingsPercentage = 100 - (($auction->currentPrice / $auction->retailPrice) * 100);
        }

        // Get similar auctions (active, ending soon, featured, upcoming) - from all categories
        // Exclude ended auctions (status = 'ended' OR endTime < now)
        $now = now();
        $similarAuctions = Auction::where('id', '!=', $auction->id)
            ->where('status', '!=', 'ended')  // Exclude ended status
            ->where('endTime', '>', $now)  // Exclude auctions past end time
            ->where(function($query) use ($now) {
                // Include: Active, Featured, or Upcoming auctions
                $query->where('status', 'active')
                      ->orWhere('featured', true)
                      ->orWhere('status', 'upcoming');
            })
            ->orderByRaw("CASE
                WHEN status = 'active' AND endTime > NOW() AND endTime <= DATE_ADD(NOW(), INTERVAL 24 HOUR) THEN 0
                WHEN status = 'active' AND endTime > NOW() THEN 1
                WHEN featured = 1 THEN 2
                WHEN status = 'upcoming' THEN 3
                ELSE 4
            END")
            ->orderBy('endTime', 'ASC')
            ->take(4)
            ->get();

        // Get auto-bid settings for current user (if authenticated)
        $autoBid = null;
        if (Auth::check()) {
            $autoBid = AutoBid::where('user_id', Auth::id())
                ->where('auction_id', $auction->id)
                ->where('is_active', true)
                ->first();
        }

        return view('auction-detail', compact(
            'auction',
            'totalBids',
            'recentBidders',
            'timeProgress',
            'timeLeft',
            'savingsPercentage',
            'similarAuctions',
            'autoBid',
            'bidsAfterEndTime',
            'finalEndTime'
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
                'is_auto_bid' => 'sometimes|boolean',
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
            if ($auction->status == 'ended') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This auction is ended.'
                ], 422);
            }

            if ($auction->status == 'upcoming') {
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

            // Check if this is an auto-bid and decrement auto-bid count
            $isAutoBid = $request->input('is_auto_bid', false);
            $autoBid = null;

            if ($isAutoBid) {
                $autoBid = AutoBid::where('user_id', $user->id)
                    ->where('auction_id', $auction->id)
                    ->where('is_active', true)
                    ->first();

                if (!$autoBid || $autoBid->bids_left <= 0) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'No active auto-bid or bids remaining.'
                    ], 403);
                }
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

                // DO NOT modify endTime in database
                // The final end time will be calculated dynamically as:
                // finalEndTime = endTime + (extensionTime * number_of_bids_after_endTime)
                // This keeps endTime constant and makes calculations predictable

                $auction->save();

                // Create bid record
                $bid = new Bid();
                $bid->user_id = $user->id;
                $bid->auction_id = $auction->id;
                $bid->amount = $newPrice;
                $bid->save();

                // If this was an auto-bid, decrement the bids_left count
                if ($isAutoBid && $autoBid) {
                    $autoBid->bids_left -= 1;

                    // Deactivate auto-bid if no bids left
                    if ($autoBid->bids_left <= 0) {
                        $autoBid->is_active = false;
                    }

                    $autoBid->save();
                }

                // Commit the transaction
                DB::commit();

                // Return success response
                return response()->json([
                    'status' => 'success',
                    'message' => 'Bid placed successfully',
                    'data' => [
                        'auction' => $auction,
                        'bid' => $bid,
                        'userBidCredits' => $user->bid_balance,
                        'autoBid' => $autoBid ? [
                            'bids_left' => $autoBid->bids_left,
                            'is_active' => $autoBid->is_active
                        ] : null
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

    /**
     * Get recent bids for an auction (AJAX endpoint for DataTables)
     *
     * @param  string  $auctionId
     * @return \Illuminate\Http\JsonResponse
     */

    public function getRecentBids($auctionId)
    {
        try {
            // Find the auction
            $auction = Auction::findOrFail($auctionId);

            // Get recent bids with user information
            $bids = Bid::where('auction_id', $auctionId)
                ->with('user')
                ->orderByDesc('amount')
                ->limit(50) // Limit to last 50 bids
                ->get()
                ->map(function($bid, $index) {
                    // Mask bidder name like in original format: J*****n
                    $maskedName = 'Anonymous';
                    if ($bid->user && $bid->user->name) {
                        $name = $bid->user->name;
                        $maskedName = substr($name, 0, 1) . '*****' . substr($name, -1);
                    }

                    return [
                            'index' => $index + 1,
                            'bidder' => $maskedName,
                            'amount' => 'AED ' . number_format($bid->amount, 2),
                            'raw_amount' => $bid->amount, // Add raw amount for sorting
                            'time_ago' => $bid->created_at->diffForHumans(),
                        ];
                    });

            return response()->json([
                'success' => true,
                'data' => $bids,
                'total' => $bids->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch bids',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get auto-bid status for current user on an auction
     */
    public function getAutoBidStatus($auctionId)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Get auction
            $auction = Auction::findOrFail($auctionId);

            // Get latest bid
            $latestBid = Bid::where('auction_id', $auctionId)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->first();

            // Get current user
            $user = Auth::user();

            // Get auto-bid for current user
            $autoBid = AutoBid::where('user_id', Auth::id())
                ->where('auction_id', $auctionId)
                ->where('is_active', true)
                ->first();

            $response = [
                'success' => true,
                'auction_status' => $auction->status,
                'has_auto_bid' => $autoBid ? true : false,
                'latest_bid_user_id' => $latestBid ? $latestBid->user_id : null,
                'current_user_id' => Auth::id(),
                'user_bid_balance' => $user->bid_balance,
                'should_auto_bid' => false,
            ];

            if ($autoBid) {
                $response['auto_bid'] = [
                    'max_bids' => $autoBid->max_bids,
                    'bids_left' => $autoBid->bids_left,
                    'is_active' => $autoBid->is_active,
                ];

                // Determine if auto-bid should be triggered
                // Check: active, bids left, auction active, last bidder is not current user, AND user has bid balance
                $response['should_auto_bid'] =
                    $autoBid->is_active &&
                    $autoBid->bids_left > 0 &&
                    $user->bid_balance > 0 &&
                    $auction->status === 'active' &&
                    ($latestBid && $latestBid->user_id !== Auth::id());

                // Deactivate auto-bid if user has no bid balance
                if ($user->bid_balance <= 0 && $autoBid->is_active) {
                    $autoBid->is_active = false;
                    $autoBid->save();
                    $response['auto_bid']['is_active'] = false;
                    $response['has_auto_bid'] = false;
                }
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch auto-bid status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
