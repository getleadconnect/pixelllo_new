<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateTestBidsAndOrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user if it doesn't exist
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Test Customer',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'customer',
                'active' => true,
                'bid_balance' => 500,
                'country' => 'US',
                'notification_preferences' => json_encode([
                    'outbid_notification' => true,
                    'ending_notification' => true,
                    'new_notification' => true,
                    'order_notification' => true,
                    'promo_notification' => true,
                ]),
            ]
        );

        // Get some auctions
        $auctions = \App\Models\Auction::limit(5)->get();

        if ($auctions->isEmpty()) {
            echo "No auctions found. Please run CreateTestAuctionsSeeder first.\n";
            return;
        }

        // Create bids for each auction
        foreach ($auctions as $auction) {
            $bidCount = rand(5, 15);
            $currentPrice = $auction->startingPrice;

            for ($i = 0; $i < $bidCount; $i++) {
                $currentPrice += $auction->bidIncrement;

                \App\Models\Bid::create([
                    'user_id' => $user->id,
                    'auction_id' => $auction->id,
                    'amount' => $currentPrice,
                    'created_at' => now()->subMinutes(rand(10, 1000)),
                    'updated_at' => now(),
                ]);
            }

            // Update the auction's current price
            $auction->currentPrice = $currentPrice;
            $auction->save();

            // Randomly assign a winner to ended auctions
            if ($auction->status === 'ended') {
                $auction->winner_id = $user->id;
                $auction->save();

                // Create an order for this auction
                \App\Models\Order::create([
                    'user_id' => $user->id,
                    'auction_id' => $auction->id,
                    'amount' => $auction->currentPrice, // For compatibility with old model
                    'subtotal' => $auction->currentPrice,
                    'shipping_cost' => rand(5, 20),
                    'tax' => $auction->currentPrice * 0.1,
                    'total' => $auction->currentPrice + rand(5, 20) + ($auction->currentPrice * 0.1),
                    'status' => ['pending', 'processing', 'shipped', 'delivered'][rand(0, 3)],
                    'shippingAddress' => json_encode([
                        'name' => 'Test Customer',
                        'line1' => '123 Test St',
                        'city' => 'Test City',
                        'state' => 'TS',
                        'zip' => '12345',
                        'country' => 'US',
                    ]),
                    'shipping_address' => json_encode([
                        'name' => 'Test Customer',
                        'line1' => '123 Test St',
                        'city' => 'Test City',
                        'state' => 'TS',
                        'zip' => '12345',
                        'country' => 'US',
                    ]),
                    'paymentMethod' => 'card',
                    'payment_method' => 'card',
                    'payment_status' => 'paid',
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
