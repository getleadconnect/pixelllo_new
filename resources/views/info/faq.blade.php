@extends('layouts.app')

@section('title', 'Frequently Asked Questions - ' . config('app.name'))

@section('content')
<div class="faq-page">
    <div class="hero-banner">
        <div class="container">
            <h1>Frequently Asked Questions</h1>
            <p class="hero-description">Find answers to common questions about Pixelllo's penny auction platform.</p>
        </div>
    </div>
    
    <div class="container">
        <div class="faq-content">
            <div class="faq-intro">
                <p>We've compiled a list of frequently asked questions to help you understand how Pixelllo works. If you can't find the answer you're looking for, please don't hesitate to <a href="{{ route('help') }}">contact our support team</a>.</p>
            </div>
            
            <div class="faq-search">
                <div class="search-container">
                    <input type="text" id="faqSearch" placeholder="Search FAQs...">
                    <button type="button"><i class="fas fa-search"></i></button>
                </div>
            </div>
            
            <div class="faq-navigation">
                <div class="category-list">
                    <button class="category-button active" data-category="all">All FAQs</button>
                    <button class="category-button" data-category="general">General</button>
                    <button class="category-button" data-category="account">Account & Registration</button>
                    <button class="category-button" data-category="bidding">Bidding & Auctions</button>
                    <button class="category-button" data-category="payment">Payment & Bid Packages</button>
                    <button class="category-button" data-category="winning">Winning & Shipping</button>
                    <button class="category-button" data-category="technical">Technical Support</button>
                </div>
            </div>
            
            <div class="faq-results" id="faqResults">
                <h2 id="categoryTitle">All Frequently Asked Questions</h2>
                <div id="noResults" class="no-results" style="display: none;">
                    <div class="no-results-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>No results found</h3>
                    <p>Sorry, we couldn't find any FAQs matching your search. Please try different keywords or browse by category.</p>
                </div>
                
                <div class="faq-section" data-category="general">
                    <h3 class="section-title">General</h3>
                    
                    <div class="faq-accordion">
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>What is Pixelllo?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>Pixelllo is a penny auction platform where users can bid on and win brand-new products at a fraction of their retail price. Unlike traditional auctions, each bid increases the price by just $0.01 and extends the auction timer. When the timer reaches zero, the last person to place a bid wins the item at the final price.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>How does a penny auction differ from a traditional auction?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>In a traditional auction, only the winner pays for the item. In a penny auction, users pay for each bid they place, regardless of whether they win. Each bid increases the auction price by a small amount (typically $0.01) and extends the auction timer. This model allows winners to purchase items at steep discounts, often 60-90% below retail value.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>Is Pixelllo legitimate? How can you offer such low prices?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>Yes, Pixelllo is a legitimate penny auction platform. We can offer products at steep discounts because our business model is based on bid purchases rather than just the final selling price. When many users participate in an auction and place bids, the combined revenue from bid purchases allows us to offer items well below retail price while maintaining a sustainable business.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>Where do the products come from?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>All products offered on Pixelllo are brand new and authentic. We source our products directly from authorized distributors, manufacturers, and reputable retailers. Every item comes with its original packaging, accessories, and manufacturer's warranty where applicable.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="faq-section" data-category="account">
                    <h3 class="section-title">Account & Registration</h3>
                    
                    <div class="faq-accordion">
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>How do I create an account?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>To create an account, click the "Register" button in the top right corner of the page. Fill out the registration form with your email address, create a password, and provide the required personal information. After submitting the form, you'll receive a verification email. Click the link in the email to verify your account, and you'll be ready to start bidding.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>Is there a fee to register?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>No, registering for a Pixelllo account is completely free. There are no membership fees or subscription costs. You only pay when you purchase bid packages or win auctions.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>Can I have multiple accounts?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>No, having multiple accounts is strictly prohibited. Each person may only have one account. Creating or using multiple accounts is considered a serious violation of our Terms of Service and may result in the permanent suspension of all associated accounts and forfeiture of any bids or winnings.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>How do I reset my password?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>To reset your password, click on the "Login" button, then select "Forgot Password?" Enter the email address associated with your account, and we'll send you a password reset link. Click the link in the email and follow the instructions to create a new password.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="faq-section" data-category="bidding">
                    <h3 class="section-title">Bidding & Auctions</h3>
                    
                    <div class="faq-accordion">
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>How do I place a bid?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>To place a bid, first make sure you're logged in and have bid credits in your account. Navigate to the auction you're interested in and click the "Bid Now" button. Each click on this button will place one bid, increase the auction price by $0.01, and extend the auction timer.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>What happens when I place a bid?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>When you place a bid, the following happens:</p>
                                <ol>
                                    <li>One bid credit is deducted from your account</li>
                                    <li>The auction price increases by $0.01</li>
                                    <li>The auction timer is extended (typically by 10-20 seconds)</li>
                                    <li>You become the highest bidder until someone else places a bid</li>
                                </ol>
                                <p>The auction continues until the timer reaches zero, and the last person to place a bid wins the item at the final auction price.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>What is the bid timer and how does it work?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>The bid timer is a countdown clock that shows how much time is left before an auction ends. When a bid is placed, the timer is extended (typically by 10-20 seconds), giving other users a chance to place additional bids. This extension prevents auctions from ending too quickly and ensures that everyone has an opportunity to participate. The auction ends when the timer reaches zero, and the last bidder wins.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>What is Auto-Bidder and how does it work?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>Auto-Bidder is a feature that automatically places bids for you according to your specified settings. To use Auto-Bidder, select the maximum number of bids you want to use and activate it for a specific auction. The system will then automatically place bids for you when you're outbid or when the timer reaches a certain threshold.</p>
                                <p>This feature is particularly useful when you can't actively monitor an auction or when you want to ensure you don't miss bidding opportunities during the final moments of an auction.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>Are bids refundable if I don't win?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>No, bids are consumed when placed and are non-refundable, regardless of whether you win the auction or not. This is part of the penny auction model and is what enables the significant discounts for winners. Think of bids as the cost of participation, similar to buying lottery tickets.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>How do I know if an auction is about to end?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>You can tell an auction is nearing its end when the timer displays a low value. However, remember that each bid extends the timer, so an auction that appears to be ending can continue for much longer if bidding remains active.</p>
                                <p>To stay informed about auctions you're interested in, you can:</p>
                                <ul>
                                    <li>Set up auction alerts in your account settings</li>
                                    <li>Add auctions to your watchlist for easy monitoring</li>
                                    <li>Use the Auto-Bidder feature to automatically place bids for you</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="faq-section" data-category="payment">
                    <h3 class="section-title">Payment & Bid Packages</h3>
                    
                    <div class="faq-accordion">
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>What are bid credits and how do I get them?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>Bid credits are the currency used to place bids on Pixelllo. Each bid credit allows you to place one bid in an auction. To get bid credits, you need to purchase a bid package from our store. We offer various packages with different quantities of bids to suit your needs and budget. The more bids you purchase at once, the lower the cost per bid.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>How much do bid credits cost?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>The cost of bid credits depends on the package you choose. Our current bid packages are:</p>
                                <ul>
                                    <li>Starter Package: 25 bids for $15 ($0.60 per bid)</li>
                                    <li>Basic Package: 50 bids for $28 ($0.56 per bid)</li>
                                    <li>Popular Package: 100 bids for $50 ($0.50 per bid)</li>
                                    <li>Premium Package: 250 bids for $100 ($0.40 per bid)</li>
                                    <li>Elite Package: 500 bids for $175 ($0.35 per bid)</li>
                                    <li>Ultimate Package: 1000 bids for $300 ($0.30 per bid)</li>
                                </ul>
                                <p>We also occasionally offer special promotions and discounts on bid packages.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>What payment methods do you accept?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>We accept the following payment methods:</p>
                                <ul>
                                    <li>Credit/Debit Cards (Visa, Mastercard, American Express, Discover)</li>
                                    <li>PayPal</li>
                                    <li>Apple Pay</li>
                                    <li>Google Pay</li>
                                    <li>Bank Transfer (ACH)</li>
                                </ul>
                                <p>All payments are processed securely using industry-standard encryption and security protocols.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>Do bid credits expire?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>No, bid credits do not expire. Once purchased, they remain in your account until used, regardless of how long it takes.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>Can I transfer bid credits to another user?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>No, bid credits cannot be transferred between users. The bid credits you purchase are tied to your account and can only be used by you.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="faq-section" data-category="winning">
                    <h3 class="section-title">Winning & Shipping</h3>
                    
                    <div class="faq-accordion">
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>What happens when I win an auction?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>When you win an auction, you'll receive an email notification congratulating you and providing instructions for completing your purchase. To claim your item, you'll need to pay the final auction price plus applicable shipping and handling charges within 24 hours.</p>
                                <p>After completing payment, your order will be processed and shipped to your registered address. You can track the status of your order in the "Orders" section of your Pixelllo dashboard.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>How long do I have to complete payment after winning?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>You have 24 hours to complete payment after winning an auction. If payment is not received within this timeframe, the item may be forfeited, and your account may be subject to restrictions. If you anticipate any difficulty meeting this deadline, please contact our customer service team immediately.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>How long does shipping take?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>Shipping times vary depending on your location and the shipping method selected:</p>
                                <ul>
                                    <li>Standard Shipping: 5-7 business days (continental U.S.)</li>
                                    <li>Expedited Shipping: 2-3 business days</li>
                                    <li>Express Shipping: 1-2 business days</li>
                                </ul>
                                <p>International shipping times vary by destination and typically take 6-14 business days. For complete information about shipping options and timeframes, please visit our <a href="{{ route('shipping') }}">Shipping & Returns</a> page.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>Do you ship internationally?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>Yes, we ship to select countries internationally, including Canada, the United Kingdom, Australia, and several European and Asian countries. International shipping rates vary based on destination, package weight, and dimensions. Please note that international customers are responsible for all customs fees, import duties, and taxes imposed by their country's government.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>What is your return policy?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>We offer a 30-day satisfaction guarantee on all items. If you're not completely satisfied with your purchase, you can return it for a full refund of the final auction price and shipping costs. Please note that the cost of bids used is non-refundable.</p>
                                <p>To be eligible for a return, your item must be unused, in the same condition that you received it, and in its original packaging. For complete details about our return process, please visit our <a href="{{ route('shipping') }}">Shipping & Returns</a> page.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="faq-section" data-category="technical">
                    <h3 class="section-title">Technical Support</h3>
                    
                    <div class="faq-accordion">
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>What browsers are supported?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>Pixelllo is optimized for the latest versions of:</p>
                                <ul>
                                    <li>Google Chrome</li>
                                    <li>Mozilla Firefox</li>
                                    <li>Apple Safari</li>
                                    <li>Microsoft Edge</li>
                                </ul>
                                <p>For the best experience, we recommend keeping your browser updated to the latest version.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>Is there a mobile app?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>Yes, we offer mobile apps for both iOS and Android devices. You can download the Pixelllo app from the Apple App Store or Google Play Store. Our mobile apps provide all the functionality of the website, including bidding, tracking auctions, and managing your account, in a mobile-friendly interface.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>Why am I experiencing lag or delays during auctions?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>Lag or delays during auctions can be caused by several factors:</p>
                                <ul>
                                    <li>Slow internet connection</li>
                                    <li>Browser issues (try clearing your cache or using a different browser)</li>
                                    <li>High server load during peak times</li>
                                    <li>Device performance (older devices may struggle with real-time updates)</li>
                                </ul>
                                <p>To minimize lag, ensure you have a stable internet connection, use a supported browser, and keep your device updated. If problems persist, try refreshing the page or contact our technical support team for assistance.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <h4>How do I enable notifications?</h4>
                                <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="faq-answer">
                                <p>To enable notifications:</p>
                                <ol>
                                    <li>Log in to your Pixelllo account</li>
                                    <li>Go to "Account Settings" or "Profile Settings"</li>
                                    <li>Select the "Notifications" tab</li>
                                    <li>Choose which notifications you want to receive (e.g., auction alerts, bid confirmations, etc.)</li>
                                    <li>Save your preferences</li>
                                </ol>
                                <p>For browser notifications, you'll need to allow Pixelllo to send notifications when prompted by your browser. For mobile notifications, ensure that notifications are enabled for the Pixelllo app in your device settings.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="contact-support">
                <h2>Still Have Questions?</h2>
                <p>Our customer support team is here to help.</p>
                <div class="support-options">
                    <a href="{{ route('help') }}" class="btn btn-primary">Contact Support</a>
                    <span class="or-divider">or</span>
                    <div class="support-contact">
                        <p><i class="fas fa-envelope"></i> support@pixelllo.com</p>
                        <p><i class="fas fa-phone"></i> +1 (555) 123-4567</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .faq-page {
        padding-bottom: 60px;
    }
    
    .hero-banner {
        background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
            url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80');
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
    
    .faq-content {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        padding: 40px;
    }
    
    .faq-intro {
        margin-bottom: 30px;
        text-align: center;
    }
    
    .faq-intro p {
        font-size: 1.05rem;
        line-height: 1.6;
        max-width: 800px;
        margin: 0 auto;
        color: var(--secondary-text);
    }
    
    .faq-intro a {
        color: var(--secondary-color);
        text-decoration: none;
        font-weight: 500;
    }
    
    /* Search */
    .faq-search {
        margin-bottom: 30px;
    }
    
    .search-container {
        display: flex;
        max-width: 600px;
        margin: 0 auto;
        border: 1px solid #ddd;
        border-radius: 50px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }
    
    .search-container input {
        flex: 1;
        padding: 15px 20px;
        border: none;
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
        cursor: pointer;
        transition: background-color 0.3s;
    }
    
    .search-container button:hover {
        background-color: var(--accent-color);
    }
    
    /* Category Navigation */
    .faq-navigation {
        margin-bottom: 30px;
        overflow-x: auto;
    }
    
    .category-list {
        display: flex;
        gap: 10px;
        padding-bottom: 5px;
    }
    
    .category-button {
        padding: 8px 15px;
        background-color: #f5f5f5;
        border: none;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--secondary-text);
        cursor: pointer;
        transition: all 0.3s;
        white-space: nowrap;
    }
    
    .category-button:hover {
        background-color: #ebebeb;
    }
    
    .category-button.active {
        background-color: var(--secondary-color);
        color: white;
    }
    
    /* Results */
    .faq-results {
        min-height: 300px;
    }
    
    .faq-results h2 {
        font-size: 1.6rem;
        font-weight: 700;
        margin-bottom: 30px;
        color: var(--dark);
        text-align: center;
        position: relative;
        padding-bottom: 10px;
    }
    
    .faq-results h2:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background-color: var(--secondary-color);
    }
    
    .no-results {
        text-align: center;
        padding: 40px 0;
    }
    
    .no-results-icon {
        font-size: 3rem;
        color: #ddd;
        margin-bottom: 15px;
    }
    
    .no-results h3 {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--dark);
    }
    
    .no-results p {
        font-size: 1rem;
        color: var(--secondary-text);
        max-width: 500px;
        margin: 0 auto;
    }
    
    /* FAQ Sections */
    .faq-section {
        margin-bottom: 40px;
    }
    
    .section-title {
        font-size: 1.4rem;
        font-weight: 600;
        margin-bottom: 20px;
        color: var(--dark);
        position: relative;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    
    /* FAQ Accordion */
    .faq-accordion {
        margin-bottom: 20px;
    }
    
    .faq-item {
        margin-bottom: 15px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }
    
    .faq-question {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background-color: #f9f9f9;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    
    .faq-question:hover {
        background-color: #f5f5f5;
    }
    
    .faq-question h4 {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
        color: var(--dark);
    }
    
    .faq-icon {
        transition: transform 0.3s;
    }
    
    .faq-item.active .faq-icon {
        transform: rotate(180deg);
    }
    
    .faq-answer {
        padding: 0;
        max-height: 0;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .faq-item.active .faq-answer {
        padding: 20px;
        max-height: 1000px;
    }
    
    .faq-answer p {
        margin-bottom: 15px;
        font-size: 0.95rem;
        line-height: 1.6;
        color: var(--secondary-text);
    }
    
    .faq-answer p:last-child {
        margin-bottom: 0;
    }
    
    .faq-answer a {
        color: var(--secondary-color);
        text-decoration: none;
        font-weight: 500;
    }
    
    .faq-answer ul,
    .faq-answer ol {
        margin-left: 20px;
        margin-bottom: 15px;
    }
    
    .faq-answer li {
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 8px;
        color: var(--secondary-text);
    }
    
    /* Contact Support */
    .contact-support {
        margin-top: 50px;
        background-color: #f9f9f9;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
    }
    
    .contact-support h2 {
        font-size: 1.6rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--dark);
    }
    
    .contact-support p {
        font-size: 1rem;
        margin-bottom: 20px;
        color: var(--secondary-text);
    }
    
    .support-options {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    
    .or-divider {
        color: var(--secondary-text);
        font-weight: 500;
    }
    
    .support-contact p {
        margin-bottom: 5px;
        font-weight: 500;
    }
    
    .support-contact i {
        color: var(--secondary-color);
        margin-right: 8px;
    }
    
    /* Responsive Styles */
    @media (max-width: 768px) {
        .hero-banner h1 {
            font-size: 2rem;
        }
        
        .faq-content {
            padding: 25px;
        }
        
        .faq-results h2 {
            font-size: 1.4rem;
        }
        
        .section-title {
            font-size: 1.2rem;
        }
        
        .faq-question h4 {
            font-size: 1rem;
        }
        
        .support-options {
            flex-direction: column;
            align-items: stretch;
        }
        
        .or-divider {
            display: none;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // FAQ Accordion functionality
        const faqItems = document.querySelectorAll('.faq-item');
        
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');
            
            question.addEventListener('click', () => {
                // Toggle active class on clicked item
                item.classList.toggle('active');
            });
        });
        
        // Category filtering
        const categoryButtons = document.querySelectorAll('.category-button');
        const faqSections = document.querySelectorAll('.faq-section');
        const noResults = document.getElementById('noResults');
        const categoryTitle = document.getElementById('categoryTitle');
        
        categoryButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Update active button
                categoryButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                const category = button.getAttribute('data-category');
                
                // Update title
                if (category === 'all') {
                    categoryTitle.textContent = 'All Frequently Asked Questions';
                } else {
                    const categoryName = button.textContent;
                    categoryTitle.textContent = categoryName + ' FAQs';
                }
                
                // Show/hide sections based on category
                let visibleSections = 0;
                
                faqSections.forEach(section => {
                    if (category === 'all' || section.getAttribute('data-category') === category) {
                        section.style.display = 'block';
                        visibleSections++;
                    } else {
                        section.style.display = 'none';
                    }
                });
                
                // Show no results message if needed
                if (visibleSections === 0) {
                    noResults.style.display = 'block';
                } else {
                    noResults.style.display = 'none';
                }
            });
        });
        
        // Search functionality
        const searchInput = document.getElementById('faqSearch');
        const faqQuestions = document.querySelectorAll('.faq-question h4');
        
        searchInput.addEventListener('input', () => {
            const searchTerm = searchInput.value.toLowerCase();
            
            // Reset category buttons
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            document.querySelector('[data-category="all"]').classList.add('active');
            
            // Show all sections first
            faqSections.forEach(section => {
                section.style.display = 'block';
            });
            
            if (searchTerm.trim() === '') {
                // If search is empty, show all FAQs
                faqItems.forEach(item => {
                    item.style.display = 'block';
                });
                noResults.style.display = 'none';
                categoryTitle.textContent = 'All Frequently Asked Questions';
                return;
            }
            
            // Update title
            categoryTitle.textContent = `Search Results for "${searchTerm}"`;
            
            // Filter FAQs based on search term
            let hasResults = false;
            
            faqItems.forEach(item => {
                const questionText = item.querySelector('.faq-question h4').textContent.toLowerCase();
                const answerText = item.querySelector('.faq-answer').textContent.toLowerCase();
                
                if (questionText.includes(searchTerm) || answerText.includes(searchTerm)) {
                    item.style.display = 'block';
                    hasResults = true;
                    
                    // Expand items that match search
                    item.classList.add('active');
                } else {
                    item.style.display = 'none';
                    item.classList.remove('active');
                }
            });
            
            // Check if each section has visible items
            faqSections.forEach(section => {
                const visibleItems = section.querySelectorAll('.faq-item[style="display: block;"]');
                if (visibleItems.length === 0) {
                    section.style.display = 'none';
                }
            });
            
            // Show no results message if needed
            if (!hasResults) {
                noResults.style.display = 'block';
            } else {
                noResults.style.display = 'none';
            }
        });
    });
</script>
@endsection