@extends('layouts.admin')

@section('title', 'User Details')
@section('page-title', 'User Details')
@section('page-subtitle', 'Viewing detailed user information')

@section('content')
<div class="admin-data-card">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">User Information</div>
        <div class="admin-data-card-actions">
            <a href="{{ url('/admin/users') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>
            <a href="{{ url('/admin/users/' . $user->id . '/edit') }}" class="btn btn-sm btn-success">
                <i class="fas fa-edit"></i> Edit User
            </a>
            @if (Auth::id() != $user->id)
                <form action="{{ url('/admin/users/' . $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash-alt"></i> Delete User
                    </button>
                </form>
            @endif
        </div>
    </div>
    <div class="admin-data-card-body">
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
            <!-- User Basic Info -->
            <div>
                <div style="text-align: center; margin-bottom: 20px;">
                    <div class="topbar-user-avatar" style="width: 100px; height: 100px; margin: 0 auto 15px;">
                        <img src="{{ asset('images/placeholders/avatar-placeholder.svg') }}" alt="{{ $user->name }}">
                    </div>
                    <h2 style="margin-bottom: 5px;">{{ $user->name }}</h2>
                    <p style="color: var(--gray);">{{ $user->email }}</p>
                    <div style="margin: 10px 0;">
                        <span class="status-badge {{ $user->role == 'admin' ? 'active' : 'pending' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                        <span class="status-badge {{ $user->active ? 'active' : 'inactive' }}" style="margin-left: 5px;">
                            {{ $user->active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                
                <div style="background-color: var(--light); padding: 15px; border-radius: 8px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span>Bid Balance:</span>
                        <span style="font-weight: 600;">{{ number_format($user->bid_balance) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span>Phone:</span>
                        <span style="font-weight: 600;">{{ $user->phone ?? 'N/A' }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span>Country:</span>
                        <span style="font-weight: 600;">{{ $user->country }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Member Since:</span>
                        <span style="font-weight: 600;">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div style="margin-top: 20px;">
                    <h3 style="margin-bottom: 10px;">Quick Actions</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <a href="#" class="btn btn-primary btn-sm" style="text-align: center;">
                            <i class="fas fa-coins"></i> Add Bids
                        </a>
                        <a href="#" class="btn btn-primary btn-sm" style="text-align: center;">
                            <i class="fas fa-envelope"></i> Send Message
                        </a>
                        <a href="#" class="btn btn-primary btn-sm" style="text-align: center;">
                            <i class="fas fa-ban"></i> {{ $user->active ? 'Deactivate' : 'Activate' }}
                        </a>
                        <a href="#" class="btn btn-primary btn-sm" style="text-align: center;">
                            <i class="fas fa-history"></i> View Activity
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- User Activity Tabs -->
            <div>
                <div class="admin-tabs" style="margin-bottom: 15px; border-bottom: 1px solid var(--light-gray);">
                    <button class="admin-tab active" data-tab="bids">Bid History</button>
                    <button class="admin-tab" data-tab="won-auctions">Won Auctions</button>
                    <button class="admin-tab" data-tab="orders">Orders</button>
                </div>
                
                <!-- Bid History Tab -->
                <div class="admin-tab-content active" id="bids-content">
                    <h3 style="margin-bottom: 15px;">Recent Bids</h3>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Auction</th>
                                <th>Amount</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bids ?? [] as $bid)
                                <tr>
                                    <td>
                                        <a href="{{ url('/admin/auctions/' . $bid->auction->id) }}">{{ Str::limit($bid->auction->title, 30) }}</a>
                                    </td>
                                    <td>${{ number_format($bid->amount, 2) }}</td>
                                    <td>{{ $bid->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <span class="status-badge {{ $bid->is_winning ? 'active' : 'inactive' }}">
                                            {{ $bid->is_winning ? 'Winning' : 'Outbid' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No bids found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Won Auctions Tab -->
                <div class="admin-tab-content" id="won-auctions-content" style="display: none;">
                    <h3 style="margin-bottom: 15px;">Won Auctions</h3>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Auction</th>
                                <th>Final Price</th>
                                <th>Retail Price</th>
                                <th>Savings</th>
                                <th>Won Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($wonAuctions ?? [] as $auction)
                                <tr>
                                    <td>
                                        <a href="{{ url('/admin/auctions/' . $auction->id) }}">{{ Str::limit($auction->title, 30) }}</a>
                                    </td>
                                    <td>${{ number_format($auction->final_price, 2) }}</td>
                                    <td>${{ number_format($auction->retail_price, 2) }}</td>
                                    <td>
                                        @php
                                            $savings = $auction->retail_price > 0 ? 
                                                (1 - ($auction->final_price / $auction->retail_price)) * 100 : 0;
                                        @endphp
                                        <span class="status-badge active">{{ number_format($savings, 0) }}%</span>
                                    </td>
                                    <td>{{ $auction->ended_at ? $auction->ended_at->format('M d, Y') : 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No won auctions found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Orders Tab -->
                <div class="admin-tab-content" id="orders-content" style="display: none;">
                    <h3 style="margin-bottom: 15px;">Orders</h3>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Auction</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Order Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders ?? [] as $order)
                                <tr>
                                    <td>
                                        <a href="{{ url('/admin/orders/' . $order->id) }}">{{ $order->id }}</a>
                                    </td>
                                    <td>
                                        <a href="{{ url('/admin/auctions/' . $order->auction_id) }}">{{ Str::limit($order->auction->title ?? 'Unknown', 30) }}</a>
                                    </td>
                                    <td>${{ number_format($order->total, 2) }}</td>
                                    <td>
                                        <span class="status-badge {{ $order->status == 'delivered' ? 'active' : ($order->status == 'processing' ? 'processing' : ($order->status == 'shipped' ? 'shipped' : 'pending')) }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No orders found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // User Activity Tabs
        const adminTabs = document.querySelectorAll('.admin-tab');
        const adminTabContents = document.querySelectorAll('.admin-tab-content');
        
        adminTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Get tab ID
                const tabId = this.getAttribute('data-tab');
                
                // Remove active class from all tabs and contents
                adminTabs.forEach(t => t.classList.remove('active'));
                adminTabContents.forEach(c => {
                    c.classList.remove('active');
                    c.style.display = 'none';
                });
                
                // Add active class to clicked tab and show content
                this.classList.add('active');
                const tabContent = document.getElementById(tabId + '-content');
                if (tabContent) {
                    tabContent.classList.add('active');
                    tabContent.style.display = 'block';
                }
            });
        });
    });
</script>
@endsection