@extends('layouts.admin')

@section('title', 'Order Details')
@section('page-title', 'Order Details')
@section('page-subtitle', 'View detailed information about this order')

@section('content')
<div class="admin-data-card">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">Order #{{ $order->id }}</div>
        <div class="admin-data-card-actions">
            <a href="{{ url('/admin/orders') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
            <a href="{{ url('/admin/users/' . ($order->user->id ?? '')) }}" class="btn btn-sm btn-info">
                <i class="fas fa-user"></i> View Customer
            </a>
            <a href="{{ url('/admin/auctions/' . ($order->auction->id ?? '')) }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-gavel"></i> View Auction
            </a>
        </div>
    </div>
    <div class="admin-data-card-body">
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
            <!-- Order Details -->
            <div>
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                    <div>
                        <h4 style="margin-bottom: 5px;">Order Information</h4>
                        <p style="color: #777; margin: 0;">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
                    </div>
                    <div>
                        <span class="status-badge 
                            {{ $order->status == 'delivered' ? 'active' : 
                               ($order->status == 'shipped' ? 'processing' : 
                                ($order->status == 'processing' ? 'pending' : 'inactive')) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
                
                <!-- Order Item -->
                <div style="background: #f9f9f9; border-radius: 8px; padding: 20px; margin-bottom: 30px;">
                    <div style="display: flex; gap: 20px;">
                        <div style="width: 120px; height: 120px;">
                            @if (!empty($order->auction->images))
                                <img src="{{ asset('storage/' . $order->auction->images[0]) }}" alt="{{ $order->auction->title }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                            @else
                                <div style="width: 100%; height: 100%; background-color: #eee; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-image" style="font-size: 2rem; color: #ccc;"></i>
                                </div>
                            @endif
                        </div>
                        <div style="flex: 1;">
                            <h4 style="margin-bottom: 10px;">{{ $order->auction->title ?? 'Unknown Auction' }}</h4>
                            <p style="color: #777; margin-bottom: 5px;">Won at final price: <strong>${{ number_format($order->auction->currentPrice ?? 0, 2) }}</strong></p>
                            <p style="color: #777; margin-bottom: 5px;">Retail price: <strong>${{ number_format($order->auction->retailPrice ?? 0, 2) }}</strong></p>
                            <p style="color: #777; margin-bottom: 0;">Savings: <strong>{{ $order->auction && $order->auction->retailPrice ? number_format(100 - (($order->auction->currentPrice / $order->auction->retailPrice) * 100), 0) : 0 }}%</strong></p>
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div style="margin-bottom: 30px;">
                    <h4 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Order Summary</h4>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <div>Item Price:</div>
                        <div>${{ number_format($order->subtotal, 2) }}</div>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <div>Shipping & Handling:</div>
                        <div>${{ number_format($order->shipping_cost, 2) }}</div>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <div>Tax:</div>
                        <div>${{ number_format($order->tax, 2) }}</div>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding-top: 10px; border-top: 1px solid #eee; font-weight: 700;">
                        <div>Order Total:</div>
                        <div>${{ number_format($order->total, 2) }}</div>
                    </div>
                </div>
                
                <!-- Order Status History (if available) -->
                @if (isset($order->status_history) && !empty($order->status_history))
                    <div style="margin-bottom: 30px;">
                        <h4 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Status History</h4>
                        <div style="display: flex; flex-direction: column;">
                            @foreach ($order->status_history as $history)
                                <div style="display: flex; margin-bottom: 15px;">
                                    <div style="width: 20px; height: 20px; border-radius: 50%; background-color: #28a745; margin-right: 15px; margin-top: 3px;"></div>
                                    <div>
                                        <div style="font-weight: 600;">{{ ucfirst($history['status']) }}</div>
                                        <div style="color: #777; font-size: 0.9rem;">{{ \Carbon\Carbon::parse($history['timestamp'])->format('F d, Y \a\t h:i A') }}</div>
                                        @if (!empty($history['comment']))
                                            <div style="margin-top: 5px;">{{ $history['comment'] }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <!-- Order Notes -->
                <div style="margin-bottom: 30px;">
                    <h4 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Order Notes</h4>
                    @if (!empty($order->notes))
                        <div style="background: #f9f9f9; border-radius: 8px; padding: 15px;">
                            {!! nl2br(e($order->notes)) !!}
                        </div>
                    @else
                        <p>No notes for this order.</p>
                    @endif
                    
                    <!-- Add Note Form -->
                    <div style="margin-top: 20px;">
                        <form action="{{ url('/admin/orders/' . $order->id . '/notes') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="note">Add Note</label>
                                <textarea name="note" id="note" class="form-control" rows="3"></textarea>
                            </div>
                            <div style="text-align: right; margin-top: 10px;">
                                <button type="submit" class="btn btn-primary">Add Note</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Customer and Shipping Info -->
            <div>
                <!-- Customer Information -->
                <div style="margin-bottom: 30px;">
                    <h4 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Customer Information</h4>
                    <p style="margin-bottom: 5px;"><strong>Name:</strong> {{ $order->user->name ?? 'Unknown' }}</p>
                    <p style="margin-bottom: 5px;"><strong>Email:</strong> {{ $order->user->email ?? 'Unknown' }}</p>
                    <p style="margin-bottom: 5px;"><strong>Phone:</strong> {{ $order->user->phone ?? 'Not provided' }}</p>
                    <p style="margin-bottom: 5px;"><strong>Account Created:</strong> {{ $order->user ? $order->user->created_at->format('M d, Y') : 'Unknown' }}</p>
                    <p style="margin-bottom: 5px;"><strong>Status:</strong> 
                        <span class="status-badge {{ $order->user && $order->user->active ? 'active' : 'inactive' }}">
                            {{ $order->user && $order->user->active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                    <p><a href="{{ url('/admin/users/' . ($order->user->id ?? '')) }}" class="btn btn-sm btn-primary">View Customer Profile</a></p>
                </div>
                
                <!-- Shipping Information -->
                <div style="margin-bottom: 30px;">
                    <h4 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Shipping Information</h4>
                    @if (!empty($order->shipping_address))
                        <p style="margin-bottom: 5px;"><strong>Name:</strong> {{ $order->shipping_address['name'] ?? $order->user->name ?? 'Unknown' }}</p>
                        <p style="margin-bottom: 5px;"><strong>Address:</strong><br>
                            {{ $order->shipping_address['line1'] ?? '' }}<br>
                            @if (!empty($order->shipping_address['line2']))
                                {{ $order->shipping_address['line2'] }}<br>
                            @endif
                            {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['zip'] ?? '' }}<br>
                            {{ $order->shipping_address['country'] ?? '' }}
                        </p>
                        <p style="margin-bottom: 5px;"><strong>Phone:</strong> {{ $order->shipping_address['phone'] ?? $order->user->phone ?? 'Not provided' }}</p>
                    @else
                        <p>No shipping information available.</p>
                    @endif
                </div>
                
                <!-- Payment Information -->
                <div style="margin-bottom: 30px;">
                    <h4 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Payment Information</h4>
                    
                    @if (!empty($order->payment_method))
                        <p style="margin-bottom: 5px;"><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                        
                        @if ($order->payment_method == 'card' && !empty($order->payment_details))
                            <p style="margin-bottom: 5px;"><strong>Card Type:</strong> {{ ucfirst($order->payment_details['card_type'] ?? 'Unknown') }}</p>
                            <p style="margin-bottom: 5px;"><strong>Last 4 Digits:</strong> •••• {{ $order->payment_details['last4'] ?? 'xxxx' }}</p>
                        @endif
                        
                        <p style="margin-bottom: 5px;"><strong>Payment Status:</strong> 
                            <span class="status-badge {{ $order->payment_status == 'paid' ? 'active' : 'pending' }}">
                                {{ ucfirst($order->payment_status ?? 'Pending') }}
                            </span>
                        </p>
                        
                        @if (!empty($order->transaction_id))
                            <p style="margin-bottom: 5px;"><strong>Transaction ID:</strong> {{ $order->transaction_id }}</p>
                        @endif
                    @else
                        <p>No payment information available.</p>
                    @endif
                </div>
                
                <!-- Update Status -->
                <div style="margin-bottom: 30px;">
                    <h4 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Update Order Status</h4>
                    
                    <form action="{{ url('/admin/orders/' . $order->id . '/status') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            </select>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <label for="status_comment">Comment (Optional)</label>
                            <textarea name="status_comment" id="status_comment" class="form-control" rows="3"></textarea>
                        </div>
                        
                        <div style="margin-top: 20px;">
                            <button type="submit" class="btn btn-primary" style="width: 100%;">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection