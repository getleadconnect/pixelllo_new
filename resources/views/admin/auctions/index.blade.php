@extends('layouts.admin')

@section('title', 'Auctions Management')
@section('page-title', 'Auctions Management')
@section('page-subtitle', 'View and manage all auctions')

@section('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<style>
    .admin-table th,
    .admin-table td {
        font-size: 14px !important;
    }

    /* Additional DataTable-like styling */
    .admin-table {
        width: 100%;
        border-collapse: collapse;
    }

    .admin-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        text-align: left;
        padding: 12px 8px;
        border-bottom: 2px solid #dee2e6;
    }

    .admin-table td {
        padding: 10px 8px;
        border-bottom: 1px solid #dee2e6;
        vertical-align: middle;
    }

    .admin-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .admin-table .btn-sm {
        padding: 4px 8px;
        font-size: 12px;
        min-width: 28px;
    }

    /* Action column styling */
    .admin-table th:last-child,
    .admin-table td:last-child {
        width: 190px;
        max-width: 190px;
        text-align: center;
    }

    .action-buttons {
        display: flex;
        gap: 3px;
        justify-content: center;
        align-items: center;
    }

    .action-btn {
        border: none;
        color: white;
        padding: 4px 6px;
        border-radius: 3px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 24px;
        height: 24px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 11px;
    }

    .action-btn.btn-primary {
        background-color: #007bff;
    }
    .action-btn.btn-primary:hover {
        background-color: #0056b3;
    }

    .action-btn.btn-success {
        background-color: #28a745;
    }
    .action-btn.btn-success:hover {
        background-color: #1e7e34;
    }

    .action-btn.btn-danger {
        background-color: #dc3545;
    }
    .action-btn.btn-danger:hover {
        background-color: #c82333;
    }

    .action-btn.btn-warning {
        background-color: #ffc107;
        color: #212529;
    }
    .action-btn.btn-warning:hover {
        background-color: #e0a800;
    }

    /* Dropdown styling */
    .dropdown-container {
        position: relative;
        display: inline-block;
    }

    .dropdown-trigger {
        background: #6c757d;
        border: none;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .dropdown-trigger:hover {
        background: #5a6268;
    }

   
    .dropdown-menu.show {
        display: block;
        right:200px;
    }

    .dropdown-actions {
        display: flex;
        flex-direction: row;
        gap: 5px;
        align-items: center;
        justify-content: center;
    }

    .dropdown-action {
        border: none;
        color: white;
        padding: 6px 8px;
        border-radius: 3px;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 28px;
        height: 28px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 12px;
    }


    /* Primary (View) - Blue */
    .dropdown-action.btn-primary {
        background-color: #007bff;
    }
    .dropdown-action.btn-primary:hover {
        background-color: #0056b3;
        color: white;
        text-decoration: none;
    }

    /* Success (Edit, Activate) - Green */
    .dropdown-action.btn-success {
        background-color: #28a745;
    }
    .dropdown-action.btn-success:hover {
        background-color: #1e7e34;
        color: white;
        text-decoration: none;
    }

    /* Danger (Delete, Cancel) - Red */
    .dropdown-action.btn-danger {
        background-color: #dc3545;
    }
    .dropdown-action.btn-danger:hover {
        background-color: #c82333;
        color: white;
        text-decoration: none;
    }

    /* Warning (End) - Orange */
    .dropdown-action.btn-warning {
        background-color: #ffc107;
        color: #212529;
    }
    .dropdown-action.btn-warning:hover {
        background-color: #e0a800;
        color: #212529;
        text-decoration: none;
    }

    .dropdown-action i {
        font-size: 12px;
    }

    /* Custom status badge for closed auctions */
    .status-badge.closed {
        background-color: #ffcccb !important;
        color: #d32f2f !important;
        border: 1px solid #ffbaba !important;
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

    .dataTables_filter input {
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 6px 12px;
        margin-left: 10px;
    }

    .dataTables_info {
        float: left;
        margin-top: 10px;
        color: #6b7280;
    }

    .dataTables_paginate {
        float: right;
        margin-top: 10px;
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

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
        background: #f3f4f6 !important;
        border: 1px solid #d1d5db !important;
    }

    .dataTables_wrapper .d-flex {
        margin-bottom: 0px !important;
    }

    .dataTables_length {
        margin-right: 20px;
    }
</style>
@endsection

@section('content')
<div class="admin-data-card" style="padding-bottom:20px;">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">All Auctions</div>
        <div class="admin-data-card-actions">
            <a href="{{ url('/admin/auctions/create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Create New Auction
            </a>
        </div>
    </div>
    <div class="admin-data-card-body">
        <!-- Filter Form -->
        <form action="{{ url('/admin/auctions') }}" method="GET" class="mb-4" id="filterForm">
            <div style="display: flex; gap: 15px; align-items: center; margin-bottom: 20px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <label style="font-weight: 600; color: #374151; margin: 0;">Status:</label>
                    <select name="status" id="statusFilter" class="form-control" style="width: 150px;">
                        <option value="">All Status</option>
                        <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="ended" {{ request('status') == 'ended' ? 'selected' : '' }}>Closed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <label style="font-weight: 600; color: #374151; margin: 0;">Visibility:</label>
                    <select name="featured" id="featuredFilter" class="form-control" style="width: 150px;">
                        <option value="">All Visibility</option>
                        <option value="true" {{ request('featured') == 'true' ? 'selected' : '' }}>Featured</option>
                        <option value="false" {{ request('featured') == 'false' ? 'selected' : '' }}>Regular</option>
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

        
        <table id="auctionsTable" class="admin-table table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th style="width:90px;">Image</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Current Price</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Featured</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($auctions as $auction)
                    <tr>
                        <td>{{ $auction->id }}</td>
                        <td>
                            @if (!empty($auction->images) && is_array($auction->images) && isset($auction->images[0]))
                                <img src="{{ asset('storage/' . $auction->images[0]) }}" alt="{{ $auction->title }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                            @else
                                <div style="width: 50px; height: 50px; background-color: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-image" style="color: #ccc;"></i>
                                </div>
                            @endif
                        </td>
                        <td>{{ Str::limit($auction->title, 30) }}</td>
                        <td>{{ $auction->category->name ?? 'Uncategorized' }}</td>
                        <td>
                            <span class="status-badge
                                {{ $auction->status == 'active' ? 'active' :
                                  ($auction->status == 'upcoming' ? 'pending' :
                                   ($auction->status == 'ended' ? 'closed' : 'inactive')) }}">
                                {{ $auction->status == 'ended' ? 'Closed' : ucfirst($auction->status) }}
                            </span>
                        </td>
                        <td>AED {{ number_format($auction->currentPrice, 2) }}</td>
                        <td>{{ $auction->startTime->format('M d, Y H:i') }}</td>
                        <td>{{ $auction->endTime->format('M d, Y H:i') }}</td>
                        <td>
                            <span class="status-badge {{ $auction->featured ? 'active' : 'inactive' }}">
                                {{ $auction->featured ? 'Featured' : 'Regular' }}
                            </span>
                        </td>
                        <td>

                    <div style="display: flex; gap: 5px;">
                                <a href="{{ url('/admin/auctions/' . $auction->id) }}" class="btn btn-sm btn-primary" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ url('/admin/auctions/' . $auction->id . '/edit') }}" class="btn btn-sm btn-success" title="Edit Auction">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if ($auction->status == 'upcoming' || $auction->status == 'cancelled')
                                    <form action="{{ url('/admin/auctions/' . $auction->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this auction?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete Auction">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                @if ($auction->status == 'upcoming')
                                    <form action="{{ url('/admin/auctions/' . $auction->id . '/status') }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="active">
                                        <button type="submit" class="btn btn-sm btn-success" title="Activate Auction">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                @if ($auction->status == 'active')
                                    <form action="{{ url('/admin/auctions/' . $auction->id . '/status') }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="ended">
                                        <button type="submit" class="btn btn-sm btn-warning" title="End Auction">
                                            <i class="fas fa-stop"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                @if ($auction->status != 'cancelled' && $auction->status != 'ended')
                                    <form action="{{ url('/admin/auctions/' . $auction->id . '/status') }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="btn btn-sm btn-danger" title="Cancel Auction">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">No auctions found</td>
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
$(document).ready(function() {
    var table = $('#auctionsTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        order: [[6, 'desc']], // Sort by Start Time column (index 6) in descending order
        dom: '<"d-flex justify-content-between align-items-center"lf>rtip', // Custom layout
        columnDefs: [
            {
                targets: -1, // Last column (Actions)
                orderable: false,
                searchable: false
            },
            {
                targets: 1, // Image column
                orderable: false,
                searchable: false
            },
            {
                targets: 0, // ID column
                orderable: true
            },
            {
                targets: 2, // Title column
                orderable: true
            },
            {
                targets: 3, // Category column
                orderable: true
            },
            {
                targets: 4, // Status column
                orderable: true
            },
            {
                targets: 5, // Current Price column
                type: 'num-fmt',
                orderable: true
            },
            {
                targets: [6, 7], // Date columns
                type: 'date',
                orderable: true
            },
            {
                targets: 8, // Featured column
                orderable: true
            }
        ],
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ auctions",
            infoEmpty: "No auctions found",
            infoFiltered: "(filtered from _MAX_ total auctions)",
            paginate: {
                first: "First",
                last: "Last",
                next: ">",
                previous: "<"
            },
            emptyTable: "No auctions found"
        },
        initComplete: function() {
            // Add custom styling to DataTables elements
            $('.dataTables_filter input').addClass('form-control form-control-sm');
            $('.dataTables_length select').addClass('form-select form-select-sm');
            $('.dataTables_length').css('margin-right', '20px');
        }
    });

    // Handle apply filter button
    $('#applyFilter').on('click', function(e) {
        e.preventDefault();

        var statusValue = $('#statusFilter').val();
        var featuredValue = $('#featuredFilter').val();
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();

        // Clear previous custom filters
        $.fn.dataTable.ext.search = [];

        // Apply combined filter
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                // Status filter (column 4)
                var statusMatch = true;
                if (statusValue) {
                    var tableStatus = data[4].toLowerCase();
                    if (statusValue === 'ended') {
                        statusMatch = tableStatus.includes('closed');
                    } else {
                        statusMatch = tableStatus.includes(statusValue);
                    }
                }

                // Featured filter (column 8)
                var featuredMatch = true;
                if (featuredValue) {
                    var tableFeatured = data[8].toLowerCase();
                    if (featuredValue === 'true') {
                        featuredMatch = tableFeatured.includes('featured');
                    } else if (featuredValue === 'false') {
                        featuredMatch = tableFeatured.includes('regular');
                    }
                }

                // Date range filter (column 6 - Start Time)
                var dateMatch = true;
                if (startDate || endDate) {
                    var startTimeStr = data[6];

                    // Parse the date from the format "MMM dd, YYYY HH:mm"
                    var dateParts = startTimeStr.split(' ');
                    var monthStr = dateParts[0];
                    var day = parseInt(dateParts[1].replace(',', ''));
                    var year = parseInt(dateParts[2]);

                    // Convert month name to number
                    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    var month = months.indexOf(monthStr);

                    // Create date object
                    var auctionDate = new Date(year, month, day);

                    // Get filter dates
                    var minDate = startDate ? new Date(startDate) : null;
                    var maxDate = endDate ? new Date(endDate) : null;

                    // Apply date filter
                    if (minDate && auctionDate < minDate) {
                        dateMatch = false;
                    }
                    if (maxDate && auctionDate > maxDate) {
                        dateMatch = false;
                    }
                }

                // Return true only if all filters match
                return statusMatch && featuredMatch && dateMatch;
            }
        );

        // Redraw the table
        table.draw();
    });

    // Clear filter button
    $('#clearFilter').on('click', function() {
        $('#statusFilter').val('');
        $('#featuredFilter').val('');
        $('#startDate').val('');
        $('#endDate').val('');

        // Clear custom search filters
        $.fn.dataTable.ext.search = [];

        // Redraw the table
        table.draw();
    });
});
</script>
@endsection