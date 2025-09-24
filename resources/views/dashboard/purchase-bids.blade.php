@extends('layouts.app')

@section('title', 'Complete Purchase - ' . config('app.name'))

@section('content')
<div class="purchase-page">
    <div class="container">
        <div class="purchase-header">
            <h1>Complete Your Purchase</h1>
            <p class="secure-badge"><i class="fas fa-lock"></i> Secure Checkout</p>
        </div>

        <div class="purchase-grid">
            <!-- Order Summary -->
            <div class="order-summary-section">
                <div class="section-card">
                    <h2>Order Summary</h2>

                    <div class="package-summary">
                        <div class="package-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="package-info">
                            <h3>{{ $package->name }}</h3>
                            <div class="package-details">
                                <div class="detail-row">
                                    <span class="label">Bid Credits:</span>
                                    <span class="value">{{ $package->bidAmount }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Price per bid:</span>
                                    <span class="value">${{ number_format($pricePerBid, 2) }}</span>
                                </div>
                                @if($package->description)
                                <div class="package-desc">
                                    {{ $package->description }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="price-breakdown">
                        <div class="price-row">
                            <span>Subtotal</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        @if($tax > 0)
                        <div class="price-row">
                            <span>Tax</span>
                            <span>${{ number_format($tax, 2) }}</span>
                        </div>
                        @endif
                        <div class="price-row total">
                            <span>Total</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <div class="current-balance">
                        <p>Your current balance: <strong>{{ $user->bid_balance }} bids</strong></p>
                        <p class="after-purchase">After purchase: <strong>{{ $user->bid_balance + $package->bidAmount }} bids</strong></p>
                    </div>
                </div>

                <div class="benefits-card">
                    <h3><i class="fas fa-shield-alt"></i> Purchase Benefits</h3>
                    <ul class="benefits-list">
                        <li><i class="fas fa-check"></i> Instant credit to your account</li>
                        <li><i class="fas fa-check"></i> Never expire bid credits</li>
                        <li><i class="fas fa-check"></i> 100% secure payment</li>
                        <li><i class="fas fa-check"></i> Money-back guarantee</li>
                    </ul>
                </div>
            </div>

            <!-- Payment Section -->
            <div class="payment-section">
                <div class="section-card">
                    <h2>Payment Method</h2>


                    <div class="payment-methods">
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="stripe" checked>
                            <div class="option-content">
                                <div class="option-header">
                                    <i class="fab fa-stripe"></i>
                                    <span>Stripe (Secure Payment)</span>
                                </div>
                                <div class="card-logos">
                                    <i class="fab fa-cc-visa"></i>
                                    <i class="fab fa-cc-mastercard"></i>
                                    <i class="fab fa-cc-amex"></i>
                                    <i class="fab fa-cc-discover"></i>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Stripe Form -->
                    <div class="payment-form" id="stripe-form">
                        <div class="stripe-info">
                            <i class="fab fa-stripe"></i>
                            <p>You will be redirected to Stripe's secure checkout page to complete your purchase.</p>
                            <p style="margin-top: 15px; font-size: 0.9rem; color: #6b7280;">
                                <i class="fas fa-shield-alt" style="color: #10b981;"></i> Your payment information is securely processed by Stripe. We never store your card details.
                            </p>
                        </div>
                    </div>


                    <div class="terms-section">
                        <label class="checkbox-label">
                            <input type="checkbox" required>
                            <span>I agree to the <a href="{{ route('terms') }}">Terms of Service</a> and <a href="{{ route('privacy') }}">Privacy Policy</a></span>
                        </label>
                    </div>

                    <form action="{{ route('stripe.checkout') }}" method="POST" id="purchase-form">
                        @csrf
                        <input type="hidden" name="package_id" value="{{ $package->id }}">

                        <div class="action-buttons">
                            <button type="submit" class="btn btn-primary btn-large">
                                <i class="fas fa-lock"></i> Proceed to Stripe Checkout (${{ number_format($total, 2) }})
                            </button>
                            <a href="{{ route('dashboard.buy-bids') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Packages
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
.purchase-page {
    padding: 40px 0 60px;
    background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    min-height: 100vh;
}

.purchase-header {
    text-align: center;
    margin-bottom: 40px;
}

.purchase-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 10px;
}

.secure-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 20px;
    background: #10b981;
    color: white;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
}

.purchase-grid {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
}

.section-card {
    background: white;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.section-card h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #ffdd00;
}

.package-summary {
    display: flex;
    gap: 20px;
    padding: 20px;
    background: #f9fafb;
    border-radius: 12px;
    margin-bottom: 25px;
}

.package-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #ffdd00 0%, #ff9900 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.package-icon i {
    font-size: 1.8rem;
    color: white;
}

.package-info h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 10px;
}

.package-details {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

.detail-row .label {
    color: #6b7280;
    font-size: 0.9rem;
}

.detail-row .value {
    font-weight: 600;
    color: #1f2937;
}

.package-desc {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #e5e7eb;
    color: #6b7280;
    font-size: 0.9rem;
}

.price-breakdown {
    padding: 20px;
    background: #f9fafb;
    border-radius: 12px;
    margin-bottom: 20px;
}

.price-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    color: #6b7280;
}

.price-row.total {
    margin-top: 10px;
    padding-top: 15px;
    border-top: 2px solid #e5e7eb;
    font-size: 1.2rem;
    font-weight: 700;
    color: #1f2937;
}

.current-balance {
    text-align: center;
    padding: 15px;
    background: #f0fdf4;
    border-radius: 12px;
    border: 1px solid #10b981;
}

.current-balance p {
    margin: 5px 0;
    color: #6b7280;
}

.current-balance strong {
    color: #10b981;
    font-size: 1.1rem;
}

.after-purchase {
    font-size: 1.1rem;
}

.benefits-card {
    background: white;
    border-radius: 16px;
    padding: 25px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    margin-top: 20px;
}

.benefits-card h3 {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.2rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 15px;
}

.benefits-card h3 i {
    color: #10b981;
}

.benefits-list {
    list-style: none;
}

.benefits-list li {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    color: #4b5563;
}

.benefits-list i {
    color: #10b981;
    font-size: 0.9rem;
}

.payment-methods {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-bottom: 25px;
}

.payment-option {
    display: block;
    cursor: pointer;
}

.payment-option input[type="radio"] {
    display: none;
}

.option-content {
    padding: 20px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.payment-option input[type="radio"]:checked + .option-content {
    border-color: #ffdd00;
    background: #fffef0;
}

.option-header {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 1.1rem;
    font-weight: 600;
    color: #1f2937;
}

.option-header i {
    font-size: 1.5rem;
    color: #6b7280;
}

.card-logos {
    display: flex;
    gap: 12px;
    margin-top: 10px;
    padding-left: 28px;
}

.card-logos i {
    font-size: 1.8rem;
    color: #6b7280;
    transition: color 0.3s ease;
}

.card-logos i.fa-cc-visa {
    color: #1a1f71;
}

.card-logos i.fa-cc-mastercard {
    color: #eb001b;
}

.card-logos i.fa-cc-amex {
    color: #006fcf;
}

.card-logos i.fa-cc-discover {
    color: #ff6000;
}

.payment-form {
    margin-bottom: 25px;
}

.payment-form.hidden {
    display: none;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-size: 0.9rem;
    font-weight: 600;
    color: #4b5563;
    margin-bottom: 8px;
}

.input-group {
    position: relative;
    display: flex;
    align-items: center;
}

.input-group i {
    position: absolute;
    left: 15px;
    color: #9ca3af;
}

.input-group input,
.input-group select {
    width: 100%;
    padding: 12px 15px 12px 45px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.input-group input:focus,
.input-group select:focus {
    outline: none;
    border-color: #ffdd00;
    box-shadow: 0 0 0 3px rgba(255, 221, 0, 0.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.paypal-info,
.stripe-info {
    text-align: center;
    padding: 40px;
    background: #f9fafb;
    border-radius: 12px;
}

.paypal-info i,
.stripe-info i {
    font-size: 3rem;
    color: #6b7280;
    margin-bottom: 15px;
}

.terms-section {
    margin: 25px 0;
    padding: 20px;
    background: #f9fafb;
    border-radius: 12px;
}

.checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    cursor: pointer;
}

.checkbox-label input[type="checkbox"] {
    margin-top: 3px;
}

.checkbox-label a {
    color: #3b82f6;
    text-decoration: none;
}

.checkbox-label a:hover {
    text-decoration: underline;
}

.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.btn {
    padding: 14px 24px;
    border: none;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-primary {
    background: #4a4a4a;
    color: white;
}

.btn-primary:hover {
    background: #333333;
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
}

.btn-large {
    padding: 18px 30px;
    font-size: 1.1rem;
}

.btn-secondary {
    background: white;
    color: #6b7280;
    border: 2px solid #e5e7eb;
}

.btn-secondary:hover {
    background: #f9fafb;
    border-color: #d1d5db;
}

@media (max-width: 992px) {
    .purchase-grid {
        grid-template-columns: 1fr;
    }

    .order-summary-section {
        order: 2;
    }

    .payment-section {
        order: 1;
    }
}

@media (max-width: 768px) {
    .purchase-header h1 {
        font-size: 2rem;
    }

    .section-card {
        padding: 20px;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .package-summary {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Stripe is the only payment method, so no switching needed
    // The form will always show Stripe information
});
</script>
@endsection