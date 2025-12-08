@extends('layouts.app')

@section('title', config('app.name') . ' - Online Auction Platform')

@section('styles')
<style>
        :root {
            --primary: #ffdd00;
            --secondary: #ff9900;
            --accent: #ff5500;
            --dark: #333333;
            --light: #f8f9fa;
            --gray: #6c757d;
            --success: #28a745;
            --danger: #dc3545;
            --info: #17a2b8;
            --warning: #ffc107;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Figtree', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
        }
        
        .container {
            width: 100%;
            max-width: 1368px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        /* Header Styles */
        .header {
            background-color: var(--primary);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark);
            text-decoration: none;
        }
        
        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        
        .nav-link {
            color: var(--dark);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav-link:hover {
            color: var(--accent);
        }
        
        .auth-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            display: inline-block;
        }
        
        .btn-primary {
            background-color: var(--secondary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--accent);
        }
        
        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--dark);
            color: var(--dark);
        }
        
        .btn-outline:hover {
            background-color: var(--dark);
            color: white;
        }
        
        /* Hero Section */
        .hero {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                url('{{ asset('images/hero-background.jpg') }}');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
            margin-bottom: 40px;
            text-align: center;
        }
        
        .hero h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .hero p {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto 30px auto;
        }

        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 50px;
            margin-bottom: 30px;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .btn-hero-secondary {
            background-color: transparent;
            border: 2px solid white;
            color: white;
            margin-left: 15px;
        }

        .btn-hero-secondary:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Featured Auctions */
        .section-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .section-title h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            position: relative;
        }

        .section-title h2::after {
            content: '';
            display: block;
            width: 50px;
            height: 3px;
            background-color: var(--primary);
            margin: 10px auto;
        }

        .auction-tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
        }

        .auction-tab {
            background: none;
            border: none;
            padding: 12px 20px;
            margin: 0 5px;
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray);
            cursor: pointer;
            position: relative;
            transition: all 0.3s;
        }

        .auction-tab:hover {
            color: var(--dark);
        }

        .auction-tab.active {
            color: var(--secondary);
        }

        .auction-tab.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 3px;
            background-color: var(--secondary);
        }

        .auction-tabs-content {
            position: relative;
            min-height: 400px;
            width: 100%;
        }

        .auction-tab-panel {
            display: none !important;
            visibility: hidden;
            height: 0;
            overflow: hidden;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .auction-tab-panel.active {
            display: block !important;
            visibility: visible;
            height: auto;
            overflow: visible;
            opacity: 1;
        }

        /* Backup method for tab panels */
        #live-tab, #upcoming-tab, #ended-tab {
            display: none;
        }

        #live-tab.active, #upcoming-tab.active, #ended-tab.active {
            display: block;
        }

        .auctions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .auction-card {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            position: relative;
            border: 1px solid #eee;
        }

        .auction-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        .auction-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background-color: var(--secondary);
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 10;
        }

        .auction-image-container {
            position: relative;
            overflow: hidden;
        }

        .auction-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.5s;
            background-color: #f3f4f6;
            display: block;
        }

        img.auction-image[src$="product-placeholder.svg"] {
            object-fit: contain;
            padding: 20px;
        }

        .auction-card:hover .auction-image {
            transform: scale(1.05);
        }

        .auction-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
            padding: 15px;
            display: flex;
            justify-content: space-between;
            color: white;
            font-size: 0.8rem;
        }

        .auction-content {
            padding: 20px;
        }

        .auction-category {
            font-size: 0.8rem;
            color: var(--gray);
            margin-bottom: 8px;
            font-weight: 500;
        }

        .auction-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 12px;
            color: var(--dark);
            line-height: 1.5;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .auction-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .auction-price {
            font-weight: 700;
            color: var(--accent);
        }

        .auction-time {
            color: var(--gray);
            font-size: 0.9rem;
            font-weight: 600;
        }

        .auction-progress {
            height: 6px;
            background-color: #eee;
            border-radius: 3px;
            margin-bottom: 12px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background-color: var(--secondary);
            border-radius: 3px;
        }

        .auction-retail {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 0.85rem;
        }

        .retail-price {
            color: var(--gray);
        }

        .savings {
            color: var(--success);
            font-weight: 600;
        }

        .notify-btn {
            color: var(--secondary);
            font-weight: 600;
            cursor: pointer;
            display: inline-block;
            font-size: 0.85rem;
            transition: all 0.3s;
        }

        .notify-btn:hover {
            color: var(--accent);
            text-decoration: underline;
        }

        .featured-auctions-footer {
            text-align: center;
        }

        .featured-auctions-note {
            margin-top: 15px;
            font-size: 0.8rem;
            color: var(--gray);
            font-style: italic;
        }
        
        /* Categories Section */
        .categories {
            padding: 50px 0;
            background-color: #f1f1f1;
        }
        
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        @media (max-width: 768px) {
            .categories-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 15px;
            }

            .category-image-container {
                height: 120px;
            }

            .category-name {
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .categories-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .category-image-container {
                height: 100px;
            }

            .category-image-placeholder i {
                font-size: 2.5rem;
            }

            .category-name {
                font-size: 0.9rem;
                margin: 10px 5px 3px;
            }

            .category-count {
                font-size: 0.75rem;
            }
        }
        
        .category-card {
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            display: block;
        }

        .category-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .category-image-container {
            width: 100%;
            height: 150px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .category-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .category-card:hover .category-image {
            transform: scale(1.1);
        }

        .category-image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .category-image-placeholder i {
            font-size: 3rem;
            color: white;
            opacity: 0.9;
        }

        .category-name {
            font-weight: 600;
            margin: 15px 10px 5px;
            color: var(--dark);
            font-size: 1.1rem;
        }

        .category-count {
            color: var(--gray);
            font-size: 0.85rem;
            padding: 0 10px 15px;
        }
        
    .auction-title a {
        color: var(--dark);
        text-decoration: none;
        transition: color 0.2s;
    }

    .auction-title a:hover {
        color: var(--primary-color);
    }

    /* Auction status styles */
    .auction-badge.ended, .auction-badge.closed {
        background-color: #e74c3c;
    }
    
    .auction-badge.ending-soon {
        background-color: #e67e22;
    }
    
    .auction-badge.featured {
        background-color: var(--secondary-color);
    }
    
    .auction-badge.active {
        background-color: #28a745 ;
    }
    
    .auction-badge.upcoming {
        background-color: #3498db;
    }

    .auction-card.closed {
        opacity: 0.8;
        position: relative;
    }
    
    .auction-card.closed::after {
        content: "AUCTION CLOSED";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-30deg);
        background-color: rgba(231, 76, 60, 0.9);
        color: white;
        font-weight: bold;
        padding: 10px 20px;
        font-size: 18px;
        z-index: 100;
        border-radius: 5px;
        white-space: nowrap;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        letter-spacing: 1px;
    }
    
    .auction-time-details {
        margin-top: 5px;
        margin-bottom: 10px;
        font-size: 0.8rem;
        color: #666;
        display: flex;
        justify-content: space-between;
    }
    
    .auction-time-details span {
        display: block;
    }
    
    .auction-end-time {
        text-align: right;
        font-weight: 500;
    }
    
    .auction-time.urgent {
        color: #e74c3c !important;
        font-weight: bold;
        animation: pulse 1s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }

    /* Empty State Styles */
    .no-auctions {
        grid-column: 1 / -1;
        width: 100%;
    }
    
    .empty-state {
        background-color: white;
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        margin: 30px auto;
        max-width: 500px;
        border: 1px solid #f0f0f0;
    }
    
    .empty-state-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        background-color: #f8f9fa;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: var(--secondary-color);
        border: 2px dashed var(--secondary-color);
    }
    
    .empty-state h3 {
        font-size: 1.5rem;
        margin-bottom: 15px;
        color: #333;
    }
    
    .empty-state p {
        color: #666;
        margin-bottom: 25px;
        max-width: 350px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.6;
    }
    
    .empty-state-btn {
        display: inline-block;
        min-width: 200px;
    }

    /* Homepage Slider Styles */
    .homepage-slider {
        position: relative;
        margin-bottom: 50px;
        overflow: hidden;
    }

    .slider-container {
        position: relative;
        width: 100%;
        height: 500px;
        overflow: hidden;
    }

    .slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.8s ease-in-out;
        z-index: 1;
    }

    .slide.active {
        opacity: 1;
        z-index: 2;
    }

    .slide-content {
        text-align: center;
        color: white;
        max-width: 800px;
        padding: 0 20px;
        z-index: 3;
        position: relative;
    }

    .slide-title {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        animation: slideInUp 1s ease-out;
    }

    .slide-subtitle {
        font-size: 1.3rem;
        margin-bottom: 30px;
        line-height: 1.6;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        animation: slideInUp 1s ease-out 0.2s both;
    }

    .slide .btn {
        animation: slideInUp 1s ease-out 0.4s both;
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Slider Navigation */
    .slider-nav {
        position: absolute;
        top: 50%;
        width: 100%;
        display: flex;
        justify-content: space-between;
        padding: 0 20px;
        z-index: 4;
        pointer-events: none;
    }

    .slider-btn {
        background-color: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.5);
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1.2rem;
        pointer-events: auto;
        backdrop-filter: blur(5px);
    }

    .slider-btn:hover {
        background-color: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.8);
        transform: scale(1.1);
    }

    .slider-btn:active {
        transform: scale(0.95);
    }

    /* Slider Dots */
    .slider-dots {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 12px;
        z-index: 4;
    }

    .dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.4);
        border: 2px solid rgba(255, 255, 255, 0.6);
        cursor: pointer;
        transition: all 0.3s ease;
        backdrop-filter: blur(5px);
    }

    .dot.active {
        background-color: var(--primary);
        border-color: var(--primary);
        transform: scale(1.2);
    }

    .dot:hover {
        background-color: rgba(255, 255, 255, 0.6);
        transform: scale(1.1);
    }

    /* Hero Stats Section */
    .hero-stats-section {
        background-color: rgba(0, 0, 0, 0.8);
        padding: 40px 0;
        position: relative;
        z-index: 3;
    }

    .hero-stats-section .hero-stats {
        display: flex;
        justify-content: center;
        gap: 80px;
        margin: 0;
    }

    .hero-stats-section .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        color: white;
    }

    .hero-stats-section .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 8px;
    }

    .hero-stats-section .stat-label {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.9);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .slider-container {
            height: 400px;
        }

        .slide-title {
            font-size: 2rem;
        }

        .slide-subtitle {
            font-size: 1.1rem;
        }

        .slider-btn {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .slider-nav {
            padding: 0 15px;
        }

        .hero-stats-section .hero-stats {
            gap: 40px;
            flex-wrap: wrap;
        }

        .hero-stats-section .stat-number {
            font-size: 2rem;
        }
    }

    @media (max-width: 480px) {
        .slider-container {
            height: 350px;
        }

        .slide-title {
            font-size: 1.8rem;
        }

        .slide-subtitle {
            font-size: 1rem;
        }

        .hero-stats-section .hero-stats {
            gap: 30px;
        }

        .hero-stats-section .stat-number {
            font-size: 1.8rem;
        }
    }
</style>
@endsection

@section('content')
    
    @if(count($sliderImages) > 0)
    <!-- Homepage Slider -->
    <section class="homepage-slider">
        <div class="slider-container">
            @foreach($sliderImages as $index => $slide)
            <div class="slide {{ $index === 0 ? 'active' : '' }}" style="background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('{{ $slide['url'] }}');">
                <div class="container">
                    <div class="slide-content">
                        @if(!empty($slide['title']))
                            <h1 class="slide-title">{{ $slide['title'] }}</h1>
                        @endif
                        @if(!empty($slide['subtitle']))
                            <p class="slide-subtitle">{{ $slide['subtitle'] }}</p>
                        @endif
                        @if(!empty($slide['button_text']) && !empty($slide['button_link']))
                            <a href="{{ $slide['button_link'] }}" class="btn btn-primary btn-lg">{{ $slide['button_text'] }}</a>
                        @endif
                        @if(empty($slide['title']) && empty($slide['subtitle']) && empty($slide['button_text']))
                            <!-- Default content when slide has no text -->
                            <h1 class="slide-title">Bid. Win. Save.</h1>
                            <p class="slide-subtitle">Experience the thrill of winning high-end products at a fraction of their retail price with Pixelllo, the premier online auction platform.</p>
                            <a href="{{ route('auctions') }}" class="btn btn-primary btn-lg">Start Bidding Now</a>
                            <a href="{{ route('how-it-works') }}" class="btn btn-outline btn-lg" style="margin-left: 15px;">How It Works</a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
            
            @if(count($sliderImages) > 1)
            <!-- Slider Navigation -->
            <div class="slider-nav">
                <button class="slider-btn prev" onclick="changeSlide(-1)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="slider-btn next" onclick="changeSlide(1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            
            <!-- Slider Dots -->
            <div class="slider-dots">
                @foreach($sliderImages as $index => $slide)
                    <button class="dot {{ $index === 0 ? 'active' : '' }}" onclick="currentSlide({{ $index + 1 }})"></button>
                @endforeach
            </div>
            @endif
        </div>
        
        <!-- Statistics Section -->
        <div class="hero-stats-section">
            <div class="container">
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">{{ number_format(App\Models\User::where('role', 'customer')->count()) }}+</span>
                        <span class="stat-label">Happy Customers </span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ number_format(App\Models\Auction::where('status', 'ended')->count()) }}+</span>
                        <span class="stat-label">Items Sold</span>
                    </div>
                    <div class="stat-item">
                        @php
                            $totalSavings = App\Models\Auction::where('status', 'ended')
                                ->whereNotNull('winner_id')->sum(DB::raw('retailPrice - currentPrice'));
                        @endphp
                        <span class="stat-number">AED {{ number_format($totalSavings)."0000" }}+</span>
                        <span class="stat-label">Customer Savings</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @else
    <!-- Default Hero Section (when no slider images) -->
    <section class="hero">
        <div class="container">
            <h1>Bid. Win. Save.</h1>
            <p>Experience the thrill of winning high-end products at a fraction of their retail price with Pixelllo, the premier online auction platform. Join thousands of satisfied customers who have saved up to 90% on brand new products!</p>

            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-number">{{ number_format(App\Models\User::where('role', 'customer')->count()) }}+</span>
                    <span class="stat-label">Happy Customers</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ number_format(App\Models\Auction::where('status', 'ended')->count()) }}+</span>
                    <span class="stat-label">Items Sold</span>
                </div>
                <div class="stat-item">
                    @php
                        
                            $totalSavings = App\Models\Auction::where('status', 'ended')
                                ->whereNotNull('winner_id')->sum(DB::raw('retailPrice - currentPrice'));
                    @endphp
                    <span class="stat-number">AED {{ number_format($totalSavings) }}+</span>
                    <span class="stat-label">Customer Savings</span>
                </div>
            </div>

            <a href="{{ route('auctions') }}" class="btn btn-primary">Start Bidding Now</a>
            <a href="{{ route('how-it-works') }}" class="btn btn-outline btn-hero-secondary">How It Works</a>
        </div>
    </section>
    @endif
    
    <!-- Featured Auctions -->
    <section class="featured-auctions" id="featured-auctions">
        <div class="container">
            <div class="section-title">
                <h2>Featured Auctions</h2>
                <p class="section-subtitle">Check out our most popular auctions currently live on Pixelllo. Don't miss your chance to bid and win!</p>
            </div>

            <div class="auction-tabs">
                <button class="auction-tab active" data-tab="live">Live Auctions</button>
                <button class="auction-tab" data-tab="upcoming">Upcoming</button>
                <button class="auction-tab" data-tab="ended">Ended Auctions</button>
            </div>

            <!-- Auction Tabs Content -->
            <div class="auction-tabs-content">
                <!-- Live Auctions Tab Panel -->
                <div class="auction-tab-panel active" id="live-tab">
                    <div class="auctions-grid">
                        @forelse($featuredAuctions as $auction)
                        @php
                            $now = now();
                            $isActive = $auction->status === 'active' && $auction->endTime > $now;
                            $isClosed = $auction->status === 'ended' || $auction->endTime < $now;

                            // Skip closed auctions in the Live tab
                            if ($isClosed) continue;

                            // Calculate time remaining using proper logic from auctionDetail
                            $timeLeft = '';
                            $timeProgress = 0;

                            if ($auction->endTime && $auction->startTime) {
                                if ($now >= $auction->endTime) {
                                    // Auction has ended
                                    $timeProgress = 100;
                                    $timeLeft = 'ENDED';
                                } elseif ($now >= $auction->startTime && $now < $auction->endTime) {
                                    // Auction is running - calculate proper countdown
                                    $totalDuration = $auction->startTime->diffInSeconds($auction->endTime);
                                    $elapsed = $auction->startTime->diffInSeconds($now);

                                    if ($totalDuration > 0) {
                                        $timeProgress = min(100, max(0, ($elapsed / $totalDuration) * 100));
                                    }

                                    // Calculate time remaining in seconds
                                    $secondsLeft = $now->diffInSeconds($auction->endTime);

                                    // Format time remaining
                                    $days = floor($secondsLeft / 86400);
                                    $hours = floor(($secondsLeft % 86400) / 3600);
                                    $minutes = floor(($secondsLeft % 3600) / 60);
                                    $secs = $secondsLeft % 60;

                                    $parts = [];
                                    if ($days > 0) $parts[] = $days . 'd';
                                    if ($hours > 0 || $days > 0) $parts[] = $hours . 'h';
                                    if ($minutes > 0 || $hours > 0 || $days > 0) $parts[] = $minutes . 'm';
                                    $parts[] = $secs . 's';

                                    $timeLeft = implode(' ', $parts);
                                } elseif ($now < $auction->startTime) {
                                    // Upcoming auction
                                    $timeProgress = 0;
                                    $timeLeft = 'Not Started';
                                }
                            }

                            $urgentTime = isset($secondsLeft) && $secondsLeft < 600 && $isActive; // Less than 10 minutes

                            // Calculate savings
                            $savings = 0;
                            if ($auction->retailPrice > 0 && $auction->currentPrice > 0) {
                                $savings = 100 - (($auction->currentPrice / $auction->retailPrice) * 100);
                            }
                        @endphp
                        <div class="auction-card">
                            <div class="auction-badge {{ $auction->featured ? 'featured' : 'active' }}">
                                {{ $auction->featured ? 'Featured' : 'Live' }}
                            </div>
                            <div class="auction-image-container">
                                @if(isset($auction->images) && is_array($auction->images) && count($auction->images) > 0)
                                    <img src="{{ asset('storage/' . $auction->images[0]) }}" alt="{{ $auction->title }}" class="auction-image" onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                                @else
                                    <img src="{{ asset('images/placeholders/product-placeholder.svg') }}" alt="{{ $auction->title }}" class="auction-image">
                                @endif
                                <div class="auction-overlay">
                                    <span class="auction-bids"><i class="fas fa-gavel"></i> {{ $auction->bids->count() }} bids</span>
                                    <span class="auction-watchers"><i class="fas fa-eye"></i> {{ rand(50, 150) }} watching</span>
                                </div>
                            </div>
                            <div class="auction-content">
                                <div class="auction-category">{{ $auction->category->name }}</div>
                                <h3 class="auction-title">
                                    <a href="{{ route('auction.detail', $auction->id) }}">{{ $auction->title }}</a>
                                </h3>
                                <div class="auction-info">
                                    <span class="auction-price">Current Bid: AED {{ number_format($auction->currentPrice, 2) }}</span>
                                    <span class="auction-time {{ $urgentTime ? 'urgent' : '' }}">
                                        <i class="fas fa-clock"></i> {{ $timeLeft }}
                                    </span>
                                </div>
                                <div class="auction-time-details">
                                    <span class="auction-start-time">
                                        Started: {{ $auction->startTime->format('M j, g:i A') }}
                                    </span>
                                    <span class="auction-end-time">
                                        Ends: {{ $auction->endTime->format('M j, g:i A') }}
                                    </span>
                                </div>
                                <div class="auction-progress">
                                    <div class="progress-bar" style="width: {{ $timeProgress }}%;"></div>
                                </div>
                                <div class="auction-retail">
                                    <span class="retail-price">Retail Price: AED {{ number_format($auction->retailPrice, 2) }}</span>
                                    <span class="savings">You Save: {{ number_format($savings, 0) }}%</span>
                                </div>
                                <a href="{{ route('auction.detail', $auction->id) }}" class="btn btn-primary" style="width: 100%;">Bid Now</a>
                            </div>
                        </div>
                        @empty
                        <div class="no-auctions">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-gavel"></i>
                                </div>
                                <h3>No Live Auctions Available</h3>
                                <p>We're preparing exciting new auctions for you. Check back soon to start bidding!</p>
                                <a href="{{ route('auctions') }}" class="btn btn-outline empty-state-btn">Browse All Auctions</a>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
                
                <!-- Upcoming Auctions Tab Panel -->
                <div class="auction-tab-panel" id="upcoming-tab">
                    <div class="auctions-grid">
                        @forelse($upcomingAuctions as $auction)
                        @php
                            $startTime = $auction->startTime;
                            $startTimeMessage = 'Starts ';
                            if ($startTime->isToday()) {
                                $startTimeMessage .= 'today at ' . $startTime->format('g:i A');
                            } elseif ($startTime->isTomorrow()) {
                                $startTimeMessage .= 'tomorrow at ' . $startTime->format('g:i A');
                            } else {
                                $startTimeMessage .= 'in ' . round(now()->diffInDays($startTime),0) . ' days';
                            }
                        @endphp
                        <div class="auction-card">
                            <div class="auction-badge upcoming">Coming Soon</div>
                            <div class="auction-image-container">
                                @if(isset($auction->images) && is_array($auction->images) && count($auction->images) > 0)
                                    <img src="{{ asset('storage/' . $auction->images[0]) }}" alt="{{ $auction->title }}" class="auction-image" onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                                @else
                                    <img src="{{ asset('images/placeholders/product-placeholder.svg') }}" alt="{{ $auction->title }}" class="auction-image">
                                @endif
                                <div class="auction-overlay">
                                    <span class="auction-watchers"><i class="fas fa-eye"></i> {{ rand(50, 200) }} watching</span>
                                </div>
                            </div>
                            <div class="auction-content">
                                <div class="auction-category">{{ $auction->category->name }}</div>
                                <h3 class="auction-title"><a href="{{ route('auction.detail', $auction->id) }}">{{ $auction->title }}</a></h3>
                                <div class="auction-info">
                                    <span class="auction-price">Starting Bid: AED {{ number_format($auction->startingPrice, 2) }}</span>
                                    <span class="auction-time">
                                        <i class="fas fa-calendar"></i> {{ $startTimeMessage }}
                                    </span>
                                </div>
                                <div class="auction-time-details">
                                    <span class="auction-start-time">
                                        Starts: {{ $auction->startTime->format('M j, g:i A') }}
                                    </span>
                                    <span class="auction-end-time">
                                        Est. End: {{ $auction->endTime->format('M j, g:i A') }}
                                    </span>
                                </div>
                                <div class="auction-progress">
                                    <div class="progress-bar" style="width: 0%;"></div>
                                </div>
                                <div class="auction-retail">
                                    <span class="retail-price">Retail Price: AED {{ number_format($auction->retailPrice, 2) }}</span>
                                    <span class="notify-btn">Get Notified</span>
                                </div>
                                <a href="{{ route('auction.detail', $auction->id) }}" class="btn btn-outline" style="width: 100%;">View Details</a>
                            </div>
                        </div>
                        @empty
                        <div class="no-auctions">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <h3>No Upcoming Auctions Scheduled</h3>
                                <p>We're working on scheduling new exciting auctions. Subscribe to our newsletter to be notified when new auctions are added!</p>
                                <a href="#" class="btn btn-outline empty-state-btn">Subscribe to Updates</a>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
                
                <!-- Ended Auctions Tab Panel -->
                <div class="auction-tab-panel" id="ended-tab">
                    <div class="auctions-grid">
                        @forelse($endedAuctions as $auction)
                        @php
                            $isClosed = true;
                            
                            // Calculate savings
                            $savings = 0;
                            if ($auction->retailPrice > 0 && $auction->currentPrice > 0) {
                                $savings = 100 - (($auction->currentPrice / $auction->retailPrice) * 100);
                            }
                        @endphp
                        <div class="auction-card closed">
                            <div class="auction-badge closed">Closed</div>
                            <div class="auction-image-container">
                                @if(isset($auction->images) && is_array($auction->images) && count($auction->images) > 0)
                                    <img src="{{ asset('storage/' . $auction->images[0]) }}" alt="{{ $auction->title }}" class="auction-image" onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                                @else
                                    <img src="{{ asset('images/placeholders/product-placeholder.svg') }}" alt="{{ $auction->title }}" class="auction-image">
                                @endif
                                <div class="auction-overlay">
                                    <span class="auction-bids"><i class="fas fa-gavel"></i> {{ $auction->bids->count() }} bids</span>
                                    <span class="auction-watchers"><i class="fas fa-eye"></i> {{ rand(50, 150) }} watching</span>
                                </div>
                            </div>
                            <div class="auction-content">
                                <div class="auction-category">{{ $auction->category->name }}</div>
                                <h3 class="auction-title"><a href="{{ route('auction.detail', $auction->id) }}">{{ $auction->title }}</a></h3>
                                <div class="auction-info">
                                    <span class="auction-price">Final Price: AED {{ number_format($auction->currentPrice, 2) }}</span>
                                    <span class="auction-time">
                                        <i class="fas fa-clock"></i> Ended
                                    </span>
                                </div>
                                <div class="auction-time-details">
                                    <span class="auction-start-time">
                                        Started: {{ $auction->startTime->format('M j, g:i A') }}
                                    </span>
                                    <span class="auction-end-time">
                                        Ended: {{ $auction->endTime->format('M j, g:i A') }}
                                    </span>
                                </div>
                                <div class="auction-progress">
                                    <div class="progress-bar" style="width: 100%;"></div>
                                </div>
                                <div class="auction-retail">
                                    <span class="retail-price">Retail Price: AED {{ number_format($auction->retailPrice, 2) }}</span>
                                    <span class="savings">Saved: {{ number_format($savings, 0) }}%</span>
                                </div>
                                <a href="{{ route('auction.detail', $auction->id) }}" class="btn btn-outline" style="width: 100%;">View Details</a>
                            </div>
                        </div>
                        @empty
                        <div class="no-auctions">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-history"></i>
                                </div>
                                <h3>No Ended Auctions Yet</h3>
                                <p>We haven't had any auctions close recently. Participate in our active auctions to become one of our first winners!</p>
                                <a href="javascript:void(0)" onclick="document.querySelector('.auction-tab[data-tab=\'live\']').click()" class="btn btn-outline empty-state-btn">View Live Auctions</a>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="featured-auctions-footer">
                <a href="{{ route('auctions') }}" class="btn btn-outline">View All Auctions</a>
                <p class="featured-auctions-note">*All auctions have countdown timers that can be extended. Last bidder when the timer reaches zero wins!</p>
            </div>
        </div>
    </section>
    
    <!-- Winners Section -->
    <section class="winners-section" style="padding: 50px 0; background-color: #ffffff;">
        <div class="container">
            <div class="section-title">
                <h2>Recent Winners</h2>
                <p class="section-subtitle">See the amazing deals our customers have won recently</p>
            </div>

            <!-- Filters -->
            <div class="winners-filters" style="display: flex; justify-content: center; gap: 15px; margin-bottom: 40px;">
                <form method="GET" action="{{ route('winners') }}" id="winners-filter-form" style="display: flex; gap: 15px;">
                    <!-- Category Filter -->
                    <select name="category" class="filter-select" onchange="document.getElementById('winners-filter-form').submit()" style="padding: 8px 15px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; min-width: 150px;">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Time Period Filter -->
                    <select name="period" class="filter-select" onchange="document.getElementById('winners-filter-form').submit()" style="padding: 8px 15px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; min-width: 150px;">
                        <option value="">All Time</option>
                        <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="this-week" {{ request('period') == 'this-week' ? 'selected' : '' }}>This Week</option>
                        <option value="last-week" {{ request('period') == 'last-week' ? 'selected' : '' }}>Last Week</option>
                        <option value="this-month" {{ request('period') == 'this-month' ? 'selected' : '' }}>This Month</option>
                        <option value="last-month" {{ request('period') == 'last-month' ? 'selected' : '' }}>Last Month</option>
                    </select>
                </form>
            </div>

            <!-- Winners Grid -->
            <div class="winners-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px; margin-bottom: 40px;">
                @forelse($recentWinners as $winner)
                @php
                    $savings = $winner->retailPrice > 0 ? (($winner->retailPrice - $winner->currentPrice) / $winner->retailPrice * 100) : 0;
                @endphp
                <div class="winner-card" style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.3s;">
                    <div class="winner-image-container" style="position: relative; height: 200px; overflow: hidden;">
                        @if(isset($winner->images) && is_array($winner->images) && count($winner->images) > 0)
                            <img src="{{ asset('storage/' . $winner->images[0]) }}" alt="{{ $winner->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/placeholders/product-placeholder.svg') }}" alt="{{ $winner->title }}" style="width: 100%; height: 100%; object-fit: contain; padding: 20px;">
                        @endif
                        <div class="winner-badge" style="position: absolute; top: 10px; right: 10px; background: #28a745; color: white; padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">WON</div>
                    </div>
                    <div class="winner-content" style="padding: 20px;">
                        <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 10px; color: #333;">{{ $winner->title }}</h4>
                        <p style="color: #666; font-size: 14px; margin-bottom: 15px;">{{ $winner->category->name ?? 'Uncategorized' }}</p>
                        <div class="winner-details" style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span style="color: #999; font-size: 13px;">Retail: <del>AED {{ number_format($winner->retailPrice, 2) }}</del></span>
                            <span style="color: #28a745; font-weight: 600; font-size: 14px;">{{ number_format($savings, 0) }}% OFF</span>
                        </div>
                        <div class="winner-price" style="font-size: 18px; font-weight: 700; color: #ff5500; margin-bottom: 10px;">Won for AED {{ number_format($winner->currentPrice, 2) }}</div>
                        <div class="winner-info" style="display: flex; justify-content: space-between; font-size: 12px; color: #999;">
                            <span>{{ $winner->winner->name ?? 'Anonymous' }}</span>
                            <span>{{ $winner->endTime->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                    <div class="empty-state-icon" style="width: 80px; height: 80px; margin: 0 auto 20px; background: #f8f9fa; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-trophy" style="font-size: 32px; color: #ffc107;"></i>
                    </div>
                    <h3 style="font-size: 24px; margin-bottom: 10px;">No Winners Yet</h3>
                    <p style="color: #666; margin-bottom: 20px;">Be the first to win an amazing deal!</p>
                    <a href="{{ route('auctions') }}" class="btn btn-primary">Browse Active Auctions</a>
                </div>
                @endforelse
            </div>

            <!-- View All Button -->
            @if($recentWinners->count() > 0)
            <div style="text-align: center;">
                <a href="{{ route('winners') }}" class="btn btn-outline">View All Winners</a>
            </div>
            @endif
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories">
        <div class="container">
            <div class="section-title">
                <h2>Popular Categories</h2>
            </div>

            <div class="categories-grid" >
                @foreach($popularCategories as $category)
                <a href="{{ route('auctions') }}?category={{ $category->slug }}" class="category-card" style="text-decoration: none; color: inherit;">
                    <div class="category-image-container">
                        @if(isset($category->image) && $category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="category-image">
                        @else
                            <div class="category-image-placeholder">
                                <i class="fas {{ getCategoryIcon($category->name) }}"></i>
                            </div>
                        @endif
                    </div>
                    <h3 class="category-name">{{ $category->name }}</h3>
                    <p class="category-count">{{ $category->auction_count }} {{ $category->auction_count == 1 ? 'auction' : 'auctions' }}</p>
                </a>
                @endforeach

            </div>
        </div>
    </section>
    
@endsection

@section('scripts')
    <script>
        // Slider Variables
        let currentSlideIndex = 0;
        let slides = [];
        let dots = [];
        let slideInterval;

        // Slider Functions
        function initializeSlider() {
            slides = document.querySelectorAll('.slide');
            dots = document.querySelectorAll('.dot');
            
            if (slides.length > 0) {
                // Set first slide as active
                showSlide(0);
                
                // Start auto-play if there are multiple slides
                if (slides.length > 1) {
                    startAutoPlay();
                }
            }
        }

        function showSlide(index) {
            // Hide all slides
            slides.forEach(slide => {
                slide.classList.remove('active');
            });
            
            // Remove active class from all dots
            dots.forEach(dot => {
                dot.classList.remove('active');
            });
            
            // Show the selected slide
            if (slides[index]) {
                slides[index].classList.add('active');
                currentSlideIndex = index;
            }
            
            // Activate the corresponding dot
            if (dots[index]) {
                dots[index].classList.add('active');
            }
        }

        function changeSlide(direction) {
            let newIndex = currentSlideIndex + direction;
            
            // Wrap around if necessary
            if (newIndex >= slides.length) {
                newIndex = 0;
            } else if (newIndex < 0) {
                newIndex = slides.length - 1;
            }
            
            showSlide(newIndex);
            
            // Restart auto-play
            restartAutoPlay();
        }

        function currentSlide(index) {
            showSlide(index - 1); // Convert to 0-based index
            restartAutoPlay();
        }

        function startAutoPlay() {
            slideInterval = setInterval(() => {
                changeSlide(1);
            }, 5000); // Change slide every 5 seconds
        }

        function stopAutoPlay() {
            if (slideInterval) {
                clearInterval(slideInterval);
            }
        }

        function restartAutoPlay() {
            stopAutoPlay();
            if (slides.length > 1) {
                startAutoPlay();
            }
        }

        // Pause auto-play when hovering over slider
        function setupSliderEvents() {
            const sliderContainer = document.querySelector('.slider-container');
            if (sliderContainer) {
                sliderContainer.addEventListener('mouseenter', stopAutoPlay);
                sliderContainer.addEventListener('mouseleave', () => {
                    if (slides.length > 1) {
                        startAutoPlay();
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Slider
            initializeSlider();
            setupSliderEvents();
            
            // Initialize Featured Auctions Tabs
            const initializeTabs = () => {
                // Get all elements
                const tabContainer = document.querySelector('.auction-tabs');
                const tabsContent = document.querySelector('.auction-tabs-content');
                const auctionTabs = document.querySelectorAll('.auction-tab');
                const auctionTabPanels = document.querySelectorAll('.auction-tab-panel');

                // Make sure all elements are found
                if (!tabContainer || !tabsContent || auctionTabs.length === 0 || auctionTabPanels.length === 0) {
                    console.error('Auction tabs or panels not found!');
                    return;
                }

                // Ensure tab content is visible
                tabsContent.style.display = 'block';

                // Hide all panels first
                auctionTabPanels.forEach(panel => {
                    panel.classList.remove('active');
                    panel.style.display = 'none';
                });

                // Reset all tabs
                auctionTabs.forEach(tab => {
                    tab.classList.remove('active');
                });

                // Set the first tab and panel as active by default
                auctionTabs[0].classList.add('active');
                const firstPanelId = auctionTabs[0].getAttribute('data-tab') + '-tab';
                const firstPanel = document.getElementById(firstPanelId);

                if (firstPanel) {
                    firstPanel.classList.add('active');
                    firstPanel.style.display = 'block';
                }

                // Add click event listeners to each tab
                auctionTabs.forEach(tab => {
                    tab.addEventListener('click', function(e) {
                        e.preventDefault();

                        // Get the tab ID
                        const tabId = this.getAttribute('data-tab');
                        const targetPanelId = tabId + '-tab';
                        const targetPanel = document.getElementById(targetPanelId);

                        // Check if the panel exists
                        if (!targetPanel) {
                            console.error(`Panel with ID "${targetPanelId}" not found!`);
                            return;
                        }

                        // Remove active class from all tabs
                        auctionTabs.forEach(t => t.classList.remove('active'));

                        // Add active class to clicked tab
                        this.classList.add('active');

                        // Hide all panels
                        auctionTabPanels.forEach(panel => {
                            panel.classList.remove('active');
                            panel.style.display = 'none';
                        });

                        // Show the corresponding panel
                        targetPanel.classList.add('active');
                        targetPanel.style.display = 'block';
                    });
                });
            };

            // Initialize tabs
            initializeTabs();
            
            // Add functionality to the Get Notified buttons
            document.querySelectorAll('.notify-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const auctionTitle = this.closest('.auction-content').querySelector('.auction-title').textContent.trim();
                    alert(`You will be notified when "${auctionTitle}" becomes available for bidding!`);
                });
            });
        });
    </script>
@endsection