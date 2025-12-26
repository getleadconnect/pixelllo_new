<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BidPurchaseHistory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bid_purchase_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'bid_amount',
        'bid_price',
        'description',
        'stripe_session_id',
        'stripe_transaction_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'bid_amount' => 'integer',
        'bid_price' => 'decimal:2',
    ];

    /**
     * Get the user that owns the bid package history.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
