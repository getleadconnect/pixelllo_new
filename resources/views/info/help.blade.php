@extends('layouts.app')

@section('title', 'Help Center - ' . config('app.name'))

@section('content')
<style>

.form-group
{
    margin-bottom: 0px !important;
}

.form-row {
  margin-bottom: 0px !important;
}

    .help-page {
        padding-bottom: 60px;
    }
    
    .hero-banner {
        background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
            url('https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80');
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
    
    .section-title {
        font-size: 1.8rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 30px;
        color: var(--dark);
        position: relative;
        padding-bottom: 10px;
    }
    
    .section-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background-color: var(--secondary-color);
    }
    
    /* Help Intro */
    .help-intro {
        text-align: center;
        max-width: 800px;
        margin: 0 auto 40px;
    }
    
    .help-intro p {
        font-size: 1.1rem;
        line-height: 1.6;
        color: var(--secondary-text);
    }
    
    /* Help Options */
    .help-options {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        margin-bottom: 60px;
    }
    
    .help-option {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        padding: 30px;
        text-align: center;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .help-option:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .option-icon {
        width: 70px;
        height: 70px;
        background-color: var(--secondary-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin: 0 auto 20px;
    }
    
    .help-option h2 {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--dark);
    }
    
    .help-option p {
        font-size: 1rem;
        margin-bottom: 20px;
        color: var(--secondary-text);
        line-height: 1.6;
    }
    
    /* Self Help Section */
    .self-help-section {
        margin-bottom: 60px;
        padding-top: 20px;
    }
    
    .help-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }
    
    .help-category {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        padding: 25px;
        transition: transform 0.3s;
    }
    
    .help-category:hover {
        transform: translateY(-5px);
    }
    
    .category-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .category-icon {
        width: 50px;
        height: 50px;
        background-color: var(--secondary-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-right: 15px;
        flex-shrink: 0;
    }
    
    .help-category h3 {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--dark);
        margin: 0;
    }
    
    .help-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .help-links li {
        margin-bottom: 12px;
        position: relative;
        padding-left: 15px;
    }
    
    .help-links li:before {
        content: '•';
        color: var(--secondary-color);
        position: absolute;
        left: 0;
        top: 0;
    }
    
    .help-links a {
        color: var(--secondary-text);
        text-decoration: none;
        font-size: 0.95rem;
        transition: color 0.3s;
    }
    
    .help-links a:hover {
        color: var(--secondary-color);
    }
    
    /* Support Channels */
    .support-channels {
        margin-bottom: 60px;
    }
    
    .channels-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }
    
    .support-channel {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        padding: 30px;
        text-align: center;
        transition: transform 0.3s;
    }
    
    .support-channel:hover {
        transform: translateY(-5px);
    }
    
    .channel-icon {
        width: 60px;
        height: 60px;
        background-color: var(--secondary-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin: 0 auto 20px;
    }
    
    .support-channel h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--dark);
    }
    
    .support-channel p {
        font-size: 0.95rem;
        line-height: 1.6;
        color: var(--secondary-text);
        margin-bottom: 15px;
    }
    
    .channel-info {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--secondary-color);
        margin-bottom: 5px;
    }
    
    .channel-detail {
        font-size: 0.9rem;
        color: var(--secondary-text);
    }
    
    /* Contact Form */
    .contact-form-section {
        margin-bottom: 60px;
        padding-top: 20px;
    }
    
    .contact-form-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        padding: 30px;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .form-intro {
        text-align: center;
        margin-bottom: 30px;
        font-size: 1rem;
        color: var(--secondary-text);
    }
    
    .contact-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
    }
    
    .form-group label {
        margin-bottom: 8px;
        font-weight: 500;
        font-size: 0.95rem;
        color: var(--dark);
    }
    
    .required {
        color: var(--danger);
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 0.95rem;
        transition: border-color 0.3s;
    }
    
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--secondary-color);
    }
    
    .form-help {
        font-size: 0.85rem;
        color: var(--secondary-text);
        margin-top: 5px;
    }
    
    .checkbox-group {
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }
    
    .checkbox-group input {
        margin-top: 3px;
    }
    
    .checkbox-group label {
        font-size: 0.9rem;
        margin-bottom: 0;
    }
    
    .checkbox-group a {
        color: var(--secondary-color);
        text-decoration: none;
        font-weight: 500;
    }
    
    /* FAQ Preview */
    .faq-preview {
        margin-bottom: 40px;
    }
    
    .faq-preview-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
        margin-bottom: 30px;
    }
    
    .faq-preview-item {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        padding: 25px;
        transition: transform 0.3s;
    }
    
    .faq-preview-item:hover {
        transform: translateY(-5px);
    }
    
    .faq-preview-item h3 {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: var(--dark);
    }
    
    .faq-preview-item p {
        font-size: 0.95rem;
        line-height: 1.6;
        color: var(--secondary-text);
        margin-bottom: 15px;
    }
    
    .preview-link {
        font-size: 0.9rem;
        color: var(--secondary-color);
        text-decoration: none;
        font-weight: 500;
        display: inline-block;
        transition: color 0.3s;
    }
    
    .preview-link:hover {
        color: var(--accent-color);
    }
    
    .view-all-faq {
        text-align: center;
    }
    
    /* Responsive Styles */
    @media (max-width: 992px) {
        .help-options,
        .channels-grid {
            grid-template-columns: 1fr;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .help-grid,
        .faq-preview-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .hero-banner h1 {
            font-size: 2rem;
        }
        
        .section-title {
            font-size: 1.5rem;
        }
        
        .help-grid,
        .faq-preview-grid {
            grid-template-columns: 1fr;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .help-option h2 {
            font-size: 1.3rem;
        }
        
        .support-channel h3 {
            font-size: 1.2rem;
        }
    }
</style>

<div class="help-page">
    <div class="hero-banner">
        <div class="container">
            <h1>Help Center</h1>
            <p class="hero-description">We're here to help you with any questions or issues you may have.</p>
        </div>
    </div>
    
    <div class="container">
        <div class="help-intro">
            <p>Welcome to the Pixelllo Help Center. Our team is dedicated to providing you with the best possible support experience. Choose from the options below to get the help you need.</p>
        </div>
        
        <div class="help-options">
            <div class="help-option">
                <div class="option-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h2>Browse Help Topics</h2>
                <p>Find answers to common questions and learn how to make the most of your Pixelllo experience.</p>
                <a href="#help-topics" class="btn btn-outline">Browse Topics</a>
            </div>
            
            <div class="help-option">
                <div class="option-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <h2>Contact Support</h2>
                <p>Need personalized assistance? Our support team is ready to help with any issues or questions.</p>
                <a href="#contact-form" class="btn btn-outline">Contact Us</a>
            </div>
            
            <div class="help-option">
                <div class="option-icon">
                    <i class="fas fa-book"></i>
                </div>
                <h2>FAQs</h2>
                <p>Get quick answers to the most frequently asked questions about Pixelllo.</p>
                <a href="{{ route('faq') }}" class="btn btn-outline">View FAQs</a>
            </div>
        </div>
        
        <div class="self-help-section" id="help-topics">
            <h2 class="section-title">Help Topics</h2>
            
            <div class="help-grid">
                <div class="help-category">
                    <div class="category-header">
                        <div class="category-icon">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <h3>Account & Registration</h3>
                    </div>
                    <ul class="help-links">
                        <li><a href="{{ route('help.topic', 'account-creation') }}">How to create an account</a></li>
                        <li><a href="{{ route('help.topic', 'profile-update') }}">Updating your profile information</a></li>
                        <li><a href="{{ route('help.topic', 'password-change') }}">Changing your password</a></li>
                        <li><a href="{{ route('help.topic', 'account-verification') }}">Account verification</a></li>
                        <li><a href="{{ route('help.topic', 'email-preferences') }}">Managing email preferences</a></li>
                        <li><a href="{{ route('help.topic', 'account-deletion') }}">Deleting your account</a></li>
                    </ul>
                </div>
                
                <div class="help-category">
                    <div class="category-header">
                        <div class="category-icon">
                            <i class="fas fa-gavel"></i>
                        </div>
                        <h3>Bidding & Auctions</h3>
                    </div>
                    <ul class="help-links">
                        <li><a href="{{ route('help.topic', 'bidding') }}">How to place bids</a></li>
                        <li><a href="{{ route('help.topic', 'bidding') }}#auction-timers">Understanding auction timers</a></li>
                        <li><a href="{{ route('help.topic', 'bidding') }}#auto-bidder">Using the Auto-Bidder feature</a></li>
                        <li><a href="{{ route('help.topic', 'bidding') }}#auction-alerts">Setting up auction alerts</a></li>
                        <li><a href="{{ route('help.topic', 'bidding') }}#watchlist">Watchlist management</a></li>
                        <li><a href="{{ route('help.topic', 'bidding') }}#strategies">Bidding strategies and tips</a></li>
                    </ul>
                </div>
                
                <div class="help-category">
                    <div class="category-header">
                        <div class="category-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <h3>Payments & Billing</h3>
                    </div>
                    <ul class="help-links">
                        <li><a href="{{ route('help.topic', 'payment') }}#bid-packages">Purchasing bid packages</a></li>
                        <li><a href="{{ route('help.topic', 'payment') }}#payment-methods">Payment methods accepted</a></li>
                        <li><a href="{{ route('help.topic', 'payment') }}#pricing">Understanding bid credit pricing</a></li>
                        <li><a href="{{ route('help.topic', 'payment') }}#transaction-history">Billing and transaction history</a></li>
                        <li><a href="{{ route('help.topic', 'payment') }}#payment-failures">Handling payment failures</a></li>
                        <li><a href="{{ route('help.topic', 'payment') }}#refunds">Refund policy</a></li>
                    </ul>
                </div>
                
                <div class="help-category">
                    <div class="category-header">
                        <div class="category-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h3>Winning & Orders</h3>
                    </div>
                    <ul class="help-links">
                        <li><a href="{{ route('help.topic', 'winning') }}#what-happens">What happens when you win</a></li>
                        <li><a href="{{ route('help.topic', 'winning') }}#complete-purchase">Completing your purchase</a></li>
                        <li><a href="{{ route('help.topic', 'winning') }}#order-tracking">Order confirmation and tracking</a></li>
                        <li><a href="{{ route('help.topic', 'winning') }}#shipping-methods">Shipping methods and timeframes</a></li>
                        <li><a href="{{ route('help.topic', 'winning') }}#international">International orders</a></li>
                        <li><a href="{{ route('help.topic', 'winning') }}#returns">Returns and exchanges</a></li>
                    </ul>
                </div>
                
                <div class="help-category">
                    <div class="category-header">
                        <div class="category-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>Security & Privacy</h3>
                    </div>
                    <ul class="help-links">
                        <li><a href="{{ route('help.topic', 'security') }}#best-practices">Account security best practices</a></li>
                        <li><a href="{{ route('help.topic', 'security') }}#two-factor">Two-factor authentication</a></li>
                        <li><a href="{{ route('help.topic', 'security') }}#privacy-settings">Privacy settings</a></li>
                        <li><a href="{{ route('help.topic', 'security') }}#data-protection">Data protection measures</a></li>
                        <li><a href="{{ route('help.topic', 'security') }}#suspicious-activity">Reporting suspicious activity</a></li>
                        <li><a href="{{ route('help.topic', 'security') }}#privacy-policy">Understanding our privacy policy</a></li>
                    </ul>
                </div>
                
                <div class="help-category">
                    <div class="category-header">
                        <div class="category-icon">
                            <i class="fas fa-laptop"></i>
                        </div>
                        <h3>Technical Support</h3>
                    </div>
                    <ul class="help-links">
                        <li><a href="{{ route('help.topic', 'technical') }}#connection-issues">Troubleshooting connection issues</a></li>
                        <li><a href="{{ route('help.topic', 'technical') }}#browser-compatibility">Browser compatibility</a></li>
                        <li><a href="{{ route('help.topic', 'technical') }}#mobile-app">Mobile app support</a></li>
                        <li><a href="{{ route('help.topic', 'technical') }}#cache-cookies">Clearing cache and cookies</a></li>
                        <li><a href="{{ route('help.topic', 'technical') }}#notifications">Enabling notifications</a></li>
                        <li><a href="{{ route('help.topic', 'technical') }}#system-requirements">System requirements</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="support-channels">
            <h2 class="section-title">Support Channels</h2>
            
            <div class="channels-grid">
                <div class="support-channel">
                    <div class="channel-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3>Email Support</h3>
                    <p>Send us a detailed message and we'll get back to you within 24 hours.</p>
                    <p class="channel-info">support@pixelllo.com</p>
                    <p class="channel-detail">Available 24/7</p>
                </div>
                
                <div class="support-channel">
                    <div class="channel-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h3>Phone Support</h3>
                    <p>Speak directly with a customer service representative for immediate assistance.</p>
                    <p class="channel-info">+1 (555) 123-4567</p>
                    <p class="channel-detail">Mon-Fri: 9am - 8pm ET<br>Sat-Sun: 10am - 6pm ET</p>
                </div>
                
                <div class="support-channel">
                    <div class="channel-icon">
                        <i class="fas fa-comment-dots"></i>
                    </div>
                    <h3>Live Chat</h3>
                    <p>Connect with a support agent instantly through our live chat service.</p>
                    <button class="btn btn-outline">Start Chat</button>
                    <p class="channel-detail">Available 7 days a week<br>8am - 10pm ET</p>
                </div>
            </div>
        </div>
        
        <div class="contact-form-section" id="contact-form">
            <h2 class="section-title">Contact Us</h2>
            
            <div class="contact-form-container">
                <p class="form-intro">Please fill out the form below with your question or issue, and we'll get back to you as soon as possible.</p>
                
                <form class="contact-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name <span class="required">*</span></label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address <span class="required">*</span></label>
                            <input type="email" id="email" name="email" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject <span class="required">*</span></label>
                        <input type="text" id="subject" name="subject" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="category">Category <span class="required">*</span></label>
                        <select id="category" name="category" required>
                            <option value="">Please select a category</option>
                            <option value="account">Account & Registration</option>
                            <option value="bidding">Bidding & Auctions</option>
                            <option value="payment">Payments & Billing</option>
                            <option value="orders">Orders & Shipping</option>
                            <option value="technical">Technical Support</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message <span class="required">*</span></label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                        <small class="form-help">Please provide as much detail as possible to help us assist you better.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="attachment">Attachment (optional)</label>
                        <input type="file" id="attachment" name="attachment">
                        <small class="form-help">You can attach a screenshot or document to help explain your issue (max 5MB).</small>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms">I agree to the <a href="{{ route('privacy') }}">Privacy Policy</a> and consent to the processing of my personal data to address my inquiry.</label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </form>
            </div>
        </div>
        
        <div class="faq-preview">
            <h2 class="section-title">Frequently Asked Questions</h2>
            
            <div class="faq-preview-grid">
                <div class="faq-preview-item">
                    <h3>How do I place a bid?</h3>
                    <p>To place a bid, navigate to the auction you're interested in and click the "Bid Now" button. Each bid will increase the price by a small amount and extend the auction timer. You need to have bid credits in your account to place bids.</p>
                    <a href="{{ route('faq') }}#bidding" class="preview-link">Learn more about bidding</a>
                </div>
                
                <div class="faq-preview-item">
                    <h3>What happens when I win an auction?</h3>
                    <p>When you win an auction, you'll receive a notification. You'll need to complete your purchase by paying the final auction price plus shipping within 24 hours. After payment, your item will be processed and shipped to your registered address.</p>
                    <a href="{{ route('faq') }}#winning" class="preview-link">Learn more about winning</a>
                </div>
                
                <div class="faq-preview-item">
                    <h3>How do I purchase bid credits?</h3>
                    <p>To purchase bid credits, log in to your account, click on "Get Bids" or visit the "Bid Packages" section. Select the package that suits your needs, proceed to checkout, and complete the payment using your preferred payment method.</p>
                    <a href="{{ route('faq') }}#payment" class="preview-link">Learn more about bid packages</a>
                </div>
                
                <div class="faq-preview-item">
                    <h3>Are the products new and authentic?</h3>
                    <p>Yes, all products on Pixelllo are brand new, authentic, and come with their original packaging and manufacturer's warranty where applicable. We source our products directly from authorized distributors and reputable retailers.</p>
                    <a href="{{ route('faq') }}#general" class="preview-link">Learn more about our products</a>
                </div>
            </div>
            
            <div class="view-all-faq">
                <a href="{{ route('faq') }}" class="btn btn-outline">View All FAQs</a>
            </div>
        </div>
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Simple form validation (just for demonstration)
        const contactForm = document.querySelector('.contact-form');
        
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Show success message (in real implementation, would submit to server)
                alert('Thank you for your message! Our support team will get back to you as soon as possible.');
                contactForm.reset();
            });
        }
    });
</script>
@endsection