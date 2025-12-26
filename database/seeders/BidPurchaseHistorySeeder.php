<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BidPurchaseHistory;
use App\Models\User;
use Carbon\Carbon;

class BidPurchaseHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users (create if none exist)
        $users = User::take(5)->get();
        
        if ($users->count() === 0) {
            // Create some test users if none exist
            $users = collect([
                User::create([
                    'name' => 'John Doe',
                    'email' => 'john@example.com',
                    'password' => bcrypt('password123'),
                    'bid_balance' => 250,
                    'role' => 'customer',
                    'active' => true,
                ]),
                User::create([
                    'name' => 'Jane Smith',
                    'email' => 'jane@example.com',
                    'password' => bcrypt('password123'),
                    'bid_balance' => 180,
                    'role' => 'customer',
                    'active' => true,
                ]),
                User::create([
                    'name' => 'Mike Johnson',
                    'email' => 'mike@example.com',
                    'password' => bcrypt('password123'),
                    'bid_balance' => 450,
                    'role' => 'customer',
                    'active' => true,
                ]),
            ]);
        }

        // Sample bid package purchase data
        $purchaseData = [
            [
                'user_id' => $users[0]->id,
                'bid_amount' => 50,
                'bid_price' => 25.00,
                'description' => 'Starter Package - 50 Bid Credits',
                'stripe_session_id' => 'cs_test_a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0',
                'stripe_transaction_id' => 'ch_3OlxR7F8mNKWJ2Vb0xY5oW1z',
                'created_at' => Carbon::now()->subDays(15),
            ],
            [
                'user_id' => $users[0]->id,
                'bid_amount' => 100,
                'bid_price' => 45.00,
                'description' => 'Popular Package - 100 Bid Credits',
                'stripe_session_id' => 'cs_test_b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1',
                'stripe_transaction_id' => 'ch_3OlxR8G9oNLXK3Wc1zZ6pX2a',
                'created_at' => Carbon::now()->subDays(8),
            ],
            [
                'user_id' => $users[1]->id,
                'bid_amount' => 25,
                'bid_price' => 15.00,
                'description' => 'Mini Package - 25 Bid Credits',
                'stripe_session_id' => 'cs_test_c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2',
                'stripe_transaction_id' => 'ch_3OlxR9H0pOMYL4Xd2aA7qY3b',
                'created_at' => Carbon::now()->subDays(12),
            ],
            [
                'user_id' => $users[1]->id,
                'bid_amount' => 200,
                'bid_price' => 85.00,
                'description' => 'Premium Package - 200 Bid Credits',
                'stripe_session_id' => 'cs_test_d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3',
                'stripe_transaction_id' => 'ch_3OlxRAI1qPNZM5Ye3bB8rZ4c',
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'user_id' => count($users) > 2 ? $users[2]->id : $users[0]->id,
                'bid_amount' => 500,
                'bid_price' => 199.00,
                'description' => 'Ultimate Package - 500 Bid Credits',
                'stripe_session_id' => 'cs_test_e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4',
                'stripe_transaction_id' => 'ch_3OlxRBJ2rQOAN6Zf4cC9sA5d',
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'user_id' => $users[0]->id,
                'bid_amount' => 75,
                'bid_price' => 32.50,
                'description' => 'Medium Package - 75 Bid Credits',
                'stripe_session_id' => 'cs_test_f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5',
                'stripe_transaction_id' => 'ch_3OlxRCK3sRPBO7Ag5dD0tB6e',
                'created_at' => Carbon::now()->subDays(1),
            ],
            [
                'user_id' => count($users) > 2 ? $users[2]->id : $users[1]->id,
                'bid_amount' => 150,
                'bid_price' => 62.50,
                'description' => 'Value Package - 150 Bid Credits',
                'stripe_session_id' => 'cs_test_g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6',
                'stripe_transaction_id' => 'ch_3OlxRDL4tSQCP8Bh6eE1uC7f',
                'created_at' => Carbon::now()->subHours(6),
            ],
        ];

        foreach ($purchaseData as $data) {
            BidPurchaseHistory::create($data);
        }

        $this->command->info('Created ' . count($purchaseData) . ' bid purchase history records.');
    }
}
