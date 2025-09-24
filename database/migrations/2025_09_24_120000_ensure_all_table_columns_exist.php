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
        // Ensure all columns exist in users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'country')) {
                $table->string('country')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('country');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('address');
            }
            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            if (!Schema::hasColumn('users', 'bid_balance')) {
                $table->integer('bid_balance')->default(0)->after('city');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('customer')->after('bid_balance');
            }
            if (!Schema::hasColumn('users', 'active')) {
                $table->boolean('active')->default(true)->after('role');
            }
            if (!Schema::hasColumn('users', 'notification_preferences')) {
                $table->json('notification_preferences')->nullable()->after('active');
            }
        });

        // Ensure all columns exist in categories table
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'image')) {
                $table->string('image')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('categories', 'featured')) {
                $table->boolean('featured')->default(false)->after('image');
            }
            if (!Schema::hasColumn('categories', 'parent_id')) {
                $table->uuid('parent_id')->nullable()->after('featured');
                $table->foreign('parent_id')->references('id')->on('categories')->nullOnDelete();
            }
        });

        // Ensure all columns exist in auctions table
        Schema::table('auctions', function (Blueprint $table) {
            if (!Schema::hasColumn('auctions', 'title')) {
                $table->string('title');
            }
            if (!Schema::hasColumn('auctions', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('auctions', 'startingPrice')) {
                $table->decimal('startingPrice', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('auctions', 'currentPrice')) {
                $table->decimal('currentPrice', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('auctions', 'bidIncrement')) {
                $table->decimal('bidIncrement', 10, 2)->default(0.01);
            }
            if (!Schema::hasColumn('auctions', 'retailPrice')) {
                $table->decimal('retailPrice', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('auctions', 'images')) {
                $table->json('images')->nullable();
            }
            if (!Schema::hasColumn('auctions', 'category_id')) {
                $table->uuid('category_id')->nullable();
                $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
            }
            if (!Schema::hasColumn('auctions', 'winner_id')) {
                $table->uuid('winner_id')->nullable();
                $table->foreign('winner_id')->references('id')->on('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('auctions', 'status')) {
                $table->string('status')->default('upcoming');
            }
            if (!Schema::hasColumn('auctions', 'startTime')) {
                $table->timestamp('startTime')->nullable();
            }
            if (!Schema::hasColumn('auctions', 'endTime')) {
                $table->timestamp('endTime')->nullable();
            }
            if (!Schema::hasColumn('auctions', 'extensionTime')) {
                $table->integer('extensionTime')->default(10);
            }
            if (!Schema::hasColumn('auctions', 'featured')) {
                $table->boolean('featured')->default(false);
            }
        });

        // Ensure all columns exist in orders table
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'user_id')) {
                $table->uuid('user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('orders', 'auction_id')) {
                $table->uuid('auction_id');
                $table->foreign('auction_id')->references('id')->on('auctions')->onDelete('cascade');
            }
            if (!Schema::hasColumn('orders', 'amount')) {
                $table->decimal('amount', 10, 2)->nullable();
            }
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
            if (!Schema::hasColumn('orders', 'shippingAddress')) {
                $table->text('shippingAddress')->nullable();
            }
            if (!Schema::hasColumn('orders', 'paymentMethod')) {
                $table->text('paymentMethod')->nullable();
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
            if (!Schema::hasColumn('orders', 'trackingNumber')) {
                $table->string('trackingNumber')->nullable();
            }
            if (!Schema::hasColumn('orders', 'status_history')) {
                $table->json('status_history')->nullable();
            }
        });

        // Ensure all columns exist in bids table
        Schema::table('bids', function (Blueprint $table) {
            if (!Schema::hasColumn('bids', 'user_id')) {
                $table->uuid('user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('bids', 'auction_id')) {
                $table->uuid('auction_id');
                $table->foreign('auction_id')->references('id')->on('auctions')->onDelete('cascade');
            }
            if (!Schema::hasColumn('bids', 'amount')) {
                $table->decimal('amount', 10, 2);
            }
            if (!Schema::hasColumn('bids', 'autobid')) {
                $table->boolean('autobid')->default(false);
            }
        });

        // Ensure all columns exist in bid_packages table
        Schema::table('bid_packages', function (Blueprint $table) {
            if (!Schema::hasColumn('bid_packages', 'name')) {
                $table->string('name');
            }
            if (!Schema::hasColumn('bid_packages', 'bidAmount')) {
                $table->integer('bidAmount');
            }
            if (!Schema::hasColumn('bid_packages', 'price')) {
                $table->decimal('price', 10, 2);
            }
            if (!Schema::hasColumn('bid_packages', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('bid_packages', 'isActive')) {
                $table->boolean('isActive')->default(true);
            }
        });

        // Ensure all columns exist in auto_bids table
        if (Schema::hasTable('auto_bids')) {
            Schema::table('auto_bids', function (Blueprint $table) {
                if (!Schema::hasColumn('auto_bids', 'user_id')) {
                    $table->uuid('user_id');
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                }
                if (!Schema::hasColumn('auto_bids', 'auction_id')) {
                    $table->uuid('auction_id');
                    $table->foreign('auction_id')->references('id')->on('auctions')->onDelete('cascade');
                }
                if (!Schema::hasColumn('auto_bids', 'max_bids')) {
                    $table->integer('max_bids');
                }
                if (!Schema::hasColumn('auto_bids', 'bids_left')) {
                    $table->integer('bids_left');
                }
                if (!Schema::hasColumn('auto_bids', 'is_active')) {
                    $table->boolean('is_active')->default(true);
                }
            });
        }

        // Ensure all columns exist in reviews table
        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                if (!Schema::hasColumn('reviews', 'user_id')) {
                    $table->uuid('user_id');
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                }
                if (!Schema::hasColumn('reviews', 'auction_id')) {
                    $table->uuid('auction_id')->nullable();
                    $table->foreign('auction_id')->references('id')->on('auctions')->nullOnDelete();
                }
                if (!Schema::hasColumn('reviews', 'order_id')) {
                    $table->uuid('order_id')->nullable();
                    $table->foreign('order_id')->references('id')->on('orders')->nullOnDelete();
                }
                if (!Schema::hasColumn('reviews', 'rating')) {
                    $table->tinyInteger('rating');
                }
                if (!Schema::hasColumn('reviews', 'title')) {
                    $table->string('title')->nullable();
                }
                if (!Schema::hasColumn('reviews', 'comment')) {
                    $table->text('comment')->nullable();
                }
                if (!Schema::hasColumn('reviews', 'images')) {
                    $table->json('images')->nullable();
                }
                if (!Schema::hasColumn('reviews', 'is_verified')) {
                    $table->boolean('is_verified')->default(false);
                }
                if (!Schema::hasColumn('reviews', 'is_published')) {
                    $table->boolean('is_published')->default(true);
                }
            });
        }

        // Ensure all columns exist in settings table
        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                if (!Schema::hasColumn('settings', 'key')) {
                    $table->string('key')->unique();
                }
                if (!Schema::hasColumn('settings', 'value')) {
                    $table->text('value')->nullable();
                }
                if (!Schema::hasColumn('settings', 'type')) {
                    $table->string('type')->default('string');
                }
                if (!Schema::hasColumn('settings', 'description')) {
                    $table->text('description')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't want to drop columns in the down method
        // as they're essential to the application
    }
};