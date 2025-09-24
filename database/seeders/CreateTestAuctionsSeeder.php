<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateTestAuctionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make sure we have a test category
        $category = \App\Models\Category::firstOrCreate([
            'name' => 'Electronics'
        ], [
            'description' => 'Electronic devices and gadgets',
            'slug' => 'electronics',
        ]);

        // Create 10 test auctions
        for ($i = 1; $i <= 10; $i++) {
            \App\Models\Auction::create([
                'title' => 'Test Auction ' . $i,
                'description' => 'This is a test auction description for item ' . $i,
                'startingPrice' => rand(5, 50) * 10,
                'currentPrice' => rand(5, 100) * 10,
                'bidIncrement' => 5,
                'retailPrice' => rand(100, 500) * 10,
                'category_id' => $category->id,
                'status' => ['upcoming', 'active', 'ended'][rand(0, 2)],
                'startTime' => now()->subDays(rand(1, 5)),
                'endTime' => now()->addDays(rand(1, 10)),
                'extensionTime' => 30,
                'featured' => (bool)rand(0, 1),
                'images' => json_encode([]),
            ]);
        }
    }
}
