<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'role' => 'admin',
            'active' => true,
            'bid_balance' => 1000,
            'country' => 'US',
            'notification_preferences' => json_encode([
                'outbid_notification' => true,
                'ending_notification' => true,
                'new_notification' => true,
                'order_notification' => true,
                'promo_notification' => true,
            ]),
        ]);
    }
}
