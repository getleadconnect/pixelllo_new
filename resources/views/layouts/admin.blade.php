<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Admin Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    <!-- Chart.js for statistics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @yield('styles')
    
    <!-- CSS Styles -->
    <style>
        :root {
            --primary: #ffdd00;
            --secondary: #ff9900;
            --accent: #ff5500;
            --dark: #111111;
            --light: #f8f9fa;
            --gray: #6c757d;
            --light-gray: #e9ecef;
            --success: #28a745;
            --danger: #dc3545;
            --info: #17a2b8;
            --warning: #ffc107;
            --admin-sidebar: #111111;
            --admin-sidebar-active: #000000;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8f9fa;
            color: var(--dark);
            line-height: 1.6;
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Admin Sidebar */
        .admin-sidebar {
            width: 280px;
            background: linear-gradient(135deg, #111111 0%, #000000 100%);
            color: white;
            padding: 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 3px 0 15px rgba(0, 0, 0, 0.3);
            z-index: 100;
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 20px 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            background: #000000;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            margin-bottom: 20px;
            height: 80px;
        }

        .sidebar-brand-icon {
            font-size: 1.8rem;
            color: var(--primary);
            background: rgba(255, 221, 0, 0.15);
            height: 45px;
            width: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .sidebar-brand-text {
            font-size: 1.3rem;
            font-weight: 700;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        .sidebar-divider {
            height: 1px;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.01) 0%, rgba(255, 255, 255, 0.1) 50%, rgba(255, 255, 255, 0.01) 100%);
            margin: 5px 20px;
            
        }

        .sidebar-heading {
            padding: 0 25px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255, 255, 255, 0.4);
            font-weight: 600;
            margin: 20px 0 10px;
        }

        .nav-item {
            position: relative;
            margin: 4px 12px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 5px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s;
            border-radius: 8px;
            margin: 2px 0;
        }

        .nav-link i {
            width: 28px;
            margin-right: 12px;
            text-align: center;
            font-size: 1.1rem;
            transition: all 0.3s;
        }

        .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .nav-link.active {
            color: white;
            background-color: rgba(0, 0, 0, 0.6);
            border-left: 3px solid var(--primary);
            padding-left: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            font-weight: 500;
        }

        .nav-link.active i {
            color: var(--primary);
        }
        
        /* Admin Content */
        .admin-content {
            flex: 1;
            margin-left: 280px;
            padding: 15px;
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        
        /* Admin Topbar */
        .admin-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            margin-bottom: 30px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .admin-topbar::after {
            content: "";
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 3px;
        }

        .topbar-title h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .topbar-title p {
            color: var(--gray);
            font-size: 1rem;
        }

        .topbar-user {
            position: relative;
            cursor: pointer;
        }

        .topbar-user-content {
            display: flex;
            align-items: center;
            gap: 15px;
            background-color: white;
            padding: 8px 15px 8px 8px;
            border-radius: 50px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }

        .topbar-user-content:hover {
            background-color: #f8f9fa;
        }

        .topbar-user-info {
            text-align: right;
        }

        .topbar-user-name {
            font-weight: 600;
            font-size: 0.95rem;
        }

        .topbar-user-role {
            font-size: 0.75rem;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .topbar-user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            border: 2px solid white;
        }

        .topbar-user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .dropdown-arrow {
            font-size: 0.8rem;
            color: var(--gray);
            margin-left: 5px;
        }

        /* Dropdown styling */
        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            z-index: 1000;
            display: none;
            min-width: 185px;
            padding: 0;
            margin: 0.5rem 0 0;
            font-size: 0.9rem;
            color: var(--dark);
            text-align: left;
            list-style: none;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .dropdown-menu.show {
            display: block;
            animation: fadeInDown 0.2s ease-out;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-user-header {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 20px;
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .dropdown-user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border: 2px solid white;
        }

        .dropdown-user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .dropdown-user-info {
            flex: 1;
        }

        .dropdown-user-name {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 2px;
        }

        .dropdown-user-email {
            font-size: 0.8rem;
            color: var(--gray);
        }

        .dropdown-divider {
            height: 1px;
            background-color: rgba(0, 0, 0, 0.05);
            margin: 0;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--dark);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: var(--secondary);
        }

        .dropdown-item i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
            font-size: 0.9rem;
        }

        .dropdown-form {
            margin: 0;
        }

        /* For spacing with Font Awesome icons */
        .mr-2 {
            margin-right: 0.5rem !important;
        }
        
        /* Admin Cards */
        .admin-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .admin-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s;
        }
        
        .admin-card:hover {
            transform: translateY(-5px);
        }
        
        .admin-card-inner {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .admin-card-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }
        
        .admin-card-icon.users {
            background-color: rgba(23, 162, 184, 0.1);
            color: var(--info);
        }
        
        .admin-card-icon.auctions {
            background-color: rgba(255, 153, 0, 0.1);
            color: var(--secondary);
        }
        
        .admin-card-icon.bids {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }
        
        .admin-card-icon.orders {
            background-color: rgba(255, 193, 7, 0.1);
            color: var(--warning);
        }
        
        .admin-card-content h3 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .admin-card-content p {
            font-size: 0.9rem;
            color: var(--gray);
        }
        
        /* Admin Data Cards */
        .admin-data-card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 35px;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }

        .admin-data-card:hover {
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
            transform: translateY(-3px);
        }

        .admin-data-card-header {
            padding: 18px 25px;
            border-bottom: 1px solid var(--light-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(248, 249, 250, 0.5);
        }

        .admin-data-card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark);
            position: relative;
            padding-left: 15px;
        }

        .admin-data-card-title::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(to bottom, var(--primary), var(--secondary));
            border-radius: 4px;
        }

        .admin-data-card-body {
            padding: 25px;
        }

        .admin-data-card-footer {
            padding: 15px 25px;
            border-top: 1px solid var(--light-gray);
            text-align: right;
            background-color: rgba(248, 249, 250, 0.3);
        }
        
        /* Tables */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        
        .admin-table th, .admin-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--light-gray);
            vertical-align: middle;
        }
        
        .admin-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            white-space: nowrap;
        }
        
        .admin-table tr:last-child td {
            border-bottom: none;
        }
        
        .admin-table tr:hover {
            background-color: #f8f9fa;
        }
        
        /* Table responsive wrapper */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        @media (max-width: 768px) {
            .admin-table {
                font-size: 0.85rem;
            }
            
            .admin-table th, .admin-table td {
                padding: 8px 10px;
            }
        }
        
        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-badge.active {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }
        
        .status-badge.pending {
            background-color: rgba(255, 193, 7, 0.1);
            color: var(--warning);
        }
        
        .status-badge.inactive {
            background-color: rgba(108, 117, 125, 0.1);
            color: var(--gray);
        }
        
        .status-badge.processing {
            background-color: rgba(23, 162, 184, 0.1);
            color: var(--info);
        }
        
        .status-badge.shipped {
            background-color: rgba(255, 153, 0, 0.1);
            color: var(--secondary);
        }
        
        .status-badge.delivered {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }
        
        /* Buttons */
        .btn {
            display: inline-block;
            font-weight: 500;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            user-select: none;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 0.9rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
        }
        
        .btn-primary {
            color: white;
            background-color: var(--secondary);
            border-color: var(--secondary);
        }
        
        .btn-primary:hover {
            background-color: var(--accent);
            border-color: var(--accent);
        }
        
        .btn-success {
            color: white;
            background-color: var(--success);
            border-color: var(--success);
        }
        
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        
        .btn-danger {
            color: white;
            background-color: var(--danger);
            border-color: var(--danger);
        }
        
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.765625rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
        
        /* Forms */
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: inline-block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .form-control {
            display: block;
            width: 100%;
            height: calc(2.25rem + 2px);
            padding: 0.375rem 0.75rem;
            font-size: 0.9rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        
        .form-control:focus {
            color: #495057;
            background-color: #fff;
            border-color: var(--secondary);
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(255, 153, 0, 0.25);
        }
        
        select.form-control {
            height: calc(2.25rem + 2px);
        }
        
        textarea.form-control {
            height: auto;
        }
        
        /* Alerts */
        .alert {
            position: relative;
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.25rem;
        }
        
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        
        .alert-warning {
            color: #856404;
            background-color: #fff3cd;
            border-color: #ffeeba;
        }
        
        .alert-info {
            color: #0c5460;
            background-color: #d1ecf1;
            border-color: #bee5eb;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            padding-left: 0;
            list-style: none;
            border-radius: 0.25rem;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        
        .page-item {
            display: flex;
            align-items: center;
        }
        
        .page-link {
            position: relative;
            display: block;
            padding: 0.5rem 0.75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: var(--secondary);
            background-color: #fff;
            border: 1px solid #dee2e6;
            text-decoration: none;
            border-radius: 0.25rem;
            margin: 0 2px;
            font-size: 0.875rem;
            min-width: 40px;
            text-align: center;
        }
        
        .page-link:hover {
            z-index: 2;
            color: var(--accent);
            text-decoration: none;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }
        
        .page-item.active .page-link {
            z-index: 1;
            color: #fff;
            background-color: var(--secondary);
            border-color: var(--secondary);
        }
        
        .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            cursor: auto;
            background-color: #fff;
            border-color: #dee2e6;
        }
        
        /* Fix for large pagination elements */
        .pagination svg {
            width: 16px !important;
            height: 16px !important;
            max-width: 16px !important;
            max-height: 16px !important;
        }
        
        .pagination .page-link svg {
            width: 14px !important;
            height: 14px !important;
        }
        
        /* Additional fixes for Laravel pagination arrows */
        .admin-data-card-footer svg {
            width: 16px !important;
            height: 16px !important;
            max-width: 16px !important;
            max-height: 16px !important;
        }
        
        .admin-data-card-footer .page-link {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 40px !important;
            height: 40px !important;
        }
        
        /* Override any default Laravel pagination styles */
        nav[role="navigation"] svg {
            width: 16px !important;
            height: 16px !important;
            max-width: 16px !important;
            max-height: 16px !important;
        }
        
        /* Charts */
        .chart-container {
            position: relative;
            margin: auto;
            height: 300px;
            width: 100%;
        }
        
        /* Responsive Sidebar */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            width: 46px;
            height: 46px;
            background: #000000;
            color: var(--primary);
            border-radius: 12px;
            z-index: 9999;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            align-items: center;
            justify-content: center;
            border: 1px solid var(--primary);
            outline: none;
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .sidebar-toggle i {
            font-size: 1.2rem;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar-toggle {
                display: flex;
            }

            .admin-sidebar {
                transform: translateX(-100%);
                transition: all 0.3s cubic-bezier(0.68, -0.55, 0.27, 1.55);
                box-shadow: none;
            }

            .admin-sidebar.active {
                transform: translateX(0);
                box-shadow: 3px 0 15px rgba(0, 0, 0, 0.2);
            }

            .admin-content {
                margin-left: 0;
                padding-top: 70px;
            }

            .admin-cards {
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                gap: 25px;
            }

            .admin-topbar {
                padding-top: 0;
            }
        }

        @media (max-width: 768px) {
            .admin-cards {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .admin-data-card {
                margin-bottom: 25px;
            }

            .admin-topbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
            }

            .topbar-user {
                align-self: flex-end;
            }

            .admin-content {
                padding: 70px 20px 20px;
            }

            .topbar-title h1 {
                font-size: 1.7rem;
            }
        }

        @media (max-width: 576px) {
            .admin-content {
                padding: 70px 15px 15px;
            }

            .admin-data-card-body {
                padding: 20px 15px;
            }

            .topbar-title h1 {
                font-size: 1.5rem;
            }

            .sidebar-toggle {
                top: 15px;
                left: 15px;
                width: 42px;
                height: 42px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar Toggle -->
        <div class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </div>
        
        <!-- Sidebar -->
        <div class="admin-sidebar" id="adminSidebar">
            <!-- Sidebar - Brand -->
            <div class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-gavel"></i>
                </div>
                <div class="sidebar-brand-text">Pixelllo Admin</div>
            </div>
            
            <!-- Divider -->
            <hr class="sidebar-divider">
            
            <!-- Nav Item - Dashboard -->
            <div class="nav-item">
                <a class="nav-link {{ request()->is('admin') ? 'active' : '' }}" href="{{ url('/admin') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">Management</div>

            <!-- Nav Item - Users -->
            <div class="nav-item">
                <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="{{ url('/admin/users') }}">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
            </div>

            <!-- Nav Item - Auctions -->
            <div class="nav-item">
                <a class="nav-link {{ request()->is('admin/auctions*') ? 'active' : '' }}" href="{{ url('/admin/auctions') }}">
                    <i class="fas fa-gavel"></i>
                    <span>Auctions</span>
                </a>
            </div>

            <!-- Nav Item - Categories -->
            <div class="nav-item">
                <a class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}" href="{{ route('admin.categories') }}">
                    <i class="fas fa-th-large"></i>
                    <span>Categories</span>
                </a>
            </div>

            <!-- Nav Item - Orders -->
            <div class="nav-item">
                <a class="nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}" href="{{ url('/admin/orders') }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
            </div>

            <!-- Nav Item - Marketing -->
            <div class="nav-item">
                <a class="nav-link {{ request()->is('admin/marketing*') ? 'active' : '' }}" href="{{ url('/admin/marketing') }}">
                    <i class="fas fa-bullhorn"></i>
                    <span>Marketing</span>
                </a>
            </div>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">Analytics</div>

            <!-- Nav Item - Statistics -->
            <div class="nav-item">
                <a class="nav-link {{ request()->is('admin/statistics') ? 'active' : '' }}" href="{{ url('/admin/statistics') }}">
                    <i class="fas fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <!-- Nav Item - User Reports -->
            <div class="nav-item">
                <a class="nav-link {{ request()->is('admin/reports/users') ? 'active' : '' }}" href="{{ url('/admin/reports/users') }}">
                    <i class="fas fa-user-tag"></i>
                    <span>User Reports</span>
                </a>
            </div>

            <!-- Nav Item - Auction Reports -->
            <div class="nav-item">
                <a class="nav-link {{ request()->is('admin/reports/auctions') ? 'active' : '' }}" href="{{ url('/admin/reports/auctions') }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Auction Reports</span>
                </a>
            </div>

            <!-- Nav Item - Sales Reports -->
            <div class="nav-item">
                <a class="nav-link {{ request()->is('admin/reports/sales') ? 'active' : '' }}" href="{{ url('/admin/reports/sales') }}">
                    <i class="fas fa-dollar-sign"></i>
                    <span>Sales Reports</span>
                </a>
            </div>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">Account</div>

            <!-- Nav Item - Settings -->
            <div class="nav-item">
                <a class="nav-link {{ request()->is('admin/settings') ? 'active' : '' }}" href="{{ url('/admin/settings') }}">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </div>

            <!-- Nav Item - Currency Settings -->
            <div class="nav-item">
                <a class="nav-link {{ request()->is('admin/settings/currencies*') ? 'active' : '' }}" href="{{ url('/admin/settings/currencies') }}">
                    <i class="fas fa-dollar-sign"></i>
                    <span>Currencies</span>
                </a>
            </div>

            <!-- Nav Item - Return to Site -->
            <div class="nav-item">
                <a class="nav-link" href="{{ url('/') }}">
                    <i class="fas fa-external-link-alt"></i>
                    <span>View Site</span>
                </a>
            </div>

            <!-- Nav Item - Logout -->
            <div class="nav-item">
                <form method="POST" action="{{ url('/logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="nav-link" style="background: none; border: none; width: 100%; text-align: left;">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Content -->
        <div class="admin-content">
            <!-- Topbar -->
            <div class="admin-topbar">
                <div class="topbar-title">
                    <h1>@yield('page-title', 'Dashboard')</h1>
                    <p>@yield('page-subtitle', 'Welcome to the admin dashboard')</p>
                </div>
                
                <div class="topbar-user dropdown">
                    <div class="topbar-user-content" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="topbar-user-info">
                            <div class="topbar-user-name">{{ Auth::user()->name ?? 'Admin User' }}</div>
                            <div class="topbar-user-role">Administrator</div>
                        </div>
                        <div class="topbar-user-avatar">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
                            @else
                                <img src="{{ asset('images/placeholders/avatar-placeholder.svg') }}" alt="Admin">
                            @endif
                        </div>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </div>

                    <!-- User dropdown menu -->
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <div class="dropdown-user-header">
                            <div class="dropdown-user-avatar">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
                                @else
                                    <img src="{{ asset('images/placeholders/avatar-placeholder.svg') }}" alt="Admin">
                                @endif
                            </div>
                            <div class="dropdown-user-info">
                                <div class="dropdown-user-name">{{ Auth::user()->name ?? 'Admin User' }}</div>
                                <div class="dropdown-user-email">{{ Auth::user()->email ?? 'admin@example.com' }}</div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ url('/admin/settings') }}">
                            <i class="fas fa-user mr-2"></i> My Profile
                        </a>
                        <a class="dropdown-item" href="{{ url('/admin/settings') }}">
                            <i class="fas fa-cog mr-2"></i> Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ url('/logout') }}" class="dropdown-form">
                            @csrf
                            <button type="submit" class="dropdown-item" style="background: none; border: none; width: 100%; text-align: left; padding: 12px 20px; cursor: pointer;">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            @yield('content')
        </div>
    </div>
    
    <!-- Scripts -->
    <script>
        // Sidebar Toggle and Dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const adminSidebar = document.getElementById('adminSidebar');

            if (sidebarToggle && adminSidebar) {
                sidebarToggle.addEventListener('click', function() {
                    adminSidebar.classList.toggle('active');
                });
            }

            // User dropdown toggle
            const userDropdown = document.getElementById('userDropdown');
            const dropdownMenu = userDropdown.nextElementSibling;

            userDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('show');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (dropdownMenu.classList.contains('show') && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.remove('show');
                }
            });
        });
    </script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Toastr Configuration and Flash Messages -->
    <script>
        // Configure toastr
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Display flash messages
        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if(session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif

        @if(session('info'))
            toastr.info("{{ session('info') }}");
        @endif

        // Display validation errors
        @if($errors->any())
            @foreach($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>

    @yield('scripts')
</body>
</html>