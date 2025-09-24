@extends('layouts.app')

@section('title', 'Categories - ' . config('app.name'))

@section('content')
<div class="categories-page">
    <div class="container">
        <div class="page-header">
            <h1>Browse Categories</h1>
            <p class="page-description">Explore products by category and find your next amazing deal. We have a wide range of items in various categories for you to bid on.</p>
        </div>

        @if($featuredCategories->count() > 0)
        <div class="featured-categories">
            <h2 class="section-title">Featured Categories</h2>
            <div class="categories-grid featured">
                @foreach($featuredCategories as $category)
                <div class="category-card featured">
                    <div class="category-image-container">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                        @else
                            <img src="https://images.unsplash.com/photo-1550009158-9ebf69173e03?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1101&q=80" alt="{{ $category->name }}">
                        @endif
                        <div class="category-overlay">
                            <h3>{{ $category->name }}</h3>
                            <p>{{ $category->description ?: 'Explore amazing deals in this category' }}</p>
                        </div>
                    </div>
                    <div class="category-stats">
                        <div class="stat">
                            <span class="stat-value">{{ $category->active_auction_count }}+</span>
                            <span class="stat-label">Active Auctions</span>
                        </div>
                        <div class="stat">
                            <span class="stat-value">{{ $category->auction_count }}</span>
                            <span class="stat-label">Total Auctions</span>
                        </div>
                    </div>
                    <a href="{{ route('auctions') }}?category={{ $category->slug }}" class="btn btn-primary btn-block">Browse Auctions</a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="all-categories">
            <h2 class="section-title">{{ $featuredCategories->count() > 0 ? 'All Categories' : 'Categories' }}</h2>
            <div class="categories-grid">
                @forelse($categories as $category)
                <div class="category-card">
                    <div class="category-image">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                        @else
                            <img src="https://images.unsplash.com/photo-1472214103451-9374bd1c798e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1101&q=80" alt="{{ $category->name }}">
                        @endif
                        @if($category->featured)
                        <div class="featured-badge">
                            <i class="fas fa-star"></i> Featured
                        </div>
                        @endif
                    </div>
                    <div class="category-content">
                        <h3>{{ $category->name }}</h3>
                        <p>{{ $category->active_auction_count }} active auctions</p>
                        <a href="{{ route('auctions') }}?category={{ $category->slug }}" class="category-link">View Auctions <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <p class="text-muted">No categories available at the moment.</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="category-cta">
            <div class="cta-content">
                <h2>Can't Find What You're Looking For?</h2>
                <p>Browse all our auctions or use the search to find specific items.</p>
                <div class="cta-buttons">
                    <a href="{{ route('auctions') }}" class="btn btn-primary">View All Auctions</a>
                    <a href="{{ route('how-it-works') }}" class="btn btn-outline">How It Works</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.categories-page {
    padding: 40px 0 60px;
}

.page-header {
    text-align: center;
    margin-bottom: 40px;
}

.page-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 15px;
    color: var(--dark);
}

.page-description {
    font-size: 1.1rem;
    color: var(--secondary-text);
    max-width: 700px;
    margin: 0 auto;
}

.section-title {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 30px;
    color: var(--dark);
    text-align: center;
}

.featured-categories {
    margin-bottom: 60px;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
    margin-bottom: 20px;
}

.categories-grid.featured {
    grid-template-columns: repeat(3, 1fr);
}

.category-card {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
    background-color: white;
    transition: transform 0.3s, box-shadow 0.3s;
    position: relative;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
}

.category-card.featured {
    display: flex;
    flex-direction: column;
}

.category-image-container {
    height: 200px;
    position: relative;
    overflow: hidden;
}

.category-image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.category-card:hover .category-image-container img {
    transform: scale(1.05);
}

.category-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 20px;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
    color: white;
}

.category-overlay h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 5px;
}

.category-overlay p {
    font-size: 0.9rem;
    opacity: 0.9;
}

.category-stats {
    display: flex;
    padding: 15px;
    border-bottom: 1px solid #eee;
}

.stat {
    flex: 1;
    text-align: center;
}

.stat-value {
    display: block;
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--secondary-color);
}

.stat-label {
    font-size: 0.8rem;
    color: var(--secondary-text);
}

.btn-block {
    display: block;
    width: calc(100% - 30px);
    margin: 15px;
    text-align: center;
    padding: 10px 20px;
    text-decoration: none;
}

.category-image {
    height: 150px;
    overflow: hidden;
    position: relative;
}

.category-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.category-card:hover .category-image img {
    transform: scale(1.05);
}

.featured-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #ffc107;
    color: #000;
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.featured-badge i {
    font-size: 0.7rem;
}

.category-content {
    padding: 15px;
}

.category-content h3 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 5px;
    color: var(--dark);
}

.category-content p {
    font-size: 0.9rem;
    color: var(--secondary-text);
    margin-bottom: 10px;
}

.category-link {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--secondary-color);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.category-link i {
    margin-left: 5px;
    transition: transform 0.2s;
}

.category-link:hover i {
    transform: translateX(3px);
}

.category-cta {
    margin-top: 60px;
    background-color: #f9f9f9;
    border-radius: 10px;
    padding: 40px;
    text-align: center;
}

.category-cta h2 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 15px;
    color: var(--dark);
}

.category-cta p {
    font-size: 1.1rem;
    color: var(--secondary-text);
    margin-bottom: 20px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.cta-buttons {
    display: flex;
    justify-content: center;
    gap: 15px;
}

.btn {
    padding: 10px 25px;
    border-radius: 5px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
    display: inline-block;
}

.btn-primary {
    background-color: var(--secondary-color);
    color: white;
    border: 2px solid var(--secondary-color);
}

.btn-primary:hover {
    background-color: #d97706;
    border-color: #d97706;
}

.btn-outline {
    background-color: transparent;
    color: var(--secondary-color);
    border: 2px solid var(--secondary-color);
}

.btn-outline:hover {
    background-color: var(--secondary-color);
    color: white;
}

@media (max-width: 992px) {
    .categories-grid.featured {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    }
}

@media (max-width: 768px) {
    .page-header h1 {
        font-size: 2rem;
    }

    .categories-grid {
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    }

    .category-cta {
        padding: 30px 20px;
    }

    .cta-buttons {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
    }
}

@media (max-width: 576px) {
    .categories-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection