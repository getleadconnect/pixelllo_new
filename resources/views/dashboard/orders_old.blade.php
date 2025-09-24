@extends('layouts.dashboard')

@section('dashboard-title', 'My Orders')

@section('dashboard-content')
<div class="dashboard-panel active">
    <div class="panel-header">
        <h2>My Orders</h2>
        <p>Track your purchases and order history</p>
    </div>

    <div class="orders-filters">
        <div class="filter-item">
            <label for="orderStatus">Status:</label>
            <select id="orderStatus" class="form-select">
                <option value="all" selected>All Orders</option>
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="shipped">Shipped</option>
                <option value="delivered">Delivered</option>
            </select>
        </div>
        <button class="btn btn-primary">Apply Filter</button>
    </div>

    <div class="orders-list">
        <!-- Order 1 -->
        <div class="order-card">
            <div class="order-header">
                <div class="order-id">
                    <span>Order #PXL8752</span>
                </div>
                <div class="order-date">
                    <span>Placed on Jul 13, 2023</span>
                </div>
                <div class="order-status pending">
                    <span>Payment Pending</span>
                </div>
            </div>
            <div class="order-content">
                <div class="order-product">
                    <div class="order-product-image">
                        <img src="https://images.unsplash.com/photo-1585155770447-2f66e2a397b5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2064&q=80" alt="Bose Headphones" onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                    </div>
                    <div class="order-product-details">
                        <h3 class="order-product-title">Bose QuietComfort 45 Noise Cancelling Headphones</h3>
                        <div class="order-product-info">
                            <div class="order-product-price">
                                <span>Winning Bid: <strong>$36.50</strong></span>
                                <span>+ Shipping: <strong>$12.99</strong></span>
                                <span>Total: <strong>$49.49</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="order-actions">
                <a href="#" class="btn btn-primary">Complete Payment</a>
                <a href="#" class="btn btn-outline">Order Details</a>
            </div>
        </div>

        <!-- Order 2 -->
        <div class="order-card">
            <div class="order-header">
                <div class="order-id">
                    <span>Order #PXL8536</span>
                </div>
                <div class="order-date">
                    <span>Placed on Jul 06, 2023</span>
                </div>
                <div class="order-status delivered">
                    <span>Delivered</span>
                </div>
            </div>
            <div class="order-content">
                <div class="order-product">
                    <div class="order-product-image">
                        <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1699&q=80" alt="Smart Watch" onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                    </div>
                    <div class="order-product-details">
                        <h3 class="order-product-title">Fitbit Versa 4 Fitness Smartwatch</h3>
                        <div class="order-product-info">
                            <div class="order-product-price">
                                <span>Winning Bid: <strong>$22.25</strong></span>
                                <span>+ Shipping: <strong>$9.99</strong></span>
                                <span>Total: <strong>$32.24</strong></span>
                            </div>
                            <div class="order-delivery-info">
                                <span>Delivered on Jul 10, 2023</span>
                                <span>Tracking #: 1Z999AA10123456784</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="order-actions">
                <a href="#" class="btn btn-primary">Leave Review</a>
                <a href="#" class="btn btn-outline">Order Details</a>
            </div>
        </div>

        <!-- Order 3 -->
        <div class="order-card">
            <div class="order-header">
                <div class="order-id">
                    <span>Order #PXL8349</span>
                </div>
                <div class="order-date">
                    <span>Placed on Jun 23, 2023</span>
                </div>
                <div class="order-status delivered">
                    <span>Delivered</span>
                </div>
            </div>
            <div class="order-content">
                <div class="order-product">
                    <div class="order-product-image">
                        <img src="https://images.unsplash.com/photo-1595941069915-4ebc5197c14a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1760&q=80" alt="Samsung Galaxy S22" onerror="this.src='{{ asset('images/placeholders/product-placeholder.svg') }}'">
                    </div>
                    <div class="order-product-details">
                        <h3 class="order-product-title">Samsung Galaxy S22 Ultra 256GB</h3>
                        <div class="order-product-info">
                            <div class="order-product-price">
                                <span>Winning Bid: <strong>$67.50</strong></span>
                                <span>+ Shipping: <strong>$14.99</strong></span>
                                <span>Total: <strong>$82.49</strong></span>
                            </div>
                            <div class="order-delivery-info">
                                <span>Delivered on Jun 28, 2023</span>
                                <span>Tracking #: 1Z999AA10123456651</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="order-actions">
                <a href="#" class="btn btn-success">Review Posted</a>
                <a href="#" class="btn btn-outline">Order Details</a>
            </div>
        </div>
    </div>
</div>
@endsection