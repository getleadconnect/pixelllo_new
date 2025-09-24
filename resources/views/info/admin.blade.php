@extends('layouts.admin')

@section('title', 'Admin Dashboard - ' . config('app.name'))

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Admin Dashboard</h1>
    </div>

    <!-- Overview Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Active Auctions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeAuctionsCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-gavel fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalRevenue, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Registered Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $usersCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingOrdersCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shipping-fast fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Area Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue Overview</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Timeframe:</div>
                            <a class="dropdown-item" href="#">Last 7 Days</a>
                            <a class="dropdown-item" href="#">Last 30 Days</a>
                            <a class="dropdown-item" href="#">Last 90 Days</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Export Data</a>
                        </div>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Auction Categories</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">View By:</div>
                            <a class="dropdown-item" href="#">Count</a>
                            <a class="dropdown-item" href="#">Revenue</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Export Data</a>
                        </div>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="categoryChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        @foreach($categoryStats as $index => $category)
                        <span class="mr-2">
                            <i class="fas fa-circle" style="color: {{ $categoryColors[$index % count($categoryColors)] }}"></i> {{ $category['name'] }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Recent Auctions -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Auctions</h6>
                    <a href="{{ route('admin.auctions') }}" class="btn btn-sm btn-primary shadow-sm">
                        View All
                    </a>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Current Price</th>
                                    <th>Bids</th>
                                    <th>End Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAuctions as $auction)
                                <tr>
                                    <td>{{ $auction->title }}</td>
                                    <td>
                                        @if($auction->status == 'active')
                                            <span class="badge badge-success">Active</span>
                                        @elseif($auction->status == 'upcoming')
                                            <span class="badge badge-primary">Upcoming</span>
                                        @elseif($auction->status == 'ended')
                                            <span class="badge badge-secondary">Ended</span>
                                        @endif
                                    </td>
                                    <td>${{ number_format($auction->currentPrice, 2) }}</td>
                                    <td>{{ $auction->bids_count }}</td>
                                    <td>{{ $auction->endTime->format('M j, Y g:i A') }}</td>
                                    <td>
                                        <a href="{{ route('admin.auctions.show', $auction->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.auctions.edit', $auction->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Users & Activity -->
        <div class="col-xl-4 col-lg-5">
            <!-- Recent Users -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">New Users</h6>
                </div>
                <div class="card-body">
                    <div class="user-list">
                        @foreach($recentUsers as $user)
                        <div class="user-item d-flex align-items-center py-2">
                            <div class="user-avatar mr-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" class="rounded-circle" width="40" height="40" alt="{{ $user->name }}">
                            </div>
                            <div class="user-info flex-grow-1">
                                <h6 class="mb-0">{{ $user->name }}</h6>
                                <small class="text-muted">Joined {{ $user->created_at->diffForHumans() }}</small>
                            </div>
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-user"></i>
                            </a>
                        </div>
                        @if(!$loop->last)
                            <hr class="my-2">
                        @endif
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.users') }}" class="btn btn-sm btn-primary">View All Users</a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
                </div>
                <div class="card-body">
                    <div class="activity-timeline">
                        @foreach($recentActivity as $activity)
                        <div class="timeline-item">
                            <div class="timeline-icon bg-{{ $activity['color'] }}">
                                <i class="fas fa-{{ $activity['icon'] }}"></i>
                            </div>
                            <div class="timeline-content">
                                <p class="mb-0">{{ $activity['message'] }}</p>
                                <small class="text-muted">{{ $activity['time'] }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Row -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="quick-actions">
                        <a href="{{ route('admin.auctions.create') }}" class="quick-action-item">
                            <div class="action-icon bg-primary">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="action-text">Add New Auction</div>
                        </a>
                        
                        <a href="{{ route('admin.users.create') }}" class="quick-action-item">
                            <div class="action-icon bg-info">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="action-text">Add New User</div>
                        </a>
                        
                        <a href="{{ route('admin.categories.create') }}" class="quick-action-item">
                            <div class="action-icon bg-success">
                                <i class="fas fa-folder-plus"></i>
                            </div>
                            <div class="action-text">Add New Category</div>
                        </a>
                        
                        <a href="{{ route('admin.orders') }}" class="quick-action-item">
                            <div class="action-icon bg-warning">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <div class="action-text">Manage Orders</div>
                        </a>
                        
                        <a href="{{ route('admin.statistics') }}" class="quick-action-item">
                            <div class="action-icon bg-dark">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="action-text">View Reports</div>
                        </a>
                        
                        <a href="{{ route('admin.settings') }}" class="quick-action-item">
                            <div class="action-icon bg-secondary">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <div class="action-text">Site Settings</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* User List Styles */
    .user-list {
        max-height: 350px;
        overflow-y: auto;
    }
    
    /* Activity Timeline Styles */
    .activity-timeline {
        position: relative;
        margin-left: 20px;
        max-height: 350px;
        overflow-y: auto;
        padding-right: 10px;
    }
    
    .timeline-item {
        position: relative;
        padding-left: 30px;
        padding-bottom: 20px;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-item:before {
        content: '';
        position: absolute;
        left: 10px;
        top: 20px;
        bottom: 0;
        width: 2px;
        background-color: #e3e6f0;
    }
    
    .timeline-item:last-child:before {
        display: none;
    }
    
    .timeline-icon {
        position: absolute;
        left: 0;
        top: 0;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 0.6rem;
    }
    
    .timeline-content {
        padding-bottom: 10px;
    }
    
    /* Quick Actions Styles */
    .quick-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }
    
    .quick-action-item {
        width: 150px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        text-decoration: none !important;
        transition: transform 0.3s;
    }
    
    .quick-action-item:hover {
        transform: translateY(-5px);
    }
    
    .action-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        margin-bottom: 10px;
    }
    
    .action-text {
        color: #5a5c69;
        font-weight: 500;
    }
    
    /* Responsive Chart Containers */
    .chart-area, .chart-pie {
        position: relative;
        height: 20rem;
    }
    
    @media (max-width: 768px) {
        .chart-area, .chart-pie {
            height: 15rem;
        }
        
        .quick-action-item {
            width: 120px;
        }
        
        .action-icon {
            width: 50px;
            height: 50px;
            font-size: 20px;
        }
    }
</style>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($revenueData['labels']) !!},
                datasets: [{
                    label: 'Revenue',
                    lineTension: 0.3,
                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: {!! json_encode($revenueData['data']) !!},
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    },
                    y: {
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            callback: function(value, index, values) {
                                return '$' + value;
                            }
                        },
                        grid: {
                            color: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            borderDashOffset: [2]
                        }
                    },
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyColor: "#858796",
                        titleMarginBottom: 10,
                        titleColor: '#6e707e',
                        titleFontSize: 14,
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        intersect: false,
                        mode: 'index',
                        caretPadding: 10,
                        callbacks: {
                            label: function(context) {
                                return 'Revenue: $' + context.parsed.y;
                            }
                        }
                    }
                }
            }
        });

        // Category Pie Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_column($categoryStats, 'name')) !!},
                datasets: [{
                    data: {!! json_encode(array_column($categoryStats, 'count')) !!},
                    backgroundColor: {!! json_encode($categoryColors) !!},
                    hoverBackgroundColor: {!! json_encode(array_map(function($color) {
                        return str_replace('0.5', '0.7', $color);
                    }, $categoryColors)) !!},
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    }
                }
            },
        });
    });
</script>
@endsection
@endsection