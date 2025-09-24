<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'description' => 'Electronic devices and gadgets',
        ]);

        Category::create([
            'name' => 'Home & Kitchen',
            'slug' => 'home-kitchen',
            'description' => 'Items for your home and kitchen',
        ]);

        Category::create([
            'name' => 'Fashion',
            'slug' => 'fashion',
            'description' => 'Clothing, shoes, and accessories',
        ]);

        $this->command->info('Categories created successfully!');
    }
}