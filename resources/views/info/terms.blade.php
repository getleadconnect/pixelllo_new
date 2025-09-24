@extends('layouts.app')

@section('title', 'Terms of Service - ' . config('app.name'))

@section('content')
<div class="terms-page">
    <div class="hero-banner">
        <div class="container">
            <h1>Terms of Service</h1>
            <p class="hero-description">Please review these terms carefully before using our service.</p>
        </div>
    </div>
    
    <div class="container">
        <div class="terms-content">
            <div class="terms-last-updated">
                <p>Last Updated: May 15, 2023</p>
            </div>
            
            <div class="terms-intro">
                <p>Welcome to Pixelllo ("we," "our," or "us"). Please read these Terms of Service ("Terms") carefully as they contain important information about your legal rights, remedies, and obligations. By accessing or using the Pixelllo platform, you agree to comply with and be bound by these Terms.</p>
                
                <p>If you do not agree to these Terms, please do not access or use our platform.</p>
            </div>
            
            <div class="terms-section">
                <h2>1. Account Registration</h2>
                <div class="terms-subsection">
                    <h3>1.1 Account Creation</h3>
                    <p>To use certain features of our platform, you must register for an account. When you register, you agree to provide accurate, current, and complete information about yourself. You are responsible for maintaining the confidentiality of your account information, including your password.</p>
                </div>
                
                <div class="terms-subsection">
                    <h3>1.2 Account Requirements</h3>
                    <p>You must be at least 18 years old and capable of forming a binding contract to register for an account. By creating an account, you represent and warrant that you meet these requirements.</p>
                </div>
                
                <div class="terms-subsection">
                    <h3>1.3 Account Security</h3>
                    <p>You are solely responsible for maintaining the security of your account and for all activities that occur under your account. You agree to notify us immediately of any unauthorized use of your account or any other breach of security.</p>
                </div>
            </div>
            
            <div class="terms-section">
                <h2>2. Auction Rules</h2>
                <div class="terms-subsection">
                    <h3>2.1 Bid Credits</h3>
                    <p>Participation in auctions requires the use of bid credits. Bid credits must be purchased and are non-refundable once used in an auction. Unused bid credits in your account do not expire and remain available for future auctions.</p>
                </div>
                
                <div class="terms-subsection">
                    <h3>2.2 Bidding Process</h3>
                    <p>Each bid placed increases the auction price by a predetermined increment (typically $0.01) and extends the auction timer. The last person to place a bid when the timer reaches zero wins the auction.</p>
                </div>
                
                <div class="terms-subsection">
                    <h3>2.3 Auction Integrity</h3>
                    <p>We are committed to maintaining the integrity of our auctions. Any attempt to manipulate the bidding process, including but not limited to using multiple accounts, automated bidding software (other than our official auto-bidder feature), or collusion with other users, is strictly prohibited and may result in account termination.</p>
                </div>
                
                <div class="terms-subsection">
                    <h3>2.4 Payment and Shipping</h3>
                    <p>Winners must complete payment for the final auction price plus applicable shipping and handling charges within 24 hours of winning. Failure to complete payment may result in forfeiture of the item, and your account may be subject to restrictions.</p>
                </div>
            </div>
            
            <div class="terms-section">
                <h2>3. Intellectual Property</h2>
                <div class="terms-subsection">
                    <h3>3.1 Platform Content</h3>
                    <p>All content on our platform, including but not limited to text, graphics, logos, icons, images, audio clips, digital downloads, and software, is the property of Pixelllo or its content suppliers and is protected by copyright, trademark, and other intellectual property laws.</p>
                </div>
                
                <div class="terms-subsection">
                    <h3>3.2 Limited License</h3>
                    <p>We grant you a limited, non-exclusive, non-transferable, and revocable license to access and use our platform for its intended purpose. This license does not include the right to:</p>
                    <ul>
                        <li>Modify, reproduce, or distribute any content from our platform</li>
                        <li>Use any data mining, robots, or similar data gathering methods</li>
                        <li>Use our platform or its content for any commercial purpose</li>
                        <li>Access our platform in an attempt to build a similar or competitive website or service</li>
                    </ul>
                </div>
            </div>
            
            <div class="terms-section">
                <h2>4. Prohibited Activities</h2>
                <p>In using our platform, you agree not to:</p>
                <ul>
                    <li>Violate any applicable laws or regulations</li>
                    <li>Infringe upon the rights of others</li>
                    <li>Use our platform to transmit any harmful code or conduct denial of service attacks</li>
                    <li>Attempt to gain unauthorized access to any portion of our platform or any systems or networks connected to our platform</li>
                    <li>Harvest or collect user information without their consent</li>
                    <li>Impersonate any person or entity or falsely state or otherwise misrepresent your affiliation with a person or entity</li>
                    <li>Interfere with or disrupt the operation of our platform</li>
                    <li>Use multiple accounts to participate in the same auction</li>
                </ul>
            </div>
            
            <div class="terms-section">
                <h2>5. Termination</h2>
                <p>We reserve the right to terminate or suspend your account and access to our platform at our sole discretion, without notice, for conduct that we believe violates these Terms or is harmful to other users, us, or third parties, or for any other reason.</p>
            </div>
            
            <div class="terms-section">
                <h2>6. Disclaimer of Warranties</h2>
                <p>OUR PLATFORM IS PROVIDED ON AN "AS IS" AND "AS AVAILABLE" BASIS. TO THE FULLEST EXTENT PERMITTED BY LAW, WE DISCLAIM ALL WARRANTIES OF ANY KIND, WHETHER EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, AND NON-INFRINGEMENT.</p>
                <p>WE DO NOT WARRANT THAT OUR PLATFORM WILL BE UNINTERRUPTED, TIMELY, SECURE, OR ERROR-FREE, THAT DEFECTS WILL BE CORRECTED, OR THAT OUR PLATFORM OR THE SERVERS THAT MAKE IT AVAILABLE ARE FREE OF VIRUSES OR OTHER HARMFUL COMPONENTS.</p>
            </div>
            
            <div class="terms-section">
                <h2>7. Limitation of Liability</h2>
                <p>TO THE FULLEST EXTENT PERMITTED BY LAW, IN NO EVENT SHALL WE, OUR AFFILIATES, OR OUR RESPECTIVE OFFICERS, DIRECTORS, EMPLOYEES, OR AGENTS BE LIABLE FOR ANY INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL, OR PUNITIVE DAMAGES, INCLUDING BUT NOT LIMITED TO LOSS OF PROFITS, DATA, USE, OR GOODWILL, ARISING OUT OF OR IN CONNECTION WITH THESE TERMS OR YOUR USE OF OUR PLATFORM.</p>
                <p>IN NO EVENT SHALL OUR AGGREGATE LIABILITY FOR ALL CLAIMS RELATED TO THESE TERMS OR OUR PLATFORM EXCEED THE GREATER OF $100 OR THE AMOUNT YOU PAID TO US IN THE PAST SIX MONTHS.</p>
            </div>
            
            <div class="terms-section">
                <h2>8. Indemnification</h2>
                <p>You agree to indemnify, defend, and hold harmless Pixelllo, its affiliates, and their respective officers, directors, employees, and agents from and against any and all claims, liabilities, damages, losses, costs, expenses, or fees (including reasonable attorneys' fees) that arise from or relate to:</p>
                <ul>
                    <li>Your use of or access to our platform</li>
                    <li>Your violation of these Terms</li>
                    <li>Your violation of any rights of another person or entity</li>
                    <li>Your user content or conduct in connection with our platform</li>
                </ul>
            </div>
            
            <div class="terms-section">
                <h2>9. Disputes and Governing Law</h2>
                <div class="terms-subsection">
                    <h3>9.1 Governing Law</h3>
                    <p>These Terms shall be governed by and construed in accordance with the laws of the State of California, without regard to its conflict of law provisions.</p>
                </div>
                
                <div class="terms-subsection">
                    <h3>9.2 Dispute Resolution</h3>
                    <p>Any dispute arising from or relating to these Terms or our platform shall be resolved through binding arbitration in San Francisco, California, administered by the American Arbitration Association in accordance with its Commercial Arbitration Rules.</p>
                </div>
                
                <div class="terms-subsection">
                    <h3>9.3 Class Action Waiver</h3>
                    <p>YOU AGREE THAT ANY DISPUTE RESOLUTION PROCEEDINGS WILL BE CONDUCTED ONLY ON AN INDIVIDUAL BASIS AND NOT IN A CLASS, CONSOLIDATED, OR REPRESENTATIVE ACTION.</p>
                </div>
            </div>
            
            <div class="terms-section">
                <h2>10. Changes to Terms</h2>
                <p>We may modify these Terms at any time. If we make changes, we will provide notice by posting the updated Terms on our platform and updating the "Last Updated" date. Your continued use of our platform after any such changes constitutes your acceptance of the new Terms.</p>
            </div>
            
            <div class="terms-section">
                <h2>11. Contact Information</h2>
                <p>If you have any questions about these Terms, please contact us at:</p>
                <div class="contact-info">
                    <p>Email: legal@pixelllo.com</p>
                    <p>Address: 123 Auction St, Suite 456, San Francisco, CA 94107</p>
                    <p>Phone: +1 (555) 123-4567</p>
                </div>
            </div>
        </div>
        
        <div class="terms-agreement">
            <p>By using Pixelllo, you acknowledge that you have read, understood, and agree to be bound by these Terms of Service.</p>
        </div>
    </div>
</div>

<style>
    .terms-page {
        padding-bottom: 60px;
    }
    
    .hero-banner {
        background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
            url('https://images.unsplash.com/photo-1450101499163-c8848c66ca85?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80');
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
    
    .terms-content {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        padding: 40px;
        margin-bottom: 30px;
    }
    
    .terms-last-updated {
        text-align: right;
        margin-bottom: 20px;
        color: var(--secondary-text);
        font-size: 0.9rem;
        font-style: italic;
    }
    
    .terms-intro {
        margin-bottom: 30px;
        padding-bottom: 30px;
        border-bottom: 1px solid #eee;
    }
    
    .terms-intro p {
        font-size: 1.05rem;
        margin-bottom: 15px;
        line-height: 1.6;
        color: var(--secondary-text);
    }
    
    .terms-section {
        margin-bottom: 30px;
        padding-bottom: 30px;
        border-bottom: 1px solid #eee;
    }
    
    .terms-section:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .terms-section h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--dark);
    }
    
    .terms-subsection {
        margin-bottom: 20px;
    }
    
    .terms-subsection h3 {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: var(--dark);
    }
    
    .terms-section p,
    .terms-subsection p {
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 15px;
        color: var(--secondary-text);
    }
    
    .terms-section ul {
        margin-left: 20px;
        margin-bottom: 15px;
    }
    
    .terms-section li {
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 8px;
        color: var(--secondary-text);
    }
    
    .contact-info {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        margin-top: 15px;
    }
    
    .contact-info p {
        margin-bottom: 8px;
    }
    
    .contact-info p:last-child {
        margin-bottom: 0;
    }
    
    .terms-agreement {
        background-color: #f9f9f9;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
    }
    
    .terms-agreement p {
        font-size: 1.05rem;
        font-weight: 500;
        color: var(--dark);
    }
    
    @media (max-width: 768px) {
        .hero-banner h1 {
            font-size: 2rem;
        }
        
        .terms-content {
            padding: 25px;
        }
        
        .terms-section h2 {
            font-size: 1.3rem;
        }
        
        .terms-subsection h3 {
            font-size: 1.1rem;
        }
    }
</style>
@endsection