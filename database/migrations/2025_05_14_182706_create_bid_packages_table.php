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
        Schema::create('bid_packages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->integer('bidAmount');
            $table->decimal('price', 10, 2);
            $table->string('description')->nullable();
            $table->boolean('isActive')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bid_packages');
    }
};
