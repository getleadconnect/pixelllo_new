@extends('layouts.admin')

@section('title', 'Marketing Dashboard')
@section('page-title', 'Marketing Dashboard')
@section('page-subtitle', 'Manage website content and promotional materials')

@section('content')
<div class="admin-cards">
    <div class="admin-card">
        <div class="admin-card-inner">
            <div class="admin-card-icon" style="background-color: rgba(255, 153, 0, 0.1); color: var(--secondary);">
                <i class="fas fa-images"></i>
            </div>
            <div class="admin-card-content">
                <h3>{{ count($sliderImages) }}</h3>
                <p>Slider Images</p>
            </div>
        </div>
    </div>
    
    <div class="admin-card">
        <div class="admin-card-inner">
            <div class="admin-card-icon" style="background-color: rgba(40, 167, 69, 0.1); color: var(--success);">
                <i class="fas fa-eye"></i>
            </div>
            <div class="admin-card-content">
                <h3>{{ count(array_filter($sliderImages, function($slide) { return $slide['active']; })) }}</h3>
                <p>Active Slides</p>
            </div>
        </div>
    </div>
    
    <div class="admin-card">
        <div class="admin-card-inner">
            <div class="admin-card-icon" style="background-color: rgba(23, 162, 184, 0.1); color: var(--info);">
                <i class="fas fa-bullhorn"></i>
            </div>
            <div class="admin-card-content">
                <h3>Coming Soon</h3>
                <p>Promotions</p>
            </div>
        </div>
    </div>
    
    <div class="admin-card">
        <div class="admin-card-inner">
            <div class="admin-card-icon" style="background-color: rgba(255, 193, 7, 0.1); color: var(--warning);">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="admin-card-content">
                <h3>Coming Soon</h3>
                <p>Email Marketing</p>
            </div>
        </div>
    </div>
</div>

<div class="admin-data-card">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">Marketing Tools</div>
    </div>
    <div class="admin-data-card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            <!-- Homepage Slider Management -->
            <div style="background: #f8f9fa; padding: 25px; border-radius: 8px; border: 1px solid #e9ecef;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                    <div style="width: 50px; height: 50px; background: rgba(255, 153, 0, 0.1); color: var(--secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-images"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 1.2rem;">Homepage Slider</h4>
                        <p style="margin: 0; color: var(--gray); font-size: 0.9rem;">Manage hero carousel images</p>
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <span style="font-weight: 500;">Current Images:</span>
                        <span style="color: var(--secondary); font-weight: 600;">{{ count($sliderImages) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: 500;">Active Slides:</span>
                        <span style="color: var(--success); font-weight: 600;">{{ count(array_filter($sliderImages, function($slide) { return $slide['active']; })) }}</span>
                    </div>
                </div>
                
                @if(count($sliderImages) > 0)
                    <div style="margin-bottom: 20px;">
                        <div style="display: flex; gap: 10px; overflow-x: auto; padding-bottom: 10px;">
                            @foreach(array_slice($sliderImages, 0, 3) as $slide)
                                <div style="min-width: 80px; height: 50px; border-radius: 4px; overflow: hidden; border: 2px solid {{ $slide['active'] ? 'var(--success)' : 'var(--gray)' }};">
                                    <img src="{{ $slide['url'] }}" alt="Slide" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            @endforeach
                            @if(count($sliderImages) > 3)
                                <div style="min-width: 80px; height: 50px; border-radius: 4px; background: #e9ecef; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; color: var(--gray);">
                                    +{{ count($sliderImages) - 3 }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                
                <a href="{{ route('admin.marketing.slider') }}" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-cog"></i> Manage Slider
                </a>
            </div>
            
            <!-- Promotions (Coming Soon) -->
            <div style="background: #f8f9fa; padding: 25px; border-radius: 8px; border: 1px solid #e9ecef; opacity: 0.6;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                    <div style="width: 50px; height: 50px; background: rgba(23, 162, 184, 0.1); color: var(--info); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 1.2rem;">Promotions</h4>
                        <p style="margin: 0; color: var(--gray); font-size: 0.9rem;">Create and manage promotional campaigns</p>
                    </div>
                </div>
                
                <p style="color: var(--gray); margin-bottom: 20px; font-style: italic;">
                    Feature coming soon. Create discount codes, promotional banners, and special offers.
                </p>
                
                <button class="btn btn-secondary" style="width: 100%;" disabled>
                    <i class="fas fa-clock"></i> Coming Soon
                </button>
            </div>
            
            <!-- Email Marketing (Coming Soon) -->
            <div style="background: #f8f9fa; padding: 25px; border-radius: 8px; border: 1px solid #e9ecef; opacity: 0.6;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                    <div style="width: 50px; height: 50px; background: rgba(255, 193, 7, 0.1); color: var(--warning); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 1.2rem;">Email Marketing</h4>
                        <p style="margin: 0; color: var(--gray); font-size: 0.9rem;">Send newsletters and notifications</p>
                    </div>
                </div>
                
                <p style="color: var(--gray); margin-bottom: 20px; font-style: italic;">
                    Feature coming soon. Send email campaigns, newsletters, and automated notifications to users.
                </p>
                
                <button class="btn btn-secondary" style="width: 100%;" disabled>
                    <i class="fas fa-clock"></i> Coming Soon
                </button>
            </div>
            
            <!-- Social Media (Coming Soon) -->
            <div style="background: #f8f9fa; padding: 25px; border-radius: 8px; border: 1px solid #e9ecef; opacity: 0.6;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                    <div style="width: 50px; height: 50px; background: rgba(220, 53, 69, 0.1); color: var(--danger); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 1.2rem;">Social Media</h4>
                        <p style="margin: 0; color: var(--gray); font-size: 0.9rem;">Manage social media integration</p>
                    </div>
                </div>
                
                <p style="color: var(--gray); margin-bottom: 20px; font-style: italic;">
                    Feature coming soon. Connect social media accounts and manage sharing options.
                </p>
                
                <button class="btn btn-secondary" style="width: 100%;" disabled>
                    <i class="fas fa-clock"></i> Coming Soon
                </button>
            </div>
        </div>
    </div>
</div>

<div class="admin-data-card">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">Quick Actions</div>
    </div>
    <div class="admin-data-card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
            <a href="{{ route('admin.marketing.slider') }}" class="btn btn-primary" style="padding: 15px; text-align: center; text-decoration: none;">
                <i class="fas fa-plus"></i><br>
                <small>Add Slider Image</small>
            </a>
            
            <a href="{{ url('/') }}" target="_blank" class="btn btn-success" style="padding: 15px; text-align: center; text-decoration: none;">
                <i class="fas fa-eye"></i><br>
                <small>Preview Homepage</small>
            </a>
            
            <button class="btn btn-secondary" style="padding: 15px;" disabled>
                <i class="fas fa-chart-bar"></i><br>
                <small>Analytics (Soon)</small>
            </button>
            
            <button class="btn btn-secondary" style="padding: 15px;" disabled>
                <i class="fas fa-cog"></i><br>
                <small>SEO Settings (Soon)</small>
            </button>
        </div>
    </div>
</div>
@endsection