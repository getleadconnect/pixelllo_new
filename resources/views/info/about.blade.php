@extends('layouts.app')

@section('title', 'About Us - ' . config('app.name'))

@section('content')
<div class="about-page">
    <div class="hero-banner">
        <div class="container">
            <h1>About Pixelllo</h1>
            <p class="hero-description">Get to know the team behind the most exciting penny auction platform.</p>
        </div>
    </div>
    
    <div class="container">
        <div class="about-intro">
            <div class="about-content">
                <h2 class="section-header">Our Story</h2>
                <p>Pixelllo was founded in 2019 by a group of e-commerce and auction enthusiasts who saw an opportunity to revolutionize the online shopping experience. Frustrated with the lack of transparency and excitement in traditional e-commerce, we set out to create a platform that combines the thrill of competitive bidding with incredible savings potential.</p>
                <p>What started as a small operation with just a few auctions per day has now grown into one of the most trusted penny auction sites in the industry, with thousands of satisfied customers and an ever-expanding catalog of premium products.</p>
            </div>
            <div class="about-image">
                <img src="https://images.unsplash.com/photo-1558403194-611308249627?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80" alt="Pixelllo founding team">
            </div>
        </div>
        
        <div class="about-mission">
            <h2 class="section-header centered">Our Mission</h2>
            <div class="mission-statement">
                <div class="mission-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <p>"To create an engaging auction platform that delivers exceptional value and excitement for our users while maintaining the highest standards of fairness and transparency."</p>
            </div>
        </div>
        
        <div class="about-values">
            <h2 class="section-header centered">Our Values</h2>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <h3>Fairness</h3>
                    <p>We are committed to maintaining a level playing field for all our users. Our auctions are conducted with complete transparency, and we continuously monitor our platform to ensure fair play.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-gem"></i>
                    </div>
                    <h3>Quality</h3>
                    <p>We only offer authentic, brand-new products from reputable manufacturers. Each item is carefully sourced and verified to ensure our customers receive exactly what they bid on.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3>Customer Focus</h3>
                    <p>Our users are at the heart of everything we do. We prioritize responsive customer service, actively seek feedback, and continuously improve our platform based on user suggestions.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Security</h3>
                    <p>We implement rigorous security measures to protect our users' data and financial information. Your privacy and security are paramount to us.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3>Innovation</h3>
                    <p>We're constantly developing new features and improving our platform to provide the most engaging auction experience possible. We embrace technology and creativity in everything we do.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Community</h3>
                    <p>We foster a friendly, supportive community of bidders who share a passion for great deals. We celebrate our winners and encourage a positive atmosphere.</p>
                </div>
            </div>
        </div>
        
        <div class="about-difference">
            <h2 class="section-header">The Pixelllo Difference</h2>
            <div class="difference-content">
                <div class="difference-block">
                    <h3>Verified Authenticity</h3>
                    <p>Every product on our platform is 100% authentic and brand new. We source directly from authorized distributors and manufacturers to guarantee quality.</p>
                </div>
                
                <div class="difference-block">
                    <h3>Transparent Operations</h3>
                    <p>Our auction system is fully transparent. We provide complete bidding histories, real-time updates, and clear rules to ensure you always know exactly what's happening.</p>
                </div>
                
                <div class="difference-block">
                    <h3>Advanced Features</h3>
                    <p>From our state-of-the-art auto-bidder to personalized auction alerts, we offer a suite of sophisticated tools to enhance your bidding strategy and experience.</p>
                </div>
                
                <div class="difference-block">
                    <h3>Responsive Support</h3>
                    <p>Our dedicated customer service team is available 7 days a week to assist with any questions or concerns. We pride ourselves on fast, friendly, and effective support.</p>
                </div>
            </div>
        </div>
        
        <div class="team-section">
            <h2 class="section-header centered">Meet Our Leadership Team</h2>
            <div class="team-grid">
                <div class="team-member">
                    <div class="member-photo">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Michael Chen">
                    </div>
                    <h3>Michael Chen</h3>
                    <p class="member-title">CEO & Co-Founder</p>
                    <p class="member-bio">With over 15 years of experience in e-commerce and digital marketplaces, Michael leads our strategic vision and oversees all aspects of our operation.</p>
                </div>
                
                <div class="team-member">
                    <div class="member-photo">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Sarah Johnson">
                    </div>
                    <h3>Sarah Johnson</h3>
                    <p class="member-title">CTO & Co-Founder</p>
                    <p class="member-bio">A computer science Ph.D., Sarah leads our engineering team and is the architectural genius behind our secure and scalable auction platform.</p>
                </div>
                
                <div class="team-member">
                    <div class="member-photo">
                        <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="David Rodriguez">
                    </div>
                    <h3>David Rodriguez</h3>
                    <p class="member-title">COO</p>
                    <p class="member-bio">David ensures that our day-to-day operations run smoothly, overseeing everything from inventory management to fulfillment and customer service.</p>
                </div>
                
                <div class="team-member">
                    <div class="member-photo">
                        <img src="https://randomuser.me/api/portraits/women/17.jpg" alt="Emily Kim">
                    </div>
                    <h3>Emily Kim</h3>
                    <p class="member-title">Chief Product Officer</p>
                    <p class="member-bio">With a background in UX design and product management, Emily leads the continuous improvement of our user experience and feature development.</p>
                </div>
            </div>
        </div>
        
        <div class="metrics-section">
            <h2 class="section-header centered">Pixelllo by the Numbers</h2>
            <div class="metrics-grid">
                <div class="metric-card">
                    <div class="metric-number">50,000+</div>
                    <div class="metric-label">Registered Users</div>
                </div>
                
                <div class="metric-card">
                    <div class="metric-number">10,000+</div>
                    <div class="metric-label">Successful Auctions</div>
                </div>
                
                <div class="metric-card">
                    <div class="metric-number">$2.5M+</div>
                    <div class="metric-label">Customer Savings</div>
                </div>
                
                <div class="metric-card">
                    <div class="metric-number">4.8/5</div>
                    <div class="metric-label">Average Rating</div>
                </div>
            </div>
        </div>
        
        <div class="testimonials">
            <h2 class="section-header centered">What Our Users Say</h2>
            <div class="testimonials-container">
                <div class="testimonial">
                    <div class="testimonial-content">
                        <p>"After trying several penny auction sites, Pixelllo stands out for its fairness and great selection. I've won three auctions so far, with my biggest win being a MacBook Pro for just $41.82!"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="Robert K.">
                        </div>
                        <div class="author-details">
                            <h4>Robert K.</h4>
                            <p>Member since 2020</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial">
                    <div class="testimonial-content">
                        <p>"What sets Pixelllo apart is their amazing customer service. When I had an issue with a shipment, they resolved it within hours. Plus, their auto-bidder feature has helped me win multiple auctions!"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <img src="https://randomuser.me/api/portraits/women/28.jpg" alt="Jessica M.">
                        </div>
                        <div class="author-details">
                            <h4>Jessica M.</h4>
                            <p>Member since 2021</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial">
                    <div class="testimonial-content">
                        <p>"I was skeptical at first, but Pixelllo has proven to be trustworthy and exciting. The platform is intuitive, and I love the rush of those final seconds in an auction. I'm officially addicted!"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <img src="https://randomuser.me/api/portraits/men/22.jpg" alt="Thomas L.">
                        </div>
                        <div class="author-details">
                            <h4>Thomas L.</h4>
                            <p>Member since 2022</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="contact-cta">
            <h2>Have Questions About Pixelllo?</h2>
            <p>Our team is here to help. Reach out to us with any questions or feedback.</p>
            <div class="cta-buttons">
                <a href="{{ route('help') }}" class="btn btn-primary">Contact Support</a>
                <a href="{{ route('faq') }}" class="btn btn-outline">Read FAQs</a>
            </div>
        </div>
    </div>
</div>

<style>
    .about-page {
        padding-bottom: 60px;
    }
    
    .hero-banner {
        background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
            url('https://images.unsplash.com/photo-1542744173-8e7e53415bb0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1770&q=80');
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
    
    .section-header {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 30px;
        color: var(--dark);
        position: relative;
    }
    
    .section-header:after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 60px;
        height: 3px;
        background-color: var(--secondary-color);
    }
    
    .section-header.centered {
        text-align: center;
    }
    
    .section-header.centered:after {
        left: 50%;
        transform: translateX(-50%);
    }
    
    /* About Intro */
    .about-intro {
        display: flex;
        gap: 40px;
        margin-bottom: 60px;
        align-items: center;
    }
    
    .about-content {
        flex: 1;
    }
    
    .about-content p {
        font-size: 1.05rem;
        margin-bottom: 15px;
        line-height: 1.7;
        color: var(--secondary-text);
    }
    
    .about-image {
        flex: 1;
    }
    
    .about-image img {
        width: 100%;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    /* Mission Section */
    .about-mission {
        margin-bottom: 60px;
    }
    
    .mission-statement {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        padding: 40px;
        text-align: center;
        position: relative;
    }
    
    .mission-icon {
        position: absolute;
        top: -25px;
        left: 50%;
        transform: translateX(-50%);
        width: 50px;
        height: 50px;
        background-color: var(--secondary-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    
    .mission-statement p {
        font-size: 1.3rem;
        font-weight: 500;
        font-style: italic;
        color: var(--dark);
        line-height: 1.7;
    }
    
    /* Values Section */
    .about-values {
        margin-bottom: 60px;
    }
    
    .values-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }
    
    .value-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        padding: 30px;
        text-align: center;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .value-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }
    
    .value-icon {
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
    
    .value-card h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--dark);
    }
    
    .value-card p {
        font-size: 0.95rem;
        line-height: 1.6;
        color: var(--secondary-text);
    }
    
    /* Difference Section */
    .about-difference {
        margin-bottom: 60px;
    }
    
    .difference-content {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
    }
    
    .difference-block {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        padding: 25px;
        transition: transform 0.3s;
    }
    
    .difference-block:hover {
        transform: translateY(-3px);
    }
    
    .difference-block h3 {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--dark);
        position: relative;
        padding-bottom: 12px;
    }
    
    .difference-block h3:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 2px;
        background-color: var(--secondary-color);
    }
    
    .difference-block p {
        font-size: 0.95rem;
        line-height: 1.6;
        color: var(--secondary-text);
    }
    
    /* Team Section */
    .team-section {
        margin-bottom: 60px;
    }
    
    .team-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 30px;
    }
    
    .team-member {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        padding: 25px;
        text-align: center;
        transition: transform 0.3s;
    }
    
    .team-member:hover {
        transform: translateY(-5px);
    }
    
    .member-photo {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto 20px;
        border: 3px solid var(--secondary-color);
    }
    
    .member-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .team-member h3 {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 5px;
        color: var(--dark);
    }
    
    .member-title {
        font-size: 0.9rem;
        color: var(--secondary-color);
        font-weight: 600;
        margin-bottom: 15px;
    }
    
    .member-bio {
        font-size: 0.9rem;
        line-height: 1.6;
        color: var(--secondary-text);
    }
    
    /* Metrics Section */
    .metrics-section {
        margin-bottom: 60px;
    }
    
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 30px;
    }
    
    .metric-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        padding: 30px;
        text-align: center;
        border-top: 4px solid var(--secondary-color);
    }
    
    .metric-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--secondary-color);
        margin-bottom: 10px;
    }
    
    .metric-label {
        font-size: 1rem;
        color: var(--secondary-text);
    }
    
    /* Testimonials */
    .testimonials {
        margin-bottom: 60px;
    }
    
    .testimonials-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }
    
    .testimonial {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        padding: 25px;
        transition: transform 0.3s;
    }
    
    .testimonial:hover {
        transform: translateY(-5px);
    }
    
    .testimonial-content {
        position: relative;
        padding: 20px 0;
        margin-bottom: 20px;
    }
    
    .testimonial-content:before,
    .testimonial-content:after {
        content: '"';
        font-size: 3rem;
        color: rgba(255, 153, 0, 0.2);
        position: absolute;
        line-height: 1;
    }
    
    .testimonial-content:before {
        top: 0;
        left: 0;
    }
    
    .testimonial-content:after {
        bottom: -20px;
        right: 0;
        transform: rotate(180deg);
    }
    
    .testimonial-content p {
        font-size: 0.95rem;
        line-height: 1.7;
        color: var(--secondary-text);
        font-style: italic;
    }
    
    .testimonial-author {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .author-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        overflow: hidden;
    }
    
    .author-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .author-details h4 {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 5px;
        color: var(--dark);
    }
    
    .author-details p {
        font-size: 0.85rem;
        color: var(--secondary-text);
    }
    
    /* CTA Section */
    .contact-cta {
        background-color: #f9f9f9;
        border-radius: 10px;
        padding: 40px;
        text-align: center;
    }
    
    .contact-cta h2 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--dark);
    }
    
    .contact-cta p {
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
    
    /* Responsive Styles */
    @media (max-width: 1100px) {
        .team-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .metrics-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 992px) {
        .about-intro {
            flex-direction: column;
        }
        
        .values-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .difference-content {
            grid-template-columns: 1fr;
        }
        
        .testimonials-container {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .hero-banner h1 {
            font-size: 2.5rem;
        }
        
        .section-header {
            font-size: 1.8rem;
        }
        
        .values-grid {
            grid-template-columns: 1fr;
        }
        
        .team-grid {
            grid-template-columns: 1fr;
        }
        
        .metrics-grid {
            grid-template-columns: 1fr;
        }
        
        .cta-buttons {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>
@endsection