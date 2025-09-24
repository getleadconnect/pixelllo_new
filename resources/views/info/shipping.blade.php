@extends('layouts.app')

@section('title', 'Shipping & Returns - ' . config('app.name'))

@section('content')
<div class="shipping-page">
    <div class="hero-banner">
        <div class="container">
            <h1>Shipping & Returns</h1>
            <p class="hero-description">Everything you need to know about our shipping process and return policy.</p>
        </div>
    </div>
    
    <div class="container">
        <div class="shipping-content">
            <div class="shipping-intro">
                <p>At Pixelllo, we strive to provide a seamless experience from winning an auction to receiving your item. This page outlines our shipping methods, delivery timeframes, and return policy to help you understand what to expect after winning an auction.</p>
            </div>
            
            <div class="shipping-tabs">
                <div class="tab-navigation">
                    <button class="tab-button active" data-tab="shipping">Shipping</button>
                    <button class="tab-button" data-tab="delivery">Delivery Timeframes</button>
                    <button class="tab-button" data-tab="returns">Returns & Refunds</button>
                    <button class="tab-button" data-tab="international">International Orders</button>
                    <button class="tab-button" data-tab="faq">FAQ</button>
                </div>
                
                <div class="tab-content">
                    <div class="tab-pane active" id="shipping">
                        <h2>Shipping Information</h2>
                        
                        <div class="info-block">
                            <h3>Payment & Processing</h3>
                            <p>After winning an auction, you'll have 24 hours to complete your payment. This includes the final auction price plus applicable shipping and handling charges. Once your payment is confirmed, your order will be processed within 1-2 business days.</p>
                        </div>
                        
                        <div class="info-block">
                            <h3>Shipping Methods</h3>
                            <p>We offer several shipping options to meet your needs:</p>
                            
                            <div class="shipping-methods">
                                <div class="shipping-method">
                                    <div class="method-icon">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                    <div class="method-details">
                                        <h4>Standard Shipping</h4>
                                        <p>5-7 business days</p>
                                        <p>Free for orders over $50</p>
                                        <p>$5.99 for orders under $50</p>
                                    </div>
                                </div>
                                
                                <div class="shipping-method">
                                    <div class="method-icon">
                                        <i class="fas fa-truck-loading"></i>
                                    </div>
                                    <div class="method-details">
                                        <h4>Expedited Shipping</h4>
                                        <p>2-3 business days</p>
                                        <p>$12.99</p>
                                    </div>
                                </div>
                                
                                <div class="shipping-method">
                                    <div class="method-icon">
                                        <i class="fas fa-shipping-fast"></i>
                                    </div>
                                    <div class="method-details">
                                        <h4>Express Shipping</h4>
                                        <p>1-2 business days</p>
                                        <p>$19.99</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="info-block">
                            <h3>Tracking Your Order</h3>
                            <p>Once your order ships, you'll receive a confirmation email with tracking information. You can also view the status of your order in the "Orders" section of your Pixelllo dashboard.</p>
                        </div>
                        
                        <div class="info-block">
                            <h3>Shipping Insurance</h3>
                            <p>All orders are insured against loss or damage during transit. For high-value items (over $500), signature confirmation may be required upon delivery.</p>
                        </div>
                    </div>
                    
                    <div class="tab-pane" id="delivery">
                        <h2>Delivery Timeframes</h2>
                        
                        <div class="info-block">
                            <h3>Processing Time</h3>
                            <p>After payment confirmation, orders typically take 1-2 business days to process before shipping. During high-volume periods (holidays, special promotions), processing may take up to 3 business days.</p>
                        </div>
                        
                        <div class="info-block">
                            <h3>Estimated Delivery Times</h3>
                            <p>The following timeframes represent our estimated delivery windows after your order has shipped:</p>
                            
                            <div class="delivery-table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Shipping Method</th>
                                            <th>Continental U.S.</th>
                                            <th>Alaska & Hawaii</th>
                                            <th>Puerto Rico</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Standard Shipping</td>
                                            <td>5-7 business days</td>
                                            <td>7-10 business days</td>
                                            <td>7-12 business days</td>
                                        </tr>
                                        <tr>
                                            <td>Expedited Shipping</td>
                                            <td>2-3 business days</td>
                                            <td>3-5 business days</td>
                                            <td>4-6 business days</td>
                                        </tr>
                                        <tr>
                                            <td>Express Shipping</td>
                                            <td>1-2 business days</td>
                                            <td>2-3 business days</td>
                                            <td>2-4 business days</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <p class="delivery-note">Note: Business days are Monday through Friday, excluding federal holidays. Orders placed after 2:00 PM ET may be processed the following business day.</p>
                        </div>
                        
                        <div class="info-block">
                            <h3>Delivery Delays</h3>
                            <p>While we strive to deliver your order within the estimated timeframes, occasional delays may occur due to:</p>
                            <ul>
                                <li>Severe weather conditions</li>
                                <li>Natural disasters</li>
                                <li>Carrier service disruptions</li>
                                <li>Customs delays (for international shipments)</li>
                                <li>High-volume shipping periods (holidays, major sales)</li>
                            </ul>
                            <p>If your order is significantly delayed, we'll notify you via email with updated delivery information.</p>
                        </div>
                    </div>
                    
                    <div class="tab-pane" id="returns">
                        <h2>Returns & Refunds</h2>
                        
                        <div class="info-block">
                            <h3>30-Day Satisfaction Guarantee</h3>
                            <p>We offer a 30-day satisfaction guarantee on all items. If you're not completely satisfied with your purchase, you can return it for a full refund of the final auction price and shipping costs. Please note that the cost of bids used is non-refundable.</p>
                        </div>
                        
                        <div class="info-block">
                            <h3>Return Process</h3>
                            <p>To initiate a return:</p>
                            <ol>
                                <li>Log in to your Pixelllo account</li>
                                <li>Go to the "Orders" section in your dashboard</li>
                                <li>Find the order you wish to return and click "Return Item"</li>
                                <li>Follow the prompts to complete your return request</li>
                                <li>Print the prepaid return shipping label (provided for domestic returns)</li>
                                <li>Pack the item securely in its original packaging, if possible</li>
                                <li>Drop off the package at the designated carrier location</li>
                            </ol>
                        </div>
                        
                        <div class="info-block">
                            <h3>Return Conditions</h3>
                            <p>To be eligible for a return, your item must be:</p>
                            <ul>
                                <li>In the same condition that you received it</li>
                                <li>Unused and in its original packaging</li>
                                <li>With all tags and labels attached</li>
                                <li>Accompanied by the original receipt or proof of purchase</li>
                            </ul>
                        </div>
                        
                        <div class="info-block">
                            <h3>Non-Returnable Items</h3>
                            <p>The following items cannot be returned:</p>
                            <ul>
                                <li>Downloadable software products</li>
                                <li>Gift cards</li>
                                <li>Personal care items (if opened)</li>
                                <li>Health and wellness items (if opened)</li>
                                <li>Perishable goods</li>
                                <li>Custom-made or personalized items</li>
                            </ul>
                        </div>
                        
                        <div class="info-block">
                            <h3>Refund Processing</h3>
                            <p>After we receive and inspect your return, we'll send you an email to notify you that we've received your item. We'll also notify you of the approval or rejection of your refund. If approved, your refund will be processed within 5 business days, and a credit will automatically be applied to your original method of payment. Please allow 5-10 business days for the refund to appear in your account, depending on your payment provider.</p>
                        </div>
                        
                        <div class="info-block">
                            <h3>Damaged or Defective Items</h3>
                            <p>If you receive a damaged or defective item, please contact our customer support team immediately at support@pixelllo.com. Include photos of the damaged item and packaging to expedite the resolution process. We'll arrange for a replacement or full refund, including return shipping costs.</p>
                        </div>
                    </div>
                    
                    <div class="tab-pane" id="international">
                        <h2>International Orders</h2>
                        
                        <div class="info-block">
                            <h3>Countries We Ship To</h3>
                            <p>Pixelllo currently ships to the following countries:</p>
                            <div class="countries-grid">
                                <div class="country-item">Canada</div>
                                <div class="country-item">United Kingdom</div>
                                <div class="country-item">Australia</div>
                                <div class="country-item">Germany</div>
                                <div class="country-item">France</div>
                                <div class="country-item">Japan</div>
                                <div class="country-item">South Korea</div>
                                <div class="country-item">Singapore</div>
                                <div class="country-item">New Zealand</div>
                                <div class="country-item">Mexico</div>
                                <div class="country-item">Brazil</div>
                                <div class="country-item">Spain</div>
                                <div class="country-item">Italy</div>
                                <div class="country-item">Netherlands</div>
                                <div class="country-item">Sweden</div>
                                <div class="country-item">Switzerland</div>
                            </div>
                            <p class="int-shipping-note">If your country is not listed, please contact our customer service team to inquire about shipping options.</p>
                        </div>
                        
                        <div class="info-block">
                            <h3>International Shipping Rates</h3>
                            <p>International shipping rates vary based on destination, package weight, and dimensions. The exact shipping cost will be calculated during checkout after you provide your shipping address.</p>
                            
                            <p>Approximate shipping rates for standard international shipping (6-14 business days):</p>
                            <ul>
                                <li>Canada: Starting at $15.99</li>
                                <li>Europe: Starting at $24.99</li>
                                <li>Australia & New Zealand: Starting at $29.99</li>
                                <li>Asia: Starting at $34.99</li>
                                <li>Other regions: Starting at $39.99</li>
                            </ul>
                            
                            <p>Expedited international shipping (3-7 business days) is available for most destinations at an additional cost.</p>
                        </div>
                        
                        <div class="info-block">
                            <h3>Customs & Import Duties</h3>
                            <p>International customers are responsible for all customs fees, import duties, and taxes imposed by their country's government. These fees are not included in the shipping costs and will be collected by the delivery carrier or customs authority upon receipt.</p>
                            <p>We legally cannot mark packages as "gifts" or undervalue merchandise to avoid customs fees.</p>
                        </div>
                        
                        <div class="info-block">
                            <h3>International Returns</h3>
                            <p>International customers are eligible for our 30-day return policy. However, return shipping costs for international orders are the responsibility of the customer, unless the item was received damaged or defective.</p>
                            <p>To initiate an international return, please contact our customer service team at international@pixelllo.com for specific instructions.</p>
                        </div>
                    </div>
                    
                    <div class="tab-pane" id="faq">
                        <h2>Frequently Asked Questions</h2>
                        
                        <div class="faq-accordion">
                            <div class="faq-item">
                                <div class="faq-question">
                                    <h3>How long do I have to complete payment after winning an auction?</h3>
                                    <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                                </div>
                                <div class="faq-answer">
                                    <p>You have 24 hours to complete payment after winning an auction. If payment is not received within this timeframe, the item may be forfeited, and your account may be subject to restrictions.</p>
                                </div>
                            </div>
                            
                            <div class="faq-item">
                                <div class="faq-question">
                                    <h3>Can I change my shipping address after placing an order?</h3>
                                    <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                                </div>
                                <div class="faq-answer">
                                    <p>Yes, you can update your shipping address as long as your order hasn't entered the shipping phase. To change your address, contact our customer service team as soon as possible with your order number and the new shipping details.</p>
                                </div>
                            </div>
                            
                            <div class="faq-item">
                                <div class="faq-question">
                                    <h3>Do you ship to P.O. boxes?</h3>
                                    <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                                </div>
                                <div class="faq-answer">
                                    <p>Yes, we can ship to P.O. boxes within the United States for standard shipping only. Expedited and express shipping options require a physical address for delivery.</p>
                                </div>
                            </div>
                            
                            <div class="faq-item">
                                <div class="faq-question">
                                    <h3>What should I do if my package is lost or damaged?</h3>
                                    <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                                </div>
                                <div class="faq-answer">
                                    <p>If your package is lost or damaged during transit, please contact our customer service team immediately. Provide your order number and any available details about the shipment. We'll work with the carrier to locate your package or process a replacement/refund as appropriate.</p>
                                </div>
                            </div>
                            
                            <div class="faq-item">
                                <div class="faq-question">
                                    <h3>Are bid credits refundable if I return an item?</h3>
                                    <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                                </div>
                                <div class="faq-answer">
                                    <p>No, bid credits used in auctions are non-refundable, even if you return the item. The refund covers only the final auction price and shipping costs.</p>
                                </div>
                            </div>
                            
                            <div class="faq-item">
                                <div class="faq-question">
                                    <h3>Do you offer gift wrapping services?</h3>
                                    <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
                                </div>
                                <div class="faq-answer">
                                    <p>Yes, we offer gift wrapping services for an additional $5.99 per item. You can select this option during checkout. We'll wrap your item in premium paper and include a gift message of your choice.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="shipping-support">
                <h2>Need More Help?</h2>
                <p>Our customer service team is available to assist you with any shipping or return questions.</p>
                <div class="support-options">
                    <div class="support-option">
                        <div class="support-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3>Email Support</h3>
                        <p>shipping@pixelllo.com</p>
                        <p>Response within 24 hours</p>
                    </div>
                    
                    <div class="support-option">
                        <div class="support-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h3>Phone Support</h3>
                        <p>+1 (555) 123-4567</p>
                        <p>Mon-Fri: 9am - 6pm ET</p>
                    </div>
                    
                    <div class="support-option">
                        <div class="support-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h3>Live Chat</h3>
                        <p>Available on our website</p>
                        <p>7 days a week: 8am - 10pm ET</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .shipping-page {
        padding-bottom: 60px;
    }
    
    .hero-banner {
        background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
            url('https://images.unsplash.com/photo-1566576721346-d4a3b4eaeb55?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1965&q=80');
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
    
    .shipping-content {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        padding: 40px;
    }
    
    .shipping-intro {
        margin-bottom: 30px;
    }
    
    .shipping-intro p {
        font-size: 1.05rem;
        line-height: 1.6;
        color: var(--secondary-text);
    }
    
    /* Tabs */
    .shipping-tabs {
        margin-bottom: 40px;
    }
    
    .tab-navigation {
        display: flex;
        overflow-x: auto;
        border-bottom: 1px solid #eee;
        margin-bottom: 30px;
    }
    
    .tab-button {
        padding: 12px 20px;
        background: none;
        border: none;
        border-bottom: 3px solid transparent;
        font-size: 1rem;
        font-weight: 600;
        color: var(--secondary-text);
        cursor: pointer;
        transition: all 0.3s;
        white-space: nowrap;
    }
    
    .tab-button:hover {
        color: var(--dark);
    }
    
    .tab-button.active {
        color: var(--secondary-color);
        border-bottom-color: var(--secondary-color);
    }
    
    .tab-content {
        position: relative;
    }
    
    .tab-pane {
        display: none;
        animation: fadeIn 0.5s;
    }
    
    .tab-pane.active {
        display: block;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .tab-pane h2 {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 25px;
        color: var(--dark);
        position: relative;
        padding-bottom: 10px;
    }
    
    .tab-pane h2:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background-color: var(--secondary-color);
    }
    
    /* Info Blocks */
    .info-block {
        margin-bottom: 30px;
    }
    
    .info-block h3 {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: var(--dark);
    }
    
    .info-block p {
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 15px;
        color: var(--secondary-text);
    }
    
    .info-block ul,
    .info-block ol {
        margin-left: 20px;
        margin-bottom: 15px;
    }
    
    .info-block li {
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 8px;
        color: var(--secondary-text);
    }
    
    /* Shipping Methods */
    .shipping-methods {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin: 20px 0;
    }
    
    .shipping-method {
        background-color: #f9f9f9;
        border-radius: 8px;
        padding: 20px;
        display: flex;
        align-items: flex-start;
        gap: 15px;
    }
    
    .method-icon {
        width: 50px;
        height: 50px;
        background-color: var(--secondary-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    
    .method-details h4 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--dark);
    }
    
    .method-details p {
        font-size: 0.9rem;
        margin-bottom: 5px;
    }
    
    /* Delivery Table */
    .delivery-table {
        margin: 20px 0;
        overflow-x: auto;
    }
    
    .delivery-table table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .delivery-table th,
    .delivery-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
        font-size: 0.95rem;
    }
    
    .delivery-table th {
        background-color: #f9f9f9;
        font-weight: 600;
        color: var(--dark);
    }
    
    .delivery-table tr:last-child td {
        border-bottom: none;
    }
    
    .delivery-note,
    .int-shipping-note {
        font-size: 0.9rem;
        font-style: italic;
        color: var(--secondary-text);
        margin-top: 15px;
    }
    
    /* Countries Grid */
    .countries-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin: 20px 0;
    }
    
    .country-item {
        background-color: #f9f9f9;
        padding: 10px;
        border-radius: 5px;
        text-align: center;
        font-size: 0.9rem;
    }
    
    /* FAQ Accordion */
    .faq-accordion {
        margin-top: 20px;
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
    
    .faq-question h3 {
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
        max-height: 500px;
    }
    
    .faq-answer p {
        margin: 0;
        font-size: 0.95rem;
        line-height: 1.6;
        color: var(--secondary-text);
    }
    
    /* Support Section */
    .shipping-support {
        margin-top: 50px;
        background-color: #f9f9f9;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
    }
    
    .shipping-support h2 {
        font-size: 1.6rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--dark);
    }
    
    .shipping-support p {
        font-size: 1rem;
        margin-bottom: 25px;
        color: var(--secondary-text);
    }
    
    .support-options {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
    
    .support-option {
        background-color: white;
        border-radius: 8px;
        padding: 25px 15px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s;
    }
    
    .support-option:hover {
        transform: translateY(-5px);
    }
    
    .support-icon {
        width: 60px;
        height: 60px;
        background-color: var(--secondary-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin: 0 auto 15px;
    }
    
    .support-option h3 {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--dark);
    }
    
    .support-option p {
        font-size: 0.9rem;
        margin-bottom: 5px;
        color: var(--secondary-text);
    }
    
    /* Responsive Styles */
    @media (max-width: 992px) {
        .shipping-methods {
            grid-template-columns: 1fr;
        }
        
        .support-options {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .hero-banner h1 {
            font-size: 2rem;
        }
        
        .shipping-content {
            padding: 25px;
        }
        
        .tab-button {
            padding: 10px 15px;
            font-size: 0.9rem;
        }
        
        .tab-pane h2 {
            font-size: 1.5rem;
        }
        
        .info-block h3 {
            font-size: 1.2rem;
        }
        
        .countries-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab functionality
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabPanes = document.querySelectorAll('.tab-pane');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons and panes
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('active'));
                
                // Add active class to clicked button and corresponding pane
                button.classList.add('active');
                const tabId = button.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });
        
        // FAQ Accordion functionality
        const faqItems = document.querySelectorAll('.faq-item');
        
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');
            
            question.addEventListener('click', () => {
                // Toggle active class on clicked item
                item.classList.toggle('active');
                
                // Close other items
                faqItems.forEach(otherItem => {
                    if (otherItem !== item) {
                        otherItem.classList.remove('active');
                    }
                });
            });
        });
        
        // Open first FAQ item by default
        if (faqItems.length > 0) {
            faqItems[0].classList.add('active');
        }
    });
</script>
@endsection