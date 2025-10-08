@extends('layouts.app')

@section('title', 'All Auctions - ' . config('app.name'))

@section('content')

<style>
.auctions-page {
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
    max-width: 800px;
    margin: 0 auto;
    color: var(--gray);
    font-size: 1.1rem;
}

.auctions-filter-container {
    margin-bottom: 30px;
}

.search-filter-bar {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 8px;
}

.search-wrapper {
    flex: 1;
    display: flex;
    align-items: center;
    background-color: white;
    border: 1px solid #ddd;
    border-radius: 6px;
    overflow: hidden;
}

.search-input {
    flex: 1;
    border: none;
    padding: 12px 15px;
    font-size: 1rem;
    outline: none;
}

.search-btn {
    background-color: var(--primary);
    border: none;
    padding: 12px 20px;
    cursor: pointer;
    color: white;
    transition: background-color 0.3s;
}

.search-btn:hover {
    background-color: var(--primary-dark);
}

.filter-toggle-btn {
    position: relative;
    background-color: white;
    border: 2px solid #ddd;
    padding: 12px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 500;
    color: #333;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
    white-space: nowrap;
}

.filter-toggle-btn:hover {
    background-color: #e9ecef;
    border-color: #999;
    color: #000;
}

.filter-toggle-btn.active {
    background-color: var(--primary);
    color: white;
    border-color: var(--primary);
}

.filter-toggle-btn.active:hover {
    background-color: var(--primary-dark);
    border-color: var(--primary-dark);
}

.filter-dropdown {
    position: absolute;
    top: calc(100% + 10px);
    right: 0;
    background-color: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    padding: 20px;
    min-width: 350px;
    z-index: 1000;
    display: none;
}

.filter-dropdown.show {
    display: block;
}

.filter-dropdown-header {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 15px;
    color: var(--dark);
    padding-bottom: 10px;
    border-bottom: 2px solid #f0f0f0;
}

.filter-group {
    margin-bottom: 15px;
}

.filter-label {
    display: block;
    font-size: 0.9rem;
    font-weight: 500;
    color: #555;
    margin-bottom: 8px;
}

.filter-select {
    width: 100%;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 10px 15px;
    font-size: 1rem;
    appearance: none;
    background-color: white;
    background-image: url('data:image/svg+xml;utf8,<svg fill="black" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>');
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 20px;
    cursor: pointer;
    transition: border-color 0.3s;
}

.filter-select:focus {
    outline: none;
    border-color: var(--primary);
}

.filter-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #f0f0f0;
}

.filter-apply-btn {
    flex: 1;
    background-color: #f89500;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s;
}

.filter-apply-btn:hover {
    background-color: #cf7e03;
}

.filter-reset-btn {
    flex: 1;
    background-color: white;
    color: var(--dark);
    border: 1px solid #ddd;
    padding: 10px 20px;
    border-radius: 6px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
}

.filter-reset-btn:hover {
    background-color: #f8f9fa;
    border-color: #999;
}

.auctions-count {
    margin-bottom: 20px;
    color: var(--gray);
}

.auctions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

.no-auctions {
    grid-column: 1 / -1;
    padding: 50px;
    text-align: center;
    background-color: #f9f9f9;
    border-radius: 8px;
    color: var(--gray);
}

.auction-card {
    border-radius: 8px;
    overflow: hidden;
    background-color: white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    position: relative;
}

.auction-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}

.auction-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background-color: var(--secondary);
    color: white;
    font-size: 0.8rem;
    padding: 5px 10px;
    border-radius: 4px;
    z-index: 10;
    font-weight: 600;
}

.auction-image-container {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.auction-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.auction-card:hover .auction-image {
    transform: scale(1.05);
}

.auction-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 15px;
    background-image: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
    color: white;
    display: flex;
    justify-content: space-between;
    font-size: 0.9rem;
}

.auction-content {
    padding: 20px;
}

.auction-category {
    font-size: 0.9rem;
    color: var(--gray);
    margin-bottom: 10px;
}

.auction-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 15px;
    line-height: 1.3;
    height: 2.8em;
    overflow: hidden;
}

.auction-title a {
    color: var(--dark);
    text-decoration: none;
    transition: color 0.3s;
}

.auction-title a:hover {
    color: var(--primary);
}

.auction-info {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
}

.auction-price {
    font-weight: 600;
    color: var(--secondary-color);
}

.auction-time {
    color: var(--secondary-text);
}

.auction-progress {
    height: 6px;
    background-color: #eee;
    border-radius: 3px;
    margin-bottom: 15px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background-color: var(--secondary-color);
}

.auction-retail {
    display: flex;
    justify-content: space-between;
    font-size: 0.85rem;
    margin-bottom: 15px;
}

.retail-price {
    color: var(--secondary-text);
    text-decoration: line-through;
}

.savings {
    color: var(--success);
    font-weight: 500;
}

/* Auction status styles */
.auction-badge.ended, .auction-badge.closed {
    background-color: #e74c3c;
}

.auction-badge.ending-soon {
    background-color: #87CEEB; /* Light blue color */
    color: #1a5490;
}

.auction-badge.upcoming {
    background-color: #3498db;
}

.auction-card.closed {
    opacity: 0.8;
    position: relative;
}

.auction-card.closed::after {
    content: "AUCTION CLOSED";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-30deg);
    background-color: rgba(231, 76, 60, 0.9);
    color: white;
    font-weight: bold;
    padding: 10px 20px;
    font-size: 18px;
    z-index: 100;
    border-radius: 5px;
    white-space: nowrap;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    letter-spacing: 1px;
}

.auction-time-details {
    margin-top: 5px;
    margin-bottom: 10px;
    font-size: 0.8rem;
    color: #666;
    display: flex;
    justify-content: space-between;
}

.auction-time-details span {
    display: block;
}

.auction-end-time {
    text-align: right;
    font-weight: 500;
}

.auction-time.urgent {
    color: #e74c3c !important;
    font-weight: bold;
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

.pagination-container {
    margin-top: 40px;
    display: flex;
    justify-content: center;
}

/* Laravel pagination styling */
.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    gap: 5px;
}

.page-item {
    display: inline-block;
}

.page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: white;
    color: var(--dark);
    text-decoration: none;
    transition: all 0.3s;
    font-weight: 500;
    border: 1px solid #eee;
}

.page-item.active .page-link {
    background-color: var(--primary);
    color: var(--dark);
    border-color: var(--primary);
}

.page-item.disabled .page-link {
    color: #ccc;
    cursor: not-allowed;
}

.page-link:hover:not(.page-item.disabled .page-link) {
    background-color: #f1f1f1;
}

@media (max-width: 768px) {
    .search-filter-bar {
        flex-direction: column;
        align-items: stretch;
    }

    .search-wrapper {
        width: 100%;
    }

    .filter-toggle-btn {
        width: 100%;
        justify-content: center;
    }

    .filter-dropdown {
        left: 0;
        right: 0;
        min-width: auto;
        width: calc(100% - 30px);
        margin: 0 15px;
    }

    .page-header h1 {
        font-size: 2rem;
    }

    .auction-card {
        min-width: 100%;
    }

    .pagination-item {
        width: 35px;
        height: 35px;
    }

    .auctions-grid {
        grid-template-columns: 1fr;
    }
}
</style>
<div class="auctions-page">
    <div class="container">


        <div class="page-header">
            <h1>All Auctions</h1>
            <p class="page-description">Browse all available auctions on Pixelllo. Bid on your favorite items and win them at a fraction of their retail price.</p>
        </div>

        <div class="auctions-filter-container">
            <div class="search-filter-bar">
                <!-- Search Box - Left Side -->
                <div class="search-wrapper">
                    <input type="text" placeholder="Search auctions..." class="search-input" id="search-input" value="{{ request('search') }}">
                    <button class="search-btn" id="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

                <!-- Filter Button - Right Side -->
                <div style="position: relative;">
                    <button class="filter-toggle-btn" id="filter-toggle-btn">
                        <i class="fas fa-filter"></i>
                        <span>Filters</span>
                        @if(request('status') || request('category') || request('sort'))
                            <span style="background-color: var(--secondary); color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: bold;">●</span>
                        @endif
                    </button>

                    <!-- Filter Dropdown Modal -->
                    <div class="filter-dropdown" id="filter-dropdown">
                        <div class="filter-dropdown-header">
                            <i class="fas fa-sliders-h"></i> Filter Auctions
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Category</label>
                            <select id="filter-category" name="filter-category" class="filter-select">
                                <option value="all" {{ request('category') == null || request('category') == 'all' ? 'selected' : '' }}>All Categories</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Sort By</label>
                            <select id="filter-sort" class="filter-select">
                                <option value="">Default (Ending Soon First)</option>
                                <option value="ending-soon" {{ request('sort') == 'ending-soon' ? 'selected' : '' }}>Ending Soon</option>
                                <option value="price-low" {{ request('sort') == 'price-low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price-high" {{ request('sort') == 'price-high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="bids" {{ request('sort') == 'bids' ? 'selected' : '' }}>Most Bids</option>
                                <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Status</label>
                            <select id="filter-status" class="filter-select">
                                <option value="" {{ request('status') == null ? 'selected' : '' }}>All Auctions</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="ending-soon" {{ request('status') == 'ending-soon' ? 'selected' : '' }}>Ending Soon</option>
                                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="ended" {{ request('status') == 'ended' ? 'selected' : '' }}>Ended/Closed</option>
                            </select>
                        </div>

                        <div class="filter-actions">
                            <button class="filter-apply-btn" id="apply-filters-btn">
                                <i class="fas fa-check"></i> Apply Filters
                            </button>
                            <button class="filter-reset-btn" id="reset-filters-btn">
                                <i class="fas fa-redo-alt"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="auctions-count">
            <span>Showing <strong>{{ $auctions->count() }}</strong> of <strong>{{ $auctions->total() }}</strong> auctions</span>
        </div>

        <div class="auctions-grid">
            @forelse($auctions as $auction)
            @php
                $now = now();
                $isActive = $auction->status === 'active' && $auction->endTime > $now;
                $isClosed = $auction->status === 'ended' || $auction->endTime < $now;
                $isEndingSoon = $isActive && $auction->endTime->diffInHours($now) < 24;

                // Calculate time remaining using proper logic from home page
                $timeLeft = '';
                $timeProgress = 0;

                if ($auction->endTime && $auction->startTime) {
                    if ($now >= $auction->endTime) {
                        // Auction has ended
                        $timeProgress = 100;
                        $timeLeft = 'ENDED';
                    } elseif ($now >= $auction->startTime && $now < $auction->endTime) {
                        // Auction is running - calculate proper countdown
                        $totalDuration = $auction->startTime->diffInSeconds($auction->endTime);
                        $elapsed = $auction->startTime->diffInSeconds($now);

                        if ($totalDuration > 0) {
                            $timeProgress = min(100, max(0, ($elapsed / $totalDuration) * 100));
                        }

                        // Calculate time remaining in seconds
                        $secondsLeft = $now->diffInSeconds($auction->endTime);

                        // Format time remaining
                        $days = floor($secondsLeft / 86400);
                        $hours = floor(($secondsLeft % 86400) / 3600);
                        $minutes = floor(($secondsLeft % 3600) / 60);
                        $secs = $secondsLeft % 60;

                        $parts = [];
                        if ($days > 0) $parts[] = $days . 'd';
                        if ($hours > 0 || $days > 0) $parts[] = $hours . 'h';
                        if ($minutes > 0 || $hours > 0 || $days > 0) $parts[] = $minutes . 'm';
                        $parts[] = $secs . 's';

                        $timeLeft = implode(' ', $parts);
                    } elseif ($now < $auction->startTime) {
                        // Upcoming auction
                        $timeProgress = 0;
                        $timeLeft = 'Not Started';
                    }
                }

                $urgentTime = isset($secondsLeft) && $secondsLeft < 600 && $isActive; // Less than 10 minutes
                $progress = $timeProgress;

                // Calculate savings
                $savings = 0;
                if ($auction->retailPrice > 0 && $auction->currentPrice > 0) {
                    $savings = 100 - (($auction->currentPrice / $auction->retailPrice) * 100);
                }

                // Determine badge
                $badgeClass = '';
                $badgeText = '';

                if ($isClosed) {
                    $badgeClass = 'closed';
                    $badgeText = 'Closed';
                } elseif ($auction->featured) {
                    $badgeClass = 'featured';
                    $badgeText = 'Featured';
                } elseif ($isEndingSoon) {
                    $badgeClass = 'ending-soon';
                    $badgeText = 'Ending Soon';
                } else {
                    $badgeClass = 'active';
                    $badgeText = 'Active';
                }
            @endphp
            <div class="auction-card {{ $isClosed ? 'closed' : '' }}">
                <div class="auction-badge {{ $badgeClass }}">{{ $badgeText }}</div>
                <div class="auction-image-container">
                    @if(isset($auction->images) && is_array($auction->images) && count($auction->images) > 0)
                        <img src="{{ asset('storage/' . $auction->images[0]) }}" alt="{{ $auction->title }}" class="auction-image" onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                    @else
                        <img src="{{ asset('images/placeholders/product-placeholder.svg') }}" alt="{{ $auction->title }}" class="auction-image">
                    @endif
                    <div class="auction-overlay">
                        <span class="auction-bids"><i class="fas fa-gavel"></i> {{ $auction->bids->count() }} bids</span>
                        <span class="auction-watchers"><i class="fas fa-eye"></i> {{ rand(50, 150) }} watching</span>
                    </div>
                </div>
                <div class="auction-content">
                    <div class="auction-category">{{ $auction->category->name }}</div>
                    <h3 class="auction-title"><a href="{{ route('auction.detail', $auction->id) }}">{{ $auction->title }}</a></h3>
                    <div class="auction-info">
                        <span class="auction-price">{{ $isClosed ? 'Final Price:' : 'Current Bid:' }} AED {{ number_format($auction->currentPrice, 2) }}</span>
                        <span class="auction-time {{ $urgentTime ? 'urgent' : '' }}">
                            <i class="fas fa-clock"></i> {{ $isClosed ? 'Ended' : $timeLeft }}
                        </span>
                    </div>
                    <div class="auction-time-details">
                        <span class="auction-start-time">
                            Started: {{ $auction->startTime->format('M j, g:i A') }}
                        </span>
                        <span class="auction-end-time">
                            {{ $isClosed ? 'Ended' : 'Ends' }}: {{ $auction->endTime->format('M j, g:i A') }}
                        </span>
                    </div>
                    <div class="auction-progress">
                        <div class="progress-bar" style="width: {{ $isClosed ? '100' : $progress }}%;"></div>
                    </div>
                    <div class="auction-retail">
                        <span class="retail-price">Retail Price: AED {{ number_format($auction->retailPrice, 2) }}</span>
                        <span class="savings">{{ $isClosed ? 'Saved' : 'You Save' }}: {{ number_format($savings, 0) }}%</span>
                    </div>
                    <a href="{{ route('auction.detail', $auction->id) }}"
                       class="btn {{ $isClosed ? 'btn-outline' : 'btn-primary' }}"
                       style="width: 100%;">
                        {{ $isClosed ? 'View Details' : 'Bid Now' }}
                    </a>
                </div>
            </div>
            @empty
            <div class="no-auctions">
                <p>No auctions available matching your criteria. Please check back later!</p>
            </div>
            @endforelse
        </div>

        <div class="pagination-container">
            {{ $auctions->links() }}
        </div>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter dropdown toggle functionality
    const filterToggleBtn = document.getElementById('filter-toggle-btn');
    const filterDropdown = document.getElementById('filter-dropdown');
    const applyFiltersBtn = document.getElementById('apply-filters-btn');
    const resetFiltersBtn = document.getElementById('reset-filters-btn');
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-btn');

    const filterCategory = document.getElementById('filter-category');
    const filterSort = document.getElementById('filter-sort');
    const filterStatus = document.getElementById('filter-status');

    // Toggle filter dropdown
    filterToggleBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        filterDropdown.classList.toggle('show');
        filterToggleBtn.classList.toggle('active');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!filterDropdown.contains(e.target) && !filterToggleBtn.contains(e.target)) {
            filterDropdown.classList.remove('show');
            filterToggleBtn.classList.remove('active');
        }
    });

    // Prevent dropdown from closing when clicking inside
    filterDropdown.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Apply filters function
    const applyFilters = () => {
        const params = new URLSearchParams(window.location.search);

        // Category filter
        if (filterCategory.value !== 'all') {
            params.set('category', filterCategory.value);
        } else {
            params.delete('category');
        }

        // Sort filter
        if (filterSort.value && filterSort.value !== '') {
            params.set('sort', filterSort.value);
        } else {
            params.delete('sort');
        }

        // Status filter
        if (filterStatus.value && filterStatus.value !== '') {
            params.set('status', filterStatus.value);
        } else {
            params.delete('status');
        }

        // Search
        const searchValue = searchInput.value.trim();
        if (searchValue) {
            params.set('search', searchValue);
        } else {
            params.delete('search');
        }

        // Reload page with filters
        window.location.href = `${window.location.pathname}?${params.toString()}`;
    };

    // Apply filters button click
    applyFiltersBtn.addEventListener('click', function() {
        applyFilters();
    });

    // Reset filters button click
    resetFiltersBtn.addEventListener('click', function() {
        window.location.href = window.location.pathname;
    });

    // Search button click
    searchButton.addEventListener('click', function() {
        applyFilters();
    });

    // Search on Enter key
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });

    // Set current filter values from URL params
    const setFilterValues = () => {
        const params = new URLSearchParams(window.location.search);

        if (params.has('category')) {
            filterCategory.value = params.get('category');
        }

        if (params.has('sort')) {
            filterSort.value = params.get('sort');
        }

        if (params.has('status')) {
            filterStatus.value = params.get('status');
        }

        if (params.has('search')) {
            searchInput.value = params.get('search');
        }
    };

    setFilterValues();
});


</script>
@endsection