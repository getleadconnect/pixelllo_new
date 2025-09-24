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
        Schema::create('auctions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description');
            $table->decimal('startingPrice', 10, 2);
            $table->decimal('currentPrice', 10, 2);
            $table->decimal('bidIncrement', 10, 2);
            $table->decimal('retailPrice', 10, 2);
            $table->json('images')->nullable();
            $table->foreignUuid('category_id')->constrained('categories');
            $table->enum('status', ['upcoming', 'active', 'ended', 'cancelled'])->default('upcoming');
            $table->dateTime('startTime');
            $table->dateTime('endTime');
            $table->integer('extensionTime')->default(30); // extension time in seconds
            $table->boolean('featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};
