@extends('layouts.dashboard')

@section('dashboard-title', 'My Watchlist')

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
        <h2>My Watchlist</h2>
        <p>Auctions you're keeping an eye on</p>
    </div>

    <div class="watchlist-items">
        @if($watchlist->count() > 0)
            @foreach($watchlist as $auction)
            @php
                $timeRemaining = '';
                $isEndingSoon = false;
                $statusClass = 'live';
                $statusText = 'Live';

                if($auction->status === 'ended') {
                    $statusClass = 'ended';
                    $statusText = 'Ended';
                    $timeRemaining = 'Auction Ended';
                } elseif($auction->status === 'scheduled') {
                    $statusClass = 'scheduled';
                    $statusText = 'Scheduled';
                    $timeRemaining = 'Not Started';
                } elseif($auction->endTime) {
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
                            if($diff->i < 30) {
                                $isEndingSoon = true;
                                $statusClass = 'ending';
                                $statusText = 'Ending Soon';
                            }
                        }
                        $timeRemaining .= ' remaining';
                    } else {
                        $statusClass = 'ended';
                        $statusText = 'Ended';
                        $timeRemaining = 'Auction Ended';
                    }
                }

                $progress = 0;
                if($auction->startTime && $auction->endTime && $auction->status === 'active') {
                    $total = \Carbon\Carbon::parse($auction->startTime)->diffInSeconds(\Carbon\Carbon::parse($auction->endTime));
                    $elapsed = \Carbon\Carbon::parse($auction->startTime)->diffInSeconds(now());
                    $progress = min(100, max(0, ($elapsed / $total) * 100));
                }

                $totalBids = isset($bidCounts[$auction->id]) ? $bidCounts[$auction->id] : 0;
            @endphp
            <div class="auction-item" style="margin-top:15px;">
                <div class="auction-item-image">
                    @if($auction->images && is_array($auction->images) && count($auction->images) > 0)
                        <img src="{{ asset('storage/' . $auction->images[0]) }}" alt="{{ $auction->title }}" onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                    @else
                        <img src="{{ asset('images/placeholders/product-placeholder.svg') }}" alt="{{ $auction->title }}">
                    @endif
                    <span class="auction-status {{ $statusClass }}">{{ $statusText }}</span>
                </div>
                <div class="auction-item-details">
                    <h3 class="auction-item-title">{{ $auction->title }}</h3>
                    <div class="auction-item-info">
                        <div class="auction-progress-container">
                            <div class="progress-stats">
                                <span>Current Bid: <strong>AED {{ number_format($auction->currentPrice, 2) }}</strong></span>
                                <span>Retail: <strong>AED {{ number_format($auction->retailPrice, 2) }}</strong></span>
                            </div>
                            @if($auction->status === 'active')
                            <div class="auction-progress">
                                <div class="progress-bar" style="width: {{ $progress }}%;"></div>
                            </div>
                            @endif
                            <div class="progress-time">
                                <span><i class="fas fa-clock"></i> {{ $timeRemaining }}</span>
                                <span>{{ $totalBids }} bids placed</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="auction-item-actions">
                    @if($auction->status === 'active')
                        <a href="{{ route('auction.detail', $auction->id) }}" class="btn btn-primary">Bid Now</a>
                    @else
                        <a href="{{ route('auction.detail', $auction->id) }}" class="btn btn-outline">View Details</a>
                    @endif
                    <form action="{{ route('dashboard.watchlist.remove') }}" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="auction_id" value="{{ $auction->id }}">
                        <button type="submit" class="btn btn-outline" onclick="return confirm('Are you sure you want to remove this from your watchlist?')">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3>Your watchlist is empty</h3>
                <p>Start adding auctions to your watchlist to keep track of items you're interested in.</p>
                <a href="{{ url('/auctions') }}" class="btn btn-primary">Browse Auctions</a>
            </div>
        @endif
    </div>

    {{-- Pagination --}}
    @if($watchlist->hasPages())
    <div class="pagination-container">
        <div class="pagination-info">
            Showing {{ $watchlist->firstItem() }} to {{ $watchlist->lastItem() }} of {{ $watchlist->total() }} watchlist items
        </div>
        <nav>
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if ($watchlist->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $watchlist->previousPageUrl() }}" rel="prev">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @php
                    $currentPage = $watchlist->currentPage();
                    $lastPage = $watchlist->lastPage();
                    $start = max(1, $currentPage - 2);
                    $end = min($lastPage, $currentPage + 2);
                @endphp

                {{-- First Page --}}
                @if($start > 1)
                    <li class="page-item">
                        <a class="page-link" href="{{ $watchlist->url(1) }}">1</a>
                    </li>
                    @if($start > 2)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                @endif

                {{-- Page Numbers --}}
                @for($i = $start; $i <= $end; $i++)
                    @if ($i == $currentPage)
                        <li class="page-item active">
                            <span class="page-link">{{ $i }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $watchlist->url($i) }}">{{ $i }}</a>
                        </li>
                    @endif
                @endfor

                {{-- Last Page --}}
                @if($end < $lastPage)
                    @if($end < $lastPage - 1)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                    <li class="page-item">
                        <a class="page-link" href="{{ $watchlist->url($lastPage) }}">{{ $lastPage }}</a>
                    </li>
                @endif

                {{-- Next Page Link --}}
                @if ($watchlist->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $watchlist->nextPageUrl() }}" rel="next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // AJAX remove from watchlist functionality
    const removeForms = document.querySelectorAll('form[action*="watchlist/remove"]');
    removeForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            if (!confirm('Are you sure you want to remove this from your watchlist?')) {
                return;
            }

            const auctionItem = this.closest('.auction-item');
            const auctionId = this.querySelector('input[name="auction_id"]').value;

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
                if (data.success) {
                    // Fade out and remove the auction item
                    auctionItem.style.transition = 'opacity 0.3s';
                    auctionItem.style.opacity = '0';
                    setTimeout(() => {
                        auctionItem.remove();

                        // Check if watchlist is now empty
                        const remainingItems = document.querySelectorAll('.auction-item');
                        if (remainingItems.length === 0) {
                            const watchlistContainer = document.querySelector('.watchlist-items');
                            watchlistContainer.innerHTML = `
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <h3>Your watchlist is empty</h3>
                                    <p>Start adding auctions to your watchlist to keep track of items you're interested in.</p>
                                    <a href="{{ url('/auctions') }}" class="btn btn-primary">Browse Auctions</a>
                                </div>
                            `;
                        }
                    }, 300);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Fallback to regular form submission
                this.submit();
            });
        });
    });
});
</script>
@endsection