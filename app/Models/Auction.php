<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Auction extends Model
{
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'startingPrice',
        'currentPrice',
        'bidIncrement',
        'retailPrice',
        'images',
        'category_id',
        'winner_id',
        'status',
        'startTime',
        'endTime',
        'extensionTime',
        'featured',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'startingPrice' => 'decimal:2',
        'currentPrice' => 'decimal:2',
        'bidIncrement' => 'decimal:2',
        'retailPrice' => 'decimal:2',
        'images' => 'json',
        'startTime' => 'datetime',
        'endTime' => 'datetime',
        'extensionTime' => 'integer',
        'featured' => 'boolean',
    ];

    /**
     * Get the category that owns the auction.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user who won the auction.
     */
    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    /**
     * Get the bids for the auction.
     */
    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    /**
     * Get the order for the auction.
     */
    public function order(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the auto bids for the auction.
     */
    public function autoBids(): HasMany
    {
        return $this->hasMany(AutoBid::class);
    }

    /**
     * Get the images attribute with proper paths
     */
    public function getImagesAttribute($value)
    {
        // Handle if value is already an array (from cast)
        if (is_array($value)) {
            $images = $value;
        } else if (is_string($value)) {
            // Decode JSON string
            $images = json_decode($value, true);
            // If decode failed or result is not array, return empty array
            if (!is_array($images)) {
                return [];
            }
        } else {
            return [];
        }
        
        // Process each image path
        return array_map(function($image) {
            // Ensure image is a string
            if (!is_string($image)) {
                return '';
            }
            
            // If it's already a relative path starting with 'auctions/', return as is
            if (strpos($image, 'auctions/') === 0) {
                return $image;
            }
            
            // If it contains the full server path, extract just the relative part
            if (strpos($image, '/var/www/html/pixello/storage/app/public/') !== false) {
                // Extract everything after 'public/'
                $parts = explode('/var/www/html/pixello/storage/app/public/', $image);
                return end($parts);
            }
            
            // If it contains just /auctions/, remove leading slash
            if (strpos($image, '/auctions/') === 0) {
                return substr($image, 1);
            }
            
            return $image;
        }, $images);
    }
}
