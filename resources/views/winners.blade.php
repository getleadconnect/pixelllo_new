@extends('layouts.app')

@section('title', 'Winners - ' . config('app.name'))

@php
use Illuminate\Support\Str;
@endphp

@section('content')


<style>
.winners-page {
    padding: 40px 0 60px;
}

.page-header {
    text-align: center;
    margin-bottom: 40px;
}

.page-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 15px;
    color: var(--dark);
}

.page-description {
    font-size: 1.1rem;
    color: var(--secondary-text);
    max-width: 700px;
    margin: 0 auto;
}

.stats-banner {
    display: flex;
    justify-content: center;
    gap: 40px;
    margin-bottom: 50px;
    text-align: center;
}

.stat-item {
    padding: 20px;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--secondary-color);
    margin-bottom: 5px;
}

.stat-label {
    color: var(--secondary-text);
}

.winners-filter {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    margin-bottom: 30px;
}

.winners-filter form {
    display: flex;
    justify-content: center;
    align-items: flex-end;
    gap: 20px;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-group label {
    font-size: 0.85rem;
    margin-bottom: 8px;
    color: var(--secondary-text);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-select {
    padding: 10px 15px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 0.95rem;
    min-width: 200px;
    background-color: white;
    transition: all 0.3s ease;
    cursor: pointer;
}

.filter-select:hover {
    border-color: var(--primary-color);
}

.filter-select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.1);
}

.winners-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

.winner-card {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
    background-color: white;
    transition: transform 0.3s, box-shadow 0.3s;
}

.winner-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
}

.winner-image-container {
    height: 200px;
    position: relative;
    overflow: hidden;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.winner-card:hover .product-image {
    transform: scale(1.05);
}

.winner-overlay {
    position: absolute;
    bottom: 0;
    right: 0;
    padding: 15px;
}

.winner-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.winner-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.winner-content {
    padding: 20px;
}

.winner-product {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 5px;
    color: var(--dark);
}

.winner-name {
    font-size: 0.95rem;
    color: var(--secondary-text);
    margin-bottom: 15px;
}

.winner-details {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.detail-item {
    display: flex;
    flex-direction: column;
}

.detail-label {
    font-size: 0.8rem;
    color: var(--secondary-text);
}

.detail-value {
    font-weight: 600;
    font-size: 0.95rem;
}

.savings-value {
    color: var(--success);
}

.winner-testimony {
    font-style: italic;
    margin-bottom: 15px;
    font-size: 0.95rem;
    color: var(--dark);
    line-height: 1.5;
}

.winner-date {
    font-size: 0.85rem;
    color: var(--secondary-text);
}

.winners-pagination {
    display: flex;
    justify-content: center;
    margin-bottom: 50px;
}

.become-winner {
    background-color: #f9f9f9;
    border-radius: 10px;
    padding: 40px;
    text-align: center;
}

.become-winner h2 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 15px;
    color: var(--dark);
}

.become-winner p {
    font-size: 1.1rem;
    color: var(--secondary-text);
    margin-bottom: 20px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.become-winner .btn {
    margin: 0 5px;
}

@media (max-width: 992px) {
    .stats-banner {
        flex-wrap: wrap;
        gap: 20px;
    }
    
    .winners-grid {
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    }
}

@media (max-width: 768px) {
    .page-header h1 {
        font-size: 2rem;
    }

    .winners-filter {
        padding: 15px;
    }

    .winners-filter form {
        flex-direction: row;
        gap: 15px;
        overflow-x: auto;
        padding-bottom: 5px;
    }

    .filter-select {
        min-width: 150px;
        font-size: 0.9rem;
        padding: 8px 12px;
    }

    .filter-group label {
        font-size: 0.75rem;
        margin-bottom: 5px;
    }

    .winner-details {
        grid-template-columns: 1fr 1fr;
    }

    .winners-grid {
        grid-template-columns: 1fr;
    }

    .become-winner {
        padding: 30px 20px;
    }
}

@media (max-width: 480px) {
    .winners-filter form {
        gap: 10px;
    }

    .filter-select {
        min-width: 130px;
        font-size: 0.85rem;
        padding: 7px 10px;
    }
}
</style>


<div class="winners-page">
    <div class="container">
        <div class="page-header">
            <h1>Our Winners</h1>
            <p class="page-description">Meet the lucky bidders who have won amazing products at a fraction of their retail price. You could be next!</p>
        </div>
        
        <div class="stats-banner">
            <div class="stat-item">
                <div class="stat-number">{{ number_format($happyWinnersCount ?? 0) }}+</div>
                <div class="stat-label">Happy Winners</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">AED {{ number_format($totalSavings ?? 0, 0) }}+</div>
                <div class="stat-label">Saved by Our Customers</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ number_format($avgSavingsPercentage ?? 0, 0) }}%</div>
                <div class="stat-label">Average Savings</div>
            </div>
        </div>
        
        <div class="winners-filter">
            <form method="GET" action="{{ route('winners') }}" id="winners-filter-form">
                <div class="filter-group">
                    <label for="filter-category">Filter by Category</label>
                    <select id="filter-category" name="category" class="filter-select" onchange="document.getElementById('winners-filter-form').submit()">
                        <option value="all" {{ request('category') == null || request('category') == 'all' ? 'selected' : '' }}>All Categories</option>
                        @if(isset($categories) && $categories->count() > 0)
                            @foreach($categories as $category)
                            <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="filter-group">
                    <label for="filter-time">Time Period</label>
                    <select id="filter-time" name="period" class="filter-select" onchange="document.getElementById('winners-filter-form').submit()">
                        <option value="" {{ !request('period') ? 'selected' : '' }}>All Time</option>
                        <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="this-week" {{ request('period') == 'this-week' ? 'selected' : '' }}>This Week</option>
                        <option value="last-week" {{ request('period') == 'last-week' ? 'selected' : '' }}>Last Week</option>
                        <option value="this-month" {{ request('period') == 'this-month' ? 'selected' : '' }}>This Month</option>
                        <option value="last-month" {{ request('period') == 'last-month' ? 'selected' : '' }}>Last Month</option>
                        <option value="last-3-months" {{ request('period') == 'last-3-months' ? 'selected' : '' }}>Last 3 Months</option>
                        <option value="last-6-months" {{ request('period') == 'last-6-months' ? 'selected' : '' }}>Last 6 Months</option>
                        <option value="this-year" {{ request('period') == 'this-year' ? 'selected' : '' }}>This Year</option>
                    </select>
                </div>
            </form>
        </div>
        
        <div class="winners-grid">
            @forelse($winners as $winner)
            @php
                $savings = $winner->retailPrice > 0 ? (($winner->retailPrice - $winner->currentPrice) / $winner->retailPrice * 100) : 0;
                $winnerUser = $winner->winner;
                $winnerLocation = $winnerUser ? ($winnerUser->city ?? 'Unknown City') : 'Unknown';
            @endphp
            <div class="winner-card">
                <div class="winner-image-container">
                    @if(isset($winner->images) && is_array($winner->images) && count($winner->images) > 0)
                        <img src="{{ asset('storage/' . $winner->images[0]) }}" alt="{{ $winner->title }}" class="product-image" onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                    @else
                        <img src="{{ asset('images/placeholders/product-placeholder.svg') }}" alt="{{ $winner->title }}" class="product-image">
                    @endif
                    <div class="winner-overlay">
                        <div class="winner-avatar">
                            @if($winnerUser && $winnerUser->avatar)
                                <img src="{{ asset('storage/' . $winnerUser->avatar) }}" alt="{{ $winnerUser->name }}">
                            @else
                                <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: 600;">
                                    {{ $winnerUser ? strtoupper(substr($winnerUser->name, 0, 1)) : '?' }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="winner-content">
                    <div class="winner-product">{{ $winner->title }}</div>
                    <div class="winner-name">{{ $winnerUser ? $winnerUser->name : 'Anonymous' }}{{ $winnerLocation != 'Unknown' ? ' from ' . $winnerLocation : '' }}</div>
                    <div class="winner-details">
                        <div class="detail-item">
                            <span class="detail-label">Final Price:</span>
                            <span class="detail-value">AED {{ number_format($winner->currentPrice, 2) }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Retail Price:</span>
                            <span class="detail-value">AED {{ number_format($winner->retailPrice, 2) }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Savings:</span>
                            <span class="detail-value savings-value">{{ number_format($savings, 0) }}%</span>
                        </div>
                    </div>
                    @if($winner->description)
                    <div class="winner-testimony">
                        "{{ Str::limit($winner->description, 150) }}"
                    </div>
                    @endif
                    <div class="winner-date">Won on {{ $winner->endTime->format('F j, Y') }}</div>
                </div>
            </div>
            @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
                <div style="width: 100px; height: 100px; margin: 0 auto 20px; background: #f8f9fa; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-trophy" style="font-size: 48px; color: #ffc107;"></i>
                </div>
                <h3 style="font-size: 28px; margin-bottom: 15px; color: #333;">No Winners Found</h3>
                <p style="color: #666; margin-bottom: 30px; font-size: 16px; max-width: 500px; margin-left: auto; margin-right: auto;">
                    @if(request('category') || request('period'))
                        No winners found for the selected filters. Try adjusting your filter criteria.
                    @else
                        Be the first to win an amazing deal! Start bidding on our active auctions now.
                    @endif
                </p>
                <a href="{{ route('auctions') }}" class="btn btn-primary" style="font-size: 16px; padding: 12px 30px;">Browse Active Auctions</a>
            </div>
            @endforelse
        </div>
        
        @if($winners->hasPages())
        <div class="winners-pagination">
            {{ $winners->links('pagination::bootstrap-4') }}
        </div>
        @endif
        
        <div class="become-winner">
            <div class="become-winner-content">
                <h2>Want to Be Our Next Winner?</h2>
                <p>Start bidding now and you could be featured on this page with your amazing deal!</p>
                <a href="{{ route('auctions') }}" class="btn btn-primary">Browse Auctions</a>
                <a href="{{ route('how-it-works') }}" class="btn btn-outline">Learn More</a>
            </div>
        </div>
    </div>
</div>
@endsection