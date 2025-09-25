@extends('layouts.admin')

@section('title', 'Auction Reports')
@section('page-title', 'Auction Reports')
@section('page-subtitle', 'Detailed analytics about auction performance and trends')

@section('content')
<div class="admin-data-card">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">Auction Performance Overview</div>
        <div class="admin-data-card-actions">
            <a href="{{ url('/admin/statistics') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-arrow-left"></i> Back to Statistics
            </a>
            <a href="#" class="btn btn-sm btn-secondary" id="exportAuctionReport">
                <i class="fas fa-download"></i> Export Report
            </a>
        </div>
    </div>
    <div class="admin-data-card-body">
        <!-- Auction Activity Chart -->
        <div style="margin-bottom: 30px;">
            <h4 style="margin-bottom: 15px;">Auction Activity (Last 30 Days)</h4>
            <div style="height: 300px;">
                <canvas id="auctionActivityChart"></canvas>
            </div>
        </div>
        
        <!-- Auction Performance Metrics -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div style="background-color: rgba(40, 167, 69, 0.1); padding: 20px; border-radius: 8px; text-align: center;">
                <h5 style="color: #28a745; margin-bottom: 10px;">Average Final Price</h5>
                <p style="font-size: 1.8rem; font-weight: 700; margin: 0;">${{ number_format($avgFinalPrice, 2) }}</p>
            </div>
            
            <div style="background-color: rgba(23, 162, 184, 0.1); padding: 20px; border-radius: 8px; text-align: center;">
                <h5 style="color: #17a2b8; margin-bottom: 10px;">Average Bids Per Auction</h5>
                <p style="font-size: 1.8rem; font-weight: 700; margin: 0;">{{ number_format($avgBidsPerAuction) }}</p>
            </div>
            
            <div style="background-color: rgba(255, 153, 0, 0.1); padding: 20px; border-radius: 8px; text-align: center;">
                <h5 style="color: #ff9900; margin-bottom: 10px;">Average Unique Bidders</h5>
                <p style="font-size: 1.8rem; font-weight: 700; margin: 0;">{{ number_format($avgUniqueBidders, 1) }}</p>
            </div>
            
            <div style="background-color: rgba(255, 193, 7, 0.1); padding: 20px; border-radius: 8px; text-align: center;">
                <h5 style="color: #ffc107; margin-bottom: 10px;">Completion Rate</h5>
                <p style="font-size: 1.8rem; font-weight: 700; margin: 0;">{{ number_format($completionRate) }}%</p>
            </div>
        </div>
        
        <!-- Auction Category Distribution -->
        <div style="margin-bottom: 30px;">
            <h4 style="margin-bottom: 15px;">Auction Distribution by Category</h4>
            <div style="height: 300px;">
                <canvas id="auctionCategoryChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Top Auctions Section -->
<div class="admin-data-card" style="margin-top: 30px;">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">Top Performing Auctions</div>
    </div>
    <div class="admin-data-card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 30px;">
            <!-- Top Auctions by Bids -->
            <div>
                <h4 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Top Auctions by Bids</h4>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Auction</th>
                            <th>Total Bids</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topAuctionsByBids ?? [] as $auction)
                            <tr>
                                <td>{{ Str::limit($auction->title, 30) }}</td>
                                <td>{{ number_format($auction->bids_count) }}</td>
                                <td>
                                    <a href="{{ url('/admin/auctions/' . $auction->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Top Auctions by Final Price -->
            <div>
                <h4 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Top Auctions by Final Price</h4>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Auction</th>
                            <th>Final Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topAuctionsByPrice ?? [] as $auction)
                            <tr>
                                <td>{{ Str::limit($auction->title, 30) }}</td>
                                <td>${{ number_format($auction->final_price, 2) }}</td>
                                <td>
                                    <a href="{{ url('/admin/auctions/' . $auction->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Auctions with Most Unique Bidders -->
            <div>
                <h4 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Auctions with Most Bidders</h4>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Auction</th>
                            <th>Unique Bidders</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($auctionsWithMostBidders ?? [] as $auction)
                            <tr>
                                <td>{{ Str::limit($auction->title, 30) }}</td>
                                <td>{{ number_format($auction->bids_count) }}</td>
                                <td>
                                    <a href="{{ url('/admin/auctions/' . $auction->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Auction Trends Section -->
<div class="admin-data-card" style="margin-top: 30px;">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">Auction Trends and Analysis</div>
    </div>
    <div class="admin-data-card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px;">
            <!-- Bid Timing Distribution -->
            <div>
                <h4 style="margin-bottom: 15px;">Bid Timing Distribution</h4>
                <div style="height: 300px;">
                    <canvas id="bidTimingChart"></canvas>
                </div>
                <p style="margin-top: 10px; font-size: 0.9rem; color: #666; text-align: center;">
                    This chart shows when bids are placed during an auction's lifecycle.
                </p>
            </div>
            
            <!-- Price Increase Over Time -->
            <div>
                <h4 style="margin-bottom: 15px;">Auction Price Increase Pattern</h4>
                <div style="height: 300px;">
                    <canvas id="priceIncreaseChart"></canvas>
                </div>
                <p style="margin-top: 10px; font-size: 0.9rem; color: #666; text-align: center;">
                    This chart shows the average price increase pattern during auctions.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auction Activity Chart (Last 30 days)
        const auctionActivityCtx = document.getElementById('auctionActivityChart').getContext('2d');

        // Use real data from controller
        const auctionActivityData = @json($auctionActivityData ?? []);
        const dates = auctionActivityData.map(d => d.date);
        const newAuctions = auctionActivityData.map(d => d.new);
        const completedAuctions = auctionActivityData.map(d => d.completed);
        
        const auctionActivityChart = new Chart(auctionActivityCtx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'New Auctions',
                    data: newAuctions,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Completed Auctions',
                    data: completedAuctions,
                    borderColor: '#17a2b8',
                    backgroundColor: 'rgba(23, 162, 184, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: {
                            maxTicksLimit: 10
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Auction Category Distribution Chart
        const auctionCategoryCtx = document.getElementById('auctionCategoryChart').getContext('2d');
        const categoryData = @json($auctionsByCategory ?? []);
        const categoryLabels = categoryData.map(d => d.category_name || 'Uncategorized');
        const categoryCounts = categoryData.map(d => d.count);

        const auctionCategoryChart = new Chart(auctionCategoryCtx, {
            type: 'bar',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Number of Auctions',
                    data: categoryCounts,
                    backgroundColor: [
                        'rgba(23, 162, 184, 0.6)',
                        'rgba(40, 167, 69, 0.6)',
                        'rgba(255, 153, 0, 0.6)',
                        'rgba(220, 53, 69, 0.6)',
                        'rgba(111, 66, 193, 0.6)',
                        'rgba(255, 193, 7, 0.6)',
                        'rgba(32, 201, 151, 0.6)',
                        'rgba(108, 117, 125, 0.6)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Bid Timing Distribution Chart
        const bidTimingCtx = document.getElementById('bidTimingChart').getContext('2d');
        const bidTimingData = @json($bidTimingData ?? []);
        const bidTimingLabels = bidTimingData.map(d => d.label);
        const bidTimingValues = bidTimingData.map(d => d.value);

        const bidTimingChart = new Chart(bidTimingCtx, {
            type: 'line',
            data: {
                labels: bidTimingLabels,
                datasets: [{
                    label: 'Bid Distribution',
                    data: bidTimingValues,
                    borderColor: '#ff9900',
                    backgroundColor: 'rgba(255, 153, 0, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Bid Volume'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Auction Lifecycle'
                        }
                    }
                }
            }
        });
        
        // Price Increase Over Time Chart
        const priceIncreaseCtx = document.getElementById('priceIncreaseChart').getContext('2d');
        const priceIncreaseData = @json($priceIncreaseData ?? []);
        const priceLabels = priceIncreaseData.length > 0 ? priceIncreaseData.map(d => d.label) : ['Start', '10%', '20%', '30%', '40%', '50%', '60%', '70%', '80%', '90%', 'End'];
        const priceValues = priceIncreaseData.length > 0 ? priceIncreaseData.map(d => d.value) : [1, 1.2, 1.5, 1.8, 2.3, 3, 4, 5.5, 7.5, 10.5, 15];

        const priceIncreaseChart = new Chart(priceIncreaseCtx, {
            type: 'line',
            data: {
                labels: priceLabels,
                datasets: [{
                    label: 'Price Increase',
                    data: priceValues,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Price Multiple (× Starting Price)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Auction Lifecycle'
                        }
                    }
                }
            }
        });
        
        // Handle export button click
        document.getElementById('exportAuctionReport').addEventListener('click', function(e) {
            e.preventDefault();
            alert('Auction report export functionality would be implemented here.');
        });
    });
</script>
@endsection