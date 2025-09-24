<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'auction_id',
        'amount', // For backward compatibility
        'subtotal',
        'shipping_cost',
        'tax',
        'total',
        'status',
        'notes',
        'shippingAddress',
        'paymentMethod',
        'payment_details',
        'payment_status',
        'transaction_id',
        'trackingNumber',
        'status_history',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'amount' => 'decimal:2',
        'shippingAddress' => 'json',
        'paymentMethod' => 'json',
        'payment_details' => 'json',
        'status_history' => 'json',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the auction that owns the order.
     */
    public function auction(): BelongsTo
    {
        return $this->belongsTo(Auction::class);
    }
}
