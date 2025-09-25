@extends('layouts.admin')

@section('title', 'Statistics')
@section('page-title', 'Platform Statistics')
@section('page-subtitle', 'Overview of platform performance and metrics')

@section('content')
<!-- Stats Cards -->
<div class="admin-cards">
    <!-- Users Card -->
    <div class="admin-card">
        <div class="admin-card-inner">
            <div class="admin-card-icon users">
                <i class="fas fa-users"></i>
            </div>
            <div class="admin-card-content">
                <h3>{{ number_format($totalUsers ?? 0) }}</h3>
                <p>Total Customers</p>
            </div>
        </div>
    </div>
    
    <!-- Auctions Card -->
    <div class="admin-card">
        <div class="admin-card-inner">
            <div class="admin-card-icon auctions">
                <i class="fas fa-gavel"></i>
            </div>
            <div class="admin-card-content">
                <h3>{{ number_format($totalAuctions ?? 0) }}</h3>
                <p>Total Auctions</p>
            </div>
        </div>
    </div>
    
    <!-- Bids Card -->
    <div class="admin-card">
        <div class="admin-card-inner">
            <div class="admin-card-icon bids">
                <i class="fas fa-hand-paper"></i>
            </div>
            <div class="admin-card-content">
                <h3>{{ number_format($totalBids ?? 0) }}</h3>
                <p>Total Bids</p>
            </div>
        </div>
    </div>
    
    <!-- Revenue Card -->
    <div class="admin-card">
        <div class="admin-card-inner">
            <div class="admin-card-icon orders" style="background-color: rgba(40, 167, 69, 0.1); color: #28a745;">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="admin-card-content">
                <h3>${{ number_format($totalRevenue ?? 0, 2) }}</h3>
                <p>Total Revenue (Paid)</p>
            </div>
        </div>
    </div>
</div>

<!-- Today's Activity -->
<div class="admin-data-card" style="margin-bottom: 30px;">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">Today's Activity</div>
    </div>
    <div class="admin-data-card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
            <div style="text-align: center; padding: 15px; background: rgba(23, 162, 184, 0.1); border-radius: 8px;">
                <i class="fas fa-user-plus" style="font-size: 24px; color: #17a2b8; margin-bottom: 10px;"></i>
                <h4 style="margin: 0; font-size: 1.5rem;">{{ number_format($todayUsers ?? 0) }}</h4>
                <p style="margin: 5px 0 0; color: #666; font-size: 0.9rem;">New Users</p>
            </div>
            <div style="text-align: center; padding: 15px; background: rgba(255, 193, 7, 0.1); border-radius: 8px;">
                <i class="fas fa-gavel" style="font-size: 24px; color: #ffc107; margin-bottom: 10px;"></i>
                <h4 style="margin: 0; font-size: 1.5rem;">{{ number_format($todayAuctions ?? 0) }}</h4>
                <p style="margin: 5px 0 0; color: #666; font-size: 0.9rem;">New Auctions</p>
            </div>
            <div style="text-align: center; padding: 15px; background: rgba(255, 153, 0, 0.1); border-radius: 8px;">
                <i class="fas fa-hand-paper" style="font-size: 24px; color: #ff9900; margin-bottom: 10px;"></i>
                <h4 style="margin: 0; font-size: 1.5rem;">{{ number_format($todayBids ?? 0) }}</h4>
                <p style="margin: 5px 0 0; color: #666; font-size: 0.9rem;">Bids Placed</p>
            </div>
            <div style="text-align: center; padding: 15px; background: rgba(40, 167, 69, 0.1); border-radius: 8px;">
                <i class="fas fa-dollar-sign" style="font-size: 24px; color: #28a745; margin-bottom: 10px;"></i>
                <h4 style="margin: 0; font-size: 1.5rem;">${{ number_format($todayRevenue ?? 0, 2) }}</h4>
                <p style="margin: 5px 0 0; color: #666; font-size: 0.9rem;">Revenue Today</p>
            </div>
        </div>
    </div>
</div>

<!-- Key Performance Metrics -->
<div class="admin-data-card" style="margin-bottom: 30px;">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">Key Performance Metrics</div>
    </div>
    <div class="admin-data-card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div style="padding: 15px; border: 1px solid #e5e7eb; border-radius: 8px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h5 style="margin: 0 0 5px; color: #666;">Active Users (30 days)</h5>
                        <h3 style="margin: 0; color: #17a2b8;">{{ number_format($activeUsers ?? 0) }}</h3>
                    </div>
                    <i class="fas fa-user-check" style="font-size: 30px; color: #17a2b8; opacity: 0.3;"></i>
                </div>
            </div>
            <div style="padding: 15px; border: 1px solid #e5e7eb; border-radius: 8px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h5 style="margin: 0 0 5px; color: #666;">Auction Conversion Rate</h5>
                        <h3 style="margin: 0; color: #28a745;">{{ number_format($auctionConversionRate ?? 0, 1) }}%</h3>
                    </div>
                    <i class="fas fa-chart-line" style="font-size: 30px; color: #28a745; opacity: 0.3;"></i>
                </div>
            </div>
            <div style="padding: 15px; border: 1px solid #e5e7eb; border-radius: 8px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h5 style="margin: 0 0 5px; color: #666;">Auction Win Rate</h5>
                        <h3 style="margin: 0; color: #ffc107;">{{ number_format($winRate ?? 0, 1) }}%</h3>
                    </div>
                    <i class="fas fa-trophy" style="font-size: 30px; color: #ffc107; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue and User Charts -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
    <!-- Monthly Revenue Chart -->
    <div class="admin-data-card">
        <div class="admin-data-card-header">
            <div class="admin-data-card-title">Monthly Revenue</div>
            <div class="admin-data-card-actions">
                <a href="{{ url('/admin/reports/sales') }}" class="btn btn-sm btn-primary">
                    View Detailed Report
                </a>
            </div>
        </div>
        <div class="admin-data-card-body">
            <div class="chart-container">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Monthly Registrations Chart -->
    <div class="admin-data-card">
        <div class="admin-data-card-header">
            <div class="admin-data-card-title">Monthly User Registrations</div>
            <div class="admin-data-card-actions">
                <a href="{{ url('/admin/reports/users') }}" class="btn btn-sm btn-primary">
                    View Detailed Report
                </a>
            </div>
        </div>
        <div class="admin-data-card-body">
            <div class="chart-container">
                <canvas id="usersChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Platform Analysis -->
<div class="admin-data-card">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">Platform Analysis</div>
    </div>
    <div class="admin-data-card-body">
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px;">
            <!-- Auction Status Distribution -->
            <div>
                <h4 style="margin-bottom: 15px;">Auction Status Distribution</h4>
                <div style="width: 100%; height: 300px;">
                    <canvas id="auctionStatusChart"></canvas>
                </div>
            </div>
            
            <!-- Order Status Distribution -->
            <div>
                <h4 style="margin-bottom: 15px;">Order Status Distribution</h4>
                <div style="width: 100%; height: 300px;">
                    <canvas id="orderStatusChart"></canvas>
                </div>
            </div>
            
            <!-- Top Bidders -->
            <div>
                <h4 style="margin-bottom: 15px;">Top Bidders</h4>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Total Bids</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topBidders ?? [] as $user)
                            <tr>
                                <td>
                                    <a href="{{ url('/admin/users/' . $user->id) }}">{{ $user->name }}</a>
                                </td>
                                <td>{{ number_format($user->bids_count) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Top Auctions -->
            <div>
                <h4 style="margin-bottom: 15px;">Top Auctions by Bids</h4>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Auction</th>
                            <th>Total Bids</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topAuctions ?? [] as $auction)
                            <tr>
                                <td>
                                    <a href="{{ url('/admin/auctions/' . $auction->id) }}">{{ Str::limit($auction->title, 30) }}</a>
                                </td>
                                <td>{{ number_format($auction->bids_count) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Monthly Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        // Prepare data for the chart
        let revenueData = Array(12).fill(0);
        
        @if (isset($monthlyRevenue) && count($monthlyRevenue) > 0)
            @foreach ($monthlyRevenue as $data)
                revenueData[{{ $data->month - 1 }}] = {{ $data->total }};
            @endforeach
        @endif
        
        const revenueChart = new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: monthNames,
                datasets: [{
                    label: 'Revenue ($)',
                    data: revenueData,
                    backgroundColor: 'rgba(40, 167, 69, 0.4)',
                    borderColor: '#28a745',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
        
        // Monthly User Registrations Chart
        const usersCtx = document.getElementById('usersChart').getContext('2d');
        
        // Prepare data for the chart
        let userData = Array(12).fill(0);
        
        @if (isset($monthlyRegistrations) && count($monthlyRegistrations) > 0)
            @foreach ($monthlyRegistrations as $data)
                userData[{{ $data->month - 1 }}] = {{ $data->count }};
            @endforeach
        @endif
        
        const usersChart = new Chart(usersCtx, {
            type: 'line',
            data: {
                labels: monthNames,
                datasets: [{
                    label: 'New Users',
                    data: userData,
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
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Auction Status Distribution Chart
        const auctionStatusCtx = document.getElementById('auctionStatusChart').getContext('2d');
        const auctionStatusChart = new Chart(auctionStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Upcoming', 'Active', 'Ended', 'Cancelled'],
                datasets: [{
                    data: [
                        {{ $auctionStatusData['upcoming'] ?? 0 }},
                        {{ $auctionStatusData['active'] ?? 0 }},
                        {{ $auctionStatusData['ended'] ?? 0 }},
                        {{ $auctionStatusData['cancelled'] ?? 0 }}
                    ],
                    backgroundColor: [
                        '#ffc107', // Upcoming (yellow)
                        '#28a745', // Active (green)
                        '#17a2b8', // Ended (blue)
                        '#dc3545'  // Cancelled (red)
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
        
        // Order Status Distribution Chart
        const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
        const orderStatusChart = new Chart(orderStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'],
                datasets: [{
                    data: [
                        {{ $orderStatusData['pending'] ?? 0 }},
                        {{ $orderStatusData['processing'] ?? 0 }},
                        {{ $orderStatusData['shipped'] ?? 0 }},
                        {{ $orderStatusData['delivered'] ?? 0 }},
                        {{ $orderStatusData['cancelled'] ?? 0 }}
                    ],
                    backgroundColor: [
                        '#ffc107', // Pending (yellow)
                        '#17a2b8', // Processing (blue)
                        '#fd7e14', // Shipped (orange)
                        '#28a745', // Delivered (green)
                        '#dc3545'  // Cancelled (red)
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
    });
</script>
@endsection