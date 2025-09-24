@extends('layouts.dashboard')

@section('dashboard-title', 'Order Details')

@section('styles')
@parent
<style>
    .order-detail-container {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f3f4f6;
    }

    .order-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
    }

    .order-status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .order-status-badge.pending {
        background-color: #fef3c7;
        color: #92400e;
    }

    .order-status-badge.processing {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .order-status-badge.shipped {
        background-color: #d1fae5;
        color: #065f46;
    }

    .order-status-badge.delivered {
        background-color: #e5e7eb;
        color: #374151;
    }

    .order-sections {
        display: grid;
        gap: 30px;
    }

    .order-section {
        padding: 25px;
        background: #f9fafb;
        border-radius: 8px;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: #6b7280;
    }

    .auction-info {
        display: flex;
        gap: 20px;
    }

    .auction-image {
        width: 120px;
        height: 120px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .auction-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .auction-details {
        flex: 1;
    }

    .auction-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 10px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: #6b7280;
        font-size: 0.9rem;
    }

    .detail-value {
        font-weight: 600;
        color: #1f2937;
    }

    .payment-section {
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 25px;
    }

    .payment-warning {
        background: #fef3c7;
        border: 1px solid #f59e0b;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .payment-warning i {
        color: #f59e0b;
        font-size: 1.5rem;
    }

    .payment-warning-text {
        flex: 1;
    }

    .payment-warning-text h4 {
        color: #92400e;
        margin: 0 0 5px 0;
        font-size: 1rem;
    }

    .payment-warning-text p {
        color: #92400e;
        margin: 0;
        font-size: 0.9rem;
    }

    .price-breakdown {
        margin: 20px 0;
    }

    .price-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
    }

    .price-row.total {
        margin-top: 10px;
        padding-top: 15px;
        border-top: 2px solid #e5e7eb;
        font-size: 1.2rem;
        font-weight: 700;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 25px;
    }

    .btn-payment {
        background: #4f46e5;
        color: white;
        padding: 12px 30px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-payment:hover {
        background: #4338ca;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }

    .btn-secondary {
        background: white;
        color: #6b7280;
        padding: 12px 30px;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }

    .payment-info {
        background: #f0fdf4;
        border: 1px solid #10b981;
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
    }

    .payment-info h4 {
        color: #065f46;
        margin: 0 0 10px 0;
    }

    .payment-info p {
        color: #047857;
        margin: 5px 0;
        font-size: 0.9rem;
    }
</style>
@endsection

@section('dashboard-content')
<div class="dashboard-panel active">
    <div class="order-detail-container">
        <!-- Order Header -->
        <div class="order-header">
            <div>
                <h1 class="order-number">Order #{{ $order->id }}</h1>
                <p style="color: #6b7280; margin-top: 5px;">
                    Placed on {{ $order->created_at->format('F d, Y \a\t g:i A') }}
                </p>
            </div>
            <span class="order-status-badge {{ strtolower($order->status) }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        <div class="order-sections">
            <!-- Auction Information -->
            <div class="order-section">
                <h3 class="section-title">
                    <i class="fas fa-gavel"></i> Auction Details
                </h3>
                <div class="auction-info">
                    <div class="auction-image">
                        @if($order->auction->images && is_array($order->auction->images) && count($order->auction->images) > 0)
                            <img src="{{ asset('storage/' . $order->auction->images[0]) }}" alt="{{ $order->auction->title }}">
                        @else
                            <img src="{{ asset('images/placeholders/product-placeholder.svg') }}" alt="{{ $order->auction->title }}">
                        @endif
                    </div>
                    <div class="auction-details">
                        <h4 class="auction-title">{{ $order->auction->title }}</h4>
                        <p style="color: #6b7280; margin-bottom: 15px;">{{ Str::limit($order->auction->description, 150) }}</p>
                        <div style="display: flex; gap: 20px;">
                            <div>
                                <span style="color: #6b7280; font-size: 0.85rem;">Retail Price</span>
                                <p style="font-weight: 600; margin: 0;">${{ number_format($order->auction->retailPrice, 2) }}</p>
                            </div>
                            <div>
                                <span style="color: #6b7280; font-size: 0.85rem;">Won At</span>
                                <p style="font-weight: 600; margin: 0; color: #10b981;">${{ number_format($order->auction->currentPrice, 2) }}</p>
                            </div>
                            <div>
                                <span style="color: #6b7280; font-size: 0.85rem;">You Saved</span>
                                <p style="font-weight: 600; margin: 0; color: #ef4444;">
                                    {{ round((($order->auction->retailPrice - $order->auction->currentPrice) / $order->auction->retailPrice) * 100) }}%
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="order-section payment-section">
                <h3 class="section-title">
                    <i class="fas fa-credit-card"></i> Payment Information
                </h3>

                @if($order->payment_status !== 'paid')
                    <!-- Payment Required Warning -->
                    <div class="payment-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div class="payment-warning-text">
                            <h4>Payment Required</h4>
                            <p>Please complete your payment to process this order.</p>
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="price-breakdown">
                        <div class="price-row">
                            <span class="detail-label">Winning Bid</span>
                            <span class="detail-value">${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="price-row">
                            <span class="detail-label">Shipping Cost</span>
                            <span class="detail-value">${{ number_format($order->shipping_cost, 2) }}</span>
                        </div>
                        <div class="price-row">
                            <span class="detail-label">Tax</span>
                            <span class="detail-value">${{ number_format($order->tax, 2) }}</span>
                        </div>
                        <div class="price-row total">
                            <span>Total Amount</span>
                            <span>${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>

                    <!-- Payment Button -->
                    <form action="{{ route('dashboard.order.payment', $order->id) }}" method="POST">
                        @csrf
                        <div class="action-buttons">
                            <button type="submit" class="btn-payment">
                                <i class="fas fa-lock"></i> Complete Payment with Stripe
                            </button>
                            <a href="{{ route('dashboard.orders') }}" class="btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Orders
                            </a>
                        </div>
                    </form>
                @else
                    <!-- Payment Completed -->
                    <div class="payment-info">
                        <h4><i class="fas fa-check-circle"></i> Payment Completed</h4>
                        <p><strong>Transaction ID:</strong> {{ $order->transaction_id }}</p>
                        <p><strong>Payment Method:</strong> {{ ucfirst($order->paymentMethod) }}</p>
                        <p><strong>Payment Date:</strong> {{ $order->payment_details['payment_date'] ?? $order->updated_at->format('F d, Y \a\t g:i A') }}</p>
                        <p><strong>Amount Paid:</strong> ${{ number_format($order->total, 2) }}</p>
                    </div>

                    <div class="action-buttons">
                        <a href="{{ route('dashboard.orders') }}" class="btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Orders
                        </a>
                    </div>
                @endif
            </div>

            <!-- Order Details -->
            <div class="order-section">
                <h3 class="section-title">
                    <i class="fas fa-info-circle"></i> Order Information
                </h3>
                <div class="detail-row">
                    <span class="detail-label">Order ID</span>
                    <span class="detail-value">{{ $order->id }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Order Status</span>
                    <span class="detail-value">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Status</span>
                    <span class="detail-value">
                        @if($order->payment_status === 'paid')
                            <span style="color: #10b981;">✓ Paid</span>
                        @else
                            <span style="color: #ef4444;">Pending Payment</span>
                        @endif
                    </span>
                </div>
                @if($order->trackingNumber)
                <div class="detail-row">
                    <span class="detail-label">Tracking Number</span>
                    <span class="detail-value">{{ $order->trackingNumber }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection