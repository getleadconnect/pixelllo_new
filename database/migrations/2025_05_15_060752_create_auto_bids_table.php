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
        Schema::create('auto_bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('auction_id')->constrained()->onDelete('cascade');
            $table->integer('max_bids')->default(10);
            $table->integer('bids_left')->default(10);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Ensure a user can only have one active auto-bid per auction
            $table->unique(['user_id', 'auction_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auto_bids');
    }
};
