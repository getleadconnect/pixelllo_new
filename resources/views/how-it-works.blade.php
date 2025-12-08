@extends('layouts.app')

@section('title', 'How It Works - ' . config('app.name'))

@section('content')
<div class="how-it-works-page">
    <div class="hero-banner">
        <div class="container">
            <h1>How Pixelllo Works</h1>
            <p class="hero-description">Understanding our penny auction platform is simple. Learn how to bid, win, and save up to 99% on brand new products.</p>
        </div>
    </div>
    
    <div class="container">
        <div class="intro-section">
            <div class="intro-content">
                <h2>What is a Penny Auction?</h2>
                <p>Penny auctions are a type of auction where each bid increases the price by a small fixed amount (often just AED 0.01) and extends the auction timer. When the timer reaches zero, the last person to place a bid wins the item at the final price, which is typically a fraction of the retail value.</p>
                <p>Pixelllo offers an exciting opportunity to win high-end products at incredible discounts. Our auctions are transparent, fair, and designed to create an engaging experience for all participants.</p>
            </div>
            <div class="intro-video">
                <div class="video-placeholder">
                    <img src="{{ asset('images/video-thumbnail.jpg') }}" alt="How Pixelllo Works" onerror="this.src='https://images.unsplash.com/photo-1533750516457-a7f992034fec?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1756&q=80'">
                    <div class="play-button">
                        <i class="fas fa-play"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="steps-section">
            <h2 class="section-title">How to Participate in 4 Easy Steps</h2>
            
            <div class="step-timeline">
                <div class="timeline-line"></div>
                
                <div class="step-card">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3>Create an Account</h3>
                        <p>Sign up for a free Pixelllo account. The registration process takes less than a minute, and you'll be ready to start bidding!</p>
                        <div class="step-image">
                            <img src="{{ asset('images/steps/create-account.jpg') }}" alt="Create Account" onerror="this.src='https://images.unsplash.com/photo-1579762715118-a6f1d4b934f1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1756&q=80'">
                        </div>
                        <a href="{{ route('register') }}" class="btn btn-outline">Register Now</a>
                    </div>
                </div>
                
                <div class="step-card right">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3>Purchase Bid Packages</h3>
                        <p>Buy bid packages to participate in auctions. Each bid costs one bid credit, and we offer various packages to suit your needs and budget.</p>
                        <div class="step-image">
                            <img src="{{ asset('images/steps/purchase-bids.jpg') }}" alt="Purchase Bids" onerror="this.src='https://images.unsplash.com/photo-1601597111158-2fceff292cdc?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80'">
                        </div>
                        <div class="bid-packages-preview">

                        @foreach($bidPackages as $bp)

                            <div class="bid-package">
                                <h4>{{$bp->name}}</h4>
                                <div class="bid-amount">{{$bp->bidAmount}} Bids</div>
                                <div class="package-price">AED {{$bp->price}}</div>
                            </div>

                        @endforeach
                            <!--<div class="bid-package">
                                <h4>Popular</h4>
                                <div class="bid-amount">100 Bids</div>
                                <div class="package-price">AED 50</div>
                            </div>
                            <div class="bid-package">
                                <h4>Premium</h4>
                                <div class="bid-amount">250 Bids</div>
                                <div class="package-price">AED 100</div>
                            </div> -->
                        </div>
                    </div>
                </div>
                
                <div class="step-card">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3>Place Your Bids</h3>
                        <p>Browse active auctions and place bids on items you're interested in. Each bid increases the price by $0.01 and adds time to the clock. Strategy is key!</p>
                        <div class="step-image">
                            <img src="{{ asset('images/steps/place-bids.jpg') }}" alt="Place Bids" onerror="this.src='https://images.unsplash.com/photo-1588365942786-4be8943f14f9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80'">
                        </div>
                        <div class="bidding-features">
                            <div class="feature">
                                <i class="fas fa-robot"></i>
                                <span>Auto-Bidder</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-bell"></i>
                                <span>Auction Alerts</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-history"></i>
                                <span>Bid History</span>
                            </div>
                        </div>
                        <a href="{{ route('auctions') }}" class="btn btn-outline">Browse Auctions</a>
                    </div>
                </div>
                
                <div class="step-card right">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h3>Win & Save Big</h3>
                        <p>If you're the last bidder when the timer reaches zero, you win! Pay the final auction price and shipping to receive your item – often at a fraction of retail price.</p>
                        <div class="step-image">
                            <img src="{{ asset('images/steps/win-save.jpg') }}" alt="Win and Save" onerror="this.src='https://images.unsplash.com/photo-1531482615713-2afd69097998?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80'">
                        </div>
                        <div class="savings-examples">
                            <div class="saving-example">
                                <div class="item-name">MacBook Pro</div>
                                <div class="price-comparison">
                                    <span class="retail">AED 1,999</span>
                                    <span class="vs">vs</span>
                                    <span class="final">AED 26.45</span>
                                </div>
                                <div class="savings-percent">99% SAVINGS</div>
                            </div>
                            <div class="saving-example">
                                <div class="item-name">iPhone 14 Pro</div>
                                <div class="price-comparison">
                                    <span class="retail">AED 1,099</span>
                                    <span class="vs">vs</span>
                                    <span class="final">AED 18.76</span>
                                </div>
                                <div class="savings-percent">98% SAVINGS</div>
                            </div>
                        </div>
                        <a href="{{ route('winners') }}" class="btn btn-outline">See Winners</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bidding-strategy">
            <h2 class="section-title">Bidding Strategies & Tips</h2>
            
            <div class="strategy-grid">
                <div class="strategy-card">
                    <div class="strategy-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>Timing is Everything</h3>
                    <p>Pay attention to auction end times. Placing bids when there's less competition can increase your chances of winning.</p>
                </div>
                
                <div class="strategy-card">
                    <div class="strategy-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h3>Use Auto-Bidder</h3>
                    <p>Set up the auto-bidder for auctions you really want to win. It will place bids automatically according to your settings.</p>
                </div>
                
                <div class="strategy-card">
                    <div class="strategy-icon">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <h3>Bid Budget Management</h3>
                    <p>Set a budget for each auction and stick to it. Remember to account for both bid costs and the final auction price.</p>
                </div>
                
                <div class="strategy-card">
                    <div class="strategy-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>Watch Before Bidding</h3>
                    <p>Observe a few auctions before jumping in. Understanding bidding patterns can help you develop your strategy.</p>
                </div>
                
                <div class="strategy-card">
                    <div class="strategy-icon">
                        <i class="fas fa-hand-paper"></i>
                    </div>
                    <h3>Selective Bidding</h3>
                    <p>Focus on a few auctions rather than spreading your bids too thin across many different items.</p>
                </div>
                
                <div class="strategy-card">
                    <div class="strategy-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Consider Competition</h3>
                    <p>Check how many users are bidding and watching an auction. Items with less attention may offer better winning chances.</p>
                </div>
            </div>
        </div>
        
        <div class="faq-section">
            <h2 class="section-title">Frequently Asked Questions</h2>
            
            <div class="accordion">
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span>How do penny auctions differ from traditional auctions?</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        <p>In traditional auctions, only the winner pays. In penny auctions, each bid costs a small fee, which is how participants can win items at such steep discounts. The final price is determined by the number of bids placed, with each bid typically increasing the price by just $0.01.</p>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span>What happens to my bids if I don't win an auction?</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        <p>Bids are consumed when placed, regardless of whether you win the auction or not. This is part of the penny auction model and why winners can get such significant discounts. Think of bids as the cost of participation, similar to buying lottery tickets.</p>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span>How does the auction timer work?</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        <p>Each auction has a countdown timer. When a bid is placed, the timer is extended (typically by 10-20 seconds). This gives other users a chance to place additional bids. The auction ends when the timer reaches zero, and the last bidder wins the item.</p>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span>Are the products new and authentic?</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        <p>Yes, all products on Pixelllo are brand new, authentic, and come with full manufacturer warranties. We source our products directly from authorized distributors and retailers to ensure quality and authenticity.</p>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span>How do I pay for items I've won?</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        <p>After winning an auction, you'll need to pay the final auction price plus shipping and handling. We accept all major credit cards, PayPal, and various other payment methods. You'll have 24 hours to complete your purchase after winning.</p>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <button class="accordion-header">
                        <span>Can I return items if I'm not satisfied?</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        <p>We offer a 30-day satisfaction guarantee on all items. If you're not completely satisfied with your purchase, you can return it for a full refund of the final auction price and shipping costs. Please note that the cost of bids used is non-refundable.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="testimonials-section">
            <h2 class="section-title">What Our Users Say</h2>
            
            <div class="testimonials-slider">
                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="testimonial-avatar">
                            <img src="{{ asset('images/avatars/user1.jpg') }}" alt="Sarah T." onerror="this.src='https://randomuser.me/api/portraits/women/45.jpg'">
                        </div>
                        <div class="testimonial-user">
                            <h4>Sarah T.</h4>
                            <div class="testimonial-location">California</div>
                            <div class="testimonial-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        <p>"I was skeptical at first, but after winning a MacBook Pro for just AED 33, I'm completely hooked! The bidding process is exciting, and the savings are incredible. Pixelllo has become my go-to for electronics."</p>
                    </div>
                    <div class="testimonial-winnings">
                        <div class="winning-item">MacBook Pro 14"</div>
                        <div class="winning-price">Won for AED 33.47</div>
                        <div class="winning-saving">Saved 98%</div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="testimonial-avatar">
                            <img src="{{ asset('images/avatars/user2.jpg') }}" alt="Michael R." onerror="this.src='https://randomuser.me/api/portraits/men/36.jpg'">
                        </div>
                        <div class="testimonial-user">
                            <h4>Michael R.</h4>
                            <div class="testimonial-location">New York</div>
                            <div class="testimonial-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        <p>"The adrenaline rush of those final seconds in an auction is unlike anything else! I've won several items at amazing prices, and the customer service is top-notch. The auto-bidder feature is a game-changer."</p>
                    </div>
                    <div class="testimonial-winnings">
                        <div class="winning-item">Sony PlayStation 5</div>
                        <div class="winning-price">Won for AED 22.18</div>
                        <div class="winning-saving">Saved 96%</div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="testimonial-avatar">
                            <img src="{{ asset('images/avatars/user3.jpg') }}" alt="Jennifer L." onerror="this.src='https://randomuser.me/api/portraits/women/63.jpg'">
                        </div>
                        <div class="testimonial-user">
                            <h4>Jennifer L.</h4>
                            <div class="testimonial-location">Texas</div>
                            <div class="testimonial-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        <p>"Pixelllo made shopping fun again! It's like a game where you can win amazing prizes. The site is easy to use, and the auctions are addictive. I've already recommended it to all my friends and family."</p>
                    </div>
                    <div class="testimonial-winnings">
                        <div class="winning-item">Diamond Pendant</div>
                        <div class="winning-price">Won for AED 28.75</div>
                        <div class="winning-saving">Saved 99%</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="cta-section">
            <h2>Ready to Start Bidding?</h2>
            <p>Join thousands of satisfied customers who have saved up to 99% on brand new products.</p>
            <div class="cta-buttons">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Create Account</a>
                <a href="{{ route('auctions') }}" class="btn btn-outline btn-lg">Browse Auctions</a>
            </div>
        </div>
    </div>
</div>

<style>
.how-it-works-page {
    padding-bottom: 60px;
}

.hero-banner {
    background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
        url('https://images.unsplash.com/photo-1629904853893-c2c8c2d58d10?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80');
    background-size: cover;
    background-position: center;
    color: white;
    padding: 80px 0;
    text-align: center;
    margin-bottom: 60px;
}

.hero-banner h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 20px;
}

.hero-description {
    font-size: 1.2rem;
    max-width: 700px;
    margin: 0 auto;
}

.section-title {
    font-size: 2rem;
    font-weight: 700;
    text-align: center;
    margin-bottom: 40px;
    color: var(--dark);
}

/* Intro Section */
.intro-section {
    display: flex;
    gap: 40px;
    margin-bottom: 60px;
    align-items: center;
}

.intro-content {
    flex: 1;
}

.intro-content h2 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 20px;
    color: var(--dark);
}

.intro-content p {
    font-size: 1.05rem;
    margin-bottom: 15px;
    line-height: 1.6;
    color: var(--secondary-text);
}

.intro-video {
    flex: 1;
}

.video-placeholder {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    height: 300px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    cursor: pointer;
}

.video-placeholder img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.play-button {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 70px;
    height: 70px;
    background-color: rgba(255, 255, 255, 0.8);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: var(--secondary-color);
    transition: all 0.3s;
}

.video-placeholder:hover .play-button {
    background-color: var(--secondary-color);
    color: white;
}

/* Steps Section */
.steps-section {
    margin-bottom: 60px;
}

.step-timeline {
    position: relative;
    padding: 30px 0;
}

.timeline-line {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 50%;
    width: 4px;
    background-color: var(--secondary-color);
    transform: translateX(-50%);
}

.step-card {
    position: relative;
    display: flex;
    margin-bottom: 60px;
    width: 50%;
    padding-right: 40px;
}

.step-card.right {
    margin-left: 50%;
    padding-right: 0;
    padding-left: 40px;
}

.step-number {
    position: absolute;
    top: 0;
    right: -20px;
    width: 40px;
    height: 40px;
    background-color: var(--secondary-color);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    font-weight: 700;
    z-index: 2;
}

.step-card.right .step-number {
    right: auto;
    left: -20px;
}

.step-content {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    padding: 25px;
    width: 100%;
}

.step-content h3 {
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 15px;
    color: var(--dark);
}

.step-content p {
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 20px;
    color: var(--secondary-text);
}

.step-image {
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 20px;
    height: 200px;
}

.step-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Bid Packages Preview */
.bid-packages-preview {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.bid-package {
    flex: 1;
    background-color: #f9f9f9;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
}

.bid-package h4 {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 5px;
    color: var(--dark);
}

.bid-amount {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--secondary-color);
    margin-bottom: 5px;
}

.package-price {
    font-size: 0.9rem;
    color: var(--secondary-text);
}

/* Bidding Features */
.bidding-features {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}

.feature {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    color: var(--secondary-text);
}

.feature i {
    color: var(--secondary-color);
}

/* Savings Examples */
.savings-examples {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}

.saving-example {
    flex: 1;
    background-color: #f9f9f9;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
}

.item-name {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 5px;
    color: var(--dark);
}

.price-comparison {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-bottom: 5px;
}

.retail {
    text-decoration: line-through;
    color: var(--secondary-text);
}

.vs {
    font-size: 0.8rem;
    color: var(--secondary-text);
}

.final {
    font-weight: 700;
    color: var(--success);
}

.savings-percent {
    font-size: 0.8rem;
    font-weight: 700;
    color: white;
    background-color: var(--success);
    padding: 3px 8px;
    border-radius: 10px;
    display: inline-block;
}

/* Bidding Strategy */
.bidding-strategy {
    margin-bottom: 60px;
}

.strategy-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.strategy-card {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    padding: 25px;
    text-align: center;
    transition: transform 0.3s, box-shadow 0.3s;
}

.strategy-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.strategy-icon {
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

.strategy-card h3 {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 15px;
    color: var(--dark);
}

.strategy-card p {
    font-size: 0.95rem;
    line-height: 1.6;
    color: var(--secondary-text);
}

/* FAQ Section */
.faq-section {
    margin-bottom: 60px;
}

.accordion {
    max-width: 800px;
    margin: 0 auto;
}

.accordion-item {
    margin-bottom: 15px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.accordion-header {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background-color: white;
    border: none;
    text-align: left;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--dark);
    cursor: pointer;
    transition: background-color 0.3s;
}

.accordion-header:hover {
    background-color: #f9f9f9;
}

.accordion-header i {
    transition: transform 0.3s;
}

.accordion-item.active .accordion-header i {
    transform: rotate(180deg);
}

.accordion-content {
    padding: 0 20px;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-out, padding 0.3s ease;
}

.accordion-item.active .accordion-content {
    padding: 0 20px 20px;
    max-height: 500px;
}

.accordion-content p {
    margin: 0;
    line-height: 1.6;
    color: var(--secondary-text);
}

/* Testimonials Section */
.testimonials-section {
    margin-bottom: 60px;
}

.testimonials-slider {
    display: flex;
    gap: 30px;
    overflow-x: auto;
    padding: 10px 5px 20px;
    scrollbar-width: thin;
}

.testimonials-slider::-webkit-scrollbar {
    height: 8px;
}

.testimonials-slider::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.testimonials-slider::-webkit-scrollbar-thumb {
    background: #ddd;
    border-radius: 10px;
}

.testimonials-slider::-webkit-scrollbar-thumb:hover {
    background: #ccc;
}

.testimonial-card {
    flex: 0 0 350px;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    padding: 25px;
}

.testimonial-header {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}

.testimonial-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    overflow: hidden;
}

.testimonial-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.testimonial-user h4 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 5px;
    color: var(--dark);
}

.testimonial-location {
    font-size: 0.9rem;
    color: var(--secondary-text);
    margin-bottom: 5px;
}

.testimonial-rating {
    color: #ffc107;
}

.testimonial-content {
    font-style: italic;
    margin-bottom: 20px;
    line-height: 1.6;
    color: var(--secondary-text);
}

.testimonial-winnings {
    background-color: #f9f9f9;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
}

.winning-item {
    font-weight: 600;
    margin-bottom: 5px;
    color: var(--dark);
}

.winning-price {
    font-size: 0.9rem;
    color: var(--secondary-text);
    margin-bottom: 5px;
}

.winning-saving {
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--success);
}

/* CTA Section */
.cta-section {
    background-color: #f9f9f9;
    border-radius: 10px;
    padding: 40px;
    text-align: center;
}

.cta-section h2 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 15px;
    color: var(--dark);
}

.cta-section p {
    font-size: 1.1rem;
    margin-bottom: 25px;
    color: var(--secondary-text);
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.cta-buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
}

.btn-lg {
    padding: 12px 25px;
    font-size: 1.1rem;
}

/* Responsive Styles */
@media (max-width: 992px) {
    .intro-section {
        flex-direction: column;
    }
    
    .strategy-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .step-timeline {
        padding-left: 30px;
    }
    
    .timeline-line {
        left: 30px;
    }
    
    .step-card, .step-card.right {
        width: 100%;
        margin-left: 0;
        padding-left: 50px;
        padding-right: 0;
    }
    
    .step-number, .step-card.right .step-number {
        left: 10px;
        right: auto;
    }
}

@media (max-width: 768px) {
    .hero-banner h1 {
        font-size: 2.5rem;
    }
    
    .section-title {
        font-size: 1.8rem;
    }
    
    .strategy-grid {
        grid-template-columns: 1fr;
    }
    
    .bid-packages-preview, .savings-examples {
        flex-direction: column;
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Accordion functionality
    const accordionItems = document.querySelectorAll('.accordion-item');

    accordionItems.forEach(item => {
        const header = item.querySelector('.accordion-header');
        
        header.addEventListener('click', () => {
            // Close all accordion items
            accordionItems.forEach(accItem => {
                if (accItem !== item) {
                    accItem.classList.remove('active');
                }
            });
            
            // Toggle current item
            item.classList.toggle('active');
        });
    });
    
    // Open first accordion item by default
    if (accordionItems.length > 0) {
        accordionItems[0].classList.add('active');
    }
    
    // Video placeholder functionality
    const videoPlaceholder = document.querySelector('.video-placeholder');
    
    if (videoPlaceholder) {
        videoPlaceholder.addEventListener('click', function() {
            alert('In a production environment, this would play a video explaining how Pixelllo works.');
        });
    }
});
</script>
@endsection