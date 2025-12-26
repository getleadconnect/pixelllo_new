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
        Schema::table('bid_purchase_histories', function (Blueprint $table) {
            $table->string('stripe_transaction_id')->nullable()->after('stripe_session_id');
            $table->index('stripe_transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bid_purchase_histories', function (Blueprint $table) {
            $table->dropIndex(['stripe_transaction_id']);
            $table->dropColumn('stripe_transaction_id');
        });
    }
};
