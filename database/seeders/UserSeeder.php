<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@pixelllo.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
                'phone' => '+1 (555) 123-4567',
                'country' => 'US',
                'address' => '123 Admin St, San Francisco, CA 94107',
                'bid_balance' => 500,
                'role' => 'admin',
                'active' => true,
                'notification_preferences' => json_encode([
                    'outbid_notification' => true,
                    'ending_notification' => true,
                    'new_notification' => true,
                    'order_notification' => true,
                    'promo_notification' => false,
                ]),
                'email_verified_at' => now(),
            ]
        );

        // Create Customer User
        User::updateOrCreate(
            ['email' => 'customer@pixelllo.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('customer123'),
                'phone' => '+1 (555) 987-6543',
                'country' => 'US',
                'address' => '456 Customer Ave, San Francisco, CA 94107',
                'bid_balance' => 250,
                'role' => 'customer',
                'active' => true,
                'notification_preferences' => json_encode([
                    'outbid_notification' => true,
                    'ending_notification' => true,
                    'new_notification' => true,
                    'order_notification' => true,
                    'promo_notification' => true,
                ]),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Users created successfully!');
        $this->command->info('Admin user: admin@pixelllo.com / admin123');
        $this->command->info('Customer user: customer@pixelllo.com / customer123');
    }
}