@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview of site activity and statistics')

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
                <p>Total Users</p>
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
    
    <!-- Orders Card -->
    <div class="admin-card">
        <div class="admin-card-inner">
            <div class="admin-card-icon orders">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="admin-card-content">
                <h3>{{ number_format($totalOrders ?? 0) }}</h3>
                <p>Total Orders</p>
            </div>
        </div>
    </div>
</div>

<div class="admin-data-row" style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
    <!-- Recent Activity Chart -->
    <div class="admin-data-card">
        <div class="admin-data-card-header">
            <div class="admin-data-card-title">Recent Activity</div>
            <div class="admin-data-card-actions">
                <select id="activityPeriod" class="form-control" style="width: auto;">
                    <option value="week">Last 7 Days</option>
                    <option value="month" selected>Last 30 Days</option>
                    <option value="year">Last 12 Months</option>
                </select>
            </div>
        </div>
        <div class="admin-data-card-body">
            <div class="chart-container">
                <canvas id="activityChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Recent Users -->
    <div class="admin-data-card">
        <div class="admin-data-card-header">
            <div class="admin-data-card-title">Recent Users</div>
            <div class="admin-data-card-actions">
                <a href="{{ url('/admin/users') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
        </div>
        <div class="admin-data-card-body">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentUsers ?? [] as $user)
                        <tr>
                            <td>
                                <a href="{{ url('/admin/users/' . $user->id) }}">{{ $user->name }}</a>
                            </td>
                            <td>
                                <span class="status-badge {{ $user->role == 'admin' ? 'active' : 'pending' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No recent users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Data Cards Row -->
<div style="margin-top: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <!-- Recent Auctions -->
    <div class="admin-data-card">
        <div class="admin-data-card-header">
            <div class="admin-data-card-title">Recent Auctions</div>
            <div class="admin-data-card-actions">
                <a href="{{ url('/admin/auctions') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
        </div>
        <div class="admin-data-card-body">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Current Price</th>
                        <th>Bids</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentAuctions ?? [] as $auction)
                        <tr>
                            <td>
                                <a href="{{ url('/admin/auctions/' . $auction->id) }}">{{ Str::limit($auction->title, 30) }}</a>
                            </td>
                            <td>
                                <span class="status-badge {{ $auction->status == 'active' ? 'active' : ($auction->status == 'pending' ? 'pending' : 'inactive') }}">
                                    {{ ucfirst($auction->status) }}
                                </span>
                            </td>
                            <td>${{ number_format($auction->current_price, 2) }}</td>
                            <td>{{ $auction->bids_count ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No recent auctions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Recent Bids -->
    <div class="admin-data-card">
        <div class="admin-data-card-header">
            <div class="admin-data-card-title">Recent Bids</div>
        </div>
        <div class="admin-data-card-body">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Auction</th>
                        <th>Amount</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentBids ?? [] as $bid)
                        <tr>
                            <td>
                                <a href="{{ url('/admin/users/' . ($bid->user->id ?? '')) }}">{{ $bid->user->name ?? 'Unknown' }}</a>
                            </td>
                            <td>
                                <a href="{{ url('/admin/auctions/' . ($bid->auction->id ?? '')) }}">{{ Str::limit($bid->auction->title ?? 'Unknown', 20) }}</a>
                            </td>
                            <td>${{ number_format($bid->amount, 2) }}</td>
                            <td>{{ $bid->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No recent bids found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Activity Chart
        const activityCtx = document.getElementById('activityChart').getContext('2d');
        const activityChart = new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7', 'Day 8', 'Day 9', 'Day 10', 'Day 11', 'Day 12', 'Day 13', 'Day 14'],
                datasets: [{
                    label: 'Bids',
                    data: [65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56, 55, 40],
                    borderColor: '#17a2b8',
                    backgroundColor: 'rgba(23, 162, 184, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Auctions',
                    data: [28, 48, 40, 19, 86, 27, 90, 28, 48, 40, 19, 86, 27, 90],
                    borderColor: '#ff9900',
                    backgroundColor: 'rgba(255, 153, 0, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Users',
                    data: [12, 19, 3, 5, 2, 3, 10, 15, 20, 25, 22, 18, 12, 8],
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: false
                    }
                }
            }
        });
        
        // Activity Period Selector
        const activityPeriod = document.getElementById('activityPeriod');
        activityPeriod.addEventListener('change', function() {
            // In a real application, this would fetch data from the backend
            // For demo purposes, just showing different random data based on selection
            if (this.value === 'week') {
                activityChart.data.labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                activityChart.data.datasets[0].data = [65, 59, 80, 81, 56, 55, 40];
                activityChart.data.datasets[1].data = [28, 48, 40, 19, 86, 27, 90];
                activityChart.data.datasets[2].data = [12, 19, 3, 5, 2, 3, 10];
            } else if (this.value === 'month') {
                activityChart.data.labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
                activityChart.data.datasets[0].data = [265, 259, 280, 281];
                activityChart.data.datasets[1].data = [128, 148, 140, 119];
                activityChart.data.datasets[2].data = [52, 59, 43, 35];
            } else if (this.value === 'year') {
                activityChart.data.labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                activityChart.data.datasets[0].data = [565, 559, 680, 681, 656, 655, 640, 665, 659, 680, 681, 656];
                activityChart.data.datasets[1].data = [328, 348, 340, 319, 386, 327, 390, 328, 348, 340, 319, 386];
                activityChart.data.datasets[2].data = [112, 119, 103, 105, 102, 103, 110, 115, 120, 125, 122, 118];
            }
            activityChart.update();
        });
    });
</script>
@endsection