@extends('layouts.admin')

@section('title', 'Create Auction')
@section('page-title', 'Create New Auction')
@section('page-subtitle', 'Add a new auction to the platform')

@section('content')
<div class="admin-data-card">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">New Auction</div>
        <div class="admin-data-card-actions">
            <a href="{{ url('/admin/auctions') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-arrow-left"></i> Back to Auctions
            </a>
        </div>
    </div>
    <div class="admin-data-card-body">
        <form action="{{ url('/admin/auctions') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin-bottom: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <!-- Basic Info Section -->
                <div>
                    <h4 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Basic Information</h4>
                    
                    <div class="form-group">
                        <label for="title">Auction Title <span style="color: red;">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description <span style="color: red;">*</span></label>
                        <textarea name="description" id="description" class="form-control" rows="5" required>{{ old('description') }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="category_id">Category <span style="color: red;">*</span></label>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            @foreach (\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="images">Product Images</label>
                        <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*">
                        <small class="form-text text-muted">You can upload multiple images. Max size: 5MB per image.</small>
                    </div>
                </div>
                
                <!-- Pricing and Timing Section -->
                <div>
                    <h4 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
                        <span>Pricing & Timing</span>
                        <span id="dubai-clock" style="font-size: 14px; font-weight: normal; color: #0c71e2;">
                            <i class="fas fa-clock"></i> Loading...
                        </span>
                    </h4>
                    
                    <div class="form-group">
                        <label for="startingPrice">Starting Price (AED) <span style="color: red;">*</span></label>
                        <input type="number" name="startingPrice" id="startingPrice" class="form-control" step="0.01" min="0" value="{{ old('startingPrice') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="bidIncrement">Bid Increment (AED) <span style="color: red;">*</span></label>
                        <input type="number" name="bidIncrement" id="bidIncrement" class="form-control" step="0.01" min="0.01" value="{{ old('bidIncrement', '0.01') }}" required>
                        <small class="form-text text-muted">The amount each bid increases the price.</small>
                    </div>

                    <div class="form-group">
                        <label for="retailPrice">Retail Price (AED) <span style="color: red;">*</span></label>
                        <input type="number" name="retailPrice" id="retailPrice" class="form-control" step="0.01" min="0" value="{{ old('retailPrice') }}" required>
                        <small class="form-text text-muted">The original retail price of the item.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="startTime">Start Time <span style="color: red;">*</span></label>
                        <input type="datetime-local" name="startTime" id="startTime" class="form-control" value="{{ old('startTime', now()->format('Y-m-d\TH:i')) }}" required>
                        <small class="form-text text-muted">Timezone: {{ config('app.timezone') }}</small>
                    </div>

                    <div class="form-group">
                        <label for="endTime">End Time <span style="color: red;">*</span></label>
                        <input type="datetime-local" name="endTime" id="endTime" class="form-control" value="{{ old('endTime', now()->addDay()->format('Y-m-d\TH:i')) }}" required>
                        <small class="form-text text-muted">Timezone: {{ config('app.timezone') }}</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="extensionTime">Extension Time (seconds) <span style="color: red;">*</span></label>
                        <input type="number" name="extensionTime" id="extensionTime" class="form-control" min="0" value="{{ old('extensionTime', '30') }}" required>
                        <small class="form-text text-muted">The amount of time added to the auction end when a bid is placed during the final minutes.</small>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="featured" id="featured" class="form-check-input" value="1" {{ old('featured') ? 'checked' : '' }}>
                            <label for="featured" class="form-check-label">Feature this auction</label>
                            <small class="form-text text-muted">Featured auctions appear in highlighted sections throughout the site.</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px; text-align: right;">
                <button type="reset" class="btn btn-secondary">Reset</button>
                <button type="submit" class="btn btn-primary">Create Auction</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startTime = document.getElementById('startTime');
        const endTime = document.getElementById('endTime');
        const dubaiClockElement = document.getElementById('dubai-clock');

        // Function to update Dubai clock
        function updateDubaiClock() {
            const now = new Date();

            // Format time for Asia/Dubai timezone (UTC+4)
            const dubaiTime = new Date(now.toLocaleString('en-US', { timeZone: 'Asia/Dubai' }));

            const options = {
                timeZone: 'Asia/Dubai',
                year: 'numeric',
                month: 'short',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            };

            const formattedTime = new Intl.DateTimeFormat('en-US', options).format(now);

            dubaiClockElement.innerHTML = '<i class="fas fa-clock"></i> Dubai: ' + formattedTime;
        }

        // Update clock immediately and then every second
        updateDubaiClock();
        setInterval(updateDubaiClock, 1000);

        // Validate end time is after start time
        endTime.addEventListener('change', function() {
            const start = new Date(startTime.value);
            const end = new Date(endTime.value);

            if (end <= start) {
                alert('End time must be after start time');
                endTime.value = '';
            }
        });

        // Auto-update end time when start time changes
        startTime.addEventListener('change', function() {
            const start = new Date(startTime.value);
            const end = new Date(endTime.value);

            if (end <= start || !endTime.value) {
                // Set end time to 24 hours after start time
                const newEnd = new Date(start.getTime() + (24 * 60 * 60 * 1000));
                const year = newEnd.getFullYear();
                const month = String(newEnd.getMonth() + 1).padStart(2, '0');
                const day = String(newEnd.getDate()).padStart(2, '0');
                const hours = String(newEnd.getHours()).padStart(2, '0');
                const minutes = String(newEnd.getMinutes()).padStart(2, '0');

                endTime.value = `${year}-${month}-${day}T${hours}:${minutes}`;
            }
        });
    });
</script>
@endsection