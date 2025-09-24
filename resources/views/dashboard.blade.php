@extends('layouts.app')

@section('title', 'Dashboard - ' . config('app.name'))

@section('content')
<div class="dashboard-page">
    <div class="container">
        <div class="dashboard-header">
            <h1>My Dashboard</h1>
            <div class="dashboard-actions">
                <span class="user-balance">
                    <i class="fas fa-coins"></i> Your Balance: <strong>{{ number_format(250) }} bids</strong>
                </span>
                <a href="#" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Buy Bids</a>
            </div>
        </div>

        <div class="dashboard-grid">
            <!-- Sidebar -->
            <div class="dashboard-sidebar">
                <div class="user-profile-card">
                    <div class="user-avatar">
                        <img src="{{ asset('images/placeholders/avatar-placeholder.svg') }}" alt="User Avatar">
                    </div>
                    <div class="user-info">
                        <h3>{{ Auth::user()->name ?? 'John Doe' }}</h3>
                        <p class="user-since">Member since {{ Auth::user()->created_at ? Auth::user()->created_at->format('M Y') : 'Jan 2023' }}</p>
                    </div>
                </div>

                <nav class="dashboard-nav">
                    <a href="#" class="dashboard-nav-item active" data-panel="activity">
                        <i class="fas fa-chart-line"></i> Activity
                    </a>
                    <a href="#" class="dashboard-nav-item" data-panel="auctions">
                        <i class="fas fa-gavel"></i> My Auctions
                    </a>
                    <a href="#" class="dashboard-nav-item" data-panel="watchlist">
                        <i class="fas fa-heart"></i> Watchlist
                    </a>
                    <a href="#" class="dashboard-nav-item" data-panel="wins">
                        <i class="fas fa-trophy"></i> My Wins
                    </a>
                    <a href="#" class="dashboard-nav-item" data-panel="history">
                        <i class="fas fa-history"></i> Bid History
                    </a>
                    <a href="#" class="dashboard-nav-item" data-panel="orders">
                        <i class="fas fa-shopping-cart"></i> Orders
                    </a>
                    <a href="#" class="dashboard-nav-item" data-panel="settings">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </nav>

                <div class="quick-stats">
                    <div class="stat-item">
                        <span class="stat-label">Active Bids</span>
                        <span class="stat-value">32</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Watchlist</span>
                        <span class="stat-value">8</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Wins</span>
                        <span class="stat-value">3</span>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="dashboard-content">
                <!-- Activity Panel -->
                <div class="dashboard-panel active" id="activity-panel">
                    <div class="panel-header">
                        <h2>Activity Overview</h2>
                        <p>Your bidding activity and recent updates</p>
                    </div>

                    <div class="dashboard-stats">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-gavel"></i></div>
                            <div class="stat-info">
                                <h3>75</h3>
                                <p>Total Bids Placed</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-trophy"></i></div>
                            <div class="stat-info">
                                <h3>3</h3>
                                <p>Auctions Won</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                            <div class="stat-info">
                                <h3>$1,250</h3>
                                <p>Total Savings</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-clock"></i></div>
                            <div class="stat-info">
                                <h3>12</h3>
                                <p>Active Auctions</p>
                            </div>
                        </div>
                    </div>

                    <div class="activity-timeline">
                        <h3>Recent Activity</h3>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-icon bid-icon">
                                    <i class="fas fa-gavel"></i>
                                </div>
                                <div class="timeline-content">
                                    <h4>Bid Placed</h4>
                                    <p>You placed a bid on <a href="#">Apple MacBook Pro 14"</a></p>
                                    <span class="timeline-time">Today, 10:25 AM</span>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon buy-icon">
                                    <i class="fas fa-coins"></i>
                                </div>
                                <div class="timeline-content">
                                    <h4>Bids Purchased</h4>
                                    <p>You purchased 100 bids for $39.99</p>
                                    <span class="timeline-time">Yesterday, 2:15 PM</span>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon win-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div class="timeline-content">
                                    <h4>Auction Won</h4>
                                    <p>You won the auction for <a href="#">Samsung Galaxy S22 Ultra</a></p>
                                    <span class="timeline-time">Jul 12, 2023</span>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon watch-icon">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="timeline-content">
                                    <h4>Added to Watchlist</h4>
                                    <p>You added <a href="#">Sony PlayStation 5</a> to your watchlist</p>
                                    <span class="timeline-time">Jul 10, 2023</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- My Auctions Panel -->
                <div class="dashboard-panel" id="auctions-panel">
                    <div class="panel-header">
                        <h2>My Auctions</h2>
                        <p>Auctions you're currently participating in</p>
                    </div>

                    <div class="panel-tabs">
                        <button class="panel-tab active" data-tab="active-auctions">Active (3)</button>
                        <button class="panel-tab" data-tab="won-auctions">Won (2)</button>
                        <button class="panel-tab" data-tab="lost-auctions">Lost (5)</button>
                    </div>

                    <div class="panel-tab-content active" id="active-auctions">
                        <div class="auctions-list">
                            <!-- Auction Item 1 -->
                            <div class="auction-item">
                                <div class="auction-item-image">
                                    <img src="https://images.unsplash.com/photo-1604242692760-2f7b0c26856d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1738&q=80" alt="Apple MacBook Pro" onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                                    <span class="auction-status live">Live</span>
                                </div>
                                <div class="auction-item-details">
                                    <h3 class="auction-item-title">Apple MacBook Pro 14" with M2 Pro Chip</h3>
                                    <div class="auction-item-info">
                                        <div class="auction-progress-container">
                                            <div class="progress-stats">
                                                <span>Current Bid: <strong>$25.50</strong></span>
                                                <span>Retail: <strong>$1,999.99</strong></span>
                                            </div>
                                            <div class="auction-progress">
                                                <div class="progress-bar" style="width: 65%;"></div>
                                            </div>
                                            <div class="progress-time">
                                                <span><i class="fas fa-clock"></i> 05:45:32 remaining</span>
                                                <span>Your Bids: <strong>12</strong></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="auction-item-actions">
                                    <a href="#" class="btn btn-primary">Bid Now</a>
                                    <a href="#" class="btn btn-outline"><i class="fas fa-heart"></i></a>
                                </div>
                            </div>

                            <!-- Auction Item 2 -->
                            <div class="auction-item">
                                <div class="auction-item-image">
                                    <img src="https://images.unsplash.com/photo-1595941069915-4ebc5197c14a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1760&q=80" alt="Samsung Galaxy S22" onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                                    <span class="auction-status ending">Ending Soon</span>
                                </div>
                                <div class="auction-item-details">
                                    <h3 class="auction-item-title">Samsung Galaxy S22 Ultra 256GB</h3>
                                    <div class="auction-item-info">
                                        <div class="auction-progress-container">
                                            <div class="progress-stats">
                                                <span>Current Bid: <strong>$18.75</strong></span>
                                                <span>Retail: <strong>$1,199.99</strong></span>
                                            </div>
                                            <div class="auction-progress">
                                                <div class="progress-bar" style="width: 85%;"></div>
                                            </div>
                                            <div class="progress-time">
                                                <span><i class="fas fa-clock"></i> 00:15:10 remaining</span>
                                                <span>Your Bids: <strong>8</strong></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="auction-item-actions">
                                    <a href="#" class="btn btn-primary">Bid Now</a>
                                    <a href="#" class="btn btn-outline"><i class="fas fa-heart-broken"></i></a>
                                </div>
                            </div>

                            <!-- Auction Item 3 -->
                            <div class="auction-item">
                                <div class="auction-item-image">
                                    <img src="https://images.unsplash.com/photo-1546868871-7041f2a55e12?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1764&q=80" alt="Apple Watch" onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                                    <span class="auction-status live">Live</span>
                                </div>
                                <div class="auction-item-details">
                                    <h3 class="auction-item-title">Apple Watch Series 8 GPS + Cellular 45mm</h3>
                                    <div class="auction-item-info">
                                        <div class="auction-progress-container">
                                            <div class="progress-stats">
                                                <span>Current Bid: <strong>$12.25</strong></span>
                                                <span>Retail: <strong>$529.00</strong></span>
                                            </div>
                                            <div class="auction-progress">
                                                <div class="progress-bar" style="width: 42%;"></div>
                                            </div>
                                            <div class="progress-time">
                                                <span><i class="fas fa-clock"></i> 04:10:35 remaining</span>
                                                <span>Your Bids: <strong>5</strong></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="auction-item-actions">
                                    <a href="#" class="btn btn-primary">Bid Now</a>
                                    <a href="#" class="btn btn-outline"><i class="fas fa-heart"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-tab-content" id="won-auctions">
                        <div class="auctions-list">
                            <!-- Won Auction 1 -->
                            <div class="auction-item">
                                <div class="auction-item-image">
                                    <img src="https://images.unsplash.com/photo-1585155770447-2f66e2a397b5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2064&q=80" alt="Bose Headphones" onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                                    <span class="auction-status won">Won</span>
                                </div>
                                <div class="auction-item-details">
                                    <h3 class="auction-item-title">Bose QuietComfort 45 Noise Cancelling Headphones</h3>
                                    <div class="auction-item-info">
                                        <div class="win-info">
                                            <div class="win-detail">
                                                <span>Final Bid:</span>
                                                <strong>$36.50</strong>
                                            </div>
                                            <div class="win-detail">
                                                <span>Retail Price:</span>
                                                <strong>$329.00</strong>
                                            </div>
                                            <div class="win-detail">
                                                <span>You Saved:</span>
                                                <strong class="saving">89%</strong>
                                            </div>
                                            <div class="win-detail">
                                                <span>Won On:</span>
                                                <strong>Jul 12, 2023</strong>
                                            </div>
                                            <div class="win-detail">
                                                <span>Bids Used:</span>
                                                <strong>24 bids</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="auction-item-actions">
                                    <a href="#" class="btn btn-primary">Complete Purchase</a>
                                    <a href="#" class="btn btn-outline">Order Details</a>
                                </div>
                            </div>

                            <!-- Won Auction 2 -->
                            <div class="auction-item">
                                <div class="auction-item-image">
                                    <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1699&q=80" alt="Smart Watch" onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                                    <span class="auction-status won">Won</span>
                                </div>
                                <div class="auction-item-details">
                                    <h3 class="auction-item-title">Fitbit Versa 4 Fitness Smartwatch</h3>
                                    <div class="auction-item-info">
                                        <div class="win-info">
                                            <div class="win-detail">
                                                <span>Final Bid:</span>
                                                <strong>$22.25</strong>
                                            </div>
                                            <div class="win-detail">
                                                <span>Retail Price:</span>
                                                <strong>$229.95</strong>
                                            </div>
                                            <div class="win-detail">
                                                <span>You Saved:</span>
                                                <strong class="saving">90%</strong>
                                            </div>
                                            <div class="win-detail">
                                                <span>Won On:</span>
                                                <strong>Jul 05, 2023</strong>
                                            </div>
                                            <div class="win-detail">
                                                <span>Bids Used:</span>
                                                <strong>18 bids</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="auction-item-actions">
                                    <a href="#" class="btn btn-success">Delivered</a>
                                    <a href="#" class="btn btn-outline">Order Details</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-tab-content" id="lost-auctions">
                        <div class="auctions-list">
                            <!-- Empty state for lost auctions -->
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-search"></i>
                                </div>
                                <h3>No lost auctions to display</h3>
                                <p>When you participate in auctions that you don't win, they will appear here.</p>
                                <a href="{{ url('/auctions') }}" class="btn btn-primary">Browse Auctions</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Watchlist Panel -->
                <div class="dashboard-panel" id="watchlist-panel">
                    <div class="panel-header">
                        <h2>My Watchlist</h2>
                        <p>Auctions you're keeping an eye on</p>
                    </div>

                    <div class="watchlist-items">
                        <!-- Watchlist Item 1 -->
                        <div class="auction-item">
                            <div class="auction-item-image">
                                <img src="https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80" alt="Dyson Vacuum" onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                                <span class="auction-status live">Live</span>
                            </div>
                            <div class="auction-item-details">
                                <h3 class="auction-item-title">Dyson V15 Detect Cordless Vacuum Cleaner</h3>
                                <div class="auction-item-info">
                                    <div class="auction-progress-container">
                                        <div class="progress-stats">
                                            <span>Current Bid: <strong>$42.25</strong></span>
                                            <span>Retail: <strong>$749.99</strong></span>
                                        </div>
                                        <div class="auction-progress">
                                            <div class="progress-bar" style="width: 58%;"></div>
                                        </div>
                                        <div class="progress-time">
                                            <span><i class="fas fa-clock"></i> 03:45:22 remaining</span>
                                            <span>29 bids placed</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="auction-item-actions">
                                <a href="#" class="btn btn-primary">Bid Now</a>
                                <a href="#" class="btn btn-outline"><i class="fas fa-trash"></i> Remove</a>
                            </div>
                        </div>

                        <!-- Watchlist Item 2 -->
                        <div class="auction-item">
                            <div class="auction-item-image">
                                <img src="https://images.unsplash.com/photo-1608156639585-b3a032ef9689?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1744&q=80" alt="Nintendo Switch" onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                                <span class="auction-status ending">Ending Soon</span>
                            </div>
                            <div class="auction-item-details">
                                <h3 class="auction-item-title">Nintendo Switch OLED with Mario Kart 8</h3>
                                <div class="auction-item-info">
                                    <div class="auction-progress-container">
                                        <div class="progress-stats">
                                            <span>Current Bid: <strong>$22.50</strong></span>
                                            <span>Retail: <strong>$399.99</strong></span>
                                        </div>
                                        <div class="auction-progress">
                                            <div class="progress-bar" style="width: 92%;"></div>
                                        </div>
                                        <div class="progress-time">
                                            <span><i class="fas fa-clock"></i> 00:17:42 remaining</span>
                                            <span>19 bids placed</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="auction-item-actions">
                                <a href="#" class="btn btn-primary">Bid Now</a>
                                <a href="#" class="btn btn-outline"><i class="fas fa-trash"></i> Remove</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- My Wins Panel -->
                <div class="dashboard-panel" id="wins-panel">
                    <div class="panel-header">
                        <h2>My Wins</h2>
                        <p>Auctions you've won</p>
                    </div>

                    <div class="panel-tabs">
                        <button class="panel-tab active" data-tab="pending-wins">Pending (1)</button>
                        <button class="panel-tab" data-tab="completed-wins">Completed (2)</button>
                    </div>

                    <div class="panel-tab-content active" id="pending-wins">
                        <div class="wins-list">
                            <!-- Pending Win 1 -->
                            <div class="win-card">
                                <div class="win-image">
                                    <img src="https://images.unsplash.com/photo-1585155770447-2f66e2a397b5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2064&q=80" alt="Bose Headphones" onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                                </div>
                                <div class="win-content">
                                    <div class="win-header">
                                        <h3>Bose QuietComfort 45 Noise Cancelling Headphones</h3>
                                        <span class="win-badge">Won on Jul 12, 2023</span>
                                    </div>
                                    <div class="win-details">
                                        <div class="win-info-grid">
                                            <div class="win-info-item">
                                                <span class="win-info-label">Final Price</span>
                                                <span class="win-info-value">$36.50</span>
                                            </div>
                                            <div class="win-info-item">
                                                <span class="win-info-label">Bids Used</span>
                                                <span class="win-info-value">24 bids</span>
                                            </div>
                                            <div class="win-info-item">
                                                <span class="win-info-label">Retail Price</span>
                                                <span class="win-info-value">$329.00</span>
                                            </div>
                                            <div class="win-info-item">
                                                <span class="win-info-label">You Saved</span>
                                                <span class="win-info-value savings">89%</span>
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
                                        <a href="#" class="btn btn-primary">Complete Purchase</a>
                                        <a href="#" class="btn btn-outline">View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-tab-content" id="completed-wins">
                        <div class="wins-list">
                            <!-- Completed Win 1 -->
                            <div class="win-card">
                                <div class="win-image">
                                    <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1699&q=80" alt="Smart Watch" onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                                </div>
                                <div class="win-content">
                                    <div class="win-header">
                                        <h3>Fitbit Versa 4 Fitness Smartwatch</h3>
                                        <span class="win-badge">Won on Jul 05, 2023</span>
                                    </div>
                                    <div class="win-details">
                                        <div class="win-info-grid">
                                            <div class="win-info-item">
                                                <span class="win-info-label">Final Price</span>
                                                <span class="win-info-value">$22.25</span>
                                            </div>
                                            <div class="win-info-item">
                                                <span class="win-info-label">Bids Used</span>
                                                <span class="win-info-value">18 bids</span>
                                            </div>
                                            <div class="win-info-item">
                                                <span class="win-info-label">Retail Price</span>
                                                <span class="win-info-value">$229.95</span>
                                            </div>
                                            <div class="win-info-item">
                                                <span class="win-info-label">You Saved</span>
                                                <span class="win-info-value savings">90%</span>
                                            </div>
                                        </div>
                                        <div class="win-status-container">
                                            <div class="win-status delivered">
                                                <i class="fas fa-check-circle"></i> Delivered
                                            </div>
                                            <p class="win-status-message">Delivered on Jul 10, 2023</p>
                                        </div>
                                    </div>
                                    <div class="win-actions">
                                        <a href="#" class="btn btn-primary">Leave Review</a>
                                        <a href="#" class="btn btn-outline">View Details</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Completed Win 2 -->
                            <div class="win-card">
                                <div class="win-image">
                                    <img src="https://images.unsplash.com/photo-1595941069915-4ebc5197c14a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1760&q=80" alt="Samsung Galaxy S22" onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                                </div>
                                <div class="win-content">
                                    <div class="win-header">
                                        <h3>Samsung Galaxy S22 Ultra 256GB</h3>
                                        <span class="win-badge">Won on Jun 22, 2023</span>
                                    </div>
                                    <div class="win-details">
                                        <div class="win-info-grid">
                                            <div class="win-info-item">
                                                <span class="win-info-label">Final Price</span>
                                                <span class="win-info-value">$67.50</span>
                                            </div>
                                            <div class="win-info-item">
                                                <span class="win-info-label">Bids Used</span>
                                                <span class="win-info-value">33 bids</span>
                                            </div>
                                            <div class="win-info-item">
                                                <span class="win-info-label">Retail Price</span>
                                                <span class="win-info-value">$1,199.99</span>
                                            </div>
                                            <div class="win-info-item">
                                                <span class="win-info-label">You Saved</span>
                                                <span class="win-info-value savings">94%</span>
                                            </div>
                                        </div>
                                        <div class="win-status-container">
                                            <div class="win-status delivered">
                                                <i class="fas fa-check-circle"></i> Delivered
                                            </div>
                                            <p class="win-status-message">Delivered on Jun 28, 2023</p>
                                        </div>
                                    </div>
                                    <div class="win-actions">
                                        <a href="#" class="btn btn-success">Review Posted</a>
                                        <a href="#" class="btn btn-outline">View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bid History Panel -->
                <div class="dashboard-panel" id="history-panel">
                    <div class="panel-header">
                        <h2>Bid History</h2>
                        <p>Your bidding activity over time</p>
                    </div>

                    <div class="bid-history-filters">
                        <div class="filter-item">
                            <label for="dateRange">Date Range:</label>
                            <select id="dateRange" class="form-select">
                                <option value="7">Last 7 days</option>
                                <option value="30" selected>Last 30 days</option>
                                <option value="90">Last 90 days</option>
                                <option value="365">Last 12 months</option>
                            </select>
                        </div>
                        <div class="filter-item">
                            <label for="bidStatus">Status:</label>
                            <select id="bidStatus" class="form-select">
                                <option value="all" selected>All Bids</option>
                                <option value="won">Winning Bids</option>
                                <option value="active">Active Bids</option>
                                <option value="lost">Lost Bids</option>
                            </select>
                        </div>
                        <button class="btn btn-primary">Apply Filters</button>
                    </div>

                    <div class="bid-history-table-container">
                        <table class="bid-history-table">
                            <thead>
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Auction</th>
                                    <th>Bid Amount</th>
                                    <th>Bid Cost</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Jul 15, 2023 10:25 AM</td>
                                    <td><a href="#">Apple MacBook Pro 14"</a></td>
                                    <td>$25.50</td>
                                    <td>1 bid</td>
                                    <td><span class="bid-status active">Active</span></td>
                                </tr>
                                <tr>
                                    <td>Jul 15, 2023 09:17 AM</td>
                                    <td><a href="#">Apple MacBook Pro 14"</a></td>
                                    <td>$24.50</td>
                                    <td>1 bid</td>
                                    <td><span class="bid-status outbid">Outbid</span></td>
                                </tr>
                                <tr>
                                    <td>Jul 14, 2023 02:40 PM</td>
                                    <td><a href="#">Samsung Galaxy S22 Ultra</a></td>
                                    <td>$18.75</td>
                                    <td>1 bid</td>
                                    <td><span class="bid-status active">Active</span></td>
                                </tr>
                                <tr>
                                    <td>Jul 12, 2023 11:52 AM</td>
                                    <td><a href="#">Bose QuietComfort 45</a></td>
                                    <td>$36.50</td>
                                    <td>1 bid</td>
                                    <td><span class="bid-status won">Won</span></td>
                                </tr>
                                <tr>
                                    <td>Jul 11, 2023 05:23 PM</td>
                                    <td><a href="#">Bose QuietComfort 45</a></td>
                                    <td>$35.50</td>
                                    <td>1 bid</td>
                                    <td><span class="bid-status outbid">Outbid</span></td>
                                </tr>
                                <tr>
                                    <td>Jul 11, 2023 01:19 PM</td>
                                    <td><a href="#">Sony WH-1000XM5</a></td>
                                    <td>$28.75</td>
                                    <td>1 bid</td>
                                    <td><span class="bid-status lost">Lost</span></td>
                                </tr>
                                <tr>
                                    <td>Jul 10, 2023 09:45 AM</td>
                                    <td><a href="#">Apple Watch Series 8</a></td>
                                    <td>$12.25</td>
                                    <td>1 bid</td>
                                    <td><span class="bid-status active">Active</span></td>
                                </tr>
                                <tr>
                                    <td>Jul 05, 2023 07:38 PM</td>
                                    <td><a href="#">Fitbit Versa 4</a></td>
                                    <td>$22.25</td>
                                    <td>1 bid</td>
                                    <td><span class="bid-status won">Won</span></td>
                                </tr>
                                <tr>
                                    <td>Jun 28, 2023 03:12 PM</td>
                                    <td><a href="#">Sony PlayStation 5</a></td>
                                    <td>$42.75</td>
                                    <td>1 bid</td>
                                    <td><span class="bid-status lost">Lost</span></td>
                                </tr>
                                <tr>
                                    <td>Jun 22, 2023 05:19 PM</td>
                                    <td><a href="#">Samsung Galaxy S22 Ultra</a></td>
                                    <td>$67.50</td>
                                    <td>1 bid</td>
                                    <td><span class="bid-status won">Won</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination">
                        <button class="page-prev" disabled><i class="fas fa-chevron-left"></i></button>
                        <span class="page-info">Page 1 of 3</span>
                        <button class="page-next"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>

                <!-- Orders Panel -->
                <div class="dashboard-panel" id="orders-panel">
                    <div class="panel-header">
                        <h2>My Orders</h2>
                        <p>Track your purchases and order history</p>
                    </div>

                    <div class="orders-filters">
                        <div class="filter-item">
                            <label for="orderStatus">Status:</label>
                            <select id="orderStatus" class="form-select">
                                <option value="all" selected>All Orders</option>
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                            </select>
                        </div>
                        <button class="btn btn-primary">Apply Filter</button>
                    </div>

                    <div class="orders-list">
                        <!-- Order 1 -->
                        <div class="order-card">
                            <div class="order-header">
                                <div class="order-id">
                                    <span>Order #PXL8752</span>
                                </div>
                                <div class="order-date">
                                    <span>Placed on Jul 13, 2023</span>
                                </div>
                                <div class="order-status pending">
                                    <span>Payment Pending</span>
                                </div>
                            </div>
                            <div class="order-content">
                                <div class="order-product">
                                    <div class="order-product-image">
                                        <img src="https://images.unsplash.com/photo-1585155770447-2f66e2a397b5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2064&q=80" alt="Bose Headphones" onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                                    </div>
                                    <div class="order-product-details">
                                        <h3 class="order-product-title">Bose QuietComfort 45 Noise Cancelling Headphones</h3>
                                        <div class="order-product-info">
                                            <div class="order-product-price">
                                                <span>Winning Bid: <strong>$36.50</strong></span>
                                                <span>+ Shipping: <strong>$12.99</strong></span>
                                                <span>Total: <strong>$49.49</strong></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="order-actions">
                                <a href="#" class="btn btn-primary">Complete Payment</a>
                                <a href="#" class="btn btn-outline">Order Details</a>
                            </div>
                        </div>

                        <!-- Order 2 -->
                        <div class="order-card">
                            <div class="order-header">
                                <div class="order-id">
                                    <span>Order #PXL8536</span>
                                </div>
                                <div class="order-date">
                                    <span>Placed on Jul 06, 2023</span>
                                </div>
                                <div class="order-status delivered">
                                    <span>Delivered</span>
                                </div>
                            </div>
                            <div class="order-content">
                                <div class="order-product">
                                    <div class="order-product-image">
                                        <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1699&q=80" alt="Smart Watch" onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                                    </div>
                                    <div class="order-product-details">
                                        <h3 class="order-product-title">Fitbit Versa 4 Fitness Smartwatch</h3>
                                        <div class="order-product-info">
                                            <div class="order-product-price">
                                                <span>Winning Bid: <strong>$22.25</strong></span>
                                                <span>+ Shipping: <strong>$9.99</strong></span>
                                                <span>Total: <strong>$32.24</strong></span>
                                            </div>
                                            <div class="order-delivery-info">
                                                <span>Delivered on Jul 10, 2023</span>
                                                <span>Tracking #: 1Z999AA10123456784</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="order-actions">
                                <a href="#" class="btn btn-primary">Leave Review</a>
                                <a href="#" class="btn btn-outline">Order Details</a>
                            </div>
                        </div>

                        <!-- Order 3 -->
                        <div class="order-card">
                            <div class="order-header">
                                <div class="order-id">
                                    <span>Order #PXL8349</span>
                                </div>
                                <div class="order-date">
                                    <span>Placed on Jun 23, 2023</span>
                                </div>
                                <div class="order-status delivered">
                                    <span>Delivered</span>
                                </div>
                            </div>
                            <div class="order-content">
                                <div class="order-product">
                                    <div class="order-product-image">
                                        <img src="https://images.unsplash.com/photo-1595941069915-4ebc5197c14a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1760&q=80" alt="Samsung Galaxy S22" onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                                    </div>
                                    <div class="order-product-details">
                                        <h3 class="order-product-title">Samsung Galaxy S22 Ultra 256GB</h3>
                                        <div class="order-product-info">
                                            <div class="order-product-price">
                                                <span>Winning Bid: <strong>$67.50</strong></span>
                                                <span>+ Shipping: <strong>$14.99</strong></span>
                                                <span>Total: <strong>$82.49</strong></span>
                                            </div>
                                            <div class="order-delivery-info">
                                                <span>Delivered on Jun 28, 2023</span>
                                                <span>Tracking #: 1Z999AA10123456651</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="order-actions">
                                <a href="#" class="btn btn-success">Review Posted</a>
                                <a href="#" class="btn btn-outline">Order Details</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Panel -->
                <div class="dashboard-panel" id="settings-panel">
                    <div class="panel-header">
                        <h2>Account Settings</h2>
                        <p>Manage your profile and preferences</p>
                    </div>

                    <div class="settings-container">
                        <!-- Profile Settings -->
                        <div class="settings-section">
                            <h3 class="settings-section-title">Profile Information</h3>
                            <form class="settings-form">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="name">Full Name</label>
                                        <input type="text" id="name" class="form-control" value="John Doe">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input type="email" id="email" class="form-control" value="john.doe@example.com">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>
                                        <input type="tel" id="phone" class="form-control" value="+1 (555) 123-4567">
                                    </div>
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <select id="country" class="form-control">
                                            <option value="US" selected>United States</option>
                                            <option value="CA">Canada</option>
                                            <option value="UK">United Kingdom</option>
                                            <option value="AU">Australia</option>
                                            <option value="DE">Germany</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>

                        <!-- Security Settings -->
                        <div class="settings-section">
                            <h3 class="settings-section-title">Security</h3>
                            <form class="settings-form">
                                <div class="form-group">
                                    <label for="current_password">Current Password</label>
                                    <input type="password" id="current_password" class="form-control">
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="new_password">New Password</label>
                                        <input type="password" id="new_password" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="confirm_password">Confirm New Password</label>
                                        <input type="password" id="confirm_password" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Update Password</button>
                                </div>
                            </form>
                        </div>

                        <!-- Notification Settings -->
                        <div class="settings-section">
                            <h3 class="settings-section-title">Notification Preferences</h3>
                            <div class="notification-settings">
                                <div class="notification-option">
                                    <div class="notification-option-label">
                                        <span>Auction Outbid Alerts</span>
                                        <p>Get notified when someone outbids you</p>
                                    </div>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="outbid_notification" checked>
                                        <label for="outbid_notification"></label>
                                    </div>
                                </div>
                                <div class="notification-option">
                                    <div class="notification-option-label">
                                        <span>Auction Ending Reminders</span>
                                        <p>Get notified when auctions you're watching are about to end</p>
                                    </div>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="ending_notification" checked>
                                        <label for="ending_notification"></label>
                                    </div>
                                </div>
                                <div class="notification-option">
                                    <div class="notification-option-label">
                                        <span>New Auction Alerts</span>
                                        <p>Get notified when new auctions matching your interests are added</p>
                                    </div>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="new_notification" checked>
                                        <label for="new_notification"></label>
                                    </div>
                                </div>
                                <div class="notification-option">
                                    <div class="notification-option-label">
                                        <span>Order Status Updates</span>
                                        <p>Get notified about changes to your order status</p>
                                    </div>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="order_notification" checked>
                                        <label for="order_notification"></label>
                                    </div>
                                </div>
                                <div class="notification-option">
                                    <div class="notification-option-label">
                                        <span>Promotional Emails</span>
                                        <p>Receive special offers, promotions, and news</p>
                                    </div>
                                    <div class="toggle-switch">
                                        <input type="checkbox" id="promo_notification">
                                        <label for="promo_notification"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dashboard navigation
    const dashboardNavItems = document.querySelectorAll('.dashboard-nav-item');
    const dashboardPanels = document.querySelectorAll('.dashboard-panel');

    dashboardNavItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get panel to show
            const targetPanel = this.getAttribute('data-panel');
            
            // Remove active class from all nav items and panels
            dashboardNavItems.forEach(navItem => navItem.classList.remove('active'));
            dashboardPanels.forEach(panel => panel.classList.remove('active'));
            
            // Add active class to clicked nav item and corresponding panel
            this.classList.add('active');
            document.getElementById(targetPanel + '-panel').classList.add('active');
        });
    });

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

    // Toggle switches
    const toggles = document.querySelectorAll('.toggle-switch input');
    toggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            console.log(`${this.id} is now ${this.checked ? 'enabled' : 'disabled'}`);
        });
    });

    // Pagination buttons
    const prevButton = document.querySelector('.page-prev');
    const nextButton = document.querySelector('.page-next');
    
    if (prevButton && nextButton) {
        let currentPage = 1;
        const totalPages = 3;
        
        const updatePagination = () => {
            const pageInfo = document.querySelector('.page-info');
            if (pageInfo) {
                pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
            }
            
            prevButton.disabled = currentPage === 1;
            nextButton.disabled = currentPage === totalPages;
        };
        
        prevButton.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                updatePagination();
            }
        });
        
        nextButton.addEventListener('click', function() {
            if (currentPage < totalPages) {
                currentPage++;
                updatePagination();
            }
        });
    }
});
</script>

@endsection