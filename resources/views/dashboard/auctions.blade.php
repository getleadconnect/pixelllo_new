@extends('layouts.dashboard')

@section('dashboard-title', 'My Auctions')

@section('styles')
@parent
<style>
    /* Pagination Styles */
    .pagination-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 30px;
        padding: 20px;
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }

    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        gap: 8px;
    }

    .pagination .page-item {
        display: inline-block;
    }

    .pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 12px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: white;
        color: #374151;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .pagination .page-link:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        color: #111827;
    }

    .pagination .page-item.active .page-link {
        background: #ff9900;
        border-color: #ff9900;
        color: white;
    }

    .pagination .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }

    .pagination-info {
        color: #6b7280;
        font-size: 0.9rem;
        margin-right: 20px;
    }

    @media (max-width: 768px) {
        .pagination-container {
            flex-direction: column;
            gap: 15px;
        }

        .pagination-info {
            margin-right: 0;
        }
    }
</style>
@endsection

@section('dashboard-content')
<div class="dashboard-panel active">
    <div class="panel-header">
        <h2>My Auctions</h2>
        <p>Auctions you're currently participating in</p>
    </div>

    <div class="panel-tabs">
        <button class="panel-tab active" data-tab="active-auctions">Active ({{ $activeAuctionsCount }})</button>
        <button class="panel-tab" data-tab="won-auctions">Won ({{ $wonAuctionsCount }})</button>
        <button class="panel-tab" data-tab="lost-auctions">Lost ({{ $lostAuctionsCount }})</button>
    </div>

    <div class="panel-tab-content active" id="active-auctions">
        <div class="auctions-list">
            @if($activeAuctions->count() > 0)
                @foreach($activeAuctions as $auction)
                @php
                    $timeRemaining = '';
                    $isEndingSoon = false;
                    if($auction->endTime) {
                        $now = now();
                        $endTime = \Carbon\Carbon::parse($auction->endTime);
                        if($now < $endTime) {
                            $diff = $now->diff($endTime);
                            if($diff->days > 0) {
                                $timeRemaining = $diff->format('%ad %hh %im');
                            } elseif($diff->h > 0) {
                                $timeRemaining = $diff->format('%hh %im %ss');
                            } else {
                                $timeRemaining = $diff->format('%im %ss');
                                $isEndingSoon = $diff->i < 30;
                            }
                        } else {
                            $timeRemaining = 'Ended';
                        }
                    }
                    $progress = 0;
                    if($auction->startTime && $auction->endTime) {
                        $total = \Carbon\Carbon::parse($auction->startTime)->diffInSeconds(\Carbon\Carbon::parse($auction->endTime));
                        $elapsed = \Carbon\Carbon::parse($auction->startTime)->diffInSeconds(now());
                        $progress = min(100, max(0, ($elapsed / $total) * 100));
                    }
                    $userBids = isset($userBidCounts[$auction->id]) ? $userBidCounts[$auction->id] : 0;
                @endphp
                <div class="auction-item">
                    <div class="auction-item-image">
                        @if($auction->images && is_array($auction->images) && count($auction->images) > 0)
                            <img src="{{ asset('storage/' . $auction->images[0]) }}" alt="{{ $auction->title }}" onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                        @else
                            <img src="{{ asset('images/placeholders/product-placeholder.svg') }}" alt="{{ $auction->title }}">
                        @endif
                        <span class="auction-status {{ $isEndingSoon ? 'ending' : 'live' }}">{{ $isEndingSoon ? 'Ending Soon' : 'Live' }}</span>
                    </div>
                    <div class="auction-item-details">
                        <h3 class="auction-item-title">{{ $auction->title }}</h3>
                        <div class="auction-item-info">
                            <div class="auction-progress-container">
                                <div class="progress-stats">
                                    <span>Current Bid: <strong>${{ number_format($auction->currentPrice, 2) }}</strong></span>
                                    <span>Retail: <strong>${{ number_format($auction->retailPrice, 2) }}</strong></span>
                                </div>
                                <div class="auction-progress">
                                    <div class="progress-bar" style="width: {{ $progress }}%;"></div>
                                </div>
                                <div class="progress-time">
                                    <span><i class="fas fa-clock"></i> {{ $timeRemaining }} remaining</span>
                                    <span>Your Bids: <strong>{{ $userBids }}</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="auction-item-actions">
                        <a href="{{ route('auction.detail', $auction->id) }}" class="btn btn-primary">Bid Now</a>
                        @if(in_array($auction->id, $watchlistIds))
                            <form action="{{ route('dashboard.watchlist.remove') }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="auction_id" value="{{ $auction->id }}">
                                <button type="submit" class="btn btn-outline" title="Remove from Watchlist">
                                    <i class="fas fa-heart" style="color: #ef4444;"></i>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('dashboard.watchlist.add') }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="auction_id" value="{{ $auction->id }}">
                                <button type="submit" class="btn btn-outline" title="Add to Watchlist">
                                    <i class="far fa-heart"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-gavel"></i>
                    </div>
                    <h3>No active auctions</h3>
                    <p>You're not currently participating in any active auctions.</p>
                    <a href="{{ url('/auctions') }}" class="btn btn-primary">Browse Auctions</a>
                </div>
            @endif
        </div>

        {{-- Pagination for Active Auctions --}}
        @if($activeAuctions->hasPages())
        <div class="pagination-container">
            <div class="pagination-info">
                Showing {{ $activeAuctions->firstItem() }} to {{ $activeAuctions->lastItem() }} of {{ $activeAuctions->total() }} active auctions
            </div>
            {{ $activeAuctions->appends(['won_page' => request('won_page'), 'lost_page' => request('lost_page')])->links('pagination::bootstrap-4') }}
        </div>
        @endif
    </div>

    <div class="panel-tab-content" id="won-auctions">
        <div class="auctions-list">
            @if($wonAuctions->count() > 0)
                @foreach($wonAuctions as $auction)
                @php
                    $finalBid = \App\Models\Bid::where('auction_id', $auction->id)
                        ->where('user_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->first();
                    $bidsUsed = \App\Models\Bid::where('auction_id', $auction->id)
                        ->where('user_id', $user->id)
                        ->count();
                    $savings = 0;
                    if($auction->retailPrice > 0 && $finalBid) {
                        $savings = round((($auction->retailPrice - $finalBid->amount) / $auction->retailPrice) * 100);
                    }
                    $order = \App\Models\Order::where('auction_id', $auction->id)
                        ->where('user_id', $user->id)
                        ->first();
                @endphp
                <div class="auction-item">
                    <div class="auction-item-image">
                        @if($auction->images && is_array($auction->images) && count($auction->images) > 0)
                            <img src="{{ asset('storage/' . $auction->images[0]) }}" alt="{{ $auction->title }}" onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                        @else
                            <img src="{{ asset('images/placeholders/product-placeholder.svg') }}" alt="{{ $auction->title }}">
                        @endif
                        <span class="auction-status won">Won</span>
                    </div>
                    <div class="auction-item-details">
                        <h3 class="auction-item-title">{{ $auction->title }}</h3>
                        <div class="auction-item-info">
                            <div class="win-info">
                                <div class="win-detail">
                                    <span>Final Bid:</span>
                                    <strong>${{ $finalBid ? number_format($finalBid->amount, 2) : '0.00' }}</strong>
                                </div>
                                <div class="win-detail">
                                    <span>Retail Price:</span>
                                    <strong>${{ number_format($auction->retailPrice, 2) }}</strong>
                                </div>
                                <div class="win-detail">
                                    <span>You Saved:</span>
                                    <strong class="saving">{{ $savings }}%</strong>
                                </div>
                                <div class="win-detail">
                                    <span>Won On:</span>
                                    <strong>{{ \Carbon\Carbon::parse($auction->endTime)->format('M d, Y') }}</strong>
                                </div>
                                <div class="win-detail">
                                    <span>Bids Used:</span>
                                    <strong>{{ $bidsUsed }} bids</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="auction-item-actions">
                        @if($order)
                            @if($order->status == 'delivered')
                                <a href="#" class="btn btn-success">Delivered</a>
                            @elseif($order->status == 'shipped')
                                <a href="#" class="btn btn-info">Shipped</a>
                            @else
                                <a href="{{ route('dashboard.orders') }}" class="btn btn-warning">Processing</a>
                            @endif
                            <a href="{{ route('dashboard.orders') }}" class="btn btn-outline">Order Details</a>
                        @else
                            <a href="{{ route('dashboard.checkout', $auction->id) }}" class="btn btn-primary">Complete Purchase</a>
                            <a href="{{ route('auction.detail', $auction->id) }}" class="btn btn-outline">View Auction</a>
                        @endif
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3>No won auctions yet</h3>
                    <p>When you win an auction, it will appear here.</p>
                    <a href="{{ url('/auctions') }}" class="btn btn-primary">Browse Auctions</a>
                </div>
            @endif
        </div>

        {{-- Pagination for Won Auctions --}}
        @if($wonAuctions->hasPages())
        <div class="pagination-container">
            <div class="pagination-info">
                Showing {{ $wonAuctions->firstItem() }} to {{ $wonAuctions->lastItem() }} of {{ $wonAuctions->total() }} won auctions
            </div>
            {{ $wonAuctions->appends(['active_page' => request('active_page'), 'lost_page' => request('lost_page')])->links('pagination::bootstrap-4') }}
        </div>
        @endif
    </div>

    <div class="panel-tab-content" id="lost-auctions">
        <div class="auctions-list">
            @if($lostAuctions->count() > 0)
                @foreach($lostAuctions as $auction)
                @php
                    $userBidsOnAuction = \App\Models\Bid::where('auction_id', $auction->id)
                        ->where('user_id', $user->id)
                        ->count();
                    $highestBid = \App\Models\Bid::where('auction_id', $auction->id)
                        ->orderBy('amount', 'desc')
                        ->first();
                    $winner = $auction->winner;
                @endphp
                <div class="auction-item">
                    <div class="auction-item-image">
                        @if($auction->images && is_array($auction->images) && count($auction->images) > 0)
                            <img src="{{ asset('storage/' . $auction->images[0]) }}" alt="{{ $auction->title }}" onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                        @else
                            <img src="{{ asset('images/placeholders/product-placeholder.svg') }}" alt="{{ $auction->title }}">
                        @endif
                        <span class="auction-status" style="background-color: #6b7280;">Lost</span>
                    </div>
                    <div class="auction-item-details">
                        <h3 class="auction-item-title">{{ $auction->title }}</h3>
                        <div class="auction-item-info">
                            <div class="win-info">
                                <div class="win-detail">
                                    <span>Final Price:</span>
                                    <strong>${{ number_format($auction->currentPrice, 2) }}</strong>
                                </div>
                                <div class="win-detail">
                                    <span>Retail Price:</span>
                                    <strong>${{ number_format($auction->retailPrice, 2) }}</strong>
                                </div>
                                <div class="win-detail">
                                    <span>Your Bids:</span>
                                    <strong>{{ $userBidsOnAuction }} bids</strong>
                                </div>
                                <div class="win-detail">
                                    <span>Ended On:</span>
                                    <strong>{{ \Carbon\Carbon::parse($auction->endTime)->format('M d, Y') }}</strong>
                                </div>
                                @if($winner)
                                <div class="win-detail">
                                    <span>Won By:</span>
                                    <strong>{{ substr($winner->name, 0, 3) }}***</strong>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="auction-item-actions">
                        <a href="{{ route('auction.detail', $auction->id) }}" class="btn btn-outline">View Details</a>
                        <a href="{{ url('/auctions') }}" class="btn btn-primary">Find Similar</a>
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>No lost auctions to display</h3>
                    <p>When you participate in auctions that you don't win, they will appear here.</p>
                    <a href="{{ url('/auctions') }}" class="btn btn-primary">Browse Auctions</a>
                </div>
            @endif
        </div>

        {{-- Pagination for Lost Auctions --}}
        @if($lostAuctions->hasPages())
        <div class="pagination-container">
            <div class="pagination-info">
                Showing {{ $lostAuctions->firstItem() }} to {{ $lostAuctions->lastItem() }} of {{ $lostAuctions->total() }} lost auctions
            </div>
            {{ $lostAuctions->appends(['active_page' => request('active_page'), 'won_page' => request('won_page')])->links('pagination::bootstrap-4') }}
        </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Determine active tab based on URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        let activeTab = 'active-auctions'; // default tab

        if (urlParams.has('won_page') && urlParams.get('won_page') > 1) {
            activeTab = 'won-auctions';
        } else if (urlParams.has('lost_page') && urlParams.get('lost_page') > 1) {
            activeTab = 'lost-auctions';
        }

        // Set initial active tab based on pagination
        if (activeTab !== 'active-auctions') {
            const tabs = document.querySelectorAll('.panel-tab');
            const tabContents = document.querySelectorAll('.panel-tab-content');

            // Remove active from all
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));

            // Set the correct tab active
            const targetTab = document.querySelector(`[data-tab="${activeTab}"]`);
            const targetContent = document.getElementById(activeTab);

            if (targetTab) targetTab.classList.add('active');
            if (targetContent) targetContent.classList.add('active');
        }

        // Panel tabs functionality
        const panelTabs = document.querySelectorAll('.panel-tab');
        panelTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Get parent panel
                const parentPanel = this.closest('.dashboard-panel');
                // Get tabs in this panel
                const tabsInPanel = parentPanel.querySelectorAll('.panel-tab');
                // Get tab contents in this panel
                const tabContents = parentPanel.querySelectorAll('.panel-tab-content');

                // Remove active class from all tabs and contents
                tabsInPanel.forEach(t => t.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));

                // Add active class to clicked tab and corresponding content
                this.classList.add('active');
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });

        // Watchlist functionality with AJAX (optional enhancement)
        const watchlistForms = document.querySelectorAll('form[action*="watchlist"]');
        watchlistForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const button = this.querySelector('button');
                const icon = button.querySelector('i');
                const auctionId = this.querySelector('input[name="auction_id"]').value;
                const isAdding = this.action.includes('add');

                // Disable button during request
                button.disabled = true;

                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.querySelector('input[name="_token"]').value,
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Accept': 'application/json'
                    },
                    body: 'auction_id=' + auctionId
                })
                .then(response => response.json())
                .then(data => {
                    // Toggle heart icon
                    if (isAdding) {
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                        icon.style.color = '#ef4444';
                        button.title = 'Remove from Watchlist';
                        form.action = form.action.replace('add', 'remove');
                    } else {
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                        icon.style.color = '';
                        button.title = 'Add to Watchlist';
                        form.action = form.action.replace('remove', 'add');
                    }

                    // Re-enable button
                    button.disabled = false;

                    // Show success message (optional)
                    if (data.message) {
                        console.log(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    button.disabled = false;
                    // Fallback to regular form submission
                    form.submit();
                });
            });
        });
    });
</script>
@endsection