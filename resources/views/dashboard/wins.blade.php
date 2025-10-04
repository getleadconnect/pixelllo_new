@extends('layouts.dashboard')

@section('dashboard-title', 'My Wins')

@section('dashboard-content')
<div class="dashboard-panel active">
    <div class="panel-header">
        <h2>My Wins</h2>
        <p>Auctions you've won</p>
        {{-- Debug: Total won auctions count --}}
        @php
            $totalWins = \App\Models\Auction::where('winner_id', Auth::id())->count();
        @endphp
        <small style="color: #666;">Debug: Found {{ $totalWins }} total wins for user ID {{ Auth::id() }}</small>
    </div>

    <div class="panel-tabs">
        <button class="panel-tab active" data-tab="pending-wins">Pending ({{ $pendingWinsCount ?? $pendingWins->total() }})</button>
        <button class="panel-tab" data-tab="completed-wins">Completed ({{ $completedWinsCount ?? $completedWins->total() }})</button>
    </div>

    <div class="panel-tab-content active" id="pending-wins">
        <div class="wins-list">
            @forelse($pendingWins as $auction)
            <div class="win-card">
                <div class="win-image">
                    @if(isset($auction->images) && is_array($auction->images) && count($auction->images) > 0)
                        <img src="{{ asset('storage/' . $auction->images[0]) }}" alt="{{ $auction->title }}" onerror="this.src='https://via.placeholder.com/300x200'">
                    @else
                        <img src="https://via.placeholder.com/300x200" alt="{{ $auction->title }}">
                    @endif
                </div>
                <div class="win-content">
                    <div class="win-header">
                        <h3>{{ $auction->title }}</h3>
                        <span class="win-badge">Won on {{ $auction->endTime->format('M d, Y') }}</span>
                    </div>
                    <div class="win-details">
                        <div class="win-info-grid">
                            <div class="win-info-item">
                                <span class="win-info-label">Final Price</span>
                                <span class="win-info-value">AED {{ number_format($auction->currentPrice, 2) }}</span>
                            </div>
                            <div class="win-info-item">
                                <span class="win-info-label">Bids Used</span>
                                <span class="win-info-value">{{ $winDetails[$auction->id]['bidsUsed'] }} bids</span>
                            </div>
                            <div class="win-info-item">
                                <span class="win-info-label">Retail Price</span>
                                <span class="win-info-value">AED {{ number_format($auction->retailPrice, 2) }}</span>
                            </div>
                            <div class="win-info-item">
                                <span class="win-info-label">You Saved</span>
                                <span class="win-info-value savings">{{ $winDetails[$auction->id]['savings'] }}%</span>
                            </div>
                        </div>
                        <div class="win-status-container">
                            <div class="win-status pending">
                                <i class="fas fa-clock"></i> Pending Payment
                            </div>
                            <p class="win-status-message">Complete your payment to receive your item</p>
                        </div>
                    </div>
                    <div class="win-actions">
                        <a href="{{ route('dashboard.checkout', $auction->id) }}" class="btn btn-primary">Complete Purchase</a>
                        <a href="{{ route('auction.detail', $auction->id) }}" class="btn btn-outline">View Details</a>
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 40px;">
                <i class="fas fa-trophy" style="font-size: 48px; color: #ccc; margin-bottom: 20px;"></i>
                <p style="color: #999;">No pending wins at the moment</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination for Pending Wins --}}
        @if($pendingWins->hasPages())
        <div class="pagination-container">
            <div class="pagination-info">
                Showing {{ $pendingWins->firstItem() }} to {{ $pendingWins->lastItem() }} of {{ $pendingWins->total() }} pending wins
            </div>
            <nav>
                <ul class="pagination">
                    {{-- Previous Page Link --}}
                    @if ($pendingWins->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $pendingWins->previousPageUrl() }}" data-tab="pending-wins">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @php
                        $currentPage = $pendingWins->currentPage();
                        $lastPage = $pendingWins->lastPage();
                        $start = max(1, $currentPage - 2);
                        $end = min($lastPage, $currentPage + 2);
                    @endphp

                    {{-- First Page --}}
                    @if($start > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $pendingWins->url(1) }}" data-tab="pending-wins">1</a>
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
                                <a class="page-link" href="{{ $pendingWins->url($i) }}" data-tab="pending-wins">{{ $i }}</a>
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
                            <a class="page-link" href="{{ $pendingWins->url($lastPage) }}" data-tab="pending-wins">{{ $lastPage }}</a>
                        </li>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($pendingWins->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $pendingWins->nextPageUrl() }}" data-tab="pending-wins">
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

    <div class="panel-tab-content" id="completed-wins">
        <div class="wins-list">
            @forelse($completedWins as $auction)
            <div class="win-card">
                <div class="win-image">
                    @if(isset($auction->images) && is_array($auction->images) && count($auction->images) > 0)
                        <img src="{{ asset('storage/' . $auction->images[0]) }}" alt="{{ $auction->title }}" onerror="this.src='https://via.placeholder.com/300x200'">
                    @else
                        <img src="https://via.placeholder.com/300x200" alt="{{ $auction->title }}">
                    @endif
                </div>
                <div class="win-content">
                    <div class="win-header">
                        <h3>{{ $auction->title }}</h3>
                        <span class="win-badge">Won on {{ $auction->endTime->format('M d, Y') }}</span>
                    </div>
                    <div class="win-details">
                        <div class="win-info-grid">
                            <div class="win-info-item">
                                <span class="win-info-label">Final Price</span>
                                <span class="win-info-value">AED {{ number_format($auction->currentPrice, 2) }}</span>
                            </div>
                            <div class="win-info-item">
                                <span class="win-info-label">Bids Used</span>
                                <span class="win-info-value">{{ $winDetails[$auction->id]['bidsUsed'] }} bids</span>
                            </div>
                            <div class="win-info-item">
                                <span class="win-info-label">Retail Price</span>
                                <span class="win-info-value">AED {{ number_format($auction->retailPrice, 2) }}</span>
                            </div>
                            <div class="win-info-item">
                                <span class="win-info-label">You Saved</span>
                                <span class="win-info-value savings">{{ $winDetails[$auction->id]['savings'] }}%</span>
                            </div>
                        </div>
                        <div class="win-status-container">
                            @if($auction->order && $auction->order->count() > 0)
                                @php $order = $auction->order->first(); @endphp
                                @if($order->status === 'delivered')
                                    <div class="win-status delivered">
                                        <i class="fas fa-check-circle"></i> Delivered
                                    </div>
                                    <p class="win-status-message">Delivered on {{ $order->updated_at->format('M d, Y') }}</p>
                                @elseif($order->status === 'shipped')
                                    <div class="win-status shipped">
                                        <i class="fas fa-shipping-fast"></i> Shipped
                                    </div>
                                    <p class="win-status-message">Shipped on {{ $order->updated_at->format('M d, Y') }}</p>
                                @elseif($order->status === 'processing')
                                    <div class="win-status processing">
                                        <i class="fas fa-box"></i> Processing
                                    </div>
                                    <p class="win-status-message">Order is being processed</p>
                                @endif
                            @else
                                <div class="win-status delivered">
                                    <i class="fas fa-check-circle"></i> Completed
                                </div>
                                <p class="win-status-message">Auction completed on {{ $auction->endTime->format('M d, Y') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="win-actions">
                        @php
                            $hasReview = \App\Models\Review::where('user_id', Auth::id())
                                ->where('auction_id', $auction->id)
                                ->exists();
                        @endphp
                        @if(!$hasReview)
                            <button class="btn btn-primary" onclick="openReviewModal('{{ $auction->id }}', '{{ $auction->title }}', '{{ $order->id ?? '' }}')">Leave Review</button>
                        @else
                            <button class="btn btn-success" disabled style="border: 2px solid #ff9900;">Review Posted</button>
                        @endif
                        <a href="{{ route('auction.detail', $auction->id) }}" class="btn btn-outline">View Details</a>
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 40px;">
                <i class="fas fa-trophy" style="font-size: 48px; color: #ccc; margin-bottom: 20px;"></i>
                <p style="color: #999;">No completed wins yet</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination for Completed Wins --}}
        @if($completedWins->hasPages())
        <div class="pagination-container">
            <div class="pagination-info">
                Showing {{ $completedWins->firstItem() }} to {{ $completedWins->lastItem() }} of {{ $completedWins->total() }} completed wins
            </div>
            <nav>
                <ul class="pagination">
                    {{-- Previous Page Link --}}
                    @if ($completedWins->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $completedWins->previousPageUrl() }}" data-tab="completed-wins">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @php
                        $currentPage = $completedWins->currentPage();
                        $lastPage = $completedWins->lastPage();
                        $start = max(1, $currentPage - 2);
                        $end = min($lastPage, $currentPage + 2);
                    @endphp

                    {{-- First Page --}}
                    @if($start > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $completedWins->url(1) }}" data-tab="completed-wins">1</a>
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
                                <a class="page-link" href="{{ $completedWins->url($i) }}" data-tab="completed-wins">{{ $i }}</a>
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
                            <a class="page-link" href="{{ $completedWins->url($lastPage) }}" data-tab="completed-wins">{{ $lastPage }}</a>
                        </li>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($completedWins->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $completedWins->nextPageUrl() }}" data-tab="completed-wins">
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
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        // Check URL parameters to determine which tab should be active
        const urlParams = new URLSearchParams(window.location.search);
        const pendingPage = urlParams.get('pending_page');
        const completedPage = urlParams.get('completed_page');

        // If completed_page parameter exists, switch to completed tab
        if (completedPage && !pendingPage) {
            const completedTab = document.querySelector('[data-tab="completed-wins"]');
            if (completedTab) {
                completedTab.click();
            }
        }

        // Handle pagination links to maintain active tab
        const paginationLinks = document.querySelectorAll('.pagination a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const tabToActivate = this.getAttribute('data-tab');
                const href = this.getAttribute('href');

                // Navigate to the page
                window.location.href = href;
            });
        });
    });
</script>
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

    .win-status.shipped {
        background-color: #17a2b8;
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
    }

    .win-status.processing {
        background-color: #ffc107;
        color: #212529;
        padding: 8px 16px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
    }

    /* Review Modal Styles */
    .review-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        animation: fadeIn 0.3s;
    }

    .review-modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .review-modal-content {
        background-color: white;
        padding: 30px;
        border-radius: 12px;
        width: 90%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        animation: slideUp 0.3s;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .review-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f3f4f6;
    }

    .review-modal-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
    }

    .review-modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #9ca3af;
        cursor: pointer;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s;
    }

    .review-modal-close:hover {
        background: #f3f4f6;
        color: #374151;
    }

    .review-form {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        font-size: 0.95rem;
    }

    .form-label .required {
        color: #ef4444;
    }

    .star-rating {
        display: flex;
        gap: 8px;
        font-size: 2rem;
    }

    .star-rating input[type="radio"] {
        display: none;
    }

    .star-rating label {
        cursor: pointer;
        color: #d1d5db;
        transition: color 0.2s;
        font-size:45px;
    }

    .star-rating input[type="radio"]:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #fbbf24;
    }

    .star-rating {
        flex-direction: row-reverse;
        justify-content: flex-end;
    }

    .form-input,
    .form-textarea {
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #ff9900;
        box-shadow: 0 0 0 3px rgba(255, 153, 0, 0.1);
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 10px;
        justify-content: flex-end;
    }

    .btn-submit {
        background: #ff9900;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        background: #e68a00;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 153, 0, 0.3);
    }

    .btn-cancel {
        background: white;
        color: #6b7280;
        padding: 12px 30px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }
</style>

<!-- Review Modal -->
<div id="reviewModal" class="review-modal" style="line-height:1rem !important;">
    <div class="review-modal-content">
        <div class="review-modal-header">
            <h2 class="review-modal-title">Leave a Review</h2>
            <button class="review-modal-close" onclick="closeReviewModal()">&times;</button>
        </div>

        <form id="reviewForm" class="review-form" method="POST" action="{{ route('dashboard.review.submit') }}">
            @csrf
            <input type="hidden" id="review_auction_id" name="auction_id">
            <input type="hidden" id="review_order_id" name="order_id">

            <div class="form-group">
                <label class="form-label">Product</label>
                <div id="review_product_name" style="color: #6b7280; font-size: 0.95rem;"></div>
            </div>

            <div class="form-group">
                <label class="form-label">Rating <span class="required">*</span></label>
                <div class="star-rating">
                    <input type="radio" id="star5" name="rating" value="5" required>
                    <label for="star5" title="5 stars">★</label>
                    <input type="radio" id="star4" name="rating" value="4">
                    <label for="star4" title="4 stars">★</label>
                    <input type="radio" id="star3" name="rating" value="3">
                    <label for="star3" title="3 stars">★</label>
                    <input type="radio" id="star2" name="rating" value="2">
                    <label for="star2" title="2 stars">★</label>
                    <input type="radio" id="star1" name="rating" value="1">
                    <label for="star1" title="1 star">★</label>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="review_title">Review Title</label>
                <input type="text" id="review_title" name="title" class="form-input" placeholder="Summarize your experience">
            </div>

            <div class="form-group">
                <label class="form-label" for="review_comment">Your Review <span class="required">*</span></label>
                <textarea id="review_comment" name="comment" class="form-textarea" required placeholder="Tell us about your experience with this product"></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">Submit Review</button>
                <button type="button" class="btn-cancel" onclick="closeReviewModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openReviewModal(auctionId, productName, orderId) {
    document.getElementById('review_auction_id').value = auctionId;
    document.getElementById('review_order_id').value = orderId || '';
    document.getElementById('review_product_name').textContent = productName;
    document.getElementById('reviewModal').classList.add('show');
}

function closeReviewModal() {
    document.getElementById('reviewModal').classList.remove('show');
    document.getElementById('reviewForm').reset();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('reviewModal');
    if (event.target == modal) {
        closeReviewModal();
    }
}

// Handle form submission
document.getElementById('reviewForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeReviewModal();
            Swal.fire({
                icon: 'success',
                title: 'Review Submitted!',
                text: 'Thank you for your feedback.',
                confirmButtonColor: '#ff9900'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to submit review',
                confirmButtonColor: '#ef4444'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while submitting your review',
            confirmButtonColor: '#ef4444'
        });
    });
});
</script>
@endsection