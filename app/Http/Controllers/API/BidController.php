<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\AutoBid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BidController extends Controller
{
    /**
     * Display a listing of the user's bids.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->input('per_page', 10);

        $bids = Bid::with(['auction.category'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $bids
        ]);
    }

    /**
     * Display the specified bid.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();

        $bid = Bid::with(['auction.category'])
            ->where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => $bid
        ]);
    }

    /**
     * Set up auto-bidding for an auction.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function autobid(Request $request)
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
}
