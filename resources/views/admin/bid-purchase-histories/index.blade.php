@extends('layouts.admin')

@section('title', 'Bid Purchase History')
@section('page-title', 'Bid Coins Purchase History')
@section('page-subtitle', 'View all bid package purchase histories')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
@endsection

@section('content')

<style>
.badge-status {
    padding: 6px 12px;
    border-radius: 4px;
    font-weight: 500;
    text-transform: uppercase;
    font-size: 11px;
}
.badge-success {
    background-color: #d4edda;
    color: #155724;
}

/* DataTable Styling */
#historiesTable {
    border-collapse: separate !important;
    border-spacing: 0;
    border: 1px solid #dee2e6 !important;
}

#historiesTable thead th {
    background-color: #f8f9fa !important;
    border: 1px solid #dee2e6 !important;
    padding: 12px !important;
    font-weight: 600;
    color: #495057;
    vertical-align: middle;
}

#historiesTable tbody td {
    border: 1px solid #dee2e6 !important;
    padding: 12px !important;
    vertical-align: middle;
    background-color: #fff;
}

#historiesTable tbody tr {
    background-color: #fff !important;
}

#historiesTable tbody tr:hover {
    background-color: #f8f9fa !important;
}

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    margin-bottom: 15px;
}

.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate {
    margin-top: 15px;
}

/* Filter Panel Styling */
.filter-panel {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 5px;
}

.btn-block {
    width: 100%;
}

#filter_btn {
    transition: all 0.3s ease;
}

#filter_btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.filter-active {
    background-color: #28a745 !important;
    border-color: #28a745 !important;
}

.filter-active:hover {
    background-color: #218838 !important;
    border-color: #1e7e34 !important;
}

/* Info Cards Styling */
.info-card {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    height: 100%;
}

.info-card:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.info-card-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    color: white;
    flex-shrink: 0;
}

.info-card-icon.total-bids {
    background: #007bff;
}

.info-card-icon.total-amount {
    background: #28a745;
}

.info-card-icon.todays-bids {
    background: #ffc107;
}

.info-card-icon.todays-amount {
    background: #dc3545;
}

.info-card-value {
    font-size: 20px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
    line-height: 1.2;
}

.info-card-label {
    font-size: 12px;
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.info-card-content {
    margin-left: 15px;
}

</style>


<!-- Statistics Cards Row -->
<div class="row ">
    <!-- Total Bids Card -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="info-card">
            <div class="d-flex align-items-center">
                <div class="info-card-icon total-bids">
                    <i class="fas fa-gavel"></i>
                </div>
                <div class="info-card-content">
                    <div class="info-card-label">Total Bid Coins</div>
                    <div class="info-card-value" id="total-bids-value">{{ number_format($statistics['total_bids']) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Amount Card -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="info-card">
            <div class="d-flex align-items-center">
                <div class="info-card-icon total-amount">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="info-card-content">
                    <div class="info-card-label">Total Amount</div>
                    <div class="info-card-value" id="total-amount-value">AED {{ number_format($statistics['total_amount'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Bids Card -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="info-card">
            <div class="d-flex align-items-center">
                <div class="info-card-icon todays-bids">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="info-card-content">
                    <div class="info-card-label">Today's Bid Coins</div>
                    <div class="info-card-value">{{ number_format($statistics['todays_bids']) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Amount Card -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="info-card">
            <div class="d-flex align-items-center">
                <div class="info-card-icon todays-amount">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="info-card-content">
                    <div class="info-card-label">Today's Amount</div>
                    <div class="info-card-value">AED {{ number_format($statistics['todays_amount'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="admin-data-card">
    <!--<div class="admin-data-card-header">
        <div class="admin-data-card-title">Bid Package Purchase History</div>
        <div class="admin-data-card-actions">
            <span class="badge badge-primary">Total: {{ count($histories) }} Purchases</span>
        </div>
    </div> -->
    <div class="admin-data-card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Filters Row -->
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card filter-panel">
                    <div class="card-body">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label for="date_range" class="form-label"><strong>Date Range Filter:</strong></label>
                                <select id="date_range" class="form-control">
                                    <option value="all">All Time</option>
                                    <option value="today">Today</option>
                                    <option value="yesterday">Yesterday</option>
                                    <option value="last_7_days">Last 7 Days</option>
                                    <option value="last_30_days">Last 30 Days</option>
                                    <option value="this_month">This Month</option>
                                    <option value="last_month">Last Month</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="start_date" class="form-label"><strong>From Date:</strong></label>
                                <input type="date" id="start_date" class="form-control" disabled>
                            </div>
                            <div class="col-md-2">
                                <label for="end_date" class="form-label"><strong>To Date:</strong></label>
                                <input type="date" id="end_date" class="form-control" disabled>
                            </div>
                            <div class="col-md-3">
                                <label for="month_filter" class="form-label"><strong>Month Filter:</strong></label>
                                <select id="month_filter" class="form-control">
                                    <option value="all">All Months</option>
                                    @php
                                        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                        $currentMonth = date('n');
                                        
                                        // Show only current year months up to current month
                                        for ($month = $currentMonth; $month >= 1; $month--) {
                                            $value = sprintf('%02d', $month); // Just month number
                                            $label = $months[$month - 1]; // Just month name
                                            echo "<option value=\"{$value}\">{$label}</option>";
                                        }
                                    @endphp
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button id="filter_btn" class="btn btn-primary btn-block">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <button id="clear_filter_btn" class="btn btn-secondary btn-block mt-1">
                                    <i class="fas fa-times"></i> Clear
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table id="historiesTable" class="datatable" style="width:100%;">
                <thead>
                    <tr>
                        <th>Sl.No</th>
                        <th>User</th>
                        <th>Description</th>
                        <th>Bid Coins</th>
                        <th>Price (AED)</th>
                        <th>Transaction ID</th>
                        <th>Purchase Date</th>
                    </tr>
                </thead>
                <tbody>
                @if(!$histories->isEmpty())
                @foreach($histories as $index => $history)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div>
                                <strong>{{ $history->user->name }}</strong><br>
                                <small class="text-muted">{{ $history->user->email }}</small>
                            </div>
                        </td>
                        <td>{{ $history->description }}</td>
                        <td>
                            <span class="badge-status badge-success">
                                {{ number_format($history->bid_amount) }} bid coins
                            </span>
                        </td>
                        <td>AED {{ number_format($history->bid_price, 2) }}</td>
                        <td>
                            @if($history->stripe_transaction_id)
                                <small class="text-monospace">{{ Str::limit($history->stripe_transaction_id, 30) }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div>
                                <strong>{{ $history->created_at->format('M d, Y') }}</strong><br>
                                <small class="text-muted">{{ $history->created_at->format('h:i A') }}</small>
                            </div>
                        </td>
                    </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #999;">
                        No bid package purchase history found.
                    </td>
                </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    // Check if table has data rows (not just the empty message)
    var hasData = $('#historiesTable tbody tr').length > 0 && !$('#historiesTable tbody tr td[colspan]').length;
    var table = null;

    // Only initialize DataTable if there's data
    if (hasData) {
        table = $('#historiesTable').DataTable({
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "order": [[6, "desc"]], // Sort by Purchase Date descending (column index 6)
            "columnDefs": [
                { "orderable": false, "targets": [0] }, // Disable sorting on Sl.No column
                { "searchable": false, "targets": [0, 5] } // Disable search on Sl.No and Transaction ID columns
            ],
            "language": {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ purchases",
                "infoEmpty": "No purchase history available",
                "infoFiltered": "(filtered from _MAX_ total purchases)",
                "zeroRecords": "No matching purchases found",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Previous"
                }
            },
            "searching": true,
            "autoWidth": false
        });
    }

    // Date range filter functionality
    $('#date_range').on('change', function() {
        const value = $(this).val();
        if (value === 'custom') {
            $('#start_date, #end_date').prop('disabled', false);
        } else {
            $('#start_date, #end_date').prop('disabled', true);
            
            // Auto-set dates based on selection
            const today = new Date();
            let startDate = null;
            let endDate = today.toISOString().split('T')[0];
            
            switch(value) {
                case 'today':
                    startDate = endDate;
                    break;
                case 'yesterday':
                    const yesterday = new Date(today);
                    yesterday.setDate(yesterday.getDate() - 1);
                    startDate = endDate = yesterday.toISOString().split('T')[0];
                    break;
                case 'last_7_days':
                    const sevenDaysAgo = new Date(today);
                    sevenDaysAgo.setDate(sevenDaysAgo.getDate() - 7);
                    startDate = sevenDaysAgo.toISOString().split('T')[0];
                    break;
                case 'last_30_days':
                    const thirtyDaysAgo = new Date(today);
                    thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
                    startDate = thirtyDaysAgo.toISOString().split('T')[0];
                    break;
                case 'this_month':
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
                    break;
                case 'last_month':
                    const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    const lastMonthEnd = new Date(today.getFullYear(), today.getMonth(), 0);
                    startDate = lastMonth.toISOString().split('T')[0];
                    endDate = lastMonthEnd.toISOString().split('T')[0];
                    break;
            }
            
            if (startDate) {
                $('#start_date').val(startDate);
                $('#end_date').val(endDate);
            } else {
                $('#start_date, #end_date').val('');
            }
        }
        
        // Clear month filter when date range is selected
        if (value !== 'all') {
            $('#month_filter').val('all');
        }
    });

    // Month filter functionality
    $('#month_filter').on('change', function() {
        const value = $(this).val();
        if (value !== 'all') {
            // Clear date range filter when month is selected
            $('#date_range').val('all');
            $('#start_date, #end_date').val('').prop('disabled', true);
        }
    });

    // Filter button functionality
    $('#filter_btn').on('click', function() {
        if (!table) return;

        const dateRange = $('#date_range').val();
        const monthFilter = $('#month_filter').val();
        let startDate = $('#start_date').val();
        let endDate = $('#end_date').val();

        // Clear any existing filters
        $.fn.dataTable.ext.search = [];

        if (dateRange !== 'all' || monthFilter !== 'all') {
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const purchaseDateCell = data[6]; // Purchase date column
                if (!purchaseDateCell || purchaseDateCell === '-') return true;

                // Extract the first line which contains the date (format: "Dec 26, 2025")
                const dateText = purchaseDateCell.split('\n')[0].trim();
                
                // Try multiple date formats that might be in the cell
                let recordDate = null;
                
                // First try: "Dec 26, 2025" format
                let dateMatch = dateText.match(/(\w{3}) (\d{1,2}), (\d{4})/);
                if (dateMatch) {
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                                   'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    const month = months.indexOf(dateMatch[1]);
                    const day = parseInt(dateMatch[2]);
                    const year = parseInt(dateMatch[3]);
                    recordDate = new Date(year, month, day);
                }
                
                // Second try: Look for any date pattern in the HTML content
                if (!recordDate) {
                    const htmlMatch = purchaseDateCell.match(/(\w{3}) (\d{1,2}), (\d{4})/);
                    if (htmlMatch) {
                        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                                       'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                        const month = months.indexOf(htmlMatch[1]);
                        const day = parseInt(htmlMatch[2]);
                        const year = parseInt(htmlMatch[3]);
                        recordDate = new Date(year, month, day);
                    }
                }
                
                // If we still can't parse the date, skip this record
                if (!recordDate || isNaN(recordDate.getTime())) {
                    return true; // Include record if we can't parse date
                }

                // Month filter
                if (monthFilter !== 'all') {
                    const currentYear = new Date().getFullYear();
                    const filterMonth = parseInt(monthFilter) - 1; // monthFilter is now just "01", "02", etc.
                    
                    if (recordDate.getFullYear() !== currentYear || recordDate.getMonth() !== filterMonth) {
                        return false;
                    }
                }

                // Date range filter
                if (dateRange !== 'all' && startDate && endDate) {
                    const start = new Date(startDate);
                    const end = new Date(endDate);
                    
                    // Set all dates to start of day for accurate comparison
                    start.setHours(0, 0, 0, 0);
                    end.setHours(23, 59, 59, 999); // Include end date completely
                    recordDate.setHours(0, 0, 0, 0);
                    
                    
                    if (recordDate < start || recordDate > end) {
                        return false;
                    }
                }

                return true;
            });
        }

        table.draw();
        
        // Update statistics after filtering
        updateStatistics();
        
        // Show filter indicator
        updateFilterIndicator();
    });

    // Clear filter functionality
    $('#clear_filter_btn').on('click', function() {
        $('#date_range').val('all');
        $('#month_filter').val('all');
        $('#start_date, #end_date').val('').prop('disabled', true);
        
        // Clear DataTable filters
        $.fn.dataTable.ext.search = [];
        if (table) {
            table.draw();
        }
        
        // Update statistics after clearing filters
        updateStatistics();
        
        updateFilterIndicator();
    });

    // Update statistics based on filtered table rows
    function updateStatistics() {
        if (!table) return;
        
        let totalBids = 0;
        let totalAmount = 0;
        
        // Get all visible rows after filtering
        const visibleRows = table.rows({ filter: 'applied' }).nodes();
        
        $(visibleRows).each(function() {
            const cells = $(this).find('td');
            if (cells.length > 4) { // Ensure it's a data row
                // Extract bid amount from column 3 (index 3)
                const bidAmountText = cells.eq(3).text();
                const bidAmountMatch = bidAmountText.match(/[\d,]+/);
                const bidAmount = bidAmountMatch ? parseInt(bidAmountMatch[0].replace(/,/g, '')) : 0;
                
                // Extract price from column 4 (index 4)
                const priceText = cells.eq(4).text();
                const priceMatch = priceText.match(/[\d,.]+/);
                const price = priceMatch ? parseFloat(priceMatch[0].replace(/,/g, '')) : 0;
                
                totalBids += bidAmount;
                totalAmount += price;
            }
        });
        
        // Update the card values
        $('#total-bids-value').text(totalBids.toLocaleString());
        $('#total-amount-value').text('AED ' + totalAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    }

    // Update filter indicator
    function updateFilterIndicator() {
        const dateRange = $('#date_range').val();
        const monthFilter = $('#month_filter').val();
        const hasFilter = dateRange !== 'all' || monthFilter !== 'all';
        
        if (hasFilter) {
            $('#filter_btn').removeClass('btn-primary').addClass('btn-success');
            $('#filter_btn i').removeClass('fa-filter').addClass('fa-check');
        } else {
            $('#filter_btn').removeClass('btn-success').addClass('btn-primary');
            $('#filter_btn i').removeClass('fa-check').addClass('fa-filter');
        }
    }


    // Initialize filter indicator
    updateFilterIndicator();
});
</script>
@endsection