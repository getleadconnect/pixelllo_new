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
        // First, drop the existing tables if needed
        Schema::dropIfExists('bids');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('auctions');
        Schema::dropIfExists('categories');

        // Create categories table first
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('slug')->unique();
            $table->uuid('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('categories')->nullOnDelete();
            $table->timestamps();
        });

        // Create auctions table with proper foreign key to categories
        Schema::create('auctions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description');
            $table->decimal('startingPrice', 10, 2);
            $table->decimal('currentPrice', 10, 2);
            $table->decimal('bidIncrement', 10, 2);
            $table->decimal('retailPrice', 10, 2);
            $table->json('images')->nullable();
            $table->foreignUuid('category_id')->constrained();
            $table->enum('status', ['upcoming', 'active', 'ended', 'cancelled'])->default('upcoming');
            $table->dateTime('startTime');
            $table->dateTime('endTime');
            $table->integer('extensionTime')->default(30); // extension time in seconds
            $table->boolean('featured')->default(false);
            $table->timestamps();
        });

        // Create bids table with proper foreign keys
        Schema::create('bids', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('auction_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->boolean('autobid')->default(false);
            $table->timestamps();
        });

        // Create orders table with proper foreign keys
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('auction_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->json('shippingAddress');
            $table->json('paymentMethod');
            $table->string('trackingNumber')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables in reverse order to avoid foreign key constraints
        Schema::dropIfExists('orders');
        Schema::dropIfExists('bids');
        Schema::dropIfExists('auctions');
        Schema::dropIfExists('categories');
    }
};
