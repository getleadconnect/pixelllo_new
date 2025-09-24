<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', config('app.name') . ' - Online Auction Platform')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Styles -->
    <style>
        :root {
            --primary-color: #ffdd00;
            --primary-color-dark: #ff9900;
            --secondary-color: #ff9900;
            --accent-color: #ff5500;
            --dark: #333333;
            --light: #f8f9fa;
            --gray: #6c757d;
            --success: #28a745;
            --danger: #dc3545;
            --info: #17a2b8;
            --warning: #ffc107;
            --secondary-text: #6c757d;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Figtree', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
        }
        
        .container {
            width: 100%;
            max-width: 1368px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        /* Header Styles */
        .header {
            background-color: var(--primary-color);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark);
            text-decoration: none;
        }
        
        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-link {
            color: var(--dark);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: var(--accent-color);
        }

        .auth-buttons {
            display: flex;
            gap: 10px;
        }

        .user-profile-link:hover {
            background-color: #d4a017 !important; /* Semi-dark yellow */
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(212, 160, 23, 0.3);
        }

        .user-profile-link:hover span {
            color: white !important;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            display: inline-block;
        }
        
        .btn-primary {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--accent-color);
        }
        
        .btn-outline {
            background-color: transparent;
            border: 2px solid var(--secondary-color);
            color: var(--dark);
        }
        
        .btn-outline:hover {
            background-color: var(--secondary-color);
            color: white;
        }
        
        /* Footer Styles */
        .footer {
            background-color: var(--dark);
            color: white;
            padding: 4rem 0 2rem;
            margin-top: 4rem;
            position: relative;
        }

        .footer-main {
            position: relative;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1.8fr;
            gap: 2.5rem;
        }

        .footer-branding {
            padding-right: 1rem;
        }

        .footer-logo {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--primary-color);
            position: relative;
            display: inline-block;
        }

        .footer-logo:after {
            content: '';
            display: block;
            width: 40px;
            height: 3px;
            background-color: var(--primary-color);
            margin-top: 0.5rem;
        }

        .footer-description {
            color: #bbb;
            margin-bottom: 1.5rem;
            line-height: 1.7;
            font-size: 0.95rem;
        }

        .footer-heading {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: white;
            position: relative;
            padding-bottom: 0.8rem;
        }

        .footer-heading:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 30px;
            height: 2px;
            background-color: var(--primary-color);
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 0.8rem;
        }

        .footer-links a {
            color: #bbb;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 0.95rem;
            position: relative;
            padding-left: 0;
            display: inline-block;
        }

        .footer-links a:hover {
            color: var(--primary-color);
            padding-left: 5px;
        }

        .footer-contact {
            list-style: none;
            padding: 0;
            margin: 0 0 1.5rem 0;
        }

        .footer-contact li {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .contact-icon {
            color: var(--primary-color);
            margin-right: 10px;
            font-size: 1rem;
            margin-top: 3px;
            flex-shrink: 0;
            width: 20px;
            text-align: center;
        }

        .contact-info {
            color: #bbb;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .social-links {
            display: flex;
            gap: 0.8rem;
            margin-top: 1.5rem;
        }

        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s;
            text-decoration: none;
        }

        .social-link:hover {
            background-color: var(--primary-color);
            color: var(--dark);
            transform: translateY(-3px);
        }

        .newsletter h4 {
            font-size: 1rem;
            color: white;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .newsletter-form {
            display: flex;
        }

        .newsletter-input {
            flex-grow: 1;
            padding: 0.7rem 1rem;
            border: none;
            border-radius: 4px 0 0 4px;
            font-size: 0.9rem;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-right: none;
        }

        .newsletter-input::placeholder {
            color: #aaa;
        }

        .newsletter-input:focus {
            outline: none;
            background-color: rgba(255, 255, 255, 0.15);
        }

        .newsletter-btn {
            padding: 0 1.2rem;
            background-color: var(--primary-color);
            color: var(--dark);
            border: none;
            border-radius: 0 4px 4px 0;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .newsletter-btn:hover {
            background-color: white;
        }

        .footer-divider {
            height: 1px;
            background-color: rgba(255, 255, 255, 0.1);
            margin: 2rem 0;
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            color: #aaa;
            font-size: 0.9rem;
            gap: 1rem;
        }

        .copyright {
            margin: 0;
        }

        .footer-payment-methods {
            display: flex;
            gap: 0.8rem;
            font-size: 1.5rem;
            color: #bbb;
        }

        @media (max-width: 1100px) {
            .footer-content {
                grid-template-columns: 1fr 1fr;
                gap: 2rem 3rem;
            }

            .footer-branding,
            .footer-column:nth-child(4) {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 768px) {
            .footer {
                padding: 3rem 0 2rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .footer-column {
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                padding-bottom: 2rem;
            }

            .footer-column:last-child {
                border-bottom: none;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }
        }
        
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                padding: 1rem 0;
            }
            
            .header-nav {
                margin-top: 1rem;
                flex-direction: column;
                align-items: flex-start;
            }
            
            .nav-menu {
                flex-direction: column;
                width: 100%;
                margin-bottom: 1rem;
            }
            
            .nav-item {
                margin-left: 0;
                margin-bottom: 0.5rem;
            }
            
            .auth-links {
                margin-left: 0;
            }
            
            .auth-link {
                margin-left: 0;
                margin-right: 1rem;
            }
        }
    </style>

    <!-- Dashboard Styles -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    @yield('styles')
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <a href="{{ url('/') }}" class="logo">Pixelllo</a>

                <nav class="nav-links">
                    <a href="{{ url('/') }}" class="nav-link">Home</a>
                    <a href="{{ url('/auctions') }}" class="nav-link">Auctions</a>
                    <a href="{{ url('/categories') }}" class="nav-link">Categories</a>
                    <a href="{{ url('/how-it-works') }}" class="nav-link">How It Works</a>
                    <a href="{{ url('/winners') }}" class="nav-link">Winners</a>
                </nav>

                <div class="auth-buttons">
                    @auth
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <a href="{{ url('/dashboard') }}" class="user-profile-link" style="display: flex; align-items: center; gap: 10px; text-decoration: none; padding: 5px 12px; border-radius: 25px; transition: all 0.3s ease;">
                                <div style="width: 35px; height: 35px; border-radius: 50%; overflow: hidden; border: 2px solid #fff; background: #f8f9fa;">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 14px; font-weight: 600;">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <span style="color: #333; font-weight: 500;">{{ auth()->user()->name }}</span>
                            </a>
                            <form method="POST" action="{{ url('/logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary" style="padding: 8px 20px;">Logout</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ url('/login') }}" class="btn btn-outline" >Login</a>
                        <a href="{{ url('/register') }}" class="btn btn-primary">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-main">
                <div class="footer-content">
                    <div class="footer-column footer-branding">
                        <h3 class="footer-logo">Pixelllo</h3>
                        <p class="footer-description">The ultimate penny auction platform where you can win amazing products at a fraction of their retail price.</p>
                        <div class="social-links">
                            <a href="#" class="social-link" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-link" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-link" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-link" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>

                    <div class="footer-column">
                        <h3 class="footer-heading">Quick Links</h3>
                        <ul class="footer-links">
                            <li><a href="{{ url('/') }}">Home</a></li>
                            <li><a href="{{ url('/auctions') }}">Auctions</a></li>
                            <li><a href="{{ url('/categories') }}">Categories</a></li>
                            <li><a href="{{ url('/how-it-works') }}">How It Works</a></li>
                            <li><a href="{{ url('/winners') }}">Winners</a></li>
                            <li><a href="{{ url('/faq') }}">FAQ</a></li>
                        </ul>
                    </div>

                    <div class="footer-column">
                        <h3 class="footer-heading">Information</h3>
                        <ul class="footer-links">
                            <li><a href="{{ url('/about') }}">About Us</a></li>
                            <li><a href="{{ url('/terms') }}">Terms of Service</a></li>
                            <li><a href="{{ url('/privacy') }}">Privacy Policy</a></li>
                            <li><a href="{{ url('/shipping') }}">Shipping & Returns</a></li>
                            <li><a href="{{ url('/help') }}">Help Center</a></li>
                            <li><a href="{{ url('/sitemap') }}">Sitemap</a></li>
                        </ul>
                    </div>

                    <div class="footer-column">
                        <h3 class="footer-heading">Contact Us</h3>
                        <ul class="footer-contact">
                            <li>
                                <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                                <div class="contact-info">support@pixelllo.com</div>
                            </li>
                            <li>
                                <div class="contact-icon"><i class="fas fa-phone"></i></div>
                                <div class="contact-info">+1 (555) 123-4567</div>
                            </li>
                            <li>
                                <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                                <div class="contact-info">123 Auction St, Suite 456<br>San Francisco, CA 94107</div>
                            </li>
                        </ul>
                        <div class="newsletter">
                            <h4>Subscribe to our newsletter</h4>
                            <form class="newsletter-form">
                                <input type="email" placeholder="Your email address" class="newsletter-input">
                                <button type="submit" class="newsletter-btn">Subscribe</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-divider"></div>

            <div class="footer-bottom">
                <p class="copyright">&copy; {{ date('Y') }} Pixelllo. All rights reserved.</p>
                <div class="footer-payment-methods">
                    <span><i class="fab fa-cc-visa"></i></span>
                    <span><i class="fab fa-cc-mastercard"></i></span>
                    <span><i class="fab fa-cc-amex"></i></span>
                    <span><i class="fab fa-cc-paypal"></i></span>
                    <span><i class="fab fa-cc-discover"></i></span>
                </div>
            </div>
        </div>
    </footer>
    
    @yield('scripts')

    <script>
        // SweetAlert for Session Messages
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                @if(session('package_details'))
                    // Purchase success with package details
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        html: `
                            <div style="text-align: center;">
                                <p style="font-size: 1.1rem; margin-bottom: 15px;">{{ session('success') }}</p>
                                <div style="background: #f0f9ff; padding: 15px; border-radius: 8px; margin-top: 15px;">
                                    <p style="margin: 5px 0; color: #1e40af; font-weight: 600;">
                                        <i class="fas fa-coins" style="color: #fbbf24;"></i>
                                        {{ session('package_details')['bid_amount'] }} bid credits added!
                                    </p>
                                    <p style="margin: 5px 0; color: #047857; font-weight: 600;">
                                        <i class="fas fa-wallet" style="color: #10b981;"></i>
                                        New balance: {{ session('package_details')['new_balance'] }} bids
                                    </p>
                                </div>
                            </div>
                        `,
                        confirmButtonText: 'Awesome!',
                        confirmButtonColor: '#10b981',
                        timer: 5000,
                        timerProgressBar: true,
                        showCloseButton: true,
                        customClass: {
                            popup: 'animated fadeInDown'
                        }
                    });
                @else
                    // General success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: "{{ session('success') }}",
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#10b981',
                        timer: 5000,
                        timerProgressBar: true,
                        showCloseButton: true,
                        customClass: {
                            popup: 'animated fadeInDown'
                        }
                    });
                @endif
            @endif

            @if(session('error'))
                // Error message
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error') }}",
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc2626',
                    showCloseButton: true,
                    customClass: {
                        popup: 'animated fadeInDown'
                    }
                });
            @endif

            @if(session('warning'))
                // Warning message
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: "{{ session('warning') }}",
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#f59e0b',
                    showCloseButton: true,
                    customClass: {
                        popup: 'animated fadeInDown'
                    }
                });
            @endif

            @if(session('info'))
                // Info message
                Swal.fire({
                    icon: 'info',
                    title: 'Information',
                    text: "{{ session('info') }}",
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3b82f6',
                    showCloseButton: true,
                    customClass: {
                        popup: 'animated fadeInDown'
                    }
                });
            @endif
        });
    </script>
</body>
</html>