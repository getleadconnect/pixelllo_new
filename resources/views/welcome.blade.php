<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }} - Online Auction Platform</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <style>
            body {
                font-family: 'Figtree', sans-serif;
                background-color: #f8f9fa;
                margin: 0;
                padding: 0;
                color: #333;
            }
            
            .container {
                max-width: 1368px;
                margin: 0 auto;
                padding: 2rem;
            }
            
            header {
                background-color: #ffdd00;
                padding: 1.5rem 0;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            
            .header-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .logo {
                font-size: 2rem;
                font-weight: 700;
                color: #333;
            }
            
            .hero {
                padding: 4rem 0;
                text-align: center;
                background-color: #fff;
                margin-top: 2rem;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            }
            
            h1 {
                font-size: 2.5rem;
                margin-bottom: 1rem;
                color: #333;
            }
            
            p {
                font-size: 1.2rem;
                color: #6c757d;
                max-width: 800px;
                margin: 0 auto 2rem auto;
                line-height: 1.6;
            }
            
            .cta-button {
                display: inline-block;
                background-color: #ff9900;
                color: white;
                padding: 0.8rem 2rem;
                border-radius: 4px;
                text-decoration: none;
                font-weight: 600;
                transition: background-color 0.3s;
            }
            
            .cta-button:hover {
                background-color: #ff5500;
            }
            
            .features {
                display: flex;
                justify-content: space-between;
                margin-top: 3rem;
                flex-wrap: wrap;
            }
            
            .feature {
                flex-basis: 30%;
                background-color: white;
                padding: 2rem;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
                margin-bottom: 2rem;
            }
            
            .feature h3 {
                color: #ff9900;
                margin-top: 0;
            }
            
            footer {
                margin-top: 4rem;
                text-align: center;
                padding: 2rem 0;
                color: #6c757d;
                border-top: 1px solid #e9ecef;
            }
            
            @media (max-width: 768px) {
                .feature {
                    flex-basis: 100%;
                }
            }
        </style>
    </head>
    <body>
        <header>
            <div class="container header-content">
                <div class="logo">Pixelllo</div>
                <nav>
                    <a href="#" class="cta-button">Login</a>
                </nav>
            </div>
        </header>
        
        <div class="container">
            <section class="hero">
                <h1>Welcome to Pixelllo</h1>
                <p>The premier online auction platform where amazing deals await. Bid on high-quality products and potentially win them at a fraction of their retail price!</p>
                <a href="#" class="cta-button">Start Bidding Now</a>
            </section>
            
            <div class="features">
                <div class="feature">
                    <h3>Real-time Bidding</h3>
                    <p>Experience the thrill of live auctions with real-time updates. Watch the countdown timer and place strategic bids to secure your win.</p>
                </div>
                <div class="feature">
                    <h3>Amazing Deals</h3>
                    <p>Find incredible bargains on brand new, high-quality products. From electronics to jewelry, there's something for everyone.</p>
                </div>
                <div class="feature">
                    <h3>Secure Payments</h3>
                    <p>Enjoy peace of mind with our secure payment system. Your transactions are protected with state-of-the-art encryption.</p>
                </div>
            </div>
        </div>
        
        <footer>
            <div class="container">
                <p>&copy; {{ date('Y') }} Pixelllo. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>