<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email_verified_at');
            $table->string('country', 2)->default('US')->after('phone');
            $table->text('address')->nullable()->after('country');
            $table->integer('bid_balance')->default(0)->after('address');
            $table->enum('role', ['customer', 'admin'])->default('customer')->after('bid_balance');
            $table->json('notification_preferences')->nullable()->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'country',
                'address',
                'bid_balance',
                'role',
                'notification_preferences'
            ]);
        });
    }
};