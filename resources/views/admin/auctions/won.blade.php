@extends('layouts.admin')

@section('title', 'Won Auctions')
@section('page-title', 'Won Auctions')
@section('page-subtitle', 'Manage won auctions and process orders')

@section('styles')
<style>
    .auction-card {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .auction-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e9ecef;
    }

    .auction-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #333;
    }

    .auction-id {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .auction-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
    }

    .detail-label {
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 5px;
    }

    .detail-value {
        font-size: 1rem;
        font-weight: 500;
        color: #333;
    }

    .status-badge {
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-completed {
        background-color: #d4edda;
        color: #155724;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .tab-container {
        margin-bottom: 30px;
    }

    .nav-tabs {
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 20px;
    }

    .nav-tabs .nav-link {
        color: #495057;
        background-color: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 10px 20px;
        margin-right: 10px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .nav-tabs .nav-link.active {
        color: #007bff;
        background-color: transparent;
        border-bottom: 3px solid #007bff;
    }

    .nav-tabs .nav-link:hover {
        color: #007bff;
        background-color: #f8f9fa;
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 3rem;
        color: #dee2e6;
        margin-bottom: 15px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tab Navigation -->
    <div class="tab-container">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#pending-orders">
                    Pending Orders ({{ $auctions->where('order', null)->count() }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#completed-orders">
                    Completed Orders ({{ $auctions->where('order', '!=', null)->count() }})
                </a>
            </li>
        </ul>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Pending Orders Tab -->
        <div class="tab-pane fade show active" id="pending-orders">
            @php
                $pendingAuctions = $auctions->filter(function($auction) {
                    return !$auction->order || !$auction->order->count();
                });
            @endphp

            @if($pendingAuctions->count() > 0)
                @foreach($pendingAuctions as $auction)
                    <div class="auction-card">
                        <div class="auction-header">
                            <div>
                                <h3 class="auction-title">{{ $auction->title }}</h3>
                                <span class="auction-id">Auction ID: {{ $auction->id }}</span>
                            </div>
                            <span class="status-badge status-pending">
                                <i class="fas fa-clock"></i> Pending Order
                            </span>
                        </div>

                        <div class="auction-details">
                            <div class="detail-item">
                                <span class="detail-label">Winner</span>
                                <span class="detail-value">
                                    {{ $auction->winner ? $auction->winner->name : 'N/A' }}
                                    @if($auction->winner)
                                        <small>({{ $auction->winner->email }})</small>
                                    @endif
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Category</span>
                                <span class="detail-value">{{ $auction->category ? $auction->category->name : 'N/A' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Final Price</span>
                                <span class="detail-value">${{ number_format($auction->currentPrice, 2) }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Retail Price</span>
                                <span class="detail-value">${{ number_format($auction->retailPrice, 2) }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Won Date</span>
                                <span class="detail-value">{{ $auction->endTime->format('M d, Y h:i A') }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Total Bids</span>
                                <span class="detail-value">{{ $auction->bids->count() }}</span>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <a href="{{ route('admin.auctions.complete-purchase-form', $auction->id) }}"
                               class="btn btn-primary">
                                <i class="fas fa-shopping-cart"></i> Complete Purchase
                            </a>
                            <a href="{{ route('admin.auctions.show', $auction->id) }}"
                               class="btn btn-info">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            <a href="{{ route('admin.users.show', $auction->winner_id) }}"
                               class="btn btn-secondary">
                                <i class="fas fa-user"></i> View Winner Profile
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="fas fa-trophy"></i>
                    <p>No pending orders at the moment</p>
                </div>
            @endif
        </div>

        <!-- Completed Orders Tab -->
        <div class="tab-pane fade" id="completed-orders">
            @php
                $completedAuctions = $auctions->filter(function($auction) {
                    return $auction->order && $auction->order->count() > 0;
                });
            @endphp

            @if($completedAuctions->count() > 0)
                @foreach($completedAuctions as $auction)
                    <div class="auction-card">
                        <div class="auction-header">
                            <div>
                                <h3 class="auction-title">{{ $auction->title }}</h3>
                                <span class="auction-id">Auction ID: {{ $auction->id }}</span>
                            </div>
                            <span class="status-badge status-completed">
                                <i class="fas fa-check-circle"></i> Order Created
                            </span>
                        </div>

                        <div class="auction-details">
                            <div class="detail-item">
                                <span class="detail-label">Winner</span>
                                <span class="detail-value">
                                    {{ $auction->winner ? $auction->winner->name : 'N/A' }}
                                    @if($auction->winner)
                                        <small>({{ $auction->winner->email }})</small>
                                    @endif
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Order ID</span>
                                <span class="detail-value">
                                    @if($auction->order->first())
                                        {{ $auction->order->first()->id }}
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Order Status</span>
                                <span class="detail-value">
                                    @if($auction->order->first())
                                        <span class="badge bg-{{ $auction->order->first()->status == 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($auction->order->first()->status) }}
                                        </span>
                                    @endif
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Total Amount</span>
                                <span class="detail-value">
                                    @if($auction->order->first())
                                        ${{ number_format($auction->order->first()->total, 2) }}
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Won Date</span>
                                <span class="detail-value">{{ $auction->endTime->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>

                        <div class="action-buttons">
                            @if($auction->order->first())
                                <a href="{{ route('admin.orders.show', $auction->order->first()->id) }}"
                                   class="btn btn-primary">
                                    <i class="fas fa-file-invoice"></i> View Order
                                </a>
                            @endif
                            <a href="{{ route('admin.auctions.show', $auction->id) }}"
                               class="btn btn-info">
                                <i class="fas fa-eye"></i> View Auction
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="fas fa-shopping-cart"></i>
                    <p>No completed orders yet</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $auctions->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality is handled by Bootstrap
});
</script>
@endsection