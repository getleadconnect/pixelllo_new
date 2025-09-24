@extends('layouts.app')

@section('title', 'Bidding & Auctions Help - ' . config('app.name'))

@section('content')
<div class="help-topic-page">
    <div class="container">
        <div class="help-topic-header">
            <h1>Bidding & Auctions Help</h1>
            <div class="breadcrumbs">
                <a href="{{ url('/') }}">Home</a> &gt; 
                <a href="{{ url('/help') }}">Help Center</a> &gt; 
                <span>Bidding & Auctions</span>
            </div>
        </div>

        <div class="help-topic-wrapper">
            <div class="help-topic-sidebar">
                <div class="sidebar-section">
                    <h3>Bidding & Auctions</h3>
                    <ul>
                        <li><a href="#how-to-bid" class="active">How to place bids</a></li>
                        <li><a href="#auction-timers">Understanding auction timers</a></li>
                        <li><a href="#auto-bidder">Using the Auto-Bidder feature</a></li>
                        <li><a href="#auction-alerts">Setting up auction alerts</a></li>
                        <li><a href="#watchlist">Watchlist management</a></li>
                        <li><a href="#strategies">Bidding strategies and tips</a></li>
                    </ul>
                </div>
                <div class="sidebar-section">
                    <h3>Related Topics</h3>
                    <ul>
                        <li><a href="{{ url('/help/account') }}">Account & Registration</a></li>
                        <li><a href="{{ url('/help/payment') }}">Payments & Billing</a></li>
                        <li><a href="{{ url('/help/winning') }}">Winning & Orders</a></li>
                    </ul>
                </div>
                <div class="need-help-box">
                    <h3>Need More Help?</h3>
                    <p>Can't find what you're looking for? Our support team is ready to assist you.</p>
                    <a href="{{ url('/help#contact-form') }}" class="btn btn-primary">Contact Support</a>
                </div>
            </div>

            <div class="help-topic-content">
                <section id="how-to-bid" class="help-section">
                    <h2>How to Place Bids</h2>
                    <p>Placing bids on Pixelllo is quick and easy. Follow these steps to start bidding:</p>
                    
                    <div class="help-steps">
                        <div class="help-step">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h3>Find an Auction</h3>
                                <p>Browse through our available auctions and find one that interests you. You can filter auctions by category, price, or status.</p>
                                <img src="{{ asset('images/help/find-auction.jpg') }}" alt="Finding an auction" class="step-image">
                            </div>
                        </div>
                        
                        <div class="help-step">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h3>Check Your Bid Balance</h3>
                                <p>Make sure you have enough bid credits in your account. Your current balance is displayed at the top of your dashboard and on the auction page.</p>
                                <img src="{{ asset('images/help/bid-balance.jpg') }}" alt="Checking bid balance" class="step-image">
                            </div>
                        </div>
                        
                        <div class="help-step">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h3>Click "Bid Now"</h3>
                                <p>On the auction page, click the "Bid Now" button to place a bid. Each bid will increase the auction price by the bid increment amount and extend the auction timer.</p>
                                <img src="{{ asset('images/help/bid-now.jpg') }}" alt="Clicking Bid Now button" class="step-image">
                            </div>
                        </div>
                        
                        <div class="help-step">
                            <div class="step-number">4</div>
                            <div class="step-content">
                                <h3>Confirm Your Bid</h3>
                                <p>A confirmation dialog will appear. Confirm your bid to proceed. One bid credit will be deducted from your account.</p>
                                <img src="{{ asset('images/help/confirm-bid.jpg') }}" alt="Confirming bid" class="step-image">
                            </div>
                        </div>
                        
                        <div class="help-step">
                            <div class="step-number">5</div>
                            <div class="step-content">
                                <h3>Monitor the Auction</h3>
                                <p>Keep an eye on the auction timer and current price. If you're outbid, you'll need to place another bid to stay in the auction.</p>
                                <img src="{{ asset('images/help/monitor-auction.jpg') }}" alt="Monitoring auction" class="step-image">
                            </div>
                        </div>
                    </div>
                    
                    <div class="help-note">
                        <h4>Important Note:</h4>
                        <p>Each bid costs one bid credit regardless of the auction's current price. Bid credits are non-refundable once used, even if you don't win the auction.</p>
                    </div>
                </section>
                
                <section id="auction-timers" class="help-section">
                    <h2>Understanding Auction Timers</h2>
                    <p>Auction timers are a crucial element of the Pixelllo bidding system. Here's what you need to know:</p>
                    
                    <div class="help-subsection">
                        <h3>How Timers Work</h3>
                        <p>Each auction starts with an initial countdown timer (typically 24 hours). When a bid is placed, the timer is extended by a few seconds (usually 10-30 seconds, depending on the auction). This ensures that everyone has a fair chance to place bids before the auction ends.</p>
                        <img src="{{ asset('images/help/timer-extension.jpg') }}" alt="Timer extension diagram" class="help-image">
                    </div>
                    
                    <div class="help-subsection">
                        <h3>Timer Colors</h3>
                        <ul class="help-list">
                            <li><span class="timer-indicator normal">Normal (Green):</span> More than 10 minutes remaining</li>
                            <li><span class="timer-indicator warning">Warning (Yellow):</span> Less than 10 minutes remaining</li>
                            <li><span class="timer-indicator urgent">Urgent (Red):</span> Less than 1 minute remaining</li>
                        </ul>
                    </div>
                    
                    <div class="help-subsection">
                        <h3>Auction End</h3>
                        <p>An auction ends when the timer reaches zero with no new bids. The last bidder wins the auction and has the right to purchase the item at the final bid price.</p>
                    </div>
                    
                    <div class="help-tip">
                        <h4>Pro Tip:</h4>
                        <p>Strategic bidders often wait until the final seconds of an auction to place their bids. This technique, known as "bid sniping," can be effective but risky if your internet connection is slow or if multiple bidders have the same strategy.</p>
                    </div>
                </section>
                
                <!-- Add more sections for the other topics -->
                <section id="auto-bidder" class="help-section">
                    <h2>Using the Auto-Bidder Feature</h2>
                    <p>Coming soon...</p>
                </section>
                
                <section id="auction-alerts" class="help-section">
                    <h2>Setting up Auction Alerts</h2>
                    <p>Coming soon...</p>
                </section>
                
                <section id="watchlist" class="help-section">
                    <h2>Watchlist Management</h2>
                    <p>Coming soon...</p>
                </section>
                
                <section id="strategies" class="help-section">
                    <h2>Bidding Strategies and Tips</h2>
                    <p>Coming soon...</p>
                </section>
                
                <div class="help-related-topics">
                    <h3>Related Articles</h3>
                    <ul>
                        <li><a href="{{ url('/help/payment/bid-packages') }}">Understanding Bid Packages and Pricing</a></li>
                        <li><a href="{{ url('/help/winning/what-happens') }}">What Happens When You Win an Auction</a></li>
                        <li><a href="{{ url('/faq#bidding') }}">Frequently Asked Questions about Bidding</a></li>
                    </ul>
                </div>
                
                <div class="help-feedback">
                    <h3>Was this article helpful?</h3>
                    <div class="feedback-buttons">
                        <button class="btn btn-outline btn-sm feedback-btn" data-value="yes"><i class="fas fa-thumbs-up"></i> Yes</button>
                        <button class="btn btn-outline btn-sm feedback-btn" data-value="no"><i class="fas fa-thumbs-down"></i> No</button>
                    </div>
                    <div class="feedback-form" style="display: none;">
                        <textarea placeholder="Please let us know how we can improve this article..."></textarea>
                        <button class="btn btn-primary btn-sm">Submit Feedback</button>
                    </div>
                    <div class="feedback-thanks" style="display: none;">
                        <p>Thank you for your feedback!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .help-topic-page {
        padding: 40px 0 60px;
    }
    
    .help-topic-header {
        margin-bottom: 40px;
        border-bottom: 1px solid #eee;
        padding-bottom: 20px;
    }
    
    .help-topic-header h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--dark);
    }
    
    .breadcrumbs {
        font-size: 0.9rem;
        color: var(--secondary-text);
    }
    
    .breadcrumbs a {
        color: var(--secondary-color);
        text-decoration: none;
        transition: color 0.2s;
    }
    
    .breadcrumbs a:hover {
        color: var(--accent-color);
    }
    
    .help-topic-wrapper {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 40px;
    }
    
    /* Sidebar Styles */
    .help-topic-sidebar {
        position: sticky;
        top: 100px;
        height: fit-content;
    }
    
    .sidebar-section {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .sidebar-section h3 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
        color: var(--dark);
    }
    
    .sidebar-section ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .sidebar-section ul li {
        margin-bottom: 10px;
    }
    
    .sidebar-section ul li a {
        color: var(--secondary-text);
        text-decoration: none;
        font-size: 0.95rem;
        display: block;
        padding: 8px 10px;
        border-radius: 4px;
        transition: all 0.2s;
    }
    
    .sidebar-section ul li a:hover {
        background-color: #f8f9fa;
        color: var(--secondary-color);
    }
    
    .sidebar-section ul li a.active {
        background-color: var(--secondary-color);
        color: white;
        font-weight: 500;
    }
    
    .need-help-box {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
    }
    
    .need-help-box h3 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--dark);
    }
    
    .need-help-box p {
        font-size: 0.9rem;
        margin-bottom: 15px;
        color: var(--secondary-text);
    }
    
    /* Content Styles */
    .help-topic-content {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 30px;
    }
    
    .help-section {
        margin-bottom: 40px;
        padding-bottom: 30px;
        border-bottom: 1px solid #eee;
    }
    
    .help-section:last-child {
        margin-bottom: 20px;
        padding-bottom: 0;
        border-bottom: none;
    }
    
    .help-section h2 {
        font-size: 1.6rem;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--dark);
    }
    
    .help-section p {
        font-size: 1rem;
        line-height: 1.6;
        color: var(--secondary-text);
        margin-bottom: 20px;
    }
    
    .help-subsection {
        margin-bottom: 25px;
    }
    
    .help-subsection h3 {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: var(--dark);
    }
    
    .help-steps {
        margin-bottom: 30px;
    }
    
    .help-step {
        display: flex;
        margin-bottom: 25px;
    }
    
    .step-number {
        width: 36px;
        height: 36px;
        background-color: var(--secondary-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin-right: 15px;
        flex-shrink: 0;
    }
    
    .step-content {
        flex-grow: 1;
    }
    
    .step-content h3 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--dark);
    }
    
    .step-content p {
        font-size: 0.95rem;
        margin-bottom: 15px;
    }
    
    .step-image, .help-image {
        width: 100%;
        max-width: 500px;
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 10px;
    }
    
    .help-note {
        background-color: #f8f9fa;
        border-left: 4px solid var(--secondary-color);
        padding: 15px 20px;
        margin-bottom: 25px;
        border-radius: 0 4px 4px 0;
    }
    
    .help-note h4 {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--dark);
    }
    
    .help-note p {
        font-size: 0.9rem;
        margin-bottom: 0;
    }
    
    .help-tip {
        background-color: #f8f9fa;
        border-left: 4px solid var(--accent-color);
        padding: 15px 20px;
        margin-bottom: 25px;
        border-radius: 0 4px 4px 0;
    }
    
    .help-tip h4 {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--dark);
    }
    
    .help-tip p {
        font-size: 0.9rem;
        margin-bottom: 0;
    }
    
    .help-list {
        list-style: none;
        padding: 0;
        margin: 0 0 20px 0;
    }
    
    .help-list li {
        margin-bottom: 10px;
        font-size: 0.95rem;
        color: var(--secondary-text);
    }
    
    .timer-indicator {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 4px;
        font-weight: 500;
        margin-right: 5px;
    }
    
    .timer-indicator.normal {
        background-color: #e8f5e9;
        color: #2e7d32;
    }
    
    .timer-indicator.warning {
        background-color: #fff8e1;
        color: #ff8f00;
    }
    
    .timer-indicator.urgent {
        background-color: #ffebee;
        color: #c62828;
    }
    
    .help-related-topics {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
    }
    
    .help-related-topics h3 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: var(--dark);
    }
    
    .help-related-topics ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .help-related-topics ul li {
        margin-bottom: 10px;
    }
    
    .help-related-topics ul li a {
        color: var(--secondary-color);
        text-decoration: none;
        font-size: 0.95rem;
        transition: color 0.2s;
    }
    
    .help-related-topics ul li a:hover {
        color: var(--accent-color);
    }
    
    .help-feedback {
        border-top: 1px solid #eee;
        padding-top: 20px;
        text-align: center;
    }
    
    .help-feedback h3 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: var(--dark);
    }
    
    .feedback-buttons {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-bottom: 15px;
    }
    
    .feedback-form textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-bottom: 10px;
        resize: vertical;
        min-height: 100px;
    }
    
    .btn-sm {
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .help-topic-wrapper {
            grid-template-columns: 1fr;
        }
        
        .help-topic-sidebar {
            position: static;
            margin-bottom: 30px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                    
                    // Update active link in sidebar
                    document.querySelectorAll('.sidebar-section ul li a').forEach(link => {
                        link.classList.remove('active');
                    });
                    this.classList.add('active');
                }
            });
        });
        
        // Feedback functionality
        const feedbackButtons = document.querySelectorAll('.feedback-btn');
        const feedbackForm = document.querySelector('.feedback-form');
        const feedbackThanks = document.querySelector('.feedback-thanks');
        
        feedbackButtons.forEach(button => {
            button.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                
                if (value === 'no') {
                    feedbackForm.style.display = 'block';
                } else {
                    feedbackThanks.style.display = 'block';
                }
                
                feedbackButtons.forEach(btn => {
                    btn.disabled = true;
                });
            });
        });
        
        const submitFeedback = document.querySelector('.feedback-form button');
        if (submitFeedback) {
            submitFeedback.addEventListener('click', function() {
                feedbackForm.style.display = 'none';
                feedbackThanks.style.display = 'block';
            });
        }
    });
</script>
@endsection