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
        Schema::table('bid_package_histories', function (Blueprint $table) {
            $table->string('stripe_session_id')->nullable()->after('description');
            $table->index('stripe_session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bid_package_histories', function (Blueprint $table) {
            $table->dropIndex(['stripe_session_id']);
            $table->dropColumn('stripe_session_id');
        });
    }
};
