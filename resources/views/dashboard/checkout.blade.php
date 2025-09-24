@extends('layouts.dashboard')

@section('dashboard-title', 'Complete Purchase')

@section('dashboard-content')
<div class="dashboard-panel active">
    <div class="panel-header">
        <h2>Complete Your Purchase</h2>
        <p>Finalize your winning auction</p>
    </div>

    <div class="checkout-container">
        <div class="checkout-content">
            <!-- Auction Details -->
            <div class="checkout-section">
                <h3>Auction Details</h3>
                <div class="auction-summary">
                    <div class="auction-summary-image">
                        @if(isset($auction->images) && is_array($auction->images) && count($auction->images) > 0)
                            <img src="{{ asset('storage/' . $auction->images[0]) }}" alt="{{ $auction->title }}">
                        @else
                            <img src="https://via.placeholder.com/150" alt="{{ $auction->title }}">
                        @endif
                    </div>
                    <div class="auction-summary-details">
                        <h4>{{ $auction->title }}</h4>
                        <p class="auction-category">Category: {{ $auction->category->name }}</p>
                        <p class="auction-won-date">Won on: {{ $auction->endTime->format('M d, Y h:i A') }}</p>
                        <div class="price-breakdown">
                            <div class="price-row">
                                <span>Winning Bid:</span>
                                <span>${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="price-row">
                                <span>Shipping:</span>
                                <span>${{ number_format($shippingCost, 2) }}</span>
                            </div>
                            <div class="price-row">
                                <span>Tax (8%):</span>
                                <span>${{ number_format($tax, 2) }}</span>
                            </div>
                            <div class="price-row total">
                                <span>Total:</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Checkout Form -->
            <form action="{{ route('dashboard.checkout.process', $auction->id) }}" method="POST" class="checkout-form">
                @csrf

                <!-- Shipping Information -->
                <div class="checkout-section">
                    <h3>Shipping Information</h3>
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="full_name">Full Name *</label>
                            <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $user->name) }}" required>
                            @error('full_name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}" required>
                            @error('phone')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group full-width">
                            <label for="address">Street Address *</label>
                            <input type="text" id="address" name="address" value="{{ old('address') }}" required>
                            @error('address')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="city">City *</label>
                            <input type="text" id="city" name="city" value="{{ old('city', $user->city ?? '') }}" required>
                            @error('city')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="state">State/Province *</label>
                            <input type="text" id="state" name="state" value="{{ old('state') }}" required>
                            @error('state')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="zip">ZIP/Postal Code *</label>
                            <input type="text" id="zip" name="zip" value="{{ old('zip') }}" required>
                            @error('zip')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="country">Country *</label>
                            <select id="country" name="country" required>
                                <option value="">Select Country</option>
                                <option value="United States" {{ old('country') == 'United States' ? 'selected' : '' }}>United States</option>
                                <option value="Canada" {{ old('country') == 'Canada' ? 'selected' : '' }}>Canada</option>
                                <option value="United Kingdom" {{ old('country') == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                                <option value="Australia" {{ old('country') == 'Australia' ? 'selected' : '' }}>Australia</option>
                                <option value="Germany" {{ old('country') == 'Germany' ? 'selected' : '' }}>Germany</option>
                                <option value="France" {{ old('country') == 'France' ? 'selected' : '' }}>France</option>
                                <option value="India" {{ old('country') == 'India' ? 'selected' : '' }}>India</option>
                            </select>
                            @error('country')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Hidden default payment method -->
                <input type="hidden" name="payment_method" value="credit_card">

                <!-- Additional Notes -->
                <div class="checkout-section">
                    <h3>Additional Notes (Optional)</h3>
                    <div class="form-group full-width">
                        <textarea id="notes" name="notes" rows="4" placeholder="Add any special instructions or notes...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="checkout-actions">
                    <a href="{{ route('dashboard.wins') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-large">
                        <i class="fas fa-lock"></i> Complete Purchase - ${{ number_format($total, 2) }}
                    </button>
                </div>

                <div class="checkout-notice">
                    <i class="fas fa-info-circle"></i>
                    <p>By completing this purchase, you agree to our terms and conditions. Your payment will be processed securely.</p>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.checkout-container {
    max-width: 800px;
    margin: 0 auto;
}

.checkout-section {
    background: white;
    border-radius: 10px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.checkout-section h3 {
    margin-bottom: 20px;
    color: #333;
    font-size: 20px;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 10px;
}

.auction-summary {
    display: flex;
    gap: 20px;
}

.auction-summary-image {
    width: 150px;
    height: 150px;
    border-radius: 10px;
    overflow: hidden;
    flex-shrink: 0;
}

.auction-summary-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.auction-summary-details {
    flex: 1;
}

.auction-summary-details h4 {
    margin-bottom: 10px;
    font-size: 18px;
    color: #333;
}

.auction-category {
    color: #666;
    margin-bottom: 5px;
}

.auction-won-date {
    color: #999;
    font-size: 14px;
    margin-bottom: 20px;
}

.price-breakdown {
    border-top: 1px solid #f0f0f0;
    padding-top: 15px;
}

.price-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    font-size: 15px;
}

.price-row.total {
    font-weight: 700;
    font-size: 18px;
    color: var(--primary-color);
    border-top: 2px solid #f0f0f0;
    padding-top: 10px;
    margin-top: 10px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
}

.form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 5px;
}

.form-group.full-width {
    grid-column: span 2;
}

.form-group label {
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 15px;
    transition: border-color 0.3s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary-color);
}

.error-message {
    color: #e74c3c;
    font-size: 13px;
    margin-top: 5px;
}

.checkout-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 30px 0;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 10px;
}

.btn-large {
    padding: 15px 30px;
    font-size: 16px;
}

.checkout-notice {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px;
    background: #f0f8ff;
    border-radius: 5px;
    margin-top: 20px;
}

.checkout-notice i {
    color: #3498db;
    font-size: 20px;
}

.checkout-notice p {
    margin: 0;
    color: #666;
    font-size: 14px;
}

@media (max-width: 768px) {
    .auction-summary {
        flex-direction: column;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-group.full-width {
        grid-column: span 1;
    }

    .checkout-actions {
        flex-direction: column;
        gap: 15px;
    }

    .checkout-actions .btn {
        width: 100%;
    }
}
</style>
@endsection