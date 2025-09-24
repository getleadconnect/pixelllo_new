@php
use App\Helpers\CurrencyHelper;
use App\Models\Setting;
$currencies = CurrencyHelper::getSupportedCurrencies();
$currentCurrency = CurrencyHelper::getCurrentCurrency();
$showSelector = Setting::get('show_currency_selector', true);
@endphp

@if($showSelector && count($currencies) > 1)
<div class="currency-selector" style="position: relative; display: inline-block;">
    <button type="button" 
            class="currency-toggle" 
            onclick="toggleCurrencyDropdown()"
            style="background: none; border: 1px solid #ddd; padding: 8px 12px; border-radius: 4px; cursor: pointer; display: flex; align-items: center; gap: 8px; font-size: 0.9rem;">
        <i class="fas fa-dollar-sign"></i>
        <span>{{ $currentCurrency }}</span>
        <i class="fas fa-chevron-down" style="font-size: 0.8rem;"></i>
    </button>
    
    <div id="currency-dropdown" 
         style="position: absolute; top: 100%; right: 0; background: white; border: 1px solid #ddd; border-radius: 4px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); z-index: 1000; min-width: 150px; display: none;">
        @foreach($currencies as $code => $config)
            <button type="button" 
                    class="currency-option"
                    data-currency="{{ $code }}"
                    onclick="changeCurrency('{{ $code }}')"
                    style="width: 100%; padding: 10px 15px; border: none; background: none; text-align: left; cursor: pointer; display: flex; justify-content: space-between; align-items: center; {{ $code === $currentCurrency ? 'background-color: #f8f9fa; font-weight: bold;' : '' }}">
                <span>{{ $code }} - {{ $config['name'] }}</span>
                <span>{{ $config['symbol'] }}</span>
            </button>
        @endforeach
    </div>
</div>

<script>
function toggleCurrencyDropdown() {
    const dropdown = document.getElementById('currency-dropdown');
    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
}

function changeCurrency(currency) {
    fetch('{{ route("currency.switch") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ currency: currency })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload the page to show new prices
            window.location.reload();
        } else {
            alert('Error changing currency: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error changing currency');
    });
    
    // Hide dropdown
    document.getElementById('currency-dropdown').style.display = 'none';
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const selector = document.querySelector('.currency-selector');
    const dropdown = document.getElementById('currency-dropdown');
    
    if (selector && dropdown && !selector.contains(event.target)) {
        dropdown.style.display = 'none';
    }
});
</script>

<style>
.currency-option:hover {
    background-color: #f8f9fa !important;
}

.currency-toggle:hover {
    background-color: #f8f9fa;
}
</style>
@endif