<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin user already exists
        $adminUser = User::where('email', 'admin@pixelllo.com')->first();
        
        if (!$adminUser) {
            // Create new admin user
            $adminUser = User::create([
                'name' => 'Admin User',
                'email' => 'admin@pixelllo.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'active' => true,
                'bid_balance' => 0,
            ]);
            
            echo "✅ Admin user created successfully!\n";
            echo "📧 Email: admin@pixelllo.com\n";
            echo "🔑 Password: admin123\n";
        } else {
            // Update existing admin user
            $adminUser->update([
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'active' => true,
            ]);
            
            echo "✅ Admin user updated successfully!\n";
            echo "📧 Email: {$adminUser->email}\n";
            echo "🔑 Password: admin123\n";
        }
        
        // Also create a backup admin if needed
        $backupAdmin = User::where('email', 'akhil@pixelllo.com')->first();
        
        if (!$backupAdmin) {
            User::create([
                'name' => 'Akhil Admin',
                'email' => 'akhil@pixelllo.com',
                'password' => Hash::make('akhil123'),
                'role' => 'admin',
                'active' => true,
                'bid_balance' => 0,
            ]);
            
            echo "✅ Backup admin user created!\n";
            echo "📧 Email: akhil@pixelllo.com\n";
            echo "🔑 Password: akhil123\n";
        }
    }
}