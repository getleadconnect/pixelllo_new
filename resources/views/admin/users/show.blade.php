@extends('layouts.admin')

@section('title', 'User Details')
@section('page-title', 'User Details')
@section('page-subtitle', 'Viewing detailed user information')

@section('content')

@section('styles')
<style>

    .icon-fa
    {
        margin-top: 9px;
        font-size: 12px;
    }

    /* Simple tab styles */
    .admin-tabs-container {
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 20px;
    }

    .admin-tabs-wrapper {
        display: flex;
        gap: 0;
    }

    .admin-tab-btn {
        padding: 12px 24px;
        border: none;
        background: transparent;
        color: #6b7280;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        border-bottom: 2px solid transparent;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .admin-tab-btn i {
        font-size: 14px;
    }

    .admin-tab-btn:hover {
        color: #4b5563;
        background-color: #f9fafb;
    }

    .admin-tab-btn.active {
        color: #3b82f6;
        border-bottom-color: #3b82f6;
        background-color: transparent;
    }

    .admin-tab-btn .tab-indicator {
        display: none;
    }

    /* Tab content */
    .admin-tab-content {
        padding-top: 10px;
    }

    /* Responsive design for smaller screens */
    @media (max-width: 768px) {
        .admin-tab-btn {
            padding: 10px 16px;
            font-size: 13px;
        }

        .admin-tab-btn i {
            font-size: 12px;
        }

        .admin-tab-btn span {
            display: none;
        }
    }

    /* Simple table hover effect */
    .admin-table tbody tr:hover {
        background-color: #f9fafb;
    }

    /* Inactive badge styling */
    .status-badge.inactive-red {
        background-color: #fee2e2;
        color: #dc2626;
        border: 1px solid #fca5a5;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    /* Modal Styles */
    .modal {
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: white;
        padding: 0;
        border-radius: 8px;
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .modal-content.modal-large {
        max-width: 800px;
    }

    .modal-header {
        padding: 20px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #6b7280;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-close:hover {
        color: #111827;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        padding: 15px 20px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        color: #374151;
    }

    .form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        font-size: 14px;
    }

    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    textarea.form-control {
        resize: vertical;
        font-family: inherit;
    }

    /* Activity Modal Styles */
    .activity-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 30px;
    }

    .stat-box {
        background-color: #f9fafb;
        padding: 15px;
        border-radius: 6px;
        text-align: center;
    }

    .stat-box h4 {
        margin: 0 0 8px 0;
        font-size: 12px;
        text-transform: uppercase;
        color: #6b7280;
        font-weight: 500;
    }

    .stat-box p {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #111827;
    }

    .activity-log {
        margin-top: 15px;
    }

    .btn-secondary {
        background-color: #6b7280;
    }

    .btn-secondary:hover {
        background-color: #4b5563;
    }

    /* Alert and confirmation styles */
    .alert-warning {
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }

    .btn-danger {
        background-color: #dc2626;
        color: white;
    }

    .btn-danger:hover {
        background-color: #b91c1c;
    }

    /* Alert animation */
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Custom Pagination Styles */
    .custom-pagination {
        padding: 15px 0;
        border-top: 1px solid #e5e7eb;
    }

    .pagination-numbers {
        display: flex;
        align-items: center;
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

    .page-link.active:hover {
        background-color: #2563eb;
        border-color: #2563eb;
    }

    .page-link.disabled {
        cursor: not-allowed;
        opacity: 0.5;
        background-color: #f9fafb;
        color: #9ca3af;
        pointer-events: none;
    }

    .page-link.page-prev,
    .page-link.page-next {
        font-weight: bold;
        font-size: 16px;
        padding: 0 10px;
    }

    .page-dots {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        color: #9ca3af;
        font-size: 14px;
        user-select: none;
    }

    @media (max-width: 640px) {
        .custom-pagination {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }

        .pagination-info {
            font-size: 13px;
        }

        .page-link {
            min-width: 28px;
            height: 28px;
            font-size: 13px;
        }
    }
</style>
@endsection

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
                        <span class="status-badge {{ $user->active ? 'active' : 'inactive-red' }}" style="margin-left: 5px;">
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
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
                        <button onclick="openSendMessageModal()" class="btn btn-primary btn-sm" style="text-align: center;">
                            <i class="fas fa-envelope"></i> Send Message
                        </button>
                        <button onclick="toggleUserStatus({{ $user->id }}, {{ $user->active ? 'false' : 'true' }})" class="btn btn-primary btn-sm" style="text-align: center;">
                            <i class="fas fa-ban"></i> {{ $user->active ? 'Deactivate' : 'Activate' }}
                        </button>
                        <button onclick="openActivityModal()" class="btn btn-primary btn-sm" style="text-align: center;">
                            <i class="fas fa-history"></i> View Activity
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- User Activity Tabs -->
            <div>
                <div class="admin-tabs-container">
                    <div class="admin-tabs-wrapper">
                        <button class="admin-tab-btn active" data-tab="bids">
                            <i class="fas fa-gavel"></i>
                            <span>Bid History</span>
                        </button>
                        <button class="admin-tab-btn" data-tab="won-auctions">
                            <i class="fas fa-trophy"></i>
                            <span>Won Auctions</span>
                        </button>
                        <button class="admin-tab-btn" data-tab="orders">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Orders</span>
                        </button>
                    </div>
                </div>
                
                <!-- Bid History Tab -->
                <div class="admin-tab-content {{ $activeTab == 'bids' ? 'active' : '' }}" id="bids-content" style="{{ $activeTab == 'bids' ? '' : 'display: none;' }}">
                    <h3 style="margin-bottom: 15px;">Bid History ({{ $bids->total() }} total)</h3>
                    <table class="admin-table" style="width: 100%; table-layout: auto;">
                        <thead>
                            <tr>
                                <th style="width: 40%;">Auction</th>
                                <th style="width: 15%;">Amount</th>
                                <th style="width: 25%;">Time</th>
                                <th style="width: 20%;">Status</th>
                            </tr>
                        </thead>
                        <tbody style="font-size:14px;">
                            @forelse ($bids as $bid)
                                <tr>
                                    <td>
                                        <a href="{{ url('/admin/auctions/' . $bid->auction->id) }}">{{ Str::limit($bid->auction->title, 50) }}</a>
                                    </td>
                                    <td>AED {{ number_format($bid->amount, 2) }}</td>
                                    <td style="white-space: nowrap;">{{ $bid->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        @php
                                            $isWinning = false;
                                            if ($bid->auction->status == 'active') {
                                                $latestBid = $bid->auction->bids()->orderBy('created_at', 'desc')->first();
                                                $isWinning = $latestBid && $latestBid->id == $bid->id;
                                            }
                                        @endphp
                                        <span class="status-badge {{ $isWinning ? 'active' : 'inactive' }}">
                                            {{ $isWinning ? 'Winning' : 'Outbid' }}
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
                    @if ($bids->hasPages())
                        <div class="custom-pagination" style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center;">
                            <div class="pagination-info" style="color: #6b7280; font-size: 14px;">
                                Showing {{ $bids->firstItem() }} to {{ $bids->lastItem() }} of {{ $bids->total() }} results
                            </div>
                            <div class="pagination-numbers" style="display: flex; gap: 2px;">
                                @php
                                    $currentPage = $bids->currentPage();
                                    $lastPage = $bids->lastPage();
                                    $start = max(1, $currentPage - 2);
                                    $end = min($lastPage, $currentPage + 2);
                                @endphp

                                {{-- Previous Button --}}
                                @if ($currentPage > 1)
                                    <a href="{{ $bids->appends(['won_page' => request('won_page'), 'orders_page' => request('orders_page')])->url($currentPage - 1) }}"
                                       class="page-link page-prev" title="Previous">
                                        <i class="fa fa-arrow-left icon-fa"></i>
                                    </a>
                                @else
                                    <span class="page-link page-prev disabled"><i class="fa fa-arrow-left icon-fa"></i></span>
                                @endif

                                @if ($start > 1)
                                    <a href="{{ $bids->appends(['won_page' => request('won_page'), 'orders_page' => request('orders_page')])->url(1) }}"
                                       class="page-link">1</a>
                                    @if ($start > 2)
                                        <span class="page-dots">...</span>
                                    @endif
                                @endif

                                @for ($i = $start; $i <= $end; $i++)
                                    <a href="{{ $bids->appends(['won_page' => request('won_page'), 'orders_page' => request('orders_page')])->url($i) }}"
                                       class="page-link {{ $i == $currentPage ? 'active' : '' }}">{{ $i }}</a>
                                @endfor

                                @if ($end < $lastPage)
                                    @if ($end < $lastPage - 1)
                                        <span class="page-dots">...</span>
                                    @endif
                                    <a href="{{ $bids->appends(['won_page' => request('won_page'), 'orders_page' => request('orders_page')])->url($lastPage) }}"
                                       class="page-link">{{ $lastPage }}</a>
                                @endif

                                {{-- Next Button --}}
                                @if ($currentPage < $lastPage)
                                    <a href="{{ $bids->appends(['won_page' => request('won_page'), 'orders_page' => request('orders_page')])->url($currentPage + 1) }}"
                                       class="page-link page-next" title="Next">
                                        <i class="fa fa-arrow-right icon-fa"></i>
                                    </a>
                                @else
                                    <span class="page-link page-next disabled"><i class="fa fa-arrow-right icon-fa"></i></span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Won Auctions Tab -->
                <div class="admin-tab-content {{ $activeTab == 'won-auctions' ? 'active' : '' }}" id="won-auctions-content" style="{{ $activeTab == 'won-auctions' ? '' : 'display: none;' }}">
                    <h3 style="margin-bottom: 15px;">Won Auctions ({{ $wonAuctions->total() }} total)</h3>
                    <table class="admin-table" style="width: 100%; table-layout: auto;">
                        <thead>
                            <tr>
                                <th style="width: 35%;">Auction</th>
                                <th style="width: 15%;">Final Price</th>
                                <th style="width: 15%;">Retail Price</th>
                                <th style="width: 15%;">Savings</th>
                                <th style="width: 20%;">Won Date</th>
                            </tr>
                        </thead>
                        <tbody style="font-size:14px;">
                            @forelse ($wonAuctions as $auction)
                                <tr>
                                    <td>
                                        <a href="{{ url('/admin/auctions/' . $auction->id) }}">{{ Str::limit($auction->title, 45) }}</a>
                                    </td>
                                    <td>AED {{ number_format($auction->currentPrice, 2) }}</td>
                                    <td>AED {{ number_format($auction->retailPrice, 2) }}</td>
                                    <td>
                                        @php
                                            $savings = $auction->retailPrice > 0 ?
                                                (1 - ($auction->currentPrice / $auction->retailPrice)) * 100 : 0;
                                        @endphp
                                        <span class="status-badge active">{{ number_format($savings, 0) }}%</span>
                                    </td>
                                    <td style="white-space: nowrap;">{{ $auction->endTime ? $auction->endTime->format('M d, Y') : 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No won auctions found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if ($wonAuctions->hasPages())
                        <div class="custom-pagination" style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center;">
                            <div class="pagination-info" style="color: #6b7280; font-size: 14px;">
                                Showing {{ $wonAuctions->firstItem() }} to {{ $wonAuctions->lastItem() }} of {{ $wonAuctions->total() }} results
                            </div>
                            <div class="pagination-numbers" style="display: flex; gap: 2px;">
                                @php
                                    $currentPage = $wonAuctions->currentPage();
                                    $lastPage = $wonAuctions->lastPage();
                                    $start = max(1, $currentPage - 2);
                                    $end = min($lastPage, $currentPage + 2);
                                @endphp

                                {{-- Previous Button --}}
                                @if ($currentPage > 1)
                                    <a href="{{ $wonAuctions->appends(['bids_page' => request('bids_page'), 'orders_page' => request('orders_page')])->url($currentPage - 1) }}"
                                       class="page-link page-prev" title="Previous">
                                        <i class="fa fa-arrow-left icon-fa"></i>
                                    </a>
                                @else
                                    <span class="page-link page-prev disabled"><i class="fa fa-arrow-left icon-fa"></i></span>
                                @endif

                                @if ($start > 1)
                                    <a href="{{ $wonAuctions->appends(['bids_page' => request('bids_page'), 'orders_page' => request('orders_page')])->url(1) }}"
                                       class="page-link">1</a>
                                    @if ($start > 2)
                                        <span class="page-dots">...</span>
                                    @endif
                                @endif

                                @for ($i = $start; $i <= $end; $i++)
                                    <a href="{{ $wonAuctions->appends(['bids_page' => request('bids_page'), 'orders_page' => request('orders_page')])->url($i) }}"
                                       class="page-link {{ $i == $currentPage ? 'active' : '' }}">{{ $i }}</a>
                                @endfor

                                @if ($end < $lastPage)
                                    @if ($end < $lastPage - 1)
                                        <span class="page-dots">...</span>
                                    @endif
                                    <a href="{{ $wonAuctions->appends(['bids_page' => request('bids_page'), 'orders_page' => request('orders_page')])->url($lastPage) }}"
                                       class="page-link">{{ $lastPage }}</a>
                                @endif

                                {{-- Next Button --}}
                                @if ($currentPage < $lastPage)
                                    <a href="{{ $wonAuctions->appends(['bids_page' => request('bids_page'), 'orders_page' => request('orders_page')])->url($currentPage + 1) }}"
                                       class="page-link page-next" title="Next">
                                        <i class="fa fa-arrow-right icon-fa"></i>
                                    </a>
                                @else
                                    <span class="page-link page-next disabled"><i class="fa fa-arrow-right icon-fa"></i></span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Orders Tab -->
                <div class="admin-tab-content {{ $activeTab == 'orders' ? 'active' : '' }}" id="orders-content" style="{{ $activeTab == 'orders' ? '' : 'display: none;' }}">
                    <h3 style="margin-bottom: 15px;">Orders ({{ $orders->total() }} total)</h3>
                    <table class="admin-table" style="width: 100%; table-layout: auto;">
                        <thead>
                            <tr>
                                <th style="width: 15%;">Order ID</th>
                                <th style="width: 35%;">Auction</th>
                                <th style="width: 15%;">Total</th>
                                <th style="width: 15%;">Status</th>
                                <th style="width: 20%;">Order Date</th>
                            </tr>
                        </thead>
                        <tbody style="font-size:14px;">
                            @forelse ($orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ url('/admin/orders/' . $order->id) }}">{{ substr($order->id, 0, 8) }}...</a>
                                    </td>
                                    <td>
                                        <a href="{{ url('/admin/auctions/' . $order->auction_id) }}">{{ Str::limit($order->auction->title ?? 'Unknown', 45) }}</a>
                                    </td>
                                    <td>AED {{ number_format($order->total, 2) }}</td>
                                    <td>
                                        <span class="status-badge {{ $order->status == 'delivered' ? 'active' : ($order->status == 'processing' ? 'processing' : ($order->status == 'shipped' ? 'shipped' : 'pending')) }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td style="white-space: nowrap;">{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No orders found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if ($orders->hasPages())
                        <div class="custom-pagination" style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center;">
                            <div class="pagination-info" style="color: #6b7280; font-size: 14px;">
                                Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} results
                            </div>
                            <div class="pagination-numbers" style="display: flex; gap: 2px;">
                                @php
                                    $currentPage = $orders->currentPage();
                                    $lastPage = $orders->lastPage();
                                    $start = max(1, $currentPage - 2);
                                    $end = min($lastPage, $currentPage + 2);
                                @endphp

                                {{-- Previous Button --}}
                                @if ($currentPage > 1)
                                    <a href="{{ $orders->appends(['bids_page' => request('bids_page'), 'won_page' => request('won_page')])->url($currentPage - 1) }}"
                                       class="page-link page-prev" title="Previous">
                                        <i class="fa fa-arrow-left icon-fa"></i>
                                    </a>
                                @else
                                    <span class="page-link page-prev disabled"><i class="fa fa-arrow-left icon-fa"></i></span>
                                @endif

                                @if ($start > 1)
                                    <a href="{{ $orders->appends(['bids_page' => request('bids_page'), 'won_page' => request('won_page')])->url(1) }}"
                                       class="page-link">1</a>
                                    @if ($start > 2)
                                        <span class="page-dots">...</span>
                                    @endif
                                @endif

                                @for ($i = $start; $i <= $end; $i++)
                                    <a href="{{ $orders->appends(['bids_page' => request('bids_page'), 'won_page' => request('won_page')])->url($i) }}"
                                       class="page-link {{ $i == $currentPage ? 'active' : '' }}">{{ $i }}</a>
                                @endfor

                                @if ($end < $lastPage)
                                    @if ($end < $lastPage - 1)
                                        <span class="page-dots">...</span>
                                    @endif
                                    <a href="{{ $orders->appends(['bids_page' => request('bids_page'), 'won_page' => request('won_page')])->url($lastPage) }}"
                                       class="page-link">{{ $lastPage }}</a>
                                @endif

                                {{-- Next Button --}}
                                @if ($currentPage < $lastPage)
                                    <a href="{{ $orders->appends(['bids_page' => request('bids_page'), 'won_page' => request('won_page')])->url($currentPage + 1) }}"
                                       class="page-link page-next" title="Next">
                                        <i class="fa fa-arrow-right icon-fa"></i>
                                    </a>
                                @else
                                    <span class="page-link page-next disabled"><i class="fa fa-arrow-right icon-fa"></i></span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Send Message Modal -->
<div id="sendMessageModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Send Message to {{ $user->name }}</h2>
            <button onclick="closeSendMessageModal()" class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="sendMessageForm" onsubmit="sendMessage(event)">
                <div class="form-group">
                    <label for="userMobile">Mobile Number</label>
                    @php
                        $countryCode = '+1'; // Default country code, you can make this dynamic based on user's country
                        if ($user->country) {
                            // Map country codes to phone codes (add more as needed)
                            $countryCodes = [
                                'US' => '+1',
                                'CA' => '+1',
                                'GB' => '+44',
                                'IN' => '+91',
                                'AU' => '+61',
                                'DE' => '+49',
                                'FR' => '+33',
                                'IT' => '+39',
                                'ES' => '+34',
                                'BR' => '+55',
                                'JP' => '+81',
                                'CN' => '+86',
                                'KR' => '+82',
                                'MX' => '+52',
                                'RU' => '+7',
                                'ZA' => '+27',
                                'AE' => '+971',
                                'SA' => '+966',
                                'EG' => '+20',
                                'NG' => '+234',
                            ];
                            $countryCode = $countryCodes[$user->country] ?? '+1';
                        }
                        $phoneNumber = $user->phone ? $countryCode . ' ' . $user->phone : 'No phone number';
                    @endphp
                    <input type="text" id="userMobile" name="mobile" class="form-control" value="{{ $phoneNumber }}" readonly style="background-color: #f3f4f6;">
                </div>
                <div class="form-group">
                    <label for="messageBody">Message</label>
                    <textarea id="messageBody" name="message" class="form-control" rows="5" placeholder="Type your message here..." required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeSendMessageModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Confirmation Modal for Deactivate/Activate -->
<div id="confirmModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-header">
            <h2 id="confirmTitle">Confirm Action</h2>
            <button onclick="closeConfirmModal()" class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <p id="confirmMessage" style="font-size: 16px; color: #374151; margin: 20px 0;"></p>
            <div class="alert-warning" style="background-color: #fef3c7; border: 1px solid #f59e0b; padding: 12px; border-radius: 6px; margin-top: 15px;">
                <strong style="color: #92400e;">Warning:</strong>
                <span id="warningMessage" style="color: #92400e;"></span>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" onclick="closeConfirmModal()" class="btn btn-secondary">Cancel</button>
            <button type="button" id="confirmActionBtn" class="btn btn-danger">Confirm</button>
        </div>
    </div>
</div>

<!-- View Activity Modal -->
<div id="activityModal" class="modal" style="display: none;">
    <div class="modal-content modal-large">
        <div class="modal-header">
            <h2>User Activity - {{ $user->name }}</h2>
            <button onclick="closeActivityModal()" class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <!-- User Info Summary -->
            <div style="background-color: #f9fafb; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div class="topbar-user-avatar" style="width: 50px; height: 50px;">
                        <img src="{{ asset('images/placeholders/avatar-placeholder.svg') }}" alt="{{ $user->name }}">
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 18px;">{{ $user->name }}</h3>
                        <p style="margin: 5px 0 0 0; color: #6b7280; font-size: 14px;">{{ $user->email }}</p>
                    </div>
                    <div style="margin-left: auto;">
                        <span class="status-badge {{ $user->active ? 'active' : 'inactive-red' }}">
                            {{ $user->active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="activity-stats">
                <div class="stat-box">
                    <h4>Last Login</h4>
                    <p>{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'Never' }}</p>
                </div>
                <div class="stat-box">
                    <h4>Total Bids</h4>
                    <p>{{ $allBids->count() ?? 0 }}</p>
                </div>
                <div class="stat-box">
                    <h4>Active Auctions</h4>
                    <p>{{ $activeAuctionCount ?? 0 }}</p>
                </div>
                <div class="stat-box">
                    <h4>Total Spent</h4>
                    <p>AED {{ number_format($totalSpent ?? 0, 2) }}</p>
                </div>
                <div class="stat-box">
                    <h4>Won Auctions</h4>
                    <p>{{ $allWonAuctions->count() ?? 0 }}</p>
                </div>
                <div class="stat-box">
                    <h4>Member Since</h4>
                    <p>{{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <h3>Recent Activity Log</h3>
            <div class="activity-log">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Date/Time</th>
                            <th>Action</th>
                            <th>Details</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $activities = collect();

                            // Add bids to activities
                            if(isset($allBids) && $allBids->count() > 0) {
                                foreach($allBids->take(10) as $bid) {
                                    $activities->push([
                                        'date' => $bid->created_at,
                                        'action' => 'Placed Bid',
                                        'details' => ($bid->auction->title ?? 'Unknown Auction') . ' - $' . number_format($bid->amount, 2),
                                        'status' => $bid->auction->status === 'active' ? 'Active' : 'Ended'
                                    ]);
                                }
                            }

                            // Add won auctions to activities
                            if(isset($allWonAuctions) && $allWonAuctions->count() > 0) {
                                foreach($allWonAuctions->take(5) as $auction) {
                                    $activities->push([
                                        'date' => $auction->endTime ?? $auction->updated_at,
                                        'action' => 'Won Auction',
                                        'details' => $auction->title . ' - Final Price: $' . number_format($auction->currentPrice, 2),
                                        'status' => 'Won'
                                    ]);
                                }
                            }

                            // Add orders to activities
                            if(isset($allOrders) && $allOrders->count() > 0) {
                                foreach($allOrders->take(5) as $order) {
                                    $activities->push([
                                        'date' => $order->created_at,
                                        'action' => 'Created Order',
                                        'details' => 'Order #' . substr($order->id, 0, 8) . ' - Total: $' . number_format($order->total, 2),
                                        'status' => ucfirst($order->status)
                                    ]);
                                }
                            }

                            // Sort activities by date (newest first)
                            $activities = $activities->sortByDesc('date')->take(15);
                        @endphp

                        @forelse($activities as $activity)
                            <tr>
                                <td>{{ $activity['date']->format('M d, Y H:i') }}</td>
                                <td>{{ $activity['action'] }}</td>
                                <td>{{ $activity['details'] }}</td>
                                <td>
                                    @php
                                        $statusClass = 'pending';
                                        if(in_array($activity['status'], ['Active', 'Won', 'delivered', 'completed'])) {
                                            $statusClass = 'active';
                                        } elseif(in_array($activity['status'], ['Ended', 'cancelled', 'failed'])) {
                                            $statusClass = 'inactive-red';
                                        } elseif(in_array($activity['status'], ['processing', 'shipped'])) {
                                            $statusClass = 'processing';
                                        }
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">{{ $activity['status'] }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No recent activity</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button onclick="closeActivityModal()" class="btn btn-primary">Close</button>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set active tab based on PHP variable
        const activeTab = '{{ $activeTab }}';

        // User Activity Tabs
        const adminTabs = document.querySelectorAll('.admin-tab-btn');
        const adminTabContents = document.querySelectorAll('.admin-tab-content');

        // Set the correct active tab on page load
        adminTabs.forEach(tab => {
            const tabId = tab.getAttribute('data-tab');
            if (tabId === activeTab) {
                tab.classList.add('active');
            } else {
                tab.classList.remove('active');
            }
        });

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

                // Update URL to preserve tab state
                const currentUrl = new URL(window.location);

                // Remove all page parameters
                currentUrl.searchParams.delete('bids_page');
                currentUrl.searchParams.delete('won_page');
                currentUrl.searchParams.delete('orders_page');

                // Update browser history without reload
                window.history.pushState({}, '', currentUrl);
            });
        });
    });

    // Send Message Modal Functions
    function openSendMessageModal() {
        document.getElementById('sendMessageModal').style.display = 'flex';
    }

    function closeSendMessageModal() {
        document.getElementById('sendMessageModal').style.display = 'none';
        document.getElementById('sendMessageForm').reset();
    }

    function sendMessage(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);

        // Add user ID to form data
        formData.append('user_id', '{{ $user->id }}');
        // Mobile is already in the form, no need to add separately

        fetch('{{ route("admin.users.message") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Message sent successfully!');
                closeSendMessageModal();
            } else {
                alert('Error sending message: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to send message. Please try again.');
        });
    }

    // View Activity Modal Functions
    function openActivityModal() {
        document.getElementById('activityModal').style.display = 'flex';
    }

    function closeActivityModal() {
        document.getElementById('activityModal').style.display = 'none';
    }

    // Confirmation Modal Functions
    function closeConfirmModal() {
        document.getElementById('confirmModal').style.display = 'none';
    }

    // Toggle User Status Function
    function toggleUserStatus(userId, activate) {
        const action = activate ? 'activate' : 'deactivate';
        const actionCapitalized = activate ? 'Activate' : 'Deactivate';

        // Set modal content
        document.getElementById('confirmTitle').textContent = `${actionCapitalized} User`;
        document.getElementById('confirmMessage').textContent = `Are you sure you want to ${action} {{ $user->name }}?`;

        // Set warning message based on action
        if (activate) {
            document.getElementById('warningMessage').textContent = 'This user will be able to log in and place bids again.';
            document.getElementById('confirmActionBtn').textContent = 'Activate User';
            document.getElementById('confirmActionBtn').className = 'btn btn-primary';
        } else {
            document.getElementById('warningMessage').textContent = 'This user will be unable to log in or place any bids until reactivated.';
            document.getElementById('confirmActionBtn').textContent = 'Deactivate User';
            document.getElementById('confirmActionBtn').className = 'btn btn-danger';
        }

        // Show modal
        document.getElementById('confirmModal').style.display = 'flex';

        // Set confirm button action
        document.getElementById('confirmActionBtn').onclick = function() {
            // Close modal
            closeConfirmModal();

            // Perform the action
            fetch('{{ url("/admin/users") }}/' + userId + '/toggle-status', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    active: activate
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showSuccessAlert(`User ${action}d successfully!`);
                    // Reload after a short delay
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showErrorAlert('Error: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorAlert('Failed to update user status. Please try again.');
            });
        };
    }

    // Helper functions for alerts
    function showSuccessAlert(message) {
        // Create a temporary success alert
        const alertDiv = document.createElement('div');
        alertDiv.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #10b981; color: white; padding: 15px 20px; border-radius: 6px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); z-index: 10000; animation: slideIn 0.3s ease;';
        alertDiv.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
        document.body.appendChild(alertDiv);

        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }

    function showErrorAlert(message) {
        // Create a temporary error alert
        const alertDiv = document.createElement('div');
        alertDiv.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #ef4444; color: white; padding: 15px 20px; border-radius: 6px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); z-index: 10000; animation: slideIn 0.3s ease;';
        alertDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
        document.body.appendChild(alertDiv);

        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            if (event.target.id === 'sendMessageModal') {
                closeSendMessageModal();
            } else if (event.target.id === 'activityModal') {
                closeActivityModal();
            } else if (event.target.id === 'confirmModal') {
                closeConfirmModal();
            }
        }
    }
</script>
@endsection