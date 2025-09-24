<?php

namespace App\Console\Commands;

use App\Models\AutoBid;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessAutoBids extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bids:process-auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process automated bids for all active auctions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting auto-bid processing...');
        
        // Get active auctions
        $activeAuctions = Auction::where('status', 'active')
            ->where('endTime', '>', now())
            ->get();
            
        $this->info("Found {$activeAuctions->count()} active auctions");
        
        foreach ($activeAuctions as $auction) {
            $this->info("Processing auto-bids for auction: {$auction->title} (ID: {$auction->id})");
            
            // Get active auto-bids for this auction with bids remaining
            $autoBids = AutoBid::where('auction_id', $auction->id)
                ->where('is_active', true)
                ->where('bids_left', '>', 0)
                ->with('user')
                ->get();
                
            $this->info("Found {$autoBids->count()} active auto-bidders");
            
            if ($autoBids->isEmpty()) {
                $this->info("No active auto-bidders for this auction, skipping");
                continue;
            }
            
            // Get the last bid (if any)
            $lastBid = $auction->bids()->latest()->first();
            $lastBidUserId = $lastBid ? $lastBid->user_id : null;
            
            foreach ($autoBids as $autoBid) {
                $user = $autoBid->user;
                
                // Skip if this user already has the winning bid
                if ($user->id === $lastBidUserId) {
                    $this->info("User {$user->id} already has the last bid, skipping");
                    continue;
                }
                
                // Skip if user doesn't have enough bid balance
                if ($user->bid_balance <= 0) {
                    $this->info("User {$user->id} has insufficient bid balance, deactivating auto-bid");
                    $autoBid->is_active = false;
                    $autoBid->save();
                    continue;
                }
                
                // Place a bid on behalf of this user
                $this->info("Placing auto-bid for user {$user->id}");
                
                try {
                    DB::beginTransaction();
                    
                    // Decrement user's bid balance
                    $user->bid_balance -= 1;
                    $user->save();
                    
                    // Decrement auto-bid count
                    $autoBid->bids_left -= 1;
                    if ($autoBid->bids_left <= 0) {
                        $autoBid->is_active = false;
                    }
                    $autoBid->save();
                    
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
                    $bid->autobid = true;
                    $bid->save();
                    
                    DB::commit();
                    
                    $this->info("Auto-bid placed successfully for user {$user->id}, new price: \${$newPrice}");
                    
                    // Only process one auto-bid per auction per run to avoid rapid bidding
                    break;
                    
                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->error("Error processing auto-bid for user {$user->id}: {$e->getMessage()}");
                    Log::error("Auto-bid error: {$e->getMessage()}", [
                        'user_id' => $user->id,
                        'auction_id' => $auction->id,
                        'exception' => $e,
                    ]);
                }
            }
        }
        
        $this->info('Auto-bid processing completed');
        return 0;
    }
}