@php
use App\Models\User;
@endphp

@extends('layouts.admin')

@section('title', 'User Reports')
@section('page-title', 'User Reports')
@section('page-subtitle', 'Detailed analytics about user behavior and performance')

@section('content')
<div class="admin-data-card">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">User Activity Overview</div>
        <div class="admin-data-card-actions">
            <a href="{{ url('/admin/statistics') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-arrow-left"></i> Back to Statistics
            </a>
            <a href="#" class="btn btn-sm btn-secondary" id="exportUserReport">
                <i class="fas fa-download"></i> Export Report
            </a>
        </div>
    </div>
    <div class="admin-data-card-body">
        <!-- User Activity Distribution Chart -->
        <div style="margin-bottom: 30px;">
            <h4 style="margin-bottom: 15px;">User Activity Distribution</h4>
            <div style="height: 300px;">
                <canvas id="userActivityChart"></canvas>
            </div>
        </div>
        
        <!-- User Growth Chart -->
        <div style="margin-bottom: 30px;">
            <h4 style="margin-bottom: 15px;">User Growth (Last 12 Months)</h4>
            <div style="height: 300px;">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Top Users by Different Metrics -->
<div class="admin-data-card" style="margin-top: 30px;">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">Top Users by Activity</div>
    </div>
    <div class="admin-data-card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 30px;">
            <!-- Top Bidders -->
            <div>
                <h4 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Top Bidders</h4>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Bids Placed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topBidders ?? [] as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ number_format($user->bids_count) }}</td>
                                <td>
                                    <a href="{{ url('/admin/users/' . $user->id) }}" class="btn btn-sm btn-primary">
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
            
            <!-- Top Winners -->
            <div>
                <h4 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Top Auction Winners</h4>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Auctions Won</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topWinners ?? [] as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ number_format($user->auctions_count) }}</td>
                                <td>
                                    <a href="{{ url('/admin/users/' . $user->id) }}" class="btn btn-sm btn-primary">
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
            
            <!-- Top Spenders -->
            <div>
                <h4 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Top Spenders</h4>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Total Spent</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topSpenders ?? [] as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>${{ number_format($user->orders_sum_total, 2) }}</td>
                                <td>
                                    <a href="{{ url('/admin/users/' . $user->id) }}" class="btn btn-sm btn-primary">
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

<!-- User Demographic Analysis -->
<div class="admin-data-card" style="margin-top: 30px;">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">User Demographics</div>
    </div>
    <div class="admin-data-card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            <!-- User by Country -->
            <div>
                <h4 style="margin-bottom: 15px;">Users by Country</h4>
                <div style="height: 300px;">
                    <canvas id="userCountryChart"></canvas>
                </div>
            </div>
            
            <!-- Users by Role -->
            <div>
                <h4 style="margin-bottom: 15px;">Users by Role</h4>
                <div style="height: 300px;">
                    <canvas id="userRoleChart"></canvas>
                </div>
            </div>
            
            <!-- User Activity Status -->
            <div>
                <h4 style="margin-bottom: 15px;">User Activity Status</h4>
                <div style="height: 300px;">
                    <canvas id="userStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // User Activity Distribution Chart
        const userActivityCtx = document.getElementById('userActivityChart').getContext('2d');
        const userActivityChart = new Chart(userActivityCtx, {
            type: 'bar',
            data: {
                labels: ['No Activity', '1-5 Bids', '6-20 Bids', '21-50 Bids', '51-100 Bids', '100+ Bids'],
                datasets: [{
                    label: 'Number of Users',
                    data: [
                        {{ $userActivityDistribution[0] ?? 0 }},
                        {{ $userActivityDistribution[1] ?? 0 }},
                        {{ $userActivityDistribution[2] ?? 0 }},
                        {{ $userActivityDistribution[3] ?? 0 }},
                        {{ $userActivityDistribution[4] ?? 0 }},
                        {{ $userActivityDistribution[5] ?? 0 }}
                    ],
                    backgroundColor: 'rgba(23, 162, 184, 0.6)',
                    borderColor: '#17a2b8',
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
        
        // User Growth Chart
        const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');

        // Use real data from controller
        const userGrowthData = @json($userGrowthData ?? []);
        const monthNames = userGrowthData.map(d => d.month);
        const monthlyData = userGrowthData.map(d => d.new_users);
        const cumulativeData = userGrowthData.map(d => d.total_users);
        
        const userGrowthChart = new Chart(userGrowthCtx, {
            type: 'line',
            data: {
                labels: monthNames,
                datasets: [{
                    label: 'New Users',
                    data: monthlyData,
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    borderColor: '#28a745',
                    borderWidth: 2,
                    yAxisID: 'y',
                    tension: 0.4
                }, {
                    label: 'Total Users',
                    data: cumulativeData,
                    backgroundColor: 'rgba(255, 153, 0, 0.2)',
                    borderColor: '#ff9900',
                    borderWidth: 2,
                    yAxisID: 'y1',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'New Users'
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        },
                        title: {
                            display: true,
                            text: 'Total Users'
                        }
                    }
                }
            }
        });
        
        // User Country Chart
        const userCountryCtx = document.getElementById('userCountryChart').getContext('2d');
        const countryData = @json($usersByCountry ?? []);
        const countryLabels = countryData.map(d => d.country || 'Unknown');
        const countryCounts = countryData.map(d => d.count || 0);

        // Add 'Other' if we have data
        const totalCountryUsers = countryCounts.reduce((a, b) => a + b, 0);
        const totalUsers = {{ User::where('role', 'customer')->count() }};
        const otherCount = Math.max(0, totalUsers - totalCountryUsers);
        if (otherCount > 0) {
            countryLabels.push('Other');
            countryCounts.push(otherCount);
        }

        const userCountryChart = new Chart(userCountryCtx, {
            type: 'pie',
            data: {
                labels: countryLabels,
                datasets: [{
                    data: countryCounts,
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.6)',
                        'rgba(23, 162, 184, 0.6)',
                        'rgba(255, 153, 0, 0.6)',
                        'rgba(255, 193, 7, 0.6)',
                        'rgba(220, 53, 69, 0.6)',
                        'rgba(108, 117, 125, 0.6)'
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
        
        // User Role Chart
        const userRoleCtx = document.getElementById('userRoleChart').getContext('2d');
        const userRoleChart = new Chart(userRoleCtx, {
            type: 'doughnut',
            data: {
                labels: ['Customers', 'Admins'],
                datasets: [{
                    data: [{{ $usersByRole['customers'] ?? 0 }}, {{ $usersByRole['admins'] ?? 0 }}],
                    backgroundColor: [
                        'rgba(23, 162, 184, 0.6)',
                        'rgba(220, 53, 69, 0.6)'
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
        
        // User Status Chart
        const userStatusCtx = document.getElementById('userStatusChart').getContext('2d');
        const userStatusChart = new Chart(userStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Active', 'Inactive'],
                datasets: [{
                    data: [{{ $usersByStatus['active'] ?? 0 }}, {{ $usersByStatus['inactive'] ?? 0 }}],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.6)',
                        'rgba(108, 117, 125, 0.6)'
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
        
        // Handle export button click
        document.getElementById('exportUserReport').addEventListener('click', function(e) {
            e.preventDefault();
            alert('User report export functionality would be implemented here.');
        });
    });
</script>
@endsection