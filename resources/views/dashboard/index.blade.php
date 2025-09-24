@extends('layouts.dashboard')

@section('title', 'Activity Overview - Dashboard')
@section('dashboard-title', 'Activity Overview')

@section('dashboard-content')
<div class="dashboard-panel active">
    <div class="panel-header">
        <h2>Activity Overview</h2>
        <p>Your bidding activity and recent updates</p>
    </div>

    <div class="dashboard-stats">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-gavel"></i></div>
            <div class="stat-info">
                <h3>{{ $totalBids }}</h3>
                <p>Total Bids Placed</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-trophy"></i></div>
            <div class="stat-info">
                <h3>{{ $wonAuctions }}</h3>
                <p>Auctions Won</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
            <div class="stat-info">
                <h3>${{ number_format($savings, 2) }}</h3>
                <p>Total Savings</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-info">
                <h3>{{ $activeAuctions }}</h3>
                <p>Active Auctions</p>
            </div>
        </div>
    </div>

    <div class="activity-timeline">
        <h3>Recent Activity</h3>
        <div class="timeline">
            @if($recentBids->isNotEmpty())
                @foreach($recentBids as $bid)
                <div class="timeline-item">
                    <div class="timeline-icon bid-icon">
                        <i class="fas fa-gavel"></i>
                    </div>
                    <div class="timeline-content">
                        <h4>Bid Placed</h4>
                        <p>You placed a bid on <a href="{{ url('/auctions/' . $bid->auction->id) }}">{{ $bid->auction->title }}</a></p>
                        <span class="timeline-time">{{ $bid->created_at ? $bid->created_at->diffForHumans() : 'Recently' }}</span>
                    </div>
                </div>
                @endforeach
            @endif

            @if($recentWins->isNotEmpty())
                @foreach($recentWins as $win)
                <div class="timeline-item">
                    <div class="timeline-icon win-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="timeline-content">
                        <h4>Auction Won</h4>
                        <p>You won the auction for <a href="{{ url('/auctions/' . $win->id) }}">{{ $win->title }}</a></p>
                        <span class="timeline-time">{{ $win->endTime->format('M d, Y') }}</span>
                    </div>
                </div>
                @endforeach
            @endif

            @if($recentWatchlist->isNotEmpty())
                @foreach($recentWatchlist as $watchItem)
                <div class="timeline-item">
                    <div class="timeline-icon watch-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="timeline-content">
                        <h4>Added to Watchlist</h4>
                        <p>You added <a href="{{ url('/auctions/' . $watchItem->id) }}">{{ $watchItem->title }}</a> to your watchlist</p>
                        <span class="timeline-time">{{ ($watchItem->pivot && $watchItem->pivot->created_at) ? $watchItem->pivot->created_at->diffForHumans() : 'Recently' }}</span>
                    </div>
                </div>
                @endforeach
            @endif

            @if($recentBids->isEmpty() && $recentWins->isEmpty() && $recentWatchlist->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <h3>No recent activity</h3>
                    <p>Start participating in auctions to see your activity here.</p>
                    <a href="{{ url('/auctions') }}" class="btn btn-primary">Browse Auctions</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection