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
        Schema::table('orders', function (Blueprint $table) {
            // Check if orders table exists, if not create it
            if (!Schema::hasTable('orders')) {
                Schema::create('orders', function (Blueprint $table) {
                    $table->uuid('id')->primary();
                    $table->uuid('user_id');
                    $table->uuid('auction_id');
                    $table->decimal('amount', 10, 2)->nullable(); // For backward compatibility
                    $table->string('shippingAddress')->nullable(); // For backward compatibility
                    $table->string('paymentMethod')->nullable(); // For backward compatibility
                    $table->string('trackingNumber')->nullable(); // For backward compatibility
                    $table->decimal('subtotal', 10, 2)->default(0);
                    $table->decimal('shipping_cost', 10, 2)->default(0);
                    $table->decimal('tax', 10, 2)->default(0);
                    $table->decimal('total', 10, 2)->default(0);
                    $table->string('status')->default('pending');
                    $table->text('notes')->nullable();
                    $table->json('shipping_address')->nullable();
                    $table->string('payment_method')->nullable();
                    $table->json('payment_details')->nullable();
                    $table->string('payment_status')->default('pending');
                    $table->string('transaction_id')->nullable();
                    $table->json('status_history')->nullable();
                    $table->timestamps();

                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                    $table->foreign('auction_id')->references('id')->on('auctions')->onDelete('cascade');
                });
                return;
            }

            // Add columns if table exists but columns don't
            if (!Schema::hasColumn('orders', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->default(0);
            }

            if (!Schema::hasColumn('orders', 'shipping_cost')) {
                $table->decimal('shipping_cost', 10, 2)->default(0);
            }

            if (!Schema::hasColumn('orders', 'tax')) {
                $table->decimal('tax', 10, 2)->default(0);
            }

            if (!Schema::hasColumn('orders', 'total')) {
                $table->decimal('total', 10, 2)->default(0);
            }

            if (!Schema::hasColumn('orders', 'status')) {
                $table->string('status')->default('pending');
            }

            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable();
            }

            if (!Schema::hasColumn('orders', 'shipping_address')) {
                $table->json('shipping_address')->nullable();
            }

            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }

            if (!Schema::hasColumn('orders', 'payment_details')) {
                $table->json('payment_details')->nullable();
            }

            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('pending');
            }

            if (!Schema::hasColumn('orders', 'transaction_id')) {
                $table->string('transaction_id')->nullable();
            }

            if (!Schema::hasColumn('orders', 'status_history')) {
                $table->json('status_history')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't want to drop these columns in the down method
        // as they're essential to the application
    }
};
