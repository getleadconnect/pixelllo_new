@extends('layouts.app')

@section('title', isset($title) ? $title . ' - Dashboard' : 'Dashboard - ' . config('app.name'))

@section('content')
<div class="dashboard-page">
    <div class="container">
        <div class="dashboard-header">
            <h1>@yield('dashboard-title', 'My Dashboard')</h1>
            <div class="dashboard-actions">
                <span class="user-balance">
                    <i class="fas fa-coins"></i> Your Balance: <strong>{{ number_format($user->bid_balance ?? 0) }} bids</strong>
                </span>
                <a href="{{ route('dashboard.buy-bids') }}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Buy Bids</a>
            </div>
        </div>

        <div class="dashboard-grid">
            <!-- Sidebar -->
            <div class="dashboard-sidebar">
                <div class="user-profile-card">
                    <div class="user-profile-top">
                        <div class="user-avatar" id="avatar-upload-trigger" style="cursor: pointer;" title="Click to change avatar">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" onerror="this.onerror=null; this.src='{{ asset('images/placeholders/avatar-placeholder.svg') }}';">
                            @else
                                <div class="avatar-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                            <div class="avatar-overlay">
                                <i class="fas fa-camera"></i>
                            </div>
                        </div>
                        <form id="avatar-upload-form" action="{{ route('dashboard.update-avatar') }}" method="POST" enctype="multipart/form-data" style="display: none;">
                            @csrf
                            <input type="file" id="avatar-input" name="avatar" accept="image/*">
                        </form>
                        <div class="user-info">
                            <h3>{{ $user->name ?? 'John Doe' }}</h3>
                            <p class="user-email">{{ $user->email ?? '' }}</p>
                        </div>
                    </div>
                    <div class="user-meta">
                        <p class="user-details">
                            Member since {{ $user->created_at ? $user->created_at->format('M Y') : 'Sep 2025' }},</p>
                        <p class="user-details"><i class="fas fa-map-marker-alt"></i> {{ $user->city ?? 'New York' }}, {{ $user->country ?? 'US' }}</p>
                        
                    </div>
                </div>

                <nav class="dashboard-nav">
                    <a href="{{ url('/dashboard') }}" class="dashboard-nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i> Activity
                    </a>
                    <a href="{{ url('/dashboard/auctions') }}" class="dashboard-nav-item {{ request()->is('dashboard/auctions') ? 'active' : '' }}">
                        <i class="fas fa-gavel"></i> My Auctions
                    </a>
                    <a href="{{ url('/dashboard/watchlist') }}" class="dashboard-nav-item {{ request()->is('dashboard/watchlist') ? 'active' : '' }}">
                        <i class="fas fa-heart"></i> Watchlist
                    </a>
                    <a href="{{ url('/dashboard/wins') }}" class="dashboard-nav-item {{ request()->is('dashboard/wins') ? 'active' : '' }}">
                        <i class="fas fa-trophy"></i> My Wins
                    </a>
                    <a href="{{ url('/dashboard/history') }}" class="dashboard-nav-item {{ request()->is('dashboard/history') ? 'active' : '' }}">
                        <i class="fas fa-history"></i> Bid History
                    </a>
                    <a href="{{ url('/dashboard/orders') }}" class="dashboard-nav-item {{ request()->is('dashboard/orders') ? 'active' : '' }}">
                        <i class="fas fa-shopping-cart"></i> Orders
                    </a>
                    <a href="{{ url('/dashboard/settings') }}" class="dashboard-nav-item {{ request()->is('dashboard/settings') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </nav>

                <div class="quick-stats">
                    <div class="stat-item">
                        <span class="stat-label">Active Bids</span>
                        <span class="stat-value">{{ $activeBidsCount ?? 0 }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Watchlist</span>
                        <span class="stat-value">{{ $watchlistCount ?? 0 }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Wins</span>
                        <span class="stat-value">{{ $winsCount ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="dashboard-content">
                @yield('dashboard-content')
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .user-profile-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .user-profile-top {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e0e0e0;
    }

    .user-avatar {
        width: 60px;
        height: 60px;
        min-width: 60px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid #f0f0f0;
        background: #f8f9fa;
        position: relative;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .user-avatar:hover {
        transform: scale(1.05);
        border-color: #ff5500;
    }

    .avatar-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
        border-radius: 50%;
    }

    .user-avatar:hover .avatar-overlay {
        opacity: 1;
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        border-radius: 50%;
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .avatar-placeholder i {
        font-size: 24px;
    }

    .user-info {
        flex: 1;
        min-width: 0;
    }

    .user-info h3 {
        margin: 0 0 5px 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-info .user-email {
        color: #666;
        font-size: 0.85rem;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-meta {
        padding-top: 0;
    }

    .user-meta .user-details {
        margin: 0;
        padding: 0;
        font-size: 0.85rem;
        color: #666;
        line-height: 1.4;
        text-align: left;
    }

    .user-meta .user-details i {
        color: #ff5500;
        margin: 0 3px;
        font-size: 0.8rem;
    }

    .dashboard-sidebar {
        background: #f8f9fa;
        border-radius: 10px;
    }

    .dashboard-nav {
        margin-top: 20px;
    }

    .dashboard-nav-item {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        color: #333;
        text-decoration: none;
        border-radius: 6px;
        margin-bottom: 5px;
        transition: all 0.3s;
    }

    .dashboard-nav-item:hover {
        background: white;
        color: #ff5500;
    }

    .dashboard-nav-item.active {
        background: white;
        color: #ff5500;
        font-weight: 600;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .dashboard-nav-item i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }

    .quick-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #dee2e6;
    }

    .quick-stats .stat-item {
        text-align: center;
    }

    .quick-stats .stat-label {
        display: block;
        font-size: 0.8rem;
        color: #666;
        margin-bottom: 5px;
    }

    .quick-stats .stat-value {
        display: block;
        font-size: 1.5rem;
        font-weight: 700;
        color: #ff5500;
    }

    .user-balance {
        background: #fff;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 0.95rem;
        color: #333;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .user-balance i {
        color: #ffdd00;
        margin-right: 5px;
    }

    .user-balance strong {
        color: #ff5500;
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .dashboard-actions {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 30px;
    }

    @media (max-width: 992px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-sidebar {
            order: 2;
        }

        .dashboard-content {
            order: 1;
        }
    }

    @media (max-width: 768px) {
        .dashboard-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .dashboard-actions {
            width: 100%;
            flex-direction: column;
        }

        .user-balance {
            width: 100%;
            text-align: center;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarTrigger = document.getElementById('avatar-upload-trigger');
    const avatarInput = document.getElementById('avatar-input');
    const avatarForm = document.getElementById('avatar-upload-form');

    if (avatarTrigger && avatarInput && avatarForm) {
        avatarTrigger.addEventListener('click', function() {
            avatarInput.click();
        });

        avatarInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const file = e.target.files[0];

                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file');
                    return;
                }

                // Validate file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Image size should be less than 5MB');
                    return;
                }

                // Create FormData and submit
                const formData = new FormData(avatarForm);

                fetch(avatarForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update avatar image
                        const avatarImg = avatarTrigger.querySelector('img');
                        if (avatarImg) {
                            avatarImg.src = data.avatar_url;
                        } else {
                            // Replace placeholder with image
                            avatarTrigger.innerHTML = `
                                <img src="${data.avatar_url}" alt="${data.user_name}" style="width: 100%; height: 100%; object-fit: cover;">
                                <div class="avatar-overlay">
                                    <i class="fas fa-camera"></i>
                                </div>
                            `;
                        }

                        // Show success message
                        if (data.message) {
                            const alertDiv = document.createElement('div');
                            alertDiv.className = 'alert alert-success alert-dismissible fade show';
                            alertDiv.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                            alertDiv.innerHTML = `
                                ${data.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            `;
                            document.body.appendChild(alertDiv);

                            setTimeout(() => {
                                alertDiv.remove();
                            }, 3000);
                        }
                    } else {
                        alert(data.message || 'Failed to update avatar');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating your avatar');
                });
            }
        });
    }
});
</script>
@endsection