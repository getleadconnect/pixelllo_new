@extends('layouts.admin')

@section('title', 'Auction Details')
@section('page-title', 'Auction Details')
@section('page-subtitle', 'View detailed information about this auction')

@section('content')
<div class="admin-data-card">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">{{ $auction->title }}</div>
        <div class="admin-data-card-actions">
            <a href="{{ url('/admin/auctions') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-arrow-left"></i> Back to Auctions
            </a>
            @if (($auction->status!=='ended'))
            <a href="{{ url('/admin/auctions/' . $auction->id . '/edit') }}" class="btn btn-sm btn-success">
                <i class="fas fa-edit"></i> Edit Auction
            </a>
            @endif
        </div>
    </div>
    <div class="admin-data-card-body">
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
            <!-- Auction Images and Status Actions -->
            <div>
                <div style="margin-bottom: 20px;">
                    <!-- Image Gallery -->
                    <div style="position: relative; margin-bottom: 15px;">
                        @if (!empty($auction->images) && is_array($auction->images) && isset($auction->images[0]))
                            <img src="{{ asset('storage/' . $auction->images[0]) }}" alt="{{ $auction->title }}" style="width: 100%; height: 300px; object-fit: cover; border-radius: 8px;">
                        @else
                            <div style="width: 100%; height: 300px; background-color: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-image" style="font-size: 3rem; color: #ccc;"></i>
                            </div>
                        @endif
                    </div>
                    
                    @if (!empty($auction->images) && is_array($auction->images) && count($auction->images) > 1)
                        <div style="display: flex; gap: 10px; overflow-x: auto; padding-bottom: 10px;">
                            @foreach ($auction->images as $index => $image)
                                <img src="{{ asset('storage/' . $image) }}" alt="{{ $auction->title }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; cursor: pointer;">
                            @endforeach
                        </div>
                    @endif
                </div>
                
                <!-- Image upload form -->
                <div style="margin-bottom: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                    <h4 style="margin-bottom: 15px;">Add Image</h4>
                    <form action="{{ url('/admin/auctions/' . $auction->id . '/images') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="image">Upload Image</label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
                
                <!-- Status Actions -->
                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                    <h4 style="margin-bottom: 15px;">Auction Actions</h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        @if ($auction->status == 'upcoming')
                            <form action="{{ url('/admin/auctions/' . $auction->id . '/status') }}" method="POST" id="activate-auction-form">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="active">
                                <button type="button" class="btn btn-success" style="width: 100%;" onclick="validateAndActivateAuction(event)">
                                    <i class="fas fa-play mr-2"></i> Activate Auction
                                </button>
                            </form>
                        @endif
                        
                        @if ($auction->status == 'active')
                            <form action="{{ url('/admin/auctions/' . $auction->id . '/status') }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="ended">
                                <button type="submit" class="btn btn-warning" style="width: 100%;">
                                    <i class="fas fa-stop mr-2"></i> End Auction
                                </button>
                            </form>
                        @endif
                        
                        @if ($auction->status != 'cancelled' && $auction->status != 'ended')
                            <form action="{{ url('/admin/auctions/' . $auction->id . '/status') }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" class="btn btn-danger" style="width: 100%;">
                                    <i class="fas fa-ban mr-2"></i> Cancel Auction
                                </button>
                            </form>
                        @endif
                        
                        <!-- Toggle Featured Status -->
                        <form action="{{ url('/admin/auctions/' . $auction->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="featured" value="{{ $auction->featured ? '0' : '1' }}">
                            <button type="submit" class="btn {{ $auction->featured ? 'btn-secondary' : 'btn-primary' }}" style="width: 100%;">
                                <i class="fas {{ $auction->featured ? 'fa-star' : 'fa-star' }} mr-2"></i> 
                                {{ $auction->featured ? 'Remove Featured' : 'Make Featured' }}
                            </button>
                        </form>
                        
                        @if ($auction->status == 'upcoming' || $auction->status == 'cancelled')
                            <form action="{{ url('/admin/auctions/' . $auction->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this auction? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="width: 100%;">
                                    <i class="fas fa-trash-alt mr-2"></i> Delete Auction
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Auction Details -->
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <div>

                        <span class="status-badge 
                            {{ $auction->status == 'active' ? 'active' : 
                              ($auction->status == 'upcoming' ? 'pending' : 
                               ($auction->status == 'ended' ? 'processing' : 'inactive')) }}" style="font-size:1.25rem;
                               @if ($auction->status == 'ended') color:red; @endif
                               ">

                                {{ ucfirst($auction->status) }}

                        </span>
                        @if ($auction->featured)
                            <span class="status-badge active" style="background-color: rgba(255, 193, 7, 0.1); color: #ffc107;">
                                <i class="fas fa-star"></i> Featured
                            </span>
                        @endif
                    </div>
                    <div>
                        <span style="color: #777; font-size: 0.9rem;">
                            Created: {{ $auction->created_at->format('M d, Y H:i') }}
                        </span>
                    </div>
                </div>
                
                <!-- Price and Time Info -->
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px;">
                    <div style="background-color: rgba(255, 153, 0, 0.1); padding: 15px; border-radius: 8px;">
                        <h5 style="margin-bottom: 5px; color: #ff9900;">Current Price</h5>
                        <p style="font-size: 1.5rem; font-weight: 700; margin: 0;">${{ number_format($auction->currentPrice, 2) }}</p>
                    </div>
                    <div style="background-color: rgba(23, 162, 184, 0.1); padding: 15px; border-radius: 8px;">
                        <h5 style="margin-bottom: 5px; color: #17a2b8;">Retail Price</h5>
                        <p style="font-size: 1.5rem; font-weight: 700; margin: 0;">${{ number_format($auction->retailPrice, 2) }}</p>
                    </div>
                    <div style="background-color: rgba(40, 167, 69, 0.1); padding: 15px; border-radius: 8px;">
                        <h5 style="margin-bottom: 5px; color: #28a745;">Start Time</h5>
                        <p style="font-size: 1.2rem; font-weight: 600; margin: 0;">{{ $auction->startTime->format('M d, Y H:i') }}</p>
                    </div>
                    <div style="background-color: rgba(220, 53, 69, 0.1); padding: 15px; border-radius: 8px;">
                        <h5 style="margin-bottom: 5px; color: #dc3545;">End Time</h5>
                        <p style="font-size: 1.2rem; font-weight: 600; margin: 0;">{{ $auction->endTime->format('M d, Y H:i') }}</p>
                    </div>
                </div>
                
                <!-- Auction Details -->
                <div style="margin-bottom: 30px;">
                    <h4 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Auction Details</h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <p><strong>Category:</strong> {{ $auction->category->name ?? 'Uncategorized' }}</p>
                            <p><strong>Starting Price:</strong> ${{ number_format($auction->startingPrice, 2) }}</p>
                            <p><strong>Bid Increment:</strong> ${{ number_format($auction->bidIncrement, 2) }}</p>
                        </div>
                        <div>
                            <p><strong>Extension Time:</strong> {{ $auction->extensionTime }} seconds</p>
                            <p><strong>Total Bids:</strong> {{ count($auction->bids) }}</p>
                            <p><strong>Winner:</strong> 
                                @if (!empty($auction->winner_id))
                                    <a href="{{ url('/admin/users/' . $auction->winner_id) }}">
                                        {{ $auction->winner->name ?? 'Unknown' }}
                                    </a>
                                @else
                                    No winner yet
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Description -->
                <div style="margin-bottom: 30px;">
                    <h4 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Description</h4>
                    <div style="line-height: 1.6;">
                        {!! nl2br(e($auction->description)) !!}
                    </div>
                </div>
                
                <!-- Bid History -->
                <div>
                    <h4 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                        Bid History 
                        <span class="badge bg-secondary" style="font-size: 0.75rem; background-color: #f0f0f0; color: #777; padding: 3px 8px; border-radius: 10px;">
                            {{ count($auction->bids) }}
                        </span>
                    </h4>
                    
                    @if (count($auction->bids) > 0)
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Bid Amount</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($auction->bids->sortByDesc('created_at') as $bid)
                                    <tr>
                                        <td>
                                            @if($bid->user)
                                                <a href="{{ url('/admin/users/' . $bid->user->id) }}">{{ $bid->user->name }}</a>
                                            @else
                                                <span class="text-muted">Deleted User</span>
                                            @endif
                                        </td>
                                        <td>${{ number_format($bid->amount, 2) }}</td>
                                        <td>{{ $bid->created_at->format('M d, Y H:i:s') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No bids have been placed yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Image gallery functionality
    document.addEventListener('DOMContentLoaded', function() {
        const thumbnails = document.querySelectorAll('.admin-data-card-body img[style*="width: 80px"]');
        const mainImage = document.querySelector('.admin-data-card-body img[style*="width: 100%"]');

        if (thumbnails.length > 0 && mainImage) {
            thumbnails.forEach(thumb => {
                thumb.addEventListener('click', function() {
                    mainImage.src = this.src;
                });
            });
        }
    });

    // Validate auction start time before activating
    function validateAndActivateAuction(event) {
        event.preventDefault();

        // Get auction start time from backend
        const startTime = new Date("{{ $auction->startTime->toIso8601String() }}");
        const currentTime = new Date();

        // Check if start time is in the future (startTime > now)
        if (startTime > currentTime) {
            // Calculate time difference
            const diffMs = startTime - currentTime;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMins / 60);
            const diffDays = Math.floor(diffHours / 24);

            let timeMessage = '';
            if (diffDays > 0) {
                timeMessage = diffDays + ' day(s)';
            } else if (diffHours > 0) {
                timeMessage = diffHours + ' hour(s)';
            } else {
                timeMessage = diffMins + ' minute(s)';
            }

            // Show SweetAlert error message
            Swal.fire({
                icon: 'error',
                title: 'Cannot Activate Auction!',
                html: '<p>The start time is <strong>' + timeMessage + '</strong> in the future.</p>' +
                      '<hr>' +
                      '<p><strong>Start Time:</strong> ' + startTime.toLocaleString() + '</p>' +
                      '<p><strong>Current Time:</strong> ' + currentTime.toLocaleString() + '</p>' +
                      '<hr>' +
                      '<p>Please wait until the start time or edit the auction to change the start time.</p>',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            });
            return false;
        }

        // If validation passes (startTime <= now), show confirmation and submit
        Swal.fire({
            icon: 'question',
            title: 'Activate Auction?',
            text: 'Are you sure you want to activate this auction?',
            showCancelButton: true,
            confirmButtonText: 'Yes, Activate',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('activate-auction-form').submit();
            }
        });
    }
</script>
@endsection