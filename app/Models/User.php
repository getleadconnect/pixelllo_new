<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'country',
        'address',
        'avatar',
        'city',
        'bid_balance',
        'role',
        'active',
        'notification_preferences',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'notification_preferences' => 'json',
            'bid_balance' => 'integer',
            'active' => 'boolean',
        ];
    }

    /**
     * Get the bids for the user.
     */
    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the auctions won by the user.
     */
    public function auctions()
    {
        return $this->hasMany(Auction::class, 'winner_id');
    }

    /**
     * Get the users watchlist auctions.
     */
    public function watchlist()
    {
        return $this->belongsToMany(Auction::class, 'user_watchlist')
                    ->withTimestamps();
    }

    /**
     * Get the auto bids for the user.
     */
    public function autoBids()
    {
        return $this->hasMany(AutoBid::class);
    }

    /**
     * Get the bid purchase histories for the user.
     */
    public function bidPurchaseHistories()
    {
        return $this->hasMany(BidPurchaseHistory::class);
    }

    /**
     * Check if user is an admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a customer
     *
     * @return bool
     */
    public function isCustomer()
    {
        return $this->role === 'customer';
    }
}
