<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuctionController extends Controller
{
    /**
     * Display a listing of the auctions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get query parameters for filtering
        $status = $request->input('status', 'active');
        $category = $request->input('category');
        $sort = $request->input('sort', 'ending-soon');
        $perPage = $request->input('per_page', 12);

        // Start building the query
        $query = Auction::query();

        // Apply status filter
        if ($status) {
            $query->where('status', $status);
        }

        // Apply category filter
        if ($category) {
            $query->whereHas('category', function($q) use ($category) {
                $q->where('id', $category)->orWhere('slug', $category);
            });
        }

        // Apply sorting
        switch ($sort) {
            case 'ending-soon':
                $query->where('status', 'active')->orderBy('endTime', 'asc');
                break;
            case 'price-low':
                $query->orderBy('currentPrice', 'asc');
                break;
            case 'price-high':
                $query->orderBy('currentPrice', 'desc');
                break;
            case 'recent':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
            default:
                $query->withCount('bids')->orderBy('bids_count', 'desc');
                break;
        }

        // Load relationships and get paginated results
        $auctions = $query->with(['category', 'bids'])
            ->paginate($perPage);

        // Return response
        return response()->json([
            'status' => 'success',
            'data' => $auctions
        ]);
    }

    /**
     * Display the specified auction.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Find the auction
        $auction = Auction::with(['category', 'bids.user'])
            ->findOrFail($id);

        // Calculate savings percentage
        $savingsPercentage = 0;
        if ($auction->retailPrice > 0 && $auction->currentPrice > 0) {
            $savingsPercentage = 100 - (($auction->currentPrice / $auction->retailPrice) * 100);
        }

        // Get time remaining if auction is active
        $timeLeft = null;
        $timeProgress = 0;

        if ($auction->status === 'active') {
            $now = now();
            $endTime = $auction->endTime;

            if ($now < $endTime) {
                $totalDuration = $auction->endTime->diffInSeconds($auction->startTime);
                $elapsed = $now->diffInSeconds($auction->startTime);
                $timeProgress = min(100, max(0, ($elapsed / $totalDuration) * 100));

                $diff = $now->diff($endTime);

                if ($diff->days > 0) {
                    $timeLeft = $diff->format('%ad %hh %im %ss');
                } else if ($diff->h > 0) {
                    $timeLeft = $diff->format('%hh %im %ss');
                } else if ($diff->i > 0) {
                    $timeLeft = $diff->format('%im %ss');
                } else {
                    $timeLeft = $diff->format('%ss');
                }
            } else {
                $timeLeft = 'Ended';
                $timeProgress = 100;
            }
        }

        // Get similar auctions
        $similarAuctions = Auction::where('category_id', $auction->category_id)
            ->where('id', '!=', $auction->id)
            ->where('status', 'active')
            ->take(4)
            ->get();

        // Return response
        return response()->json([
            'status' => 'success',
            'data' => [
                'auction' => $auction,
                'savingsPercentage' => $savingsPercentage,
                'timeLeft' => $timeLeft,
                'timeProgress' => $timeProgress,
                'similarAuctions' => $similarAuctions
            ]
        ]);
    }

    /**
     * Place a bid on an auction.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function placeBid(Request $request, $id)
    {
        try {
            // Get the authenticated user
            $user = Auth::user();

            // Find the auction
            $auction = Auction::findOrFail($id);

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
     * Get featured auctions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function featured(Request $request)
    {
        $limit = $request->input('limit', 6);

        $auctions = Auction::where('status', 'active')
            ->where('featured', true)
            ->with(['category', 'bids'])
            ->take($limit)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $auctions
        ]);
    }

    /**
     * Get auctions ending soon.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function endingSoon(Request $request)
    {
        $limit = $request->input('limit', 6);

        $auctions = Auction::where('status', 'active')
            ->where('endTime', '>', now())
            ->orderBy('endTime', 'asc')
            ->with(['category', 'bids'])
            ->take($limit)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $auctions
        ]);
    }

    /**
     * Get recently ended auctions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function recentlyEnded(Request $request)
    {
        $limit = $request->input('limit', 6);

        $auctions = Auction::where('status', 'ended')
            ->orderBy('endTime', 'desc')
            ->with(['category', 'bids.user'])
            ->take($limit)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $auctions
        ]);
    }
}
