@extends('layouts.dashboard')

@section('dashboard-title', 'My Orders')

@section('styles')
@parent
<style>
    .orders-container {
        padding: 20px;
    }

    .panel-header {
        margin-bottom: 30px;
    }

    .panel-header h2 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .panel-header p {
        color: #6b7280;
        font-size: 1rem;
    }

    .orders-filters {
        display: flex;
        gap: 15px;
        align-items: center;
        margin-bottom: 30px;
        padding: 20px;
        background: #f9fafb;
        border-radius: 12px;
    }

    .filter-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter-item label {
        font-weight: 600;
        color: #374151;
    }

    .form-select {
        padding: 8px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: white;
        font-size: 0.95rem;
        min-width: 180px;
    }

    .btn-filter {
        background: #ff9900;
        color: white;
        padding: 8px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-filter:hover {
        background: #e68a00;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 153, 0, 0.3);
    }

    .orders-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .order-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .order-card:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    .order-header-left {
        display: flex;
        gap: 30px;
        align-items: center;
    }

    .order-id {
        font-weight: 700;
        color: #1f2937;
        font-size: 1rem;
    }

    .order-date {
        color: #6b7280;
        font-size: 0.9rem;
    }

    .order-status {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .order-status.pending {
        background-color: #fef3c7;
        color: #92400e;
    }

    .order-status.processing {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .order-status.shipped {
        background-color: #d1fae5;
        color: #065f46;
    }

    .order-status.delivered {
        background-color: #d4f4dd;
        color: #065f46;
    }

    .order-status.payment-pending {
        background-color: #fef3c7;
        color: #d97706;
    }

    .order-content {
        padding: 20px;
    }

    .order-product {
        display: flex;
        gap: 20px;
    }

    .order-product-image {
        width: 115px;
        height: 115px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
        background: #f3f4f6;
    }

    .order-product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .order-product-details {
        flex: 1;
    }

    .order-product-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 12px;
        line-height: 1.4;
    }

    .order-product-info {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .order-product-price {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
        color: #6b7280;
        font-size: 0.95rem;
    }

    .order-product-price strong {
        color: #1f2937;
        font-weight: 600;
    }

    .order-delivery-info {
        display: flex;
        gap: 20px;
        color: #6b7280;
        font-size: 0.9rem;
        margin-top: 8px;
    }

    .order-actions {
        display: flex;
        gap: 12px;
        padding: 10px 20px;
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 20px;
        border-radius: 8px;
        font-size: 0.95rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: #ff9900;
        color: white;
    }

    .btn-primary:hover {
        background: #e68a00;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 153, 0, 0.3);
    }

    .btn-outline {
        background: white;
        color: #6b7280;
        border: 2px solid #e5e7eb;
    }

    .btn-outline:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        color: #374151;
    }

    .btn-success {
        background: #10b981;
        color: white;
        cursor: default;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
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
        .order-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .order-header-left {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .order-product {
            flex-direction: column;
        }

        .order-product-image {
            width: 100%;
            height: 200px;
        }

        .order-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

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
        <h2>My Orders</h2>
        <p>Track your purchases and order history</p>
    </div>

    <div class="orders-filters">
        <div class="filter-item">
            <label for="orderStatus">Status:</label>
            <select id="orderStatus" class="form-select">
                <option value="all" selected>All Orders</option>
                <option value="pending">Pending Payment</option>
                <option value="processing">Processing</option>
                <option value="shipped">Shipped</option>
                <option value="delivered">Delivered</option>
            </select>
        </div>
        <button class="btn-filter" onclick="filterOrders()">Apply Filter</button>
    </div>

    <div class="orders-list">
        @forelse($orders as $order)
        @php
            // Determine payment status
            $paymentPending = $order->payment_status !== 'paid';

            // Get the first image from auction
            $productImage = null;
            if ($order->auction && $order->auction->images) {
                $images = is_string($order->auction->images) ? json_decode($order->auction->images, true) : $order->auction->images;
                $productImage = is_array($images) && count($images) > 0 ? $images[0] : null;
            }

            // Format order ID for display
            $displayOrderId = 'PXL' . strtoupper(substr($order->id, 0, 4));
        @endphp
        <div class="order-card" data-status="{{ $paymentPending ? 'pending' : strtolower($order->status) }}">
            <div class="order-header">
                <div class="order-header-left">
                    <div class="order-id">Order #{{ $displayOrderId }}</div>
                    <div class="order-date">Placed on {{ $order->created_at->format('M d, Y') }}</div>
                </div>
                <div class="order-status {{ $paymentPending ? 'payment-pending' : strtolower($order->status) }}">
                    {{ $paymentPending ? 'PAYMENT PENDING' : strtoupper($order->status) }}
                </div>
            </div>
            <div class="order-content">
                <div class="order-product">
                    <div class="order-product-image">
                        @if($productImage)
                            <img src="{{ asset('storage/' . $productImage) }}"
                                 alt="{{ $order->auction->title }}"
                                 onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                        @else
                            <img src="{{ asset('images/placeholders/product-placeholder.svg') }}"
                                 alt="{{ $order->auction->title ?? 'Product' }}">
                        @endif
                    </div>
                    <div class="order-product-details">
                        <h3 class="order-product-title">{{ $order->auction->title ?? 'Product Name' }}</h3>
                        <div class="order-product-info">
                            <div class="order-product-price">
                                <span>Winning Bid: <strong>AED {{ number_format($order->subtotal ?? $order->auction->currentPrice ?? 0, 2) }}</strong></span>
                                <span>+ Shipping: <strong>AED {{ number_format($order->shipping_cost ?? 0, 2) }}</strong></span>
                                <span>Total: <strong>AED {{ number_format($order->total ?? $order->amount ?? 0, 2) }}</strong></span>
                            </div>
                            @if($order->status === 'delivered' || $order->status === 'shipped')
                            <div class="order-delivery-info">
                                @if($order->status === 'delivered')
                                    <span>Delivered on {{ $order->updated_at->format('M d, Y') }}</span>
                                @endif
                                @if($order->trackingNumber)
                                    <span>Tracking #: {{ $order->trackingNumber }}</span>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="order-actions">
                @if($paymentPending)
                    <a href="{{ route('dashboard.order.show', $order->id) }}" class="btn btn-primary">Complete Payment</a>
                @elseif($order->status === 'delivered')
                    @php
                        $hasReview = \App\Models\Review::where('user_id', Auth::id())
                            ->where('auction_id', $order->auction_id)
                            ->exists();
                    @endphp
                    @if(!$hasReview)
                        <a href="{{ route('dashboard.wins') }}#completed-wins" class="btn btn-primary">Leave Review</a>
                    @else
                        <button class="btn btn-success" disabled style="background: #10b981; cursor: default; border: 2px solid #ff9900;">Review Posted</button>
                    @endif
                @endif
                <a href="{{ route('dashboard.order.show', $order->id) }}" class="btn btn-outline">Order Details</a>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h3>No Orders Yet</h3>
            <p>You haven't placed any orders yet. Start bidding on auctions to win amazing products!</p>
            <a href="{{ route('auctions') }}" class="btn btn-primary">Browse Auctions</a>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
    <div class="pagination-container">
        <div class="pagination-info">
            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
        </div>
        <nav>
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if ($orders->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $orders->previousPageUrl() }}" rel="prev">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @php
                    $currentPage = $orders->currentPage();
                    $lastPage = $orders->lastPage();
                    $start = max(1, $currentPage - 2);
                    $end = min($lastPage, $currentPage + 2);
                @endphp

                {{-- First Page --}}
                @if($start > 1)
                    <li class="page-item">
                        <a class="page-link" href="{{ $orders->url(1) }}">1</a>
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
                            <a class="page-link" href="{{ $orders->url($i) }}">{{ $i }}</a>
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
                        <a class="page-link" href="{{ $orders->url($lastPage) }}">{{ $lastPage }}</a>
                    </li>
                @endif

                {{-- Next Page Link --}}
                @if ($orders->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $orders->nextPageUrl() }}" rel="next">
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
function filterOrders() {
    const status = document.getElementById('orderStatus').value;
    const orders = document.querySelectorAll('.order-card');

    orders.forEach(order => {
        if (status === 'all') {
            order.style.display = 'block';
        } else {
            const orderStatus = order.dataset.status;
            if (orderStatus === status) {
                order.style.display = 'block';
            } else {
                order.style.display = 'none';
            }
        }
    });
}
</script>
@endsection