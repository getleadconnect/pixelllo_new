@extends('layouts.admin')

@section('title', 'Complete Purchase - ' . $auction->title)
@section('page-title', 'Complete Purchase')
@section('page-subtitle', 'Create order for won auction')

@section('styles')
<style>
    .purchase-form-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .form-card {
        background: white;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .form-card h4 {
        margin-bottom: 20px;
        color: #333;
        font-weight: 600;
    }

    .auction-summary {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #dee2e6;
    }

    .summary-item:last-child {
        border-bottom: none;
        font-weight: bold;
        font-size: 1.1rem;
        color: #007bff;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 8px;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }

    .payment-method-options {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .payment-option {
        flex: 1;
        min-width: 150px;
    }

    .payment-option input[type="radio"] {
        display: none;
    }

    .payment-option label {
        display: block;
        padding: 15px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .payment-option input[type="radio"]:checked + label {
        border-color: #007bff;
        background-color: #f0f8ff;
        color: #007bff;
    }

    .payment-option label:hover {
        border-color: #007bff;
        background-color: #f8f9fa;
    }

    .btn-group-footer {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 30px;
    }

    .alert-info {
        background-color: #d1ecf1;
        border-color: #bee5eb;
        color: #0c5460;
    }

    .winner-info {
        background: #e8f4f8;
        border-left: 4px solid #007bff;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
    }

    .winner-info h5 {
        margin-bottom: 10px;
        color: #007bff;
    }

    .winner-detail {
        margin-bottom: 5px;
        color: #495057;
    }

    .price-calculator {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-top: 20px;
    }

    .calc-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        align-items: center;
    }

    .calc-row.total {
        border-top: 2px solid #dee2e6;
        padding-top: 10px;
        font-size: 1.2rem;
        font-weight: bold;
        color: #007bff;
    }

    .form-text {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 5px;
    }
</style>
@endsection

@section('content')
<div class="purchase-form-container">
    <!-- Auction Summary Card -->
    <div class="form-card">
        <h4><i class="fas fa-trophy"></i> Auction Details</h4>
        <div class="auction-summary">
            <div class="summary-item">
                <span>Auction Title:</span>
                <strong>{{ $auction->title }}</strong>
            </div>
            <div class="summary-item">
                <span>Auction ID:</span>
                <span>{{ $auction->id }}</span>
            </div>
            <div class="summary-item">
                <span>Category:</span>
                <span>{{ $auction->category ? $auction->category->name : 'N/A' }}</span>
            </div>
            <div class="summary-item">
                <span>Won Date:</span>
                <span>{{ $auction->endTime->format('M d, Y h:i A') }}</span>
            </div>
            <div class="summary-item">
                <span>Retail Price:</span>
                <span>AED {{ number_format($auction->retailPrice, 2) }}</span>
            </div>
            <div class="summary-item">
                <span>Final Bid Price:</span>
                <span>AED {{ number_format($auction->currentPrice, 2) }}</span>
            </div>
        </div>

        <!-- Winner Information -->
        <div class="winner-info">
            <h5><i class="fas fa-user-circle"></i> Winner Information</h5>
            <div class="winner-detail">
                <strong>Name:</strong> {{ $auction->winner->name }}
            </div>
            <div class="winner-detail">
                <strong>Email:</strong> {{ $auction->winner->email }}
            </div>
            @if($auction->winner->phone)
            <div class="winner-detail">
                <strong>Phone:</strong> {{ $auction->winner->phone }}
            </div>
            @endif
        </div>
    </div>

    <!-- Order Form Card -->
    <div class="form-card">
        <h4><i class="fas fa-shopping-cart"></i> Order Information</h4>

        <form action="{{ route('admin.auctions.complete-purchase', $auction->id) }}" method="POST">
            @csrf

            <!-- Shipping Address -->
            <div class="form-group">
                <label for="shipping_address" class="form-label">
                    <i class="fas fa-map-marker-alt"></i> Shipping Address
                </label>
                <textarea
                    name="shipping_address"
                    id="shipping_address"
                    class="form-control @error('shipping_address') is-invalid @enderror"
                    rows="3"
                    required
                    placeholder="Enter complete shipping address...">{{ old('shipping_address', $auction->winner->address ?? '') }}</textarea>
                @error('shipping_address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text">Enter the complete shipping address including street, city, state, and ZIP code</small>
            </div>

            <!-- Payment Method -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-credit-card"></i> Payment Method
                </label>
                <div class="payment-method-options">
                    <div class="payment-option">
                        <input type="radio" name="payment_method" id="credit_card" value="credit_card" checked>
                        <label for="credit_card">
                            <i class="fas fa-credit-card"></i><br>
                            Credit Card
                        </label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" name="payment_method" id="paypal" value="paypal">
                        <label for="paypal">
                            <i class="fab fa-paypal"></i><br>
                            PayPal
                        </label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" name="payment_method" id="bank_transfer" value="bank_transfer">
                        <label for="bank_transfer">
                            <i class="fas fa-university"></i><br>
                            Bank Transfer
                        </label>
                    </div>
                </div>
                @error('payment_method')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>

            <!-- Price Calculation -->
            <div class="price-calculator">
                <h5 class="mb-3">Price Breakdown</h5>

                <div class="calc-row">
                    <span>Subtotal (Final Bid Price):</span>
                    <span id="subtotal">AED {{ number_format($auction->currentPrice, 2) }}</span>
                </div>

                <div class="form-group">
                    <div class="calc-row">
                        <label for="shipping_cost" style="margin: 0;">Shipping Cost:</label>
                        <div style="width: 150px;">
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input
                                    type="number"
                                    name="shipping_cost"
                                    id="shipping_cost"
                                    class="form-control @error('shipping_cost') is-invalid @enderror"
                                    value="{{ old('shipping_cost', '0.00') }}"
                                    min="0"
                                    step="0.01"
                                    required
                                    onchange="calculateTotal()">
                            </div>
                        </div>
                    </div>
                    @error('shipping_cost')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="calc-row">
                        <label for="tax" style="margin: 0;">Tax:</label>
                        <div style="width: 150px;">
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input
                                    type="number"
                                    name="tax"
                                    id="tax"
                                    class="form-control @error('tax') is-invalid @enderror"
                                    value="{{ old('tax', '0.00') }}"
                                    min="0"
                                    step="0.01"
                                    required
                                    onchange="calculateTotal()">
                            </div>
                        </div>
                    </div>
                    @error('tax')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="calc-row total">
                    <span>Total Amount:</span>
                    <span id="total_amount">AED {{ number_format($auction->currentPrice, 2) }}</span>
                </div>
            </div>

            <!-- Order Notes -->
            <div class="form-group mt-3">
                <label for="notes" class="form-label">
                    <i class="fas fa-sticky-note"></i> Order Notes (Optional)
                </label>
                <textarea
                    name="notes"
                    id="notes"
                    class="form-control @error('notes') is-invalid @enderror"
                    rows="3"
                    placeholder="Any special instructions or notes about this order...">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="btn-group-footer">
                <a href="{{ route('admin.auctions.won') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-check-circle"></i> Complete Purchase
                </button>
            </div>
        </form>
    </div>

    <!-- Information Alert -->
    <div class="alert alert-info" role="alert">
        <i class="fas fa-info-circle"></i>
        <strong>Note:</strong> After completing this purchase, an order will be created with status "Pending".
        The winner will need to complete the payment through the available payment methods.
    </div>
</div>
@endsection

@section('scripts')
<script>
function calculateTotal() {
    const subtotal = {{ $auction->currentPrice }};
    const shipping = parseFloat(document.getElementById('shipping_cost').value) || 0;
    const tax = parseFloat(document.getElementById('tax').value) || 0;
    const total = subtotal + shipping + tax;

    document.getElementById('total_amount').textContent = '$' + total.toFixed(2);
}

// Calculate on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();
});
</script>
@endsection