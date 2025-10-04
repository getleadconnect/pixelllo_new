@extends('layouts.app')

@section('title', 'Buy Bid Credits - ' . config('app.name'))

@section('content')


<style>
.buy-bids-page {
    padding: 40px 0 60px;
    background: #f9fafb;
    min-height: 100vh;
}

.page-header {
    text-align: center;
    margin-bottom: 50px;
}

.page-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 15px;
    color: #1f2937;
}

.page-description {
    font-size: 1.2rem;
    color: #6b7280;
    margin-bottom: 25px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.current-balance {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    background-color: #f3f4f6;
    border-radius: 50px;
    font-size: 1rem;
}

.balance-amount {
    font-size: 1.3rem;
    color: #ff9900;
    font-weight: 700;
}

.packages-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 35px;
    margin-bottom: 60px;
    max-width: 1600px;
    margin-left: auto;
    margin-right: auto;
    padding: 0 20px;
}

.package-card {
    position: relative;
    background: white;
    border: 2px solid #ffdd00;
    border-radius: 16px;
    padding: 18px 18px;
    text-align: center;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
    min-height: 255px;
}

.package-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(255, 221, 0, 0.3);
    border-color: #ff9900;
}

.package-card.featured {
    border-color: #ff9900;
    background: linear-gradient(to bottom, #ffffff, #fffef0);
    box-shadow: 0 15px 40px rgba(255, 221, 0, 0.35);
    transform: scale(1.03);
}

.popular-badge {
    position: absolute;
    top: -10px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 4px 18px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.package-header h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 10px;
}

.bid-amount {
    margin-bottom: 10px;
}

.bid-amount .amount {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: #ff9900;
    line-height: 1;
}

.bid-amount .label {
    display: block;
    font-size: 0.75rem;
    color: #6b7280;
    margin-top: 2px;
}

.package-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 8px;
}

.package-price .currency {
    font-size: 1.1rem;
    vertical-align: super;
}

.package-description {
    font-size: 0.8rem;
    color: #6b7280;
    margin-bottom: 10px;
    padding: 6px;
    background-color: #f9fafb;
    border-radius: 6px;
    line-height: 1.3;
}

.package-details {
    margin-bottom: 12px;
    padding-top: 10px;
    border-top: 1px solid #e5e7eb;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
}

.detail-label {
    font-size: 0.75rem;
    color: #6b7280;
}

.detail-value {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.85rem;
}

.detail-item.savings .detail-value {
    color: #10b981;
    font-size: 0.9rem;
}

.btn-block {
    width: 100%;
    padding: 8px 16px;
    font-size: 0.9rem;
    font-weight: 600;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background-color: #4a4a4a;
    color: white;
    font-weight: 600;
}

.btn-primary:hover {
    background-color: #333333;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

.package-footer {
    margin-top: auto;
    padding-top: 10px;
    border-top: 1px solid #e5e7eb;
    font-size: 0.75rem;
    color: #6b7280;
}

.package-footer i {
    color: #10b981;
    margin-right: 5px;
}

.info-section {
    margin-top: 80px;
    padding: 60px 40px;
    background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    border-radius: 20px;
    max-width: 1600px;
    margin-left: auto;
    margin-right: auto;
}

.info-section h3 {
    text-align: center;
    font-size: 1.8rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 30px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
}

.info-item {
    text-align: center;
}

.info-item i {
    font-size: 2.5rem;
    color: #ffdd00;
    margin-bottom: 15px;
}

.info-item h4 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 8px;
}

.info-item p {
    font-size: 0.95rem;
    color: #6b7280;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state-icon {
    font-size: 4rem;
    color: #d1d5db;
    margin-bottom: 20px;
}

.empty-state h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 10px;
}

.empty-state p {
    font-size: 1rem;
    color: #6b7280;
    margin-bottom: 25px;
}

@media (max-width: 1200px) {
    .packages-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
    }
}

@media (max-width: 768px) {
    .buy-bids-page {
        padding: 30px 0 40px;
    }

    .page-header h1 {
        font-size: 2rem;
    }

    .packages-grid {
        grid-template-columns: 1fr;
        padding: 0 15px;
    }

    .package-card.featured {
        transform: none;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .info-section {
        padding: 40px 20px;
    }
}
</style>


<div class="buy-bids-page">
    <div class="container">
        <div class="page-header">
            <h1>Buy Bid Credits</h1>
            <p class="page-description">Purchase bid packages to participate in auctions and win amazing deals!</p>
            <div class="current-balance">
                <span>Your Current Balance:</span>
                <strong class="balance-amount">{{ $user->bid_balance }} Bids</strong>
            </div>
        </div>

    @if($bidPackages->count() > 0)
    <div class="packages-grid">
        @foreach($bidPackages as $package)
        
        <div class="package-card">
            <div class="package-header">
                <h3>{{ $package->name }}</h3>
                <div class="bid-amount">
                    <span class="amount">{{ $package->bidAmount }}</span>
                    <span class="label">Bid Credits</span>
                </div>
            </div>

            <div class="package-price">
                <span class="currency">AED</span>
                <span class="price">{{ number_format($package->price, 2) }}</span>
            </div>

            @if($package->description)
            <div class="package-description">
                {{ $package->description }}
            </div>
            @endif

            <div style="margin-top:15px;">
            <form action="{{ route('dashboard.purchase-bids') }}" method="GET" class="buy-form">
                <input type="hidden" name="package_id" value="{{ $package->id }}">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-shopping-cart"></i> Buy Now
                </button>
            </form>
            </div>
            <div class="package-footer">
                <i class="fas fa-lock"></i> Secure Payment
            </div>
        </div>
        @endforeach
    </div>

    <div class="info-section">
        <h3>Why Buy Bid Credits?</h3>
        <div class="info-grid">
            <div class="info-item">
                <i class="fas fa-trophy"></i>
                <h4>Win Amazing Deals</h4>
                <p>Save up to 95% on brand new products</p>
            </div>
            <div class="info-item">
                <i class="fas fa-shield-alt"></i>
                <h4>100% Authentic</h4>
                <p>All products are brand new and come with warranty</p>
            </div>
            <div class="info-item">
                <i class="fas fa-truck"></i>
                <h4>Free Shipping</h4>
                <p>Free shipping on all won auctions</p>
            </div>
            <div class="info-item">
                <i class="fas fa-undo"></i>
                <h4>Buy It Now Option</h4>
                <p>Use your bids towards buying the product at retail price</p>
            </div>
        </div>
    </div>

    @else
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="fas fa-coins"></i>
        </div>
        <h3>No Bid Packages Available</h3>
        <p>Bid packages are currently unavailable. Please check back later.</p>
        <a href="{{ route('dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
    </div>
    @endif
    </div>
</div>

@endsection