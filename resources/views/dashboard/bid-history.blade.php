@extends('layouts.dashboard')

@section('dashboard-title', 'Bid History')

@section('dashboard-content')
<div class="dashboard-panel active">
    <div class="panel-header">
        <h2>Bid History</h2>
        <p>Your bidding activity over time</p>
    </div>

    <div class="bid-history-filters">
        <form method="GET" action="{{ route('dashboard.history') }}" id="filterForm">
            <div class="filter-item">
                <label for="dateRange">Date Range:</label>
                <select id="dateRange" name="dateRange" class="form-select">
                    <option value="7" {{ $dateRange == 7 ? 'selected' : '' }}>Last 7 days</option>
                    <option value="30" {{ $dateRange == 30 ? 'selected' : '' }}>Last 30 days</option>
                    <option value="90" {{ $dateRange == 90 ? 'selected' : '' }}>Last 90 days</option>
                    <option value="365" {{ $dateRange == 365 ? 'selected' : '' }}>Last 12 months</option>
                </select>
            </div>
            <div class="filter-item">
                <label for="bidStatus">Status:</label>
                <select id="bidStatus" name="bidStatus" class="form-select">
                    <option value="all" {{ $bidStatus == 'all' ? 'selected' : '' }}>All Bids</option>
                    <option value="won" {{ $bidStatus == 'won' ? 'selected' : '' }}>Winning Bids</option>
                    <option value="active" {{ $bidStatus == 'active' ? 'selected' : '' }}>Active Bids</option>
                    <option value="lost" {{ $bidStatus == 'lost' ? 'selected' : '' }}>Lost Bids</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Apply Filters</button>
        </form>
    </div>

    <div class="bid-history-table-container">
        @if($bidHistory->count() > 0)
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
            <tbody style="font-size:14px;">
                @foreach($bidHistory as $bid)
                @php
                    $status = isset($bidStatuses[$bid->id]) ? $bidStatuses[$bid->id] : 'unknown';
                    $statusClass = '';
                    $statusText = '';

                    switch($status) {
                        case 'won':
                            $statusClass = 'won';
                            $statusText = 'Won';
                            break;
                        case 'active':
                            $statusClass = 'active';
                            $statusText = 'Active';
                            break;
                        case 'outbid':
                            $statusClass = 'outbid';
                            $statusText = 'Outbid';
                            break;
                        case 'lost':
                            $statusClass = 'lost';
                            $statusText = 'Lost';
                            break;
                        default:
                            $statusClass = 'unknown';
                            $statusText = 'Unknown';
                    }
                @endphp
                <tr>
                    <td>{{ $bid->created_at->format('M d, Y h:i A') }}</td>
                    <td>
                        @if($bid->auction)
                            <a href="{{ route('auction.detail', $bid->auction->id) }}">
                                {{ Str::limit($bid->auction->title, 40) }}
                            </a>
                        @else
                            <span>Auction Not Found</span>
                        @endif
                    </td>
                    <td>${{ number_format($bid->amount, 2) }}</td>
                    <td>1 bid</td>
                    <td style="color: {{ $status == 'won' ? '#10b981' : ($status == 'active' ? '#3b82f6' : ($status == 'outbid' ? '#f59e0b' : ($status == 'lost' ? '#ef4444' : '#6b7280'))) }}; font-weight: 500;">{{ $statusText }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-history"></i>
            </div>
            <h3>No bid history found</h3>
            <p>
                @if($bidStatus !== 'all')
                    No {{ $bidStatus }} bids found in the last {{ $dateRange }} days.
                @else
                    You haven't placed any bids in the last {{ $dateRange }} days.
                @endif
            </p>
            <a href="{{ url('/auctions') }}" class="btn btn-primary">Browse Auctions</a>
        </div>
        @endif
    </div>

    @if($bidHistory->count() > 0)
    <div class="pagination">
        @if($bidHistory->previousPageUrl())
            <a href="{{ $bidHistory->previousPageUrl() }}&dateRange={{ $dateRange }}&bidStatus={{ $bidStatus }}" class="page-prev">
                <i class="fas fa-chevron-left"></i>
            </a>
        @else
            <button class="page-prev" disabled><i class="fas fa-chevron-left"></i></button>
        @endif

        <span class="page-info">Page {{ $bidHistory->currentPage() }} of {{ $bidHistory->lastPage() }}</span>

        @if($bidHistory->nextPageUrl())
            <a href="{{ $bidHistory->nextPageUrl() }}&dateRange={{ $dateRange }}&bidStatus={{ $bidStatus }}" class="page-next">
                <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <button class="page-next" disabled><i class="fas fa-chevron-right"></i></button>
        @endif
    </div>
    @endif
</div>

<style>
/* Additional styles for the bid history page */
.bid-history-filters form {
    display: flex;
    align-items: flex-end;
    gap: 1rem;
    flex-wrap: wrap;
}

.filter-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-item label {
    font-weight: 500;
    color: #6b7280;
    font-size: 0.875rem;
}

.form-select {
    padding: 0.5rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    background-color: white;
    min-width: 150px;
}

.bid-history-table {
    width: 100%;
    border-collapse: collapse;
}

.bid-history-table th {
    text-align: left;
    padding: 0.75rem;
    border-bottom: 2px solid #e5e7eb;
    font-weight: 600;
    color: #374151;
}

.bid-history-table td {
    padding: 0.75rem;
    border-bottom: 1px solid #e5e7eb;
}

.bid-history-table tbody tr:hover {
    background-color: #f9fafb;
}

.bid-history-table a {
    color: #3b82f6;
    text-decoration: none;
}

.bid-history-table a:hover {
    text-decoration: underline;
}

.pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin-top: 2rem;
}

.page-prev, .page-next {
    padding: 0.5rem 1rem;
    background-color: #3b82f6;
    color: white;
    border: none;
    border-radius: 0.375rem;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.page-prev:disabled, .page-next:disabled {
    background-color: #d1d5db;
    cursor: not-allowed;
}

.page-prev:not(:disabled):hover, .page-next:not(:disabled):hover {
    background-color: #2563eb;
}

a.page-prev, a.page-next {
    color: white;
}

.page-info {
    font-weight: 500;
    color: #6b7280;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-state-icon {
    font-size: 3rem;
    color: #9ca3af;
    margin-bottom: 1rem;
}

.empty-state h3 {
    margin-bottom: 0.5rem;
    color: #374151;
}

.empty-state p {
    color: #6b7280;
    margin-bottom: 1.5rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when filter values change
    const dateRange = document.getElementById('dateRange');
    const bidStatus = document.getElementById('bidStatus');
    const filterForm = document.getElementById('filterForm');

    if (dateRange && bidStatus && filterForm) {
        const autoSubmit = () => {
            filterForm.submit();
        };

        // Optional: Enable auto-submit on change
        // dateRange.addEventListener('change', autoSubmit);
        // bidStatus.addEventListener('change', autoSubmit);
    }
});
</script>
@endsection