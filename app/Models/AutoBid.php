<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoBid extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'auction_id',
        'max_bids',
        'bids_left',
        'is_active',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    /**
     * Get the user that owns the auto bid.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the auction that the auto bid is for.
     */
    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }
}