@extends('layouts.admin')

@section('title', 'Orders Management')
@section('page-title', 'Orders Management')
@section('page-subtitle', 'View and manage all orders')

@section('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endsection

@section('content')
<style>
    .dropdown-item {
        padding: 5px 20px !important;
    }

    /* DataTables Custom Styling */
    .dataTables_wrapper {
        padding: 0;
    }

    .dataTables_length {
        float: left;
    }

    .dataTables_filter {
        float: right;
    }

    .dataTables_info {
        float: left;
        margin-top: 10px;
    }

    .dataTables_paginate {
        float: right;
        margin-top: 10px;
    }

    table.dataTable thead th {
        border-bottom: 2px solid #e5e7eb;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }

    table.dataTable tbody td {
        padding: 12px 8px;
        vertical-align: middle;
    }

    .dt-buttons {
        float: left;
        margin-right: 20px;
    }

    .dt-button {
        border-radius: 6px;
        margin-right: 5px;
    }
        div.dataTables_wrapper div.dataTables_info {
            padding-top: 0px !important;
        }
        
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 4px 0px;
        margin: 0 1px;
        border-radius: 4px;
        font-size: 14px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--primary-color) !important;
        border: 1px solid var(--primary-color) !important;
        color: white !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f3f4f6 !important;
        border: 1px solid #d1d5db !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: var(--primary-color) !important;
        border: 1px solid var(--primary-color) !important;
        color: white !important;
    }

    /* Adjust DataTable controls layout */
    .dataTables_wrapper .d-flex {
        margin-bottom: 0px !important;
    }

    .dataTables_length {
        margin-right: 20px;
    }
</style>
<div class="admin-data-card">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">All Orders</div>
        <div class="admin-data-card-actions">
            <a href="{{ url('/admin/statistics') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-chart-bar"></i> View Sales Statistics
            </a>
        </div>
    </div>
    <div class="admin-data-card-body">
        <!-- Filter Form -->
        <form action="{{ url('/admin/orders') }}" method="GET" class="mb-4" id="filterForm">
            <div style="display: flex; gap: 15px; align-items: center; margin-bottom: 20px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <label style="font-weight: 600; color: #374151; margin: 0;">Status:</label>
                    <select name="status" id="statusFilter" class="form-control" style="width: 150px;">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    </select>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <label style="font-weight: 600; color: #374151; margin: 0;">Date Range:</label>
                    <input type="date" id="startDate" name="start_date" class="form-control" style="width: 150px;" value="{{ request('start_date') }}">
                    <span style="color: #6b7280;">to</span>
                    <input type="date" id="endDate" name="end_date" class="form-control" style="width: 150px;" value="{{ request('end_date') }}">
                </div>
                <div>
                    <button type="button" id="applyFilter" class="btn btn-primary">Apply Filter</button>
                    <button type="button" id="clearFilter" class="btn btn-secondary">Clear</button>
                </div>
            </div>
        </form>
        
        <table id="ordersTable" class="admin-table table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Auction</th>
                    <th>Order Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>
                            <a href="{{ url('/admin/users/' . ($order->user->id ?? '')) }}">
                                {{ $order->user->name ?? 'Unknown User' }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ url('/admin/auctions/' . ($order->auction->id ?? '')) }}">
                                {{ Str::limit($order->auction->title ?? 'Unknown Auction', 30) }}
                            </a>
                        </td>
                        <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                        <td>AED {{ number_format($order->total, 2) }}</td>
                        <td>
                            <span class="status-badge 
                                {{ $order->status == 'delivered' ? 'active' : 
                                   ($order->status == 'shipped' ? 'processing' : 
                                    ($order->status == 'processing' ? 'pending' : 'inactive')) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ url('/admin/orders/' . $order->id) }}" class="btn btn-sm btn-primary" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <!-- Quick Status Update Dropdown -->
                                <div class="dropdown" style="display: inline-block;">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $order->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        Update Status
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $order->id }}">
                                        <li>
                                            <form action="{{ url('/admin/orders/' . $order->id . '/status') }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="pending">
                                                <button type="submit" class="dropdown-item" {{ $order->status == 'pending' ? 'disabled' : '' }}>Pending</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ url('/admin/orders/' . $order->id . '/status') }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="processing">
                                                <button type="submit" class="dropdown-item" {{ $order->status == 'processing' ? 'disabled' : '' }}>Processing</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ url('/admin/orders/' . $order->id . '/status') }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="shipped">
                                                <button type="submit" class="dropdown-item" {{ $order->status == 'shipped' ? 'disabled' : '' }}>Shipped</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ url('/admin/orders/' . $order->id . '/status') }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="delivered">
                                                <button type="submit" class="dropdown-item" {{ $order->status == 'delivered' ? 'disabled' : '' }}>Delivered</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No orders found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Pagination will be handled by DataTables -->
</div>
@endsection

@section('scripts')
<!-- DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
    // Initialize DataTable
    $(document).ready(function() {
        var table = $('#ordersTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[3, 'desc']], // Sort by Order Date column (index 3) in descending order
            dom: '<"d-flex justify-content-between align-items-center"lf>rtip', // Custom layout without buttons
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ orders",
                infoEmpty: "No orders found",
                infoFiltered: "(filtered from _MAX_ total orders)",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "<",
                    next: ">"
                },
                emptyTable: "No orders found"
            },
            columnDefs: [
                {
                    targets: -1, // Last column (Actions)
                    orderable: false,
                    searchable: false
                },
                {
                    targets: 4, // Total column
                    render: function(data, type, row) {
                        if (type === 'display') {
                            return data;
                        }
                        // For sorting, extract numeric value
                        return parseFloat(data.replace(/[^0-9.-]+/g,""));
                    }
                },
                {
                    targets: 3, // Date column
                    type: 'date'
                }
            ],
            initComplete: function() {
                // Add custom styling to DataTables elements
                $('.dataTables_filter input').addClass('form-control form-control-sm');
                $('.dataTables_length select').addClass('form-select form-select-sm');
            }
        });

        // Handle apply filter button
        $('#applyFilter').on('click', function(e) {
            e.preventDefault();

            var statusValue = $('#statusFilter').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();

            // Apply status filter on column 5 (Status column)
            if (statusValue) {
                table.column(5).search(statusValue, false, false);
            } else {
                table.column(5).search('');
            }

            // Apply date range filter using custom search
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    // Get the date from column 3 (Order Date)
                    var orderDateStr = data[3];

                    // Parse the date from the format "MMM dd, YYYY HH:mm"
                    var dateParts = orderDateStr.split(' ');
                    var monthStr = dateParts[0];
                    var day = parseInt(dateParts[1].replace(',', ''));
                    var year = parseInt(dateParts[2]);

                    // Convert month name to number
                    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    var month = months.indexOf(monthStr);

                    // Create date object
                    var orderDate = new Date(year, month, day);

                    // Get filter dates
                    var minDate = startDate ? new Date(startDate) : null;
                    var maxDate = endDate ? new Date(endDate) : null;

                    // Apply filter
                    if ((minDate === null || orderDate >= minDate) &&
                        (maxDate === null || orderDate <= maxDate)) {
                        return true;
                    }
                    return false;
                }
            );

            // Redraw the table
            table.draw();
        });

        // Clear filter button
        $('#clearFilter').on('click', function() {
            $('#statusFilter').val('');
            $('#startDate').val('');
            $('#endDate').val('');

            // Clear all filters
            table.column(5).search('');

            // Clear custom search
            $.fn.dataTable.ext.search = [];

            table.draw();
        });
    });

    // Bootstrap dropdown functionality
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownToggleList = document.querySelectorAll('.dropdown-toggle');
        dropdownToggleList.forEach(function(dropdownToggle) {
            dropdownToggle.addEventListener('click', function() {
                const dropdownMenu = this.nextElementSibling;
                if (dropdownMenu.classList.contains('show')) {
                    dropdownMenu.classList.remove('show');
                } else {
                    // Close any open dropdown menus
                    document.querySelectorAll('.dropdown-menu.show').forEach(function(openMenu) {
                        openMenu.classList.remove('show');
                    });
                    dropdownMenu.classList.add('show');
                }
            });
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.matches('.dropdown-toggle') && !event.target.closest('.dropdown-menu')) {
                document.querySelectorAll('.dropdown-menu.show').forEach(function(openMenu) {
                    openMenu.classList.remove('show');
                });
            }
        });
    });
</script>
@endsection