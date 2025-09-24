<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Skip this migration for MySQL/MariaDB
        // Row Level Security is PostgreSQL-specific
        if (DB::connection()->getDriverName() !== 'pgsql') {
            return;
        }

        // List of all tables that need RLS enabled
        $tables = [
            'auctions',
            'auto_bids',
            'bids',
            'cache',
            'cache_locks',
            'categories',
            'failed_jobs',
            'job_batches',
            'jobs',
            'migrations',
            'orders',
            'password_reset_tokens',
            'personal_access_tokens',
            'sessions',
            'settings',
            'user_watchlist',
            'users'
        ];

        // Enable RLS on all tables
        foreach ($tables as $table) {
            DB::statement("ALTER TABLE {$table} ENABLE ROW LEVEL SECURITY");
        }

        // Create a policy that allows the database owner full access
        // This ensures Laravel can still access all tables
        foreach ($tables as $table) {
            DB::statement("
                CREATE POLICY \"Enable all access for database owner\" ON {$table}
                FOR ALL
                USING (true)
                WITH CHECK (true)
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Skip this migration for MySQL/MariaDB
        // Row Level Security is PostgreSQL-specific
        if (DB::connection()->getDriverName() !== 'pgsql') {
            return;
        }

        // List of all tables
        $tables = [
            'auctions',
            'auto_bids',
            'bids',
            'cache',
            'cache_locks',
            'categories',
            'failed_jobs',
            'job_batches',
            'jobs',
            'migrations',
            'orders',
            'password_reset_tokens',
            'personal_access_tokens',
            'sessions',
            'settings',
            'user_watchlist',
            'users'
        ];

        // Drop policies and disable RLS
        foreach ($tables as $table) {
            DB::statement("DROP POLICY IF EXISTS \"Enable all access for database owner\" ON {$table}");
            DB::statement("ALTER TABLE {$table} DISABLE ROW LEVEL SECURITY");
        }
    }
};
