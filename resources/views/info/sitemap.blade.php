@extends('layouts.app')

@section('title', 'Sitemap - ' . config('app.name'))

@section('content')
<div class="sitemap-page">
    <div class="hero-banner">
        <div class="container">
            <h1>Sitemap</h1>
            <p class="hero-description">A complete map of all pages on the Pixelllo website.</p>
        </div>
    </div>
    
    <div class="container">
        <div class="sitemap-intro">
            <p>Welcome to the Pixelllo sitemap. This page provides a comprehensive overview of all sections and pages available on our website to help you navigate and find exactly what you're looking for.</p>
        </div>
        
        <div class="sitemap-grid">
            <div class="sitemap-section">
                <div class="section-header">
                    <h2>Main Pages</h2>
                </div>
                <ul class="sitemap-links">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ url('/auctions') }}">All Auctions</a></li>
                    <li><a href="{{ url('/categories') }}">Categories</a></li>
                    <li><a href="{{ url('/how-it-works') }}">How It Works</a></li>
                    <li><a href="{{ url('/winners') }}">Winners</a></li>
                </ul>
            </div>
            
            <div class="sitemap-section">
                <div class="section-header">
                    <h2>User Account</h2>
                </div>
                <ul class="sitemap-links">
                    <li><a href="{{ url('/login') }}">Login</a></li>
                    <li><a href="{{ url('/register') }}">Register</a></li>
                    <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ url('/dashboard/auctions') }}">My Active Auctions</a></li>
                    <li><a href="{{ url('/dashboard/watchlist') }}">My Watchlist</a></li>
                    <li><a href="{{ url('/dashboard/wins') }}">My Wins</a></li>
                    <li><a href="{{ url('/dashboard/history') }}">Bid History</a></li>
                    <li><a href="{{ url('/dashboard/orders') }}">My Orders</a></li>
                    <li><a href="{{ url('/dashboard/settings') }}">Account Settings</a></li>
                </ul>
            </div>
            
            <div class="sitemap-section">
                <div class="section-header">
                    <h2>Auction Categories</h2>
                </div>
                <ul class="sitemap-links">
                    <li><a href="{{ url('/categories/electronics') }}">Electronics</a></li>
                    <li><a href="{{ url('/categories/home-garden') }}">Home & Garden</a></li>
                    <li><a href="{{ url('/categories/fashion') }}">Fashion & Accessories</a></li>
                    <li><a href="{{ url('/categories/jewelry') }}">Jewelry & Watches</a></li>
                    <li><a href="{{ url('/categories/sports') }}">Sports & Outdoors</a></li>
                    <li><a href="{{ url('/categories/toys-games') }}">Toys & Games</a></li>
                    <li><a href="{{ url('/categories/travel') }}">Travel & Experiences</a></li>
                    <li><a href="{{ url('/categories/vehicles') }}">Vehicles & Accessories</a></li>
                    <li><a href="{{ url('/categories/gift-cards') }}">Gift Cards</a></li>
                </ul>
            </div>
            
            <div class="sitemap-section">
                <div class="section-header">
                    <h2>Information</h2>
                </div>
                <ul class="sitemap-links">
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('terms') }}">Terms of Service</a></li>
                    <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('shipping') }}">Shipping & Returns</a></li>
                    <li><a href="{{ route('faq') }}">FAQ</a></li>
                    <li><a href="{{ route('help') }}">Help Center</a></li>
                    <li><a href="{{ url('/blog') }}">Blog</a></li>
                    <li><a href="{{ url('/press') }}">Press Releases</a></li>
                    <li><a href="{{ url('/careers') }}">Careers</a></li>
                </ul>
            </div>
            
            <div class="sitemap-section">
                <div class="section-header">
                    <h2>Support & Contact</h2>
                </div>
                <ul class="sitemap-links">
                    <li><a href="{{ url('/contact') }}">Contact Us</a></li>
                    <li><a href="{{ url('/support-tickets') }}">Support Tickets</a></li>
                    <li><a href="{{ url('/feedback') }}">Feedback</a></li>
                    <li><a href="{{ url('/report-issue') }}">Report an Issue</a></li>
                    <li><a href="{{ url('/partnerships') }}">Business Partnerships</a></li>
                </ul>
            </div>
            
            <div class="sitemap-section">
                <div class="section-header">
                    <h2>Legal Pages</h2>
                </div>
                <ul class="sitemap-links">
                    <li><a href="{{ url('/legal/user-agreement') }}">User Agreement</a></li>
                    <li><a href="{{ url('/legal/auction-rules') }}">Auction Rules</a></li>
                    <li><a href="{{ url('/legal/cookies') }}">Cookie Policy</a></li>
                    <li><a href="{{ url('/legal/accessibility') }}">Accessibility Statement</a></li>
                    <li><a href="{{ url('/legal/copyright') }}">Copyright Notice</a></li>
                </ul>
            </div>
            
            <div class="sitemap-section">
                <div class="section-header">
                    <h2>Special Auctions</h2>
                </div>
                <ul class="sitemap-links">
                    <li><a href="{{ url('/auctions/featured') }}">Featured Auctions</a></li>
                    <li><a href="{{ url('/auctions/new') }}">New Arrivals</a></li>
                    <li><a href="{{ url('/auctions/ending-soon') }}">Ending Soon</a></li>
                    <li><a href="{{ url('/auctions/penny-start') }}">Penny Start Auctions</a></li>
                    <li><a href="{{ url('/auctions/free-shipping') }}">Free Shipping Auctions</a></li>
                </ul>
            </div>
            
            <div class="sitemap-section">
                <div class="section-header">
                    <h2>Community</h2>
                </div>
                <ul class="sitemap-links">
                    <li><a href="{{ url('/testimonials') }}">User Testimonials</a></li>
                    <li><a href="{{ url('/success-stories') }}">Success Stories</a></li>
                    <li><a href="{{ url('/refer-friend') }}">Refer a Friend</a></li>
                    <li><a href="{{ url('/social-media') }}">Social Media</a></li>
                    <li><a href="{{ url('/community-guidelines') }}">Community Guidelines</a></li>
                </ul>
            </div>
            
            <div class="sitemap-section">
                <div class="section-header">
                    <h2>Resources</h2>
                </div>
                <ul class="sitemap-links">
                    <li><a href="{{ url('/resources/bidding-strategies') }}">Bidding Strategies</a></li>
                    <li><a href="{{ url('/resources/auction-guides') }}">Auction Guides</a></li>
                    <li><a href="{{ url('/resources/tutorials') }}">Video Tutorials</a></li>
                    <li><a href="{{ url('/resources/glossary') }}">Auction Terminology</a></li>
                    <li><a href="{{ url('/resources/tips') }}">Tips for New Users</a></li>
                </ul>
            </div>
        </div>
        
        <div class="sitemap-footer">
            <h2>Looking for Something Specific?</h2>
            <div class="search-container">
                <input type="text" placeholder="Search the entire website...">
                <button type="button"><i class="fas fa-search"></i> Search</button>
            </div>
            <p class="sitemap-note">Can't find what you're looking for? <a href="{{ route('help') }}">Contact our support team</a> for assistance.</p>
        </div>
    </div>
</div>

<style>
    .sitemap-page {
        padding-bottom: 60px;
    }
    
    .hero-banner {
        background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
            url('https://images.unsplash.com/photo-1511174511562-5f7f82f97c5f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 60px 0;
        text-align: center;
        margin-bottom: 40px;
    }
    
    .hero-banner h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 15px;
    }
    
    .hero-description {
        font-size: 1.1rem;
        max-width: 700px;
        margin: 0 auto;
    }
    
    .sitemap-intro {
        text-align: center;
        max-width: 800px;
        margin: 0 auto 40px;
    }
    
    .sitemap-intro p {
        font-size: 1.05rem;
        line-height: 1.6;
        color: var(--secondary-text);
    }
    
    .sitemap-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        margin-bottom: 50px;
    }
    
    .sitemap-section {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        padding: 25px;
        transition: transform 0.3s;
    }
    
    .sitemap-section:hover {
        transform: translateY(-5px);
    }
    
    .section-header {
        margin-bottom: 20px;
        border-bottom: 2px solid var(--secondary-color);
        padding-bottom: 10px;
    }
    
    .section-header h2 {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
    }
    
    .sitemap-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .sitemap-links li {
        margin-bottom: 12px;
        position: relative;
        padding-left: 15px;
    }
    
    .sitemap-links li:before {
        content: '•';
        color: var(--secondary-color);
        position: absolute;
        left: 0;
        top: 0;
    }
    
    .sitemap-links a {
        color: var(--secondary-text);
        text-decoration: none;
        font-size: 0.95rem;
        transition: color 0.3s;
    }
    
    .sitemap-links a:hover {
        color: var(--secondary-color);
    }
    
    .sitemap-footer {
        background-color: #f9f9f9;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
    }
    
    .sitemap-footer h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--dark);
    }
    
    .search-container {
        display: flex;
        max-width: 600px;
        margin: 0 auto 20px;
        border-radius: 50px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }
    
    .search-container input {
        flex: 1;
        padding: 15px 20px;
        border: 1px solid #ddd;
        border-right: none;
        border-radius: 50px 0 0 50px;
        font-size: 1rem;
    }
    
    .search-container input:focus {
        outline: none;
    }
    
    .search-container button {
        padding: 0 20px;
        background-color: var(--secondary-color);
        color: white;
        border: none;
        border-radius: 0 50px 50px 0;
        cursor: pointer;
        transition: background-color 0.3s;
        font-size: 1rem;
        font-weight: 500;
    }
    
    .search-container button:hover {
        background-color: var(--accent-color);
    }
    
    .sitemap-note {
        font-size: 0.95rem;
        color: var(--secondary-text);
        margin-top: 20px;
    }
    
    .sitemap-note a {
        color: var(--secondary-color);
        text-decoration: none;
        font-weight: 500;
    }
    
    /* Responsive Styles */
    @media (max-width: 992px) {
        .sitemap-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .hero-banner h1 {
            font-size: 2rem;
        }
        
        .sitemap-grid {
            grid-template-columns: 1fr;
        }
        
        .section-header h2 {
            font-size: 1.2rem;
        }
        
        .sitemap-footer h2 {
            font-size: 1.3rem;
        }
        
        .search-container {
            flex-direction: column;
            border-radius: 10px;
            overflow: visible;
            box-shadow: none;
        }
        
        .search-container input {
            border-radius: 10px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }
        
        .search-container button {
            border-radius: 10px;
            padding: 12px 0;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Simple search functionality (just for demonstration)
        const searchContainer = document.querySelector('.search-container');
        const searchInput = searchContainer.querySelector('input');
        const searchButton = searchContainer.querySelector('button');
        
        if (searchButton) {
            searchButton.addEventListener('click', function() {
                if (searchInput.value.trim() !== '') {
                    alert('Search functionality would redirect to search results for: ' + searchInput.value);
                }
            });
        }
        
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && this.value.trim() !== '') {
                    alert('Search functionality would redirect to search results for: ' + this.value);
                }
            });
        }
    });
</script>
@endsection