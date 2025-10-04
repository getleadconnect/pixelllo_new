@extends('layouts.admin')

@section('title', 'Won Auctions')
@section('page-title', 'Won Auctions')
@section('page-subtitle', 'Manage won auctions and process orders')

@section('styles')
<style>
     .icon-fa
    {
        margin-top: 9px;
        font-size: 12px;
    }
    .nav-tabs {
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 20px;
    }

    .nav-tabs .nav-link {
        color: #6b7280;
        background: transparent;
        border: none;
        border-bottom: 2px solid transparent;
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .nav-tabs .nav-link:hover {
        color: #4b5563;
        background-color: #f9fafb;
    }

    .nav-tabs .nav-link.active {
        color: #3b82f6;
        background-color: transparent;
        border-bottom-color: #3b82f6;
    }

    .order-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        transition: box-shadow 0.2s;
    }

    .order-card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e5e7eb;
    }

    .order-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
    }

    .order-id {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .order-details {
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
        color: #6b7280;
        margin-bottom: 5px;
    }

    .detail-value {
        font-size: 1rem;
        font-weight: 500;
        color: #1f2937;
    }

    .status-badge {
        padding: 5px 12px;
        border-radius: 4px;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-block;
    }

    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
        border: 1px solid #fbbf24;
    }

    .status-paid {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #34d399;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6b7280;
    }

    .empty-state i {
        font-size: 3rem;
        color: #e5e7eb;
        margin-bottom: 15px;
    }

    .empty-state p {
        font-size: 1.125rem;
        margin: 0;
    }

    /* Custom Pagination */
    .custom-pagination {
        padding: 15px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #e5e7eb;
        margin-top: 30px;
    }

    .pagination-info {
        color: #6b7280;
        font-size: 14px;
    }

    .pagination-numbers {
        display: flex;
        gap: 5px;
    }

    .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 8px;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        color: #374151;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
        background-color: white;
    }

    .page-link:hover {
        background-color: #f3f4f6;
        border-color: #d1d5db;
    }

    .page-link.active {
        background-color: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    .page-link.disabled {
        cursor: not-allowed;
        opacity: 0.5;
        background-color: #f9fafb;
        color: #9ca3af;
        pointer-events: none;
    }

    .page-dots {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        color: #9ca3af;
        font-size: 14px;
    }

    .auction-without-order {
        background-color: #fef2f2;
        border-color: #fecaca;
    }
</style>
@endsection

@section('content')
<div class="admin-data-card">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">Won Auctions Management</div>
    </div>
    <div class="admin-data-card-body">
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

        <!-- Tab Navigation -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $tab == 'pending' ? 'active' : '' }}"
                   href="{{ route('admin.auctions.won', ['tab' => 'pending']) }}">
                    Pending Orders ({{ $pendingCount }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tab == 'completed' ? 'active' : '' }}"
                   href="{{ route('admin.auctions.won', ['tab' => 'completed']) }}">
                    Completed Orders ({{ $completedCount }})
                </a>
            </li>
        </ul>

        <!-- Orders List -->
        @if($orders->count() > 0)
            @foreach($orders as $order)
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <h3 class="order-title">
                                {{ $order->auction ? Str::limit($order->auction->title, 50) : 'Auction Deleted' }}
                            </h3>
                            <span class="order-id">Order ID: {{ substr($order->id, 0, 8) }}...</span>
                        </div>
                        <span class="status-badge {{ $order->payment_status == 'paid' ? 'status-paid' : 'status-pending' }}">
                            <i class="fas {{ $order->payment_status == 'paid' ? 'fa-check-circle' : 'fa-clock' }}"></i>
                            {{ $order->payment_status == 'paid' ? 'Payment Completed' : 'Payment Pending' }}
                        </span>
                    </div>

                    <div class="order-details">
                        <div class="detail-item">
                            <span class="detail-label">Customer</span>
                            <span class="detail-value">
                                {{ $order->user ? $order->user->name : 'N/A' }}
                                @if($order->user)
                                    <small class="text-muted">({{ $order->user->email }})</small>
                                @endif
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Auction Price</span>
                            <span class="detail-value">AED {{ number_format($order->auction_price ?? 0, 2) }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Shipping</span>
                            <span class="detail-value">AED {{ number_format($order->shipping_cost ?? 0, 2) }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Total Amount</span>
                            <span class="detail-value" style="color: #059669; font-weight: 600;">
                                AED {{ number_format($order->total, 2) }}
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Order Date</span>
                            <span class="detail-value">{{ $order->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Order Status</span>
                            <span class="detail-value">
                                <span class="badge bg-{{
                                    $order->status == 'delivered' ? 'success' :
                                    ($order->status == 'shipped' ? 'info' :
                                    ($order->status == 'processing' ? 'warning' : 'secondary'))
                                }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </span>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i> View Order
                        </a>
                        @if($order->user)
                            <a href="{{ route('admin.users.show', $order->user_id) }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-user"></i> View Customer
                            </a>
                        @endif
                        @if($order->auction)
                            <a href="{{ route('admin.auctions.show', $order->auction_id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-gavel"></i> View Auction
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            @if ($orders->hasPages())
                <div class="custom-pagination">
                    <div class="pagination-info">
                        Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} results
                    </div>
                    <div class="pagination-numbers">
                        @php
                            $currentPage = $orders->currentPage();
                            $lastPage = $orders->lastPage();
                            $start = max(1, $currentPage - 2);
                            $end = min($lastPage, $currentPage + 2);
                        @endphp

                        {{-- Previous Button --}}
                        @if ($currentPage > 1)
                            <a href="{{ $orders->appends(['tab' => $tab])->url($currentPage - 1) }}"
                               class="page-link" title="Previous">
                                <i class="fa fa-arrow-left icon-fa"></i>
                            </a>
                        @else
                            <span class="page-link disabled"><i class="fa fa-arrow-left icon-fa"></i></span>
                        @endif

                        @if ($start > 1)
                            <a href="{{ $orders->appends(['tab' => $tab])->url(1) }}"
                               class="page-link">1</a>
                            @if ($start > 2)
                                <span class="page-dots">...</span>
                            @endif
                        @endif

                        @for ($i = $start; $i <= $end; $i++)
                            <a href="{{ $orders->appends(['tab' => $tab])->url($i) }}"
                               class="page-link {{ $i == $currentPage ? 'active' : '' }}">{{ $i }}</a>
                        @endfor

                        @if ($end < $lastPage)
                            @if ($end < $lastPage - 1)
                                <span class="page-dots">...</span>
                            @endif
                            <a href="{{ $orders->appends(['tab' => $tab])->url($lastPage) }}"
                               class="page-link">{{ $lastPage }}</a>
                        @endif

                        {{-- Next Button --}}
                        @if ($currentPage < $lastPage)
                            <a href="{{ $orders->appends(['tab' => $tab])->url($currentPage + 1) }}"
                               class="page-link" title="Next">
                                <i class="fa fa-arrow-right icon-fa"></i>
                            </a>
                        @else
                            <span class="page-link disabled"><i class="fa fa-arrow-right icon-fa"></i></span>
                        @endif
                    </div>
                </div>
            @endif
        @else
            <div class="empty-state">
                <i class="fas {{ $tab == 'pending' ? 'fa-clock' : 'fa-check-circle' }}"></i>
                <p>No {{ $tab }} orders at the moment</p>
            </div>
        @endif

        <!-- Won Auctions Without Orders (Legacy Support) -->
        @if($wonAuctionsWithoutOrders->count() > 0)
            <div style="margin-top: 40px; padding-top: 30px; border-top: 2px solid #e5e7eb;">
                <h3 style="margin-bottom: 20px; color: #dc2626;">
                    <i class="fas fa-exclamation-triangle"></i> Won Auctions Without Orders
                </h3>
                <p class="text-muted mb-3">These auctions have winners but no order records. </p>

                @foreach($wonAuctionsWithoutOrders as $auction)
                    <div class="order-card auction-without-order">
                        <div class="order-header">
                            <div>
                                <h3 class="order-title">{{ Str::limit($auction->title, 50) }}</h3>
                                <span class="order-id">Auction ID: {{ substr($auction->id, 0, 8) }}...</span>
                            </div>
                            <span class="status-badge status-pending">
                                <i class="fas fa-exclamation"></i> No Order Created
                            </span>
                        </div>

                        <div class="order-details">
                            <div class="detail-item">
                                <span class="detail-label">Winner</span>
                                <span class="detail-value">
                                    {{ $auction->winner ? $auction->winner->name : 'N/A' }}
                                    @if($auction->winner)
                                        <small class="text-muted">({{ $auction->winner->email }})</small>
                                    @endif
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Final Price</span>
                                <span class="detail-value">AED {{ number_format($auction->currentPrice, 2) }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Won Date</span>
                                <span class="detail-value">{{ $auction->endTime ? $auction->endTime->format('M d, Y h:i A') : 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="action-buttons">
                           <!-- <a href="{{ route('admin.auctions.complete-purchase-form', $auction->id) }}"
                               class="btn btn-success btn-sm">
                                <i class="fas fa-plus-circle"></i> Create Order
                            </a> -->
                            <a href="{{ route('admin.auctions.show', $auction->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> View Auction
                            </a>
                        </div>
                    </div>
                @endforeach

                @if ($wonAuctionsWithoutOrders->hasPages())
                    <div class="mt-3">
                        {{ $wonAuctionsWithoutOrders->appends(['tab' => $tab])->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

