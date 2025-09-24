@extends('layouts.admin')

@section('title', 'Slider Management')
@section('page-title', 'Homepage Slider')
@section('page-subtitle', 'Manage hero carousel images and content')

@section('content')
<div class="admin-data-card">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">Upload New Slider Image</div>
        <div class="admin-data-card-actions">
            <a href="{{ route('admin.marketing') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Marketing
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
        
        <form action="{{ route('admin.marketing.slider.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <!-- Image Upload -->
                <div>
                    <h4 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Image Upload</h4>
                    
                    <div class="form-group">
                        <label for="image">Slider Image <span style="color: red;">*</span></label>
                        <div style="border: 2px dashed #ddd; padding: 20px; text-align: center; margin-top: 10px;">
                            <input type="file" name="image" id="image" accept="image/jpeg,image/jpg,image/png,image/webp" required onchange="previewSliderImage(this)">
                            <div id="image-preview" style="margin-top: 15px; display: none;">
                                <img id="preview-img" style="max-width: 100%; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                            </div>
                            <small class="form-text text-muted" style="display: block; margin-top: 10px;">
                                Supported formats: JPEG, PNG, WebP. Max size: 5MB<br>
                                Recommended size: 1920x600px for best quality
                            </small>
                        </div>
                    </div>
                </div>
                
                <!-- Slide Content -->
                <div>
                    <h4 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Slide Content</h4>
                    
                    <div class="form-group">
                        <label for="title">Slide Title</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Enter slide title...">
                        <small class="form-text text-muted">Optional: Main heading text for the slide</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="subtitle">Slide Subtitle</label>
                        <textarea name="subtitle" id="subtitle" class="form-control" rows="3" placeholder="Enter slide description..."></textarea>
                        <small class="form-text text-muted">Optional: Supporting text or description</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="button_text">Button Text</label>
                        <input type="text" name="button_text" id="button_text" class="form-control" placeholder="Shop Now, Learn More, etc.">
                        <small class="form-text text-muted">Optional: Call-to-action button text</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="button_link">Button Link</label>
                        <input type="url" name="button_link" id="button_link" class="form-control" placeholder="https://example.com">
                        <small class="form-text text-muted">Optional: Where the button should link to</small>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px; text-align: right;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-upload"></i> Upload Slider Image
                </button>
            </div>
        </form>
    </div>
</div>

@if(count($sliderImages) > 0)
<div class="admin-data-card">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">Current Slider Images ({{ count($sliderImages) }})</div>
        <div class="admin-data-card-actions">
            <small class="text-muted">Drag to reorder slides</small>
        </div>
    </div>
    <div class="admin-data-card-body">
        <div id="slider-list" style="display: grid; gap: 20px;">
            @foreach($sliderImages as $slide)
                <div class="slider-item" data-index="{{ $slide['index'] }}" style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; padding: 20px;">
                    <div style="display: grid; grid-template-columns: 200px 1fr auto; gap: 20px; align-items: start;">
                        <!-- Image Preview -->
                        <div style="position: relative;">
                            <img src="{{ $slide['url'] }}" alt="Slider Image" style="width: 100%; height: 120px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                            @if(!$slide['active'])
                                <div style="position: absolute; top: 5px; right: 5px; background: rgba(220, 53, 69, 0.9); color: white; padding: 2px 6px; border-radius: 3px; font-size: 0.75rem;">
                                    Inactive
                                </div>
                            @else
                                <div style="position: absolute; top: 5px; right: 5px; background: rgba(40, 167, 69, 0.9); color: white; padding: 2px 6px; border-radius: 3px; font-size: 0.75rem;">
                                    Active
                                </div>
                            @endif
                            <div style="position: absolute; top: 5px; left: 5px; background: rgba(0, 0, 0, 0.7); color: white; padding: 2px 6px; border-radius: 3px; font-size: 0.75rem;">
                                #{{ $slide['order'] }}
                            </div>
                        </div>
                        
                        <!-- Slide Content -->
                        <div>
                            <form action="{{ route('admin.marketing.slider.update', $slide['index']) }}" method="POST" class="slide-form">
                                @csrf
                                @method('PUT')
                                
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                                    <div class="form-group" style="margin-bottom: 10px;">
                                        <label style="font-size: 0.85rem; font-weight: 600;">Title</label>
                                        <input type="text" name="title" class="form-control" value="{{ $slide['title'] }}" style="height: 35px; font-size: 0.9rem;">
                                    </div>
                                    
                                    <div class="form-group" style="margin-bottom: 10px;">
                                        <label style="font-size: 0.85rem; font-weight: 600;">Button Text</label>
                                        <input type="text" name="button_text" class="form-control" value="{{ $slide['button_text'] }}" style="height: 35px; font-size: 0.9rem;">
                                    </div>
                                </div>
                                
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <label style="font-size: 0.85rem; font-weight: 600;">Subtitle</label>
                                    <textarea name="subtitle" class="form-control" rows="2" style="font-size: 0.9rem;">{{ $slide['subtitle'] }}</textarea>
                                </div>
                                
                                <div class="form-group" style="margin-bottom: 15px;">
                                    <label style="font-size: 0.85rem; font-weight: 600;">Button Link</label>
                                    <input type="url" name="button_link" class="form-control" value="{{ $slide['button_link'] }}" style="height: 35px; font-size: 0.9rem;">
                                </div>
                                
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <label style="display: flex; align-items: center; gap: 8px; font-size: 0.9rem;">
                                        <input type="checkbox" name="active" value="1" {{ $slide['active'] ? 'checked' : '' }}>
                                        <span>Active</span>
                                    </label>
                                    
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-save"></i> Update
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Actions -->
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <div class="drag-handle" style="cursor: move; background: #e9ecef; border-radius: 4px; padding: 8px; text-align: center; font-size: 1.2rem; color: var(--gray);">
                                <i class="fas fa-grip-vertical"></i>
                            </div>
                            
                            <form action="{{ route('admin.marketing.slider.delete', $slide['index']) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this slider image?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" style="width: 100%;">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="admin-data-card-footer">
        <div style="text-align: center; color: var(--gray); font-size: 0.9rem;">
            <i class="fas fa-info-circle"></i> 
            Drag slider items to reorder them. Changes are saved automatically.
        </div>
    </div>
</div>
@else
<div class="admin-data-card">
    <div class="admin-data-card-body" style="text-align: center; padding: 60px 20px;">
        <div style="font-size: 4rem; color: var(--light-gray); margin-bottom: 20px;">
            <i class="fas fa-images"></i>
        </div>
        <h3 style="color: var(--gray); margin-bottom: 10px;">No Slider Images</h3>
        <p style="color: var(--gray); margin-bottom: 30px;">Upload your first slider image to get started with the homepage carousel.</p>
        <a href="#image" class="btn btn-primary" onclick="document.getElementById('image').focus();">
            <i class="fas fa-plus"></i> Upload First Image
        </a>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
    // Image preview function
    function previewSliderImage(input) {
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

    // Sortable functionality for reordering slides
    document.addEventListener('DOMContentLoaded', function() {
        const sliderList = document.getElementById('slider-list');
        
        if (sliderList && typeof Sortable !== 'undefined') {
            new Sortable(sliderList, {
                handle: '.drag-handle',
                animation: 150,
                onEnd: function(evt) {
                    const items = Array.from(sliderList.children);
                    const newOrder = items.map(item => parseInt(item.dataset.index));
                    
                    // Send AJAX request to update order
                    fetch('{{ route("admin.marketing.slider.reorder") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            order: newOrder
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            const alert = document.createElement('div');
                            alert.className = 'alert alert-success';
                            alert.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; background: #d4edda; color: #155724; padding: 10px 15px; border-radius: 4px;';
                            alert.textContent = 'Slider order updated successfully!';
                            document.body.appendChild(alert);
                            
                            setTimeout(() => alert.remove(), 3000);
                        }
                    })
                    .catch(error => {
                        console.error('Error updating order:', error);
                    });
                }
            });
        }
    });
</script>
@endsection