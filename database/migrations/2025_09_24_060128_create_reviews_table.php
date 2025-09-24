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
        Schema::create('reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->uuid('auction_id');
            $table->uuid('order_id')->nullable();
            $table->integer('rating'); // 1-5 stars
            $table->string('title')->nullable();
            $table->text('comment');
            $table->json('images')->nullable(); // For review images
            $table->boolean('is_verified')->default(true); // Verified purchase
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('auction_id');
            $table->index('order_id');
            $table->unique(['user_id', 'auction_id']); // One review per auction per user

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('auction_id')->references('id')->on('auctions')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};