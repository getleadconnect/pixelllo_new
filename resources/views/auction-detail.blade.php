@extends('layouts.app')

@section('title', $auction->title . ' - ' . config('app.name'))

@section('content')


<style>
.auction-detail-page {
    padding: 40px 0;
}

.breadcrumbs {
    margin-bottom: 30px;
    font-size: 14px;
}

.breadcrumb-separator {
    margin: 0 10px;
    color: #999;
}

.breadcrumbs .current {
    font-weight: 600;
    color: #333;
}

.auction-detail-content {
    display: flex;
    gap: 30px;
    margin-bottom: 50px;
}

.auction-detail-left {
    flex: 3;
}

.auction-detail-right {
    flex: 2;
}

.auction-image-gallery {
    margin-bottom: 30px;
    border: 1px solid #eee;
    border-radius: 10px;
    overflow: hidden;
}

.auction-image-main {
    position: relative;
    height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f9f9f9;
}

.auction-image-main img {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
}

.auction-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    padding: 8px 15px;
    background-color: var(--secondary-color);
    color: white;
    font-weight: 600;
    font-size: 14px;
    border-radius: 20px;
    z-index: 2;
}

.auction-badge.upcoming {
    background-color: #f39c12;
}

.auction-badge.ended {
    background-color: #e74c3c;
}

.auction-thumbnails {
    display: flex;
    padding: 15px;
    gap: 10px;
    background-color: #f9f9f9;
}

.thumbnail-item {
    width: 80px;
    height: 80px;
    border: 2px solid transparent;
    border-radius: 5px;
    overflow: hidden;
    cursor: pointer;
}

.thumbnail-item.active {
    border-color: var(--primary-color);
}

.thumbnail-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.auction-description, .auction-details-specs {
    margin-bottom: 30px;
    padding: 25px;
    background-color: #f9f9f9;
    border-radius: 10px;
}

.auction-description h3, .auction-details-specs h3 {
    margin-bottom: 20px;
    font-size: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}

.description-content {
    line-height: 1.6;
}

/* Show More/Show Less button - Mobile only */
.show-more-btn {
    display: none;
    margin-top: 10px;
    padding: 8px 20px;
    background-color: #ff5500;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: background-color 0.3s;
}

.show-more-btn:hover {
    background-color: #ff6600;
}

/* Mobile view - Limit description height */
@media (max-width: 768px) {
    .description-content.collapsed {
        max-height: 100px;
        overflow: hidden;
        position: relative;
    }

    .description-content.collapsed::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 40px;
        background: linear-gradient(to bottom, transparent, white);
    }

    .show-more-btn {
        display: inline-block !important;
    }
}

.specs-content ul {
    list-style: none;
    padding: 0;
}

.specs-content li {
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.specs-content li:last-child {
    border-bottom: none;
}

.specs-content strong {
    font-weight: 600;
    margin-right: 10px;
}

.auction-bid-box {
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
}

.auction-bid-box h2 {
    margin-bottom: 20px;
    font-size: 22px;
    line-height: 1.3;
}

.auction-price-container {
    margin-bottom: 20px;
}

.current-price {
    margin-bottom: 10px;
}

.price-label, .savings-label {
    font-size: 14px;
    color: #666;
}

.price-value, .savings-value {
    font-size: 24px;
    font-weight: 700;
    color: var(--primary-color);
    display: block;
}

.retail-price .price-value {
    font-size: 18px;
    text-decoration: line-through;
    color: #999;
}

.time-remaining {
    margin-bottom: 25px;
}

.time-label {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    font-size: 14px;
}

.time-value {
    font-weight: 600;
}

.progress-container {
    height: 10px;
    background-color: #eee;
    border-radius: 5px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background-color: var(--secondary-color);
}

.bid-stats {
    display: flex;
    justify-content: space-between;
    margin-bottom: 25px;
    text-align: center;
}

.stat {
    flex: 1;
    padding: 10px;
}

.stat-value {
    display: block;
    font-size: 20px;
    font-weight: 700;
    color: #333;
}

.stat-label {
    font-size: 12px;
    color: #666;
}

.bid-actions {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

.btn-block-auto {
    flex: 1;
}

.bid-notice {
    font-size: 12px;
    color: #666;
    line-height: 1.5;
}

.recent-bids, .auction-share {
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.05);
    margin-bottom: 30px;
}

.recent-bids h3, .auction-share h3 {
    margin-bottom: 15px;
    font-size: 18px;
}

.bid-list {
    list-style: none;
    padding: 0;
}

.bid-item {
    padding: 12px 0;
    border-bottom: 1px solid #eee;
}

.bid-item:last-child {
    border-bottom: none;
}

.bidder-info {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
}

.bidder-name {
    font-weight: 600;
}

.bid-price {
    color: var(--primary-color);
    font-weight: 600;
}

.bid-time {
    font-size: 12px;
    color: #999;
}

.no-bids {
    color: #999;
    font-style: italic;
}

.share-buttons {
    display: flex;
    gap: 10px;
}

.share-button {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
    transition: all 0.2s;
}

.share-button:hover {
    transform: scale(1.1);
}

.facebook {
    background-color: #3b5998;
}

.twitter {
    background-color: #1da1f2;
}

.pinterest {
    background-color: #bd081c;
}

.email {
    background-color: #333;
}

.auction-detail-tabs {
    margin-bottom: 30px;
    display: flex;
    justify-content: center;
    gap: 20px;
    padding: 20px 0;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.auction-tab {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 30px;
    background: white;
    border: 2px solid transparent;
    border-radius: 50px;
    font-size: 16px;
    font-weight: 600;
    color: #666;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
}

.auction-tab i {
    font-size: 18px;
    transition: transform 0.3s ease;
}

.auction-tab:before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 193, 7, 0.2), transparent);
    transition: left 0.5s ease;
}

.auction-tab:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    color: var(--primary-color);
    border-color: var(--primary-color);
}

.auction-tab:hover:before {
    left: 100%;
}

.auction-tab:hover i {
    transform: rotate(15deg) scale(1.1);
}

.auction-tab.active {
    background: linear-gradient(135deg, var(--primary-color), #ff9500);
    color: white;
    border-color: var(--primary-color);
    box-shadow: 0 6px 25px rgba(255, 193, 7, 0.4);
}

.auction-tab.active i {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.tab-panel {
    display: none;
    padding: 40px;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 20px;
    margin-bottom: 50px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
    animation: fadeIn 0.5s ease;
}

.tab-panel.active {
    display: block;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.tab-panel h3 {
    margin-bottom: 25px;
    font-size: 22px;
    color: var(--dark);
}

.tab-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
}

.info-item {
    text-align: center;
    padding: 20px;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.info-icon {
    font-size: 30px;
    color: var(--primary-color);
    margin-bottom: 15px;
}

.info-item h4 {
    margin-bottom: 10px;
    font-size: 18px;
}

.info-item p {
    color: #666;
    line-height: 1.5;
}

.cta-container {
    text-align: center;
    margin-top: 30px;
}

.shipping-info h4 {
    margin: 20px 0 10px;
    font-size: 18px;
}

.shipping-info p {
    margin-bottom: 15px;
    line-height: 1.5;
}

.similar-auctions h2 {
    margin-bottom: 25px;
    font-size: 28px;
}

.auctions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
}

@media (max-width: 992px) {
    .auction-detail-content {
        flex-direction: column;
    }
    
    .auction-detail-left,
    .auction-detail-right {
        flex: none;
        width: 100%;
    }
}

@media (max-width: 768px) {
    .bid-stats {
        flex-wrap: wrap;
    }

    .stat {
        flex: 0 0 calc(50% - 10px);
        margin-bottom: 20px;
    }

    .auctions-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    }

    .auction-detail-tabs {
        flex-direction: column;
        padding: 15px;
        gap: 10px;
    }

    .auction-tab {
        width: 100%;
        justify-content: center;
        padding: 12px 20px;
    }

    .auction-tab span {
        display: inline-block;
    }

    .auction-tab i {
        font-size: 16px;
    }
}

/* Bid Animation Styles */
@keyframes bidPulse {
    0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 153, 0, 0.7); }
    50% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(255, 153, 0, 0); }
    100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 153, 0, 0); }
}

@keyframes priceUpdate {
    0% { color: white; background-color: var(--secondary-color); transform: scale(1.1); }
    100% { color: var(--primary-color); background-color: transparent; transform: scale(1); }
}

.bid-animation {
    animation: bidPulse 0.8s;
}

.price-update-animation {
    animation: priceUpdate 1.5s ease-out;
    border-radius: 4px;
    padding: 2px 8px;
}

/* Countdown Timer Animation */
.time-value.urgent {
    color: var(--danger);
    font-weight: 700;
}
</style>



<div class="auction-detail-page">
    <div class="container">
        <div class="breadcrumbs">
            <a href="{{ route('home') }}">Home</a> 
            <span class="breadcrumb-separator">›</span>
            <a href="{{ route('auctions') }}">Auctions</a>
            <span class="breadcrumb-separator">›</span>
            <a href="{{ route('categories') }}">{{ $auction->category->name }}</a>
            <span class="breadcrumb-separator">›</span>
            <span class="current">{{ $auction->title }}</span>
        </div>

        <div class="auction-detail-content">
            <div class="auction-detail-left">
                <div class="auction-image-gallery">
                    <div class="auction-image-main">
                        @if(isset($auction->images[0]))
                            <img src="{{ asset('storage/' . $auction->images[0]) }}" alt="{{ $auction->title }}" id="main-auction-image">
                        @else
                            <img src="https://via.placeholder.com/600x400" alt="{{ $auction->title }}" id="main-auction-image">
                        @endif
                        
                        @if($auction->status === 'active')
                            <div class="auction-badge">{{ $auction->featured ? 'Featured' : 'Active' }}</div>
                        @elseif($auction->status === 'upcoming')
                            <div class="auction-badge upcoming">Coming Soon</div>
                        @elseif($auction->status === 'ended')
                            <div class="auction-badge ended">Closed</div>
                        @endif
                    </div>
                    
                    @if(isset($auction->images) && is_array($auction->images) && count($auction->images) > 1)
                    <div class="auction-thumbnails">
                        @foreach($auction->images as $index => $image)
                            <div class="thumbnail-item {{ $index === 0 ? 'active' : '' }}"
                                 onclick="changeMainImage('{{ asset('storage/' . $image) }}', this)">
                                <img src="{{ asset('storage/' . $image) }}" alt="{{ $auction->title }} thumbnail {{ $index + 1 }}">
                            </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <div class="auction-description">
                    <h3>Product Description</h3>
                    <div class="description-content" id="product-description">
                        {!! nl2br(e($auction->description)) !!}
                    </div>
                    <button id="toggle-description-btn" class="show-more-btn" style="display: none;">Show More</button>
                </div>

                <div class="auction-details-specs">
                    <h3>Product Specifications</h3>
                    <div class="specs-content">
                        <ul>
                            <li><strong>Category:</strong> {{ $auction->category->name }}</li>
                            <li><strong>Retail Price:</strong> AED {{ number_format($auction->retailPrice, 2) }}</li>
                            <li><strong>Status:</strong> {{ ucfirst($auction->status) }}</li>
                            <!-- Add more specification fields here -->
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="auction-detail-right">
                <div class="auction-bid-box">
                    <h2>{{ $auction->title }}</h2>
                    
                    @if($auction->status === 'ended')
                        <div class="auction-badge ended" style="display: inline-block; margin-bottom: 20px;">Closed</div>
                    @endif

                    @if($auction->status === 'active')
                        <div class="auction-price-container">
                            <div class="current-price">
                                <span class="price-label">Current Bid:</span>
                                <span class="price-value">AED {{ number_format($auction->currentPrice, 2) }}</span>
                            </div>
                            <div class="retail-price">
                                <span class="price-label">Retail Price:</span>
                                <span class="price-value">AED {{ number_format($auction->retailPrice, 2) }}</span>
                            </div>
                            <div class="savings">
                                <span class="savings-label">You Save:</span>
                                <span class="savings-value">{{ number_format($savingsPercentage, 0) }}%</span>
                            </div>
                        </div>
                        
                        <div class="time-remaining">
                            <div class="time-label">
                                <span>Time Remaining:</span>
                                <span id="time-countdown" class="time-value">{{ $timeLeft }}</span>
                            </div>
                            <div class="progress-container">
                                <div id="time-progress-bar" class="progress-bar" style="width: {{ $timeProgress }}%;"></div>
                            </div>
                        </div>
                        
                        <div class="bid-stats">
                            <div class="stat">
                                <span class="stat-value">{{ $totalBids }}</span>
                                <span class="stat-label">Bids</span>
                            </div>
                            <div class="stat">
                                <span class="stat-value">AED {{ number_format($auction->bidIncrement, 2) }}</span>
                                <span class="stat-label">Bid Increment</span>
                            </div>
                            <div class="stat">
                                <span class="stat-value">+10s</span>
                                <span class="stat-label">Time Extension</span>
                            </div>
                        </div>
                        
                        @if($auction->status === 'active')
                        <div class="bid-actions">
                            <button class="btn btn-primary btn-block-auto btn-bid">Place Bid Now</button>
                            <button class="btn btn-outline btn-block-auto btn-autobid">Set Auto-Bidder</button>
                        </div>

                        <div class="bid-notice">
                            <p>Placing a bid will cost you 1 bid credit and extend the auction by {{ $auction->extensionTime }} seconds!</p>
                        </div>
                        @endif
                    @elseif($auction->status === 'upcoming')
                        <div class="upcoming-auction-info">
                            <p>This auction starts on {{ $auction->startTime->format('F j, Y \a\t g:i A') }}</p>
                            <button class="btn btn-outline btn-block-auto">Notify Me When Live</button>
                        </div>
                    @elseif($auction->status === 'ended')
                        <div class="ended-auction-info">
                            <div class="auction-price-container">
                                <div class="current-price">
                                    <span class="price-label">Final Price:</span>
                                    <span class="price-value">AED {{ number_format($auction->currentPrice, 2) }}</span>
                                </div>
                                <div class="retail-price">
                                    <span class="price-label">Retail Price:</span>
                                    <span class="price-value">AED {{ number_format($auction->retailPrice, 2) }}</span>
                                </div>
                            </div>
                            <p style="margin-top: 20px; color: #666;">This auction ended on {{ $auction->endTime->format('F j, Y \a\t g:i A') }}</p>
                            @if($auction->winner)
                                <div style="background: #d4edda; border: 1px solid #c3e6cb; border-radius: 8px; padding: 15px; margin-top: 20px;">
                                    <h4 style="color: #155724; margin-bottom: 10px; font-size: 18px;">
                                        <i class="fas fa-trophy" style="color: #ffc107;"></i> Auction Winner
                                    </h4>
                                    <p style="color: #155724; font-weight: 600; margin-bottom: 5px;">
                                        Winner: {{ $auction->winner->name }}
                                    </p>
                                    <p style="color: #155724;">
                                        Winning bid: AED {{ number_format($auction->currentPrice, 2) }}
                                    </p>
                                </div>
                            @elseif($auction->bids->count() > 0)
                                <p style="color: #27ae60; font-weight: 600;">Winning bid: AED {{ number_format($auction->currentPrice, 2) }}</p>
                            @else
                                <p style="color: #999;">No bids were placed on this auction.</p>
                            @endif
                        </div>
                    @endif
                </div>
                
                <div class="recent-bids">
                    <h3>Recent Bids <small id="bids-count" style="color: #666; font-size: 14px;"></small></h3>

                    <div class="table-responsive">
                        <table id="recent-bids-table" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Bidder</th>
                                    <th>Time</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- DataTable will populate this via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="auction-share">
                    <h3>Share This Auction</h3>
                    <div class="share-buttons">
                        <a href="#" class="share-button facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="share-button twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="share-button pinterest"><i class="fab fa-pinterest-p"></i></a>
                        <a href="#" class="share-button email"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="auction-tabs auction-detail-tabs">
            <button class="auction-tab active" data-tab="how-it-works">
                <i class="fas fa-info-circle"></i>
                <span>How It Works</span>
            </button>
            <button class="auction-tab" data-tab="shipping">
                <i class="fas fa-shipping-fast"></i>
                <span>Shipping & Returns</span>
            </button>
            <button class="auction-tab" data-tab="reviews">
                <i class="fas fa-star"></i>
                <span>Customer Reviews</span>
            </button>
        </div>
        
        <div class="auction-tab-content">
            <div class="tab-panel active" id="how-it-works-panel">
                <h3>How Penny Auctions Work</h3>
                <div class="tab-info-grid">
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-gavel"></i></div>
                        <h4>Place Your Bid</h4>
                        <p>Each bid costs one bid credit and raises the price by just AED {{ number_format($auction->bidIncrement, 2) }}.</p>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-clock"></i></div>
                        <h4>Extend the Timer</h4>
                        <p>Every bid extends the auction by {{ $auction->extensionTime }} seconds to give others a chance to bid.</p>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-trophy"></i></div>
                        <h4>Win the Auction</h4>
                        <p>When the timer hits zero, the last bidder wins the item at the final discounted price!</p>
                    </div>
                </div>
                <div class="cta-container">
                    <a href="{{ route('how-it-works') }}" class="btn btn-outline">Learn More About Penny Auctions</a>
                </div>
            </div>
            
            <div class="tab-panel" id="shipping-panel">
                <h3>Shipping & Returns</h3>
                <div class="shipping-info">
                    <h4>Shipping</h4>
                    <p>We offer free standard shipping on all won items. Expedited shipping options are available at checkout.</p>
                    <p>Items typically ship within 1-3 business days after payment is received.</p>
                    
                    <h4>Returns</h4>
                    <p>We offer a 30-day satisfaction guarantee on all items. If you're not completely satisfied, you may return the item for a full refund minus shipping costs.</p>
                    <p>Please contact our customer support team to initiate a return.</p>
                </div>
            </div>
            
            <div class="tab-panel" id="reviews-panel">
                <h3>Customer Reviews</h3>
                <div class="reviews-container">
                    <p>Customer reviews for this product will appear here.</p>
                    <!-- Reviews would be loaded here -->
                </div>
            </div>
        </div>
        
        <div class="similar-auctions">
            <h2>Similar Auctions</h2>
            <div class="auctions-grid">
                @foreach($similarAuctions as $similarAuction)
                    <div class="auction-card">
                        <div class="auction-badge">{{ $similarAuction->featured ? 'Featured' : 'Active' }}</div>
                        <div class="auction-image-container">
                            @if(isset($similarAuction->images[0]))
                                <img src="{{ asset('storage/' . $similarAuction->images[0]) }}" alt="{{ $similarAuction->title }}" class="auction-image">
                            @else
                                <img src="https://via.placeholder.com/300x200" alt="{{ $similarAuction->title }}" class="auction-image">
                            @endif
                            <div class="auction-overlay">
                                <span class="auction-bids"><i class="fas fa-gavel"></i> {{ $similarAuction->bids->count() }} bids</span>
                                <span class="auction-watchers"><i class="fas fa-eye"></i> {{ rand(50, 150) }} watching</span>
                            </div>
                        </div>
                        <div class="auction-content">
                            <div class="auction-category">{{ $similarAuction->category->name }}</div>
                            <h3 class="auction-title">{{ $similarAuction->title }}</h3>
                            <div class="auction-info">
                                <span class="auction-price">Current Bid: AED {{ number_format($similarAuction->currentPrice, 2) }}</span>
                                <span class="auction-time"><i class="fas fa-clock"></i> {{ now()->diffForHumans($similarAuction->endTime, ['parts' => 1]) }}</span>
                            </div>
                            <div class="auction-progress">
                                @php
                                    $progress = 0;
                                    $totalDuration = $similarAuction->endTime->diffInSeconds($similarAuction->startTime);
                                    $elapsed = now()->diffInSeconds($similarAuction->startTime);
                                    $progress = min(100, max(0, ($elapsed / $totalDuration) * 100));
                                @endphp
                                <div class="progress-bar" style="width: {{ $progress }}%;"></div>
                            </div>
                            <div class="auction-retail">
                                <span class="retail-price">Retail Price: AED {{ number_format($similarAuction->retailPrice, 2) }}</span>
                                @php
                                    $savings = 0;
                                    if ($similarAuction->retailPrice > 0 && $similarAuction->currentPrice > 0) {
                                        $savings = 100 - (($similarAuction->currentPrice / $similarAuction->retailPrice) * 100);
                                    }
                                @endphp
                                <span class="savings">You Save: {{ number_format($savings, 0) }}%</span>
                            </div>
                            <a href="{{ route('auction.detail', $similarAuction->id) }}" class="btn btn-primary" style="width: 100%;">Bid Now</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
// Function to change main image when clicking thumbnails
function changeMainImage(imageSrc, thumbnailElement) {
    document.getElementById('main-auction-image').src = imageSrc;

    // Update active thumbnail
    document.querySelectorAll('.thumbnail-item').forEach(function(item) {
        item.classList.remove('active');
    });
    thumbnailElement.classList.add('active');
}

document.addEventListener('DOMContentLoaded', function() {
    // Show More/Show Less functionality for description (Mobile only)
    const descriptionContent = document.getElementById('product-description');
    const toggleBtn = document.getElementById('toggle-description-btn');

    if (descriptionContent && toggleBtn) {
        // Check if we're on mobile and description is long enough
        function checkDescriptionHeight() {
            if (window.innerWidth <= 768) {
                // Add collapsed class initially on mobile
                descriptionContent.classList.add('collapsed');

                // Check if content is actually taller than max-height
                if (descriptionContent.scrollHeight > 100) {
                    toggleBtn.style.display = 'inline-block';
                } else {
                    toggleBtn.style.display = 'none';
                    descriptionContent.classList.remove('collapsed');
                }
            } else {
                // Remove collapsed class on desktop
                descriptionContent.classList.remove('collapsed');
                toggleBtn.style.display = 'none';
            }
        }

        // Initial check
        checkDescriptionHeight();

        // Re-check on window resize
        window.addEventListener('resize', checkDescriptionHeight);

        // Toggle button click handler
        toggleBtn.addEventListener('click', function() {
            if (descriptionContent.classList.contains('collapsed')) {
                descriptionContent.classList.remove('collapsed');
                toggleBtn.textContent = 'Show Less';
            } else {
                descriptionContent.classList.add('collapsed');
                toggleBtn.textContent = 'Show More';
            }
        });
    }

    // Tab switching functionality
    const tabs = document.querySelectorAll('.auction-detail-tabs .auction-tab');
    const tabPanels = document.querySelectorAll('.tab-panel');

    // Timer functionality
    const countdownElement = document.getElementById('time-countdown');
    const progressBar = document.getElementById('time-progress-bar');

    // Initialize auction countdown timer if on an active auction
    if (countdownElement && progressBar) {
        initializeCountdownTimer();
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active'));

            // Add active class to clicked tab
            this.classList.add('active');

            // Hide all tab panels
            tabPanels.forEach(panel => panel.classList.remove('active'));

            // Show the corresponding panel
            const tabId = this.getAttribute('data-tab');
            document.getElementById(tabId + '-panel').classList.add('active');
        });
    });

    // Function to initialize and run the countdown timer
    function initializeCountdownTimer() {
        @if($auction->status === 'active' && $auction->endTime > now())
            // Parse auction end time (use ISO format for better compatibility)
            const endTime = new Date("{{ $auction->endTime->toIso8601String() }}").getTime();
            const startTime = new Date("{{ $auction->startTime->toIso8601String() }}").getTime();
            const totalDuration = endTime - startTime;
            let auctionEndAlertShown = false; // Flag to prevent repeated alerts

            console.log('Countdown Timer Debug:');
            console.log('End Time:', new Date(endTime));
            console.log('Start Time:', new Date(startTime));
            console.log('Current Time:', new Date());
            console.log('Time Remaining (ms):', endTime - new Date().getTime());

            // Update the timer every second
            const timerInterval = setInterval(function() {
                // Get current time
                const now = new Date().getTime();

                // Calculate remaining time
                const distance = endTime - now;

                // If auction has ended
                if (distance < 0) {
                    clearInterval(timerInterval);
                    countdownElement.textContent = "ENDED";
                    countdownElement.classList.add('urgent');
                    progressBar.style.width = "100%";

                    // Show alert dialog only once and reload page
                    if (!auctionEndAlertShown) {
                        auctionEndAlertShown = true;
                        alert('This auction has ended!');
                        window.location.reload();
                    }

                    return;
                }

                // Calculate days, hours, minutes, seconds
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Format the countdown display
                let timeDisplay = "";

                if (days > 0) {
                    timeDisplay += days + "d ";
                }

                if (hours > 0 || days > 0) {
                    timeDisplay += hours + "h ";
                }

                timeDisplay += minutes + "m " + seconds + "s";

                // Update countdown text
                countdownElement.textContent = timeDisplay;

                // Add urgency class for last minute
                if (distance < 60000) { // less than a minute
                    countdownElement.classList.add('urgent');

                    // Make progress bar pulse when under a minute
                    if (!progressBar.classList.contains('bid-animation')) {
                        progressBar.classList.add('bid-animation');
                    }
                }

                // Update progress bar
                const elapsed = now - startTime;
                const progress = Math.min(100, Math.max(0, (elapsed / totalDuration) * 100));
                progressBar.style.width = progress + "%";

            }, 1000);
        @endif
    }

    // Handle bid button click
    const bidButton = document.querySelector('.btn-bid');
    if (bidButton) {
        bidButton.addEventListener('click', function() {
            // Check if user is authenticated
            @auth
                // Show confirmation modal
                if (confirm('Are you sure you want to place a bid of AED {{ number_format($auction->currentPrice + $auction->bidIncrement, 2) }}? This will cost you 1 bid credit.')) {
                    // Set loading state on button
                    bidButton.disabled = true;
                    bidButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

                    // Create the CSRF token for the AJAX request
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    // Debug logging
                    console.log('CSRF Token:', csrfToken);
                    console.log('Bid URL:', '{{ url(route('bid.now')) }}');
                    console.log('Auction ID:', '{{ $auction->id }}');

                    // Make AJAX request - using regular POST form submission with absolute URL
                    fetch('{{ url(route('bid.now')) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            auction_id: '{{ $auction->id }}'
                        }),
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw response;
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Success - animate the bid box and price change
                        const bidBox = document.querySelector('.auction-bid-box');
                        const priceValue = document.querySelector('.price-value');

                        // Trigger animation
                        bidBox.classList.add('bid-animation');
                        priceValue.classList.add('price-update-animation');

                        // Update price display without reloading
                        priceValue.textContent = '$' + parseFloat(data.data.auction.currentPrice).toFixed(2);

                        // Update bid counter
                        const bidCounter = document.querySelector('.bid-stats .stat:first-child .stat-value');
                        if (bidCounter) {
                            bidCounter.textContent = parseInt(bidCounter.textContent) + 1;
                        }

                        // Update user's bid credit display if visible
                        const userCredits = document.querySelector('.user-credits');
                        if (userCredits) {
                            userCredits.textContent = data.data.userBidCredits;
                        }

                        // Reset bid button
                        bidButton.disabled = false;
                        bidButton.innerHTML = 'Place Bid Now';

                        // Extend timer based on the extension time
                        const endTime = new Date("{{ $auction->endTime }}").getTime();
                        const newEndTime = endTime + ({{ $auction->extensionTime }} * 1000);
                        // Update end time in the existing timer calculations

                        // Remove animation classes after animation completes
                        setTimeout(() => {
                            bidBox.classList.remove('bid-animation');
                            priceValue.classList.remove('price-update-animation');
                        }, 1000);

                        // Add the bid to the recent bids list
                        updateRecentBids(data.data.bid);
                    })
                    .catch(async (error) => {
                        console.log('Bid error:', error);
                        console.log('Error status:', error.status);

                        // Reset button state
                        bidButton.disabled = false;
                        bidButton.innerHTML = 'Place Bid Now';

                        try {
                            const errorData = await error.json();
                            console.log('Error data:', errorData);

                            // Handle specific errors
                            if (error.status === 403) {
                                if (errorData.message === 'Insufficient bid credits') {
                                    alert('You do not have enough bid credits. Please purchase more bids to continue.');
                                    window.location.href = '{{ url('/dashboard/bids/purchase') }}';
                                } else {
                                    alert(errorData.message || 'You are not authorized to place this bid.');
                                }
                            } else if (error.status === 422) {
                                alert(errorData.message || 'Invalid bid. Please try again.');
                            } else {
                                alert('Error: ' + (errorData.message || 'Unknown error occurred'));
                            }
                        } catch (e) {
                            console.log('Could not parse error response as JSON');
                            alert('Network error or server is not responding. Please try again.');
                        }
                    });
                }
            @else
                // User is not authenticated, redirect to login page
                window.location.href = "{{ url(route('login')) }}?redirect={{ urlencode(request()->url()) }}";
            @endauth
        });
    }

    // Handle auto-bid button click
    const autoBidButton = document.querySelector('.btn-autobid');
    if (autoBidButton) {
        autoBidButton.addEventListener('click', function() {
            @auth
                // Open modal for auto-bid settings
                const maxBids = prompt('Enter the maximum number of bids to place automatically (1-100):', '10');

                if (maxBids !== null) {
                    const maxBidsNum = parseInt(maxBids);

                    if (isNaN(maxBidsNum) || maxBidsNum < 1 || maxBidsNum > 100) {
                        alert('Please enter a valid number between 1 and 100.');
                        return;
                    }

                    // Set loading state
                    autoBidButton.disabled = true;
                    autoBidButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Setting up...';

                    // Create the CSRF token for the AJAX request
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    // Make AJAX request to set up auto-bidding with absolute URL
                    fetch('{{ url(route('auto.bid')) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            auction_id: '{{ $auction->id }}',
                            max_bids: maxBidsNum
                        }),
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw response;
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Success - show confirmation and refresh
                        alert('Auto-bidder has been set up successfully!');
                        window.location.reload();
                    })
                    .catch(async (error) => {
                        // Reset button state
                        autoBidButton.disabled = false;
                        autoBidButton.innerHTML = 'Set Auto-Bidder';

                        try {
                            const errorData = await error.json();
                            alert(errorData.message || 'Error setting up auto-bidder. Please try again.');
                        } catch (e) {
                            alert('There was an error setting up the auto-bidder. Please try again.');
                        }
                    });
                }
            @else
                // User is not authenticated, redirect to login page
                window.location.href = "{{ url(route('login')) }}?redirect={{ urlencode(request()->url()) }}";
            @endauth
        });
    }

    // Function to update the recent bids list
    function updateRecentBids(bid) {
        const bidsList = document.querySelector('.bid-list');
        const noBids = document.querySelector('.no-bids');

        if (noBids) {
            // Remove the "no bids" message if it exists
            noBids.remove();

            // Create a new bid list if it doesn't exist
            if (!bidsList) {
                const newBidsList = document.createElement('ul');
                newBidsList.className = 'bid-list';
                document.querySelector('.recent-bids').appendChild(newBidsList);
            }
        }

        if (bidsList) {
            // Create a new bid item
            const bidItem = document.createElement('li');
            bidItem.className = 'bid-item';

            // Format the bidder name (first letter * last letter)
            @if(Auth::check())
                const bidderName = '{{ Auth::user()->name }}';
                const maskedName = bidderName.charAt(0) + '*****' + bidderName.charAt(bidderName.length - 1);
            @else
                const maskedName = 'Guest';
            @endif

            // HTML for the bid item
            bidItem.innerHTML = `
                <div class="bidder-info">
                    <span class="bidder-name">${maskedName}</span>
                    <span class="bid-price">$${parseFloat(bid.amount).toFixed(2)}</span>
                </div>
                <div class="bid-time">just now</div>
            `;

            // Add animation to the new bid item
            bidItem.style.backgroundColor = 'rgba(255, 221, 0, 0.1)';

            // Add to the top of the list
            if (bidsList.firstChild) {
                bidsList.insertBefore(bidItem, bidsList.firstChild);
            } else {
                bidsList.appendChild(bidItem);
            }

            // Remove the animation after a short time
            setTimeout(() => {
                bidItem.style.backgroundColor = '';
            }, 3000);

            // Limit the list to 10 items
            const items = bidsList.querySelectorAll('.bid-item');
            if (items.length > 10) {
                for (let i = 10; i < items.length; i++) {
                    items[i].remove();
                }
            }
        }
    }
});
</script>

<!-- DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<style>
/* DataTable Styling */
.table-responsive {
    margin-top: 15px;
    overflow-x: auto;
}

#recent-bids-table {
    width: 100% !important;
    font-size: 14px;
    border: none !important;
}

#recent-bids-table thead {
    display: none !important; /* Hide the entire header row */
}

#recent-bids-table thead th {
    background-color: #f8f9fa;
    font-weight: 600;
    padding: 12px 8px;
    border: none !important;
}

#recent-bids-table tbody td {
    padding: 10px 8px;
    vertical-align: middle;
    border: none !important;
}

#recent-bids-table tbody tr {
    border: none !important;
}

#recent-bids-table tbody tr:hover {
    background-color: transparent; /* Remove gray hover effect */
}

/* Amount cell styling */
#recent-bids-table .amount-cell {
    text-align: right !important;
    font-weight: 600 !important;
    color: #ff5500 !important;
    font-size: 15px !important;
}

#recent-bids-table thead th:last-child {
    text-align: right !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 0.5em 1em;
    margin-left: 2px;
}

.dataTables_wrapper .dataTables_info {
    padding-top: 1em;
    font-size: 13px;
}

.dataTables_wrapper .dataTables_filter {
    float: right;
    text-align: right;
}

.dataTables_wrapper .dataTables_filter input {
    margin-left: 0.5em;
    padding: 5px 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.dataTables_wrapper .dataTables_length select {
    padding: 5px 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin: 0 5px;
}
</style>

<script>
$(document).ready(function() {
    let recentBidsTable;
    const auctionId = '{{ $auction->id }}';
    const apiUrl = '{{ route("api.auction.recent-bids", ["auctionId" => $auction->id]) }}';

    // Initialize DataTable
    function initializeDataTable() {
        recentBidsTable = $('#recent-bids-table').DataTable({
            ajax: {
                url: apiUrl,
                dataSrc: function(json) {
                    // Update the count
                    $('#bids-count').text('(' + json.total + ' total bids)');
                    return json.data;
                },
                error: function(xhr, error, thrown) {
                    console.error('Error fetching bids:', error);
                }
            },
            columns: [
                { data: 'bidder', width: '30%' },
                { data: 'time_ago', width: '40%' },
                {
                    data: 'amount',
                    width: '30%',
                    className: 'text-right amount-cell' // Add custom class for styling
                }
            ],
            order: [[2, 'desc']], // Order by time (most recent first)
            paging: false, // Disable pagination - show all bids
            searching: false, // Disable search box
            info: false, // Disable "Showing X to Y of Z entries"
            lengthChange: false, // Disable "Show X entries" dropdown
            responsive: true,
            autoWidth: false,
            stripeClasses: [], // Remove odd/even row classes
            language: {
                emptyTable: "No bids placed yet. Be the first!",
                info: "Showing _START_ to _END_ of _TOTAL_ bids",
                infoEmpty: "Showing 0 to 0 of 0 bids",
                infoFiltered: "(filtered from _MAX_ total bids)",
                search: "Search bids:",
                lengthMenu: "Show _MENU_ bids per page",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            },
            drawCallback: function(settings) {
                console.log('DataTable reloaded at:', new Date().toLocaleTimeString());
            }
        });
    }

    // Initialize the table on page load
    initializeDataTable();

    // Auto-refresh every 3 seconds
    setInterval(function() {
        if (recentBidsTable) {
            recentBidsTable.ajax.reload(null, false); // false = stay on current page
            console.log('Refreshing bids table...');
        }
    }, 3000); // 3000ms = 3 seconds

    console.log('Recent Bids DataTable initialized with auto-refresh every 3 seconds');
});
</script>

@endsection