@extends('layouts.admin')

@section('title', 'Edit Auction')
@section('page-title', 'Edit Auction')
@section('page-subtitle', 'Update auction details')

@section('content')
<div class="admin-data-card">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">Edit Auction: {{ $auction->title }}</div>
        <div class="admin-data-card-actions">
            <a href="{{ url('/admin/auctions/' . $auction->id) }}" class="btn btn-sm btn-primary">
                <i class="fas fa-eye"></i> View Auction
            </a>
            <a href="{{ url('/admin/auctions') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Auctions
            </a>
        </div>
    </div>
    <div class="admin-data-card-body">
        @if (session('success'))
            <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                {{ session('error') }}
            </div>
        @endif
        
        <form action="{{ url('/admin/auctions/' . $auction->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            @if ($errors->any())
                <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
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
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $auction->title) }}" {{ $auction->status != 'upcoming' ? 'readonly' : 'required' }}>
                        @if ($auction->status != 'upcoming')
                            <small class="form-text text-muted">The title cannot be changed once the auction is active or completed.</small>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description <span style="color: red;">*</span></label>
                        <textarea name="description" id="description" class="form-control" rows="5" {{ $auction->status != 'upcoming' ? 'readonly' : 'required' }}>{{ old('description', $auction->description) }}</textarea>
                        @if ($auction->status != 'upcoming')
                            <small class="form-text text-muted">The description cannot be changed once the auction is active or completed.</small>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <label for="category_id">Category <span style="color: red;">*</span></label>
                        <select name="category_id" id="category_id" class="form-control" {{ $auction->status != 'upcoming' ? 'disabled' : 'required' }}>
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $auction->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @if ($auction->status != 'upcoming')
                            <input type="hidden" name="category_id" value="{{ $auction->category_id }}">
                            <small class="form-text text-muted">The category cannot be changed once the auction is active or completed.</small>
                        @endif
                    </div>
                    
                    <!-- Current Images -->
                    @if (!empty($auction->images) && count($auction->images) > 0)
                        <div class="form-group">
                            <label>Current Images ({{ count($auction->images) }} images)</label>
                            @if (config('app.debug'))
                                <small class="form-text text-muted">Debug: {{ json_encode($auction->images) }}</small>
                            @endif
                            <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px;">
                                @foreach ($auction->images as $index => $image)
                                    <div style="position: relative; width: 100px; height: 100px;">
                                        <img src="{{ asset('storage/' . $image) }}"
                                             alt="Auction Image {{ $index + 1 }}"
                                             style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div style="display: none; width: 100px; height: 100px; background: #f8f9fa; border: 1px solid #ddd; border-radius: 4px; align-items: center; justify-content: center; font-size: 12px; color: #666;">
                                            Image not found
                                        </div>
                                        <button type="button" onclick="deleteImage({{ $index }})"
                                                style="position: absolute; top: -5px; right: -5px; background: red; color: white; width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: none; font-size: 12px; cursor: pointer;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Upload New Images -->
                    <div class="form-group">
                        <label>Upload New Images</label>
                        <div style="border: 2px dashed #ddd; padding: 20px; text-align: center; margin-top: 10px;">
                            <div style="margin-bottom: 10px;">
                                <input type="file" id="upload-image-input" accept="image/jpeg,image/jpg,image/png,image/gif" style="margin-bottom: 10px;" onchange="previewImage(this)">
                            </div>
                            <div id="image-preview" style="margin-bottom: 10px; display: none;">
                                <img id="preview-img" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                            </div>
                            <div>
                                <button type="button" onclick="uploadImage()" class="btn btn-sm btn-primary">Upload Image</button>
                            </div>
                            <small class="form-text text-muted" style="display: block; margin-top: 10px;">
                                Supported formats: JPEG, PNG, JPG, GIF. Max size: 5MB<br>
                                Storage path: {{ storage_path('app/public/auctions') }}<br>
                                @if (config('app.debug'))
                                    Public URL: {{ asset('storage/auctions/test.jpg') }}
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
                
                <!-- Pricing and Timing Section -->
                <div>
                    <h4 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Pricing & Timing</h4>
                    
                    <div class="form-group">
                        <label for="startingPrice">Starting Price ($) <span style="color: red;">*</span></label>
                        <input type="number" name="startingPrice" id="startingPrice" class="form-control" step="0.01" min="0" value="{{ old('startingPrice', $auction->startingPrice) }}" {{ $auction->status != 'upcoming' ? 'readonly' : 'required' }}>
                        @if ($auction->status != 'upcoming')
                            <small class="form-text text-muted">The starting price cannot be changed once the auction is active or completed.</small>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <label for="bidIncrement">Bid Increment ($) <span style="color: red;">*</span></label>
                        <input type="number" name="bidIncrement" id="bidIncrement" class="form-control" step="0.01" min="0.01" value="{{ old('bidIncrement', $auction->bidIncrement) }}" {{ $auction->status != 'upcoming' ? 'readonly' : 'required' }}>
                        @if ($auction->status != 'upcoming')
                            <small class="form-text text-muted">The bid increment cannot be changed once the auction is active or completed.</small>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <label for="retailPrice">Retail Price ($) <span style="color: red;">*</span></label>
                        <input type="number" name="retailPrice" id="retailPrice" class="form-control" step="0.01" min="0" value="{{ old('retailPrice', $auction->retailPrice) }}" {{ $auction->status != 'upcoming' ? 'readonly' : 'required' }}>
                        @if ($auction->status != 'upcoming')
                            <small class="form-text text-muted">The retail price cannot be changed once the auction is active or completed.</small>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <label for="startTime">Start Time <span style="color: red;">*</span></label>
                        <input type="datetime-local" name="startTime" id="startTime" class="form-control" value="{{ old('startTime', $auction->startTime->format('Y-m-d\TH:i')) }}" {{ $auction->status != 'upcoming' ? 'readonly' : 'required' }}>
                        @if ($auction->status != 'upcoming')
                            <small class="form-text text-muted">The start time cannot be changed once the auction is active or completed.</small>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <label for="endTime">End Time <span style="color: red;">*</span></label>
                        <input type="datetime-local" name="endTime" id="endTime" class="form-control" value="{{ old('endTime', $auction->endTime->format('Y-m-d\TH:i')) }}" {{ $auction->status == 'ended' || $auction->status == 'cancelled' ? 'readonly' : 'required' }}>
                        @if ($auction->status == 'ended' || $auction->status == 'cancelled')
                            <small class="form-text text-muted">The end time cannot be changed once the auction is completed or cancelled.</small>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <label for="extensionTime">Extension Time (seconds) <span style="color: red;">*</span></label>
                        <input type="number" name="extensionTime" id="extensionTime" class="form-control" min="0" value="{{ old('extensionTime', $auction->extensionTime) }}" {{ $auction->status != 'upcoming' ? 'readonly' : 'required' }}>
                        @if ($auction->status != 'upcoming')
                            <small class="form-text text-muted">The extension time cannot be changed once the auction is active or completed.</small>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="featured" id="featured" class="form-check-input" value="1" {{ old('featured', $auction->featured) ? 'checked' : '' }}>
                            <label for="featured" class="form-check-label">Feature this auction</label>
                            <small class="form-text text-muted">Featured auctions appear in highlighted sections throughout the site.</small>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Current Status</label>
                        <div>
                            <span class="status-badge 
                                {{ $auction->status == 'active' ? 'active' : 
                                  ($auction->status == 'upcoming' ? 'pending' : 
                                   ($auction->status == 'ended' ? 'processing' : 'inactive')) }}">
                                {{ ucfirst($auction->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px; text-align: right;">
                <a href="{{ url('/admin/auctions/' . $auction->id) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Auction</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<!-- Hidden forms for image operations -->
<form id="delete-image-form" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<form id="upload-image-form" action="{{ route('admin.auctions.upload-image', $auction->id) }}" method="POST" enctype="multipart/form-data" style="display: none;">
    @csrf
    <input type="file" name="image" id="hidden-image-input">
</form>

<script>
    // Function to delete an image
    function deleteImage(index) {
        if (confirm('Are you sure you want to delete this image?')) {
            const form = document.getElementById('delete-image-form');
            form.action = '{{ url("/admin/auctions/" . $auction->id . "/delete-image") }}/' + index;
            form.submit();
        }
    }

    // Function to upload an image
    function uploadImage() {
        const fileInput = document.getElementById('upload-image-input');
        if (fileInput.files && fileInput.files[0]) {
            // Create a new form data and submit
            const form = document.getElementById('upload-image-form');
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('image', fileInput.files[0]);

            fetch(form.action, {
                method: 'POST',
                body: formData
            }).then(response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('Failed to upload image');
                }
            }).catch(error => {
                alert('Error uploading image: ' + error);
            });
        } else {
            alert('Please select an image to upload');
        }
    }

    // Image preview function
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Validate end time is after start time
        const startTime = document.getElementById('startTime');
        const endTime = document.getElementById('endTime');
        
        if (startTime && endTime && !startTime.readOnly) {
            endTime.addEventListener('change', function() {
                const start = new Date(startTime.value);
                const end = new Date(endTime.value);
                
                if (end <= start) {
                    alert('End time must be after start time');
                    endTime.value = '{{ $auction->endTime->format('Y-m-d\TH:i') }}';
                }
            });
            
            startTime.addEventListener('change', function() {
                const start = new Date(startTime.value);
                const end = new Date(endTime.value);
                
                if (end <= start) {
                    // Set end time to 24 hours after start time
                    const newEnd = new Date(start);
                    newEnd.setHours(newEnd.getHours() + 24);
                    endTime.value = newEnd.toISOString().slice(0, 16);
                }
            });
        }
    });
</script>
@endsection