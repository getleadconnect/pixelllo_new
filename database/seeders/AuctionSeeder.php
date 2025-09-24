<?php

namespace Database\Seeders;

use App\Models\Auction;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AuctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get category IDs
        $electronics = Category::where('slug', 'electronics')->first();
        $homeKitchen = Category::where('slug', 'home-kitchen')->first();
        $fashion = Category::where('slug', 'fashion')->first();

        if (!$electronics || !$homeKitchen || !$fashion) {
            $this->command->error('Categories not found! Please seed categories first.');
            return;
        }

        // Create a test auction with the specific ID from the previous conversation
        Auction::create([
            'id' => '123e4567-e89b-12d3-a456-426614174001',
            'title' => 'Apple iPhone 14 Pro - 256GB',
            'description' => 'The latest iPhone model with 256GB storage. Features the dynamic island, always-on display, and a 48MP camera.',
            'startingPrice' => 0.01,
            'currentPrice' => 75.25,
            'bidIncrement' => 0.25,
            'retailPrice' => 1099.00,
            'images' => json_encode([
                'https://placehold.co/800x600/png?text=iPhone+14+Pro+Front',
                'https://placehold.co/800x600/png?text=iPhone+14+Pro+Back',
                'https://placehold.co/800x600/png?text=iPhone+14+Pro+Side',
            ]),
            'category_id' => $electronics->id,
            'status' => 'active',
            'startTime' => Carbon::now()->subDays(3),
            'endTime' => Carbon::now()->addDays(1),
            'extensionTime' => 10,
            'featured' => true,
        ]);

        // Create a few more auctions
        Auction::create([
            'title' => 'Samsung 65" QLED 4K Smart TV',
            'description' => 'Stunning 4K resolution with Quantum Dot technology for vibrant colors and deep blacks.',
            'startingPrice' => 0.01,
            'currentPrice' => 120.50,
            'bidIncrement' => 0.50,
            'retailPrice' => 1299.99,
            'images' => json_encode([
                'https://placehold.co/800x600/png?text=Samsung+TV+Front',
                'https://placehold.co/800x600/png?text=Samsung+TV+Side',
            ]),
            'category_id' => $electronics->id,
            'status' => 'active',
            'startTime' => Carbon::now()->subDays(2),
            'endTime' => Carbon::now()->addDays(2),
            'extensionTime' => 10,
            'featured' => true,
        ]);

        Auction::create([
            'title' => 'KitchenAid Stand Mixer - Professional Series',
            'description' => 'Professional 5 Plus Series 5 Quart Bowl-Lift Stand Mixer with premium accessories.',
            'startingPrice' => 0.01,
            'currentPrice' => 45.75,
            'bidIncrement' => 0.25,
            'retailPrice' => 449.99,
            'images' => json_encode([
                'https://placehold.co/800x600/png?text=KitchenAid+Mixer',
            ]),
            'category_id' => $homeKitchen->id,
            'status' => 'active',
            'startTime' => Carbon::now()->subDays(1),
            'endTime' => Carbon::now()->addDays(3),
            'extensionTime' => 10,
            'featured' => false,
        ]);

        $this->command->info('Auctions created successfully!');
    }
}