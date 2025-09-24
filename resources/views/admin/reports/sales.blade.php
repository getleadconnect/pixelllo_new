@extends('layouts.admin')

@section('title', 'Sales Reports')
@section('page-title', 'Sales Reports')
@section('page-subtitle', 'Detailed analytics about sales performance and revenue')

@section('content')
<div class="admin-data-card">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">Sales Performance Overview</div>
        <div class="admin-data-card-actions">
            <a href="{{ url('/admin/statistics') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-arrow-left"></i> Back to Statistics
            </a>
            <a href="#" class="btn btn-sm btn-secondary" id="exportSalesReport">
                <i class="fas fa-download"></i> Export Report
            </a>
        </div>
    </div>
    <div class="admin-data-card-body">
        <!-- Revenue Metrics Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div style="background-color: rgba(40, 167, 69, 0.1); padding: 20px; border-radius: 8px; text-align: center;">
                <h5 style="color: #28a745; margin-bottom: 10px;">Total Revenue</h5>
                <p style="font-size: 1.8rem; font-weight: 700; margin: 0;">${{ number_format($totalRevenue ?? 45875.50, 2) }}</p>
            </div>
            
            <div style="background-color: rgba(23, 162, 184, 0.1); padding: 20px; border-radius: 8px; text-align: center;">
                <h5 style="color: #17a2b8; margin-bottom: 10px;">Average Order Value</h5>
                <p style="font-size: 1.8rem; font-weight: 700; margin: 0;">${{ number_format($avgOrderValue ?? 98.45, 2) }}</p>
            </div>
            
            <div style="background-color: rgba(255, 153, 0, 0.1); padding: 20px; border-radius: 8px; text-align: center;">
                <h5 style="color: #ff9900; margin-bottom: 10px;">Total Orders</h5>
                <p style="font-size: 1.8rem; font-weight: 700; margin: 0;">{{ number_format($totalOrders ?? 465) }}</p>
            </div>
            
            <div style="background-color: rgba(255, 193, 7, 0.1); padding: 20px; border-radius: 8px; text-align: center;">
                <h5 style="color: #ffc107; margin-bottom: 10px;">Conversion Rate</h5>
                <p style="font-size: 1.8rem; font-weight: 700; margin: 0;">{{ number_format($conversionRate ?? 78.5) }}%</p>
            </div>
        </div>
        
        <!-- Monthly Sales Chart -->
        <div style="margin-bottom: 30px;">
            <h4 style="margin-bottom: 15px;">Monthly Sales (Last 12 Months)</h4>
            <div style="height: 300px;">
                <canvas id="monthlySalesChart"></canvas>
            </div>
        </div>
        
        <!-- Sales Comparison -->
        <div style="margin-bottom: 30px;">
            <h4 style="margin-bottom: 15px;">Revenue Comparison (YoY)</h4>
            <div style="height: 300px;">
                <canvas id="yearComparisonChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Sales by Category Section -->
<div class="admin-data-card" style="margin-top: 30px;">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">Sales by Category</div>
    </div>
    <div class="admin-data-card-body">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <!-- Category Sales Chart -->
            <div>
                <h4 style="margin-bottom: 15px;">Revenue by Category</h4>
                <div style="height: 300px;">
                    <canvas id="categorySalesChart"></canvas>
                </div>
            </div>
            
            <!-- Category Table -->
            <div>
                <h4 style="margin-bottom: 15px;">Category Performance</h4>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Revenue</th>
                            <th>Orders</th>
                            <th>Avg. Sale</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $categories = [
                                ['name' => 'Electronics', 'revenue' => 12580.25, 'orders' => 95],
                                ['name' => 'Fashion', 'revenue' => 8750.80, 'orders' => 120],
                                ['name' => 'Home & Garden', 'revenue' => 6320.15, 'orders' => 78],
                                ['name' => 'Sports', 'revenue' => 5425.50, 'orders' => 65],
                                ['name' => 'Collectibles', 'revenue' => 4875.30, 'orders' => 42],
                                ['name' => 'Jewelry', 'revenue' => 3980.75, 'orders' => 25],
                                ['name' => 'Toys', 'revenue' => 2450.20, 'orders' => 30],
                                ['name' => 'Other', 'revenue' => 1492.55, 'orders' => 10],
                            ];
                        @endphp
                        
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category['name'] }}</td>
                                <td>${{ number_format($category['revenue'], 2) }}</td>
                                <td>{{ number_format($category['orders']) }}</td>
                                <td>${{ number_format($category['revenue'] / $category['orders'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Sales Trends and Analysis -->
<div class="admin-data-card" style="margin-top: 30px;">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">Sales Trends and Analysis</div>
    </div>
    <div class="admin-data-card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px;">
            <!-- Daily Sales Distribution -->
            <div>
                <h4 style="margin-bottom: 15px;">Daily Sales Distribution</h4>
                <div style="height: 300px;">
                    <canvas id="dailySalesChart"></canvas>
                </div>
                <p style="margin-top: 10px; font-size: 0.9rem; color: #666; text-align: center;">
                    This chart shows sales distribution by day of the week.
                </p>
            </div>
            
            <!-- Hourly Sales Distribution -->
            <div>
                <h4 style="margin-bottom: 15px;">Hourly Sales Distribution</h4>
                <div style="height: 300px;">
                    <canvas id="hourlySalesChart"></canvas>
                </div>
                <p style="margin-top: 10px; font-size: 0.9rem; color: #666; text-align: center;">
                    This chart shows sales distribution by hour of the day.
                </p>
            </div>
            
            <!-- Customer Value Tiers -->
            <div>
                <h4 style="margin-bottom: 15px;">Customer Value Tiers</h4>
                <div style="height: 300px;">
                    <canvas id="customerTiersChart"></canvas>
                </div>
                <p style="margin-top: 10px; font-size: 0.9rem; color: #666; text-align: center;">
                    Distribution of customers by their lifetime value.
                </p>
            </div>
            
            <!-- Repeat Purchase Rate -->
            <div>
                <h4 style="margin-bottom: 15px;">Repeat Purchase Rate</h4>
                <div style="height: 300px;">
                    <canvas id="repeatPurchaseChart"></canvas>
                </div>
                <p style="margin-top: 10px; font-size: 0.9rem; color: #666; text-align: center;">
                    Percentage of customers with repeat purchases.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Monthly Sales Chart
        const monthlySalesCtx = document.getElementById('monthlySalesChart').getContext('2d');
        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        // Prepare data for the chart
        let monthlySalesData = [];
        let monthlyOrdersData = [];
        
        @if (isset($monthlySales) && count($monthlySales) > 0)
            @foreach ($monthlySales as $data)
                monthlySalesData[{{ $data->month - 1 }}] = {{ $data->total }};
                monthlyOrdersData[{{ $data->month - 1 }}] = {{ $data->count }};
            @endforeach
        @else
            // Sample data if not available
            monthlySalesData = [3250, 3750, 4200, 4800, 5300, 4950, 5250, 5800, 6200, 5900, 6500, 7200];
            monthlyOrdersData = [35, 40, 45, 50, 55, 50, 55, 60, 65, 60, 68, 75];
        @endif
        
        const monthlySalesChart = new Chart(monthlySalesCtx, {
            type: 'bar',
            data: {
                labels: monthNames,
                datasets: [{
                    label: 'Revenue',
                    data: monthlySalesData,
                    backgroundColor: 'rgba(40, 167, 69, 0.6)',
                    borderColor: '#28a745',
                    borderWidth: 1,
                    yAxisID: 'y'
                }, {
                    label: 'Orders',
                    data: monthlyOrdersData,
                    type: 'line',
                    borderColor: '#ff9900',
                    backgroundColor: 'rgba(255, 153, 0, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y1'
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
                            text: 'Revenue ($)'
                        },
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
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
                            text: 'Orders'
                        }
                    }
                }
            }
        });
        
        // Year over Year Comparison Chart
        const yearComparisonCtx = document.getElementById('yearComparisonChart').getContext('2d');
        const yearComparisonChart = new Chart(yearComparisonCtx, {
            type: 'line',
            data: {
                labels: monthNames,
                datasets: [{
                    label: 'This Year',
                    data: [3250, 3750, 4200, 4800, 5300, 4950, 5250, 5800, 6200, 5900, 6500, 7200],
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Last Year',
                    data: [2800, 3200, 3600, 4100, 4500, 4200, 4500, 4900, 5300, 5000, 5500, 6200],
                    borderColor: '#17a2b8',
                    backgroundColor: 'rgba(23, 162, 184, 0.1)',
                    borderWidth: 2,
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
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
        
        // Category Sales Chart
        const categorySalesCtx = document.getElementById('categorySalesChart').getContext('2d');
        const categorySalesChart = new Chart(categorySalesCtx, {
            type: 'pie',
            data: {
                labels: ['Electronics', 'Fashion', 'Home & Garden', 'Sports', 'Collectibles', 'Jewelry', 'Toys', 'Other'],
                datasets: [{
                    data: [12580.25, 8750.80, 6320.15, 5425.50, 4875.30, 3980.75, 2450.20, 1492.55],
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
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += '$' + parseFloat(context.raw).toLocaleString();
                                return label;
                            }
                        }
                    }
                }
            }
        });
        
        // Daily Sales Distribution Chart
        const dailySalesCtx = document.getElementById('dailySalesChart').getContext('2d');
        const dailySalesChart = new Chart(dailySalesCtx, {
            type: 'bar',
            data: {
                labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                datasets: [{
                    label: 'Orders',
                    data: [60, 65, 70, 75, 90, 120, 85],
                    backgroundColor: 'rgba(40, 167, 69, 0.6)',
                    borderColor: '#28a745',
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
        
        // Hourly Sales Distribution Chart
        const hourlySalesCtx = document.getElementById('hourlySalesChart').getContext('2d');
        const hours = Array.from({length: 24}, (_, i) => `${i}:00`);
        const hourlySalesChart = new Chart(hourlySalesCtx, {
            type: 'line',
            data: {
                labels: hours,
                datasets: [{
                    label: 'Orders',
                    data: [2, 1, 1, 0, 0, 1, 3, 8, 15, 20, 25, 30, 35, 32, 28, 25, 30, 40, 45, 35, 25, 15, 8, 4],
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
                    },
                    x: {
                        ticks: {
                            maxTicksLimit: 12
                        }
                    }
                }
            }
        });
        
        // Customer Tiers Chart
        const customerTiersCtx = document.getElementById('customerTiersChart').getContext('2d');
        const customerTiersChart = new Chart(customerTiersCtx, {
            type: 'doughnut',
            data: {
                labels: ['$1-$50', '$51-$100', '$101-$250', '$251-$500', '$501-$1000', '$1000+'],
                datasets: [{
                    data: [35, 25, 20, 10, 7, 3],
                    backgroundColor: [
                        'rgba(108, 117, 125, 0.6)',
                        'rgba(23, 162, 184, 0.6)',
                        'rgba(40, 167, 69, 0.6)',
                        'rgba(255, 193, 7, 0.6)',
                        'rgba(255, 153, 0, 0.6)',
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
        
        // Repeat Purchase Chart
        const repeatPurchaseCtx = document.getElementById('repeatPurchaseChart').getContext('2d');
        const repeatPurchaseChart = new Chart(repeatPurchaseCtx, {
            type: 'doughnut',
            data: {
                labels: ['One-time Buyers', '2-3 Purchases', '4-5 Purchases', '6+ Purchases'],
                datasets: [{
                    data: [60, 25, 10, 5],
                    backgroundColor: [
                        'rgba(108, 117, 125, 0.6)',
                        'rgba(23, 162, 184, 0.6)',
                        'rgba(40, 167, 69, 0.6)',
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
        
        // Handle export button click
        document.getElementById('exportSalesReport').addEventListener('click', function(e) {
            e.preventDefault();
            alert('Sales report export functionality would be implemented here.');
        });
    });
</script>
@endsection