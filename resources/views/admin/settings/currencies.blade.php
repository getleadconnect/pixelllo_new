@extends('layouts.admin')

@section('title', 'Currency Settings')
@section('page-title', 'Currency Management')
@section('page-subtitle', 'Manage supported currencies and exchange rates')

@section('content')
<div class="admin-cards">
    <div class="admin-card">
        <div class="admin-card-inner">
            <div class="admin-card-icon" style="background-color: rgba(255, 153, 0, 0.1); color: var(--secondary);">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="admin-card-content">
                <h3>{{ count($currencies) }}</h3>
                <p>Supported Currencies</p>
            </div>
        </div>
    </div>
    
    <div class="admin-card">
        <div class="admin-card-inner">
            <div class="admin-card-icon" style="background-color: rgba(40, 167, 69, 0.1); color: var(--success);">
                <i class="fas fa-star"></i>
            </div>
            <div class="admin-card-content">
                <h3>{{ $defaultCurrency }}</h3>
                <p>Default Currency</p>
            </div>
        </div>
    </div>
    
    <div class="admin-card">
        <div class="admin-card-inner">
            <div class="admin-card-icon" style="background-color: rgba(23, 162, 184, 0.1); color: var(--info);">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <div class="admin-card-content">
                <h3>{{ $currentCurrency }}</h3>
                <p>Current Session</p>
            </div>
        </div>
    </div>
    
    <div class="admin-card">
        <div class="admin-card-inner">
            <div class="admin-card-icon" style="background-color: rgba(255, 193, 7, 0.1); color: var(--warning);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="admin-card-content">
                <h3>Manual</h3>
                <p>Update Method</p>
            </div>
        </div>
    </div>
</div>

<!-- Currency Settings -->
<div class="admin-data-card">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">Currency Settings</div>
    </div>
    <div class="admin-data-card-body">
        @if (session('success'))
            <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                {{ session('error') }}
            </div>
        @endif
        
        <form action="{{ route('admin.settings.currencies.settings') }}" method="POST">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <div>
                    <div class="form-group">
                        <label for="default_currency">Default Currency</label>
                        <select name="default_currency" id="default_currency" class="form-control" required>
                            @foreach($currencies as $code => $config)
                                <option value="{{ $code }}" {{ $defaultCurrency == $code ? 'selected' : '' }}>
                                    {{ $code }} - {{ $config['name'] }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">The primary currency for your store</small>
                    </div>
                </div>
                
                <div>
                    <div class="form-group">
                        <label>Display Options</label>
                        <div style="margin-top: 10px;">
                            <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                                <input type="checkbox" name="show_currency_selector" value="1" {{ \App\Models\Setting::get('show_currency_selector', true) ? 'checked' : '' }}>
                                <span>Show currency selector to users</span>
                            </label>
                            
                            <label style="display: flex; align-items: center; gap: 8px;">
                                <input type="checkbox" name="remember_user_choice" value="1" {{ \App\Models\Setting::get('remember_user_choice', true) ? 'checked' : '' }}>
                                <span>Remember user's currency choice</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 20px; text-align: right;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Settings
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Exchange Rates -->
<div class="admin-data-card">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">Exchange Rates (1 USD =)</div>
        <div class="admin-data-card-actions">
            <small class="text-muted">Last updated: Manual</small>
        </div>
    </div>
    <div class="admin-data-card-body">
        <form action="{{ route('admin.settings.currencies.rates') }}" method="POST">
            @csrf
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                @foreach($currencies as $code => $config)
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #e9ecef;">
                        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                            <div style="width: 50px; height: 50px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem; border: 2px solid #e9ecef;">
                                {{ $code }}
                            </div>
                            <div>
                                <h5 style="margin: 0;">{{ $config['name'] }}</h5>
                                <small style="color: var(--gray);">{{ $config['symbol'] }}</small>
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="rate_{{ $code }}">Exchange Rate</label>
                            <input type="number" 
                                   name="rates[{{ $code }}]" 
                                   id="rate_{{ $code }}" 
                                   class="form-control" 
                                   value="{{ $config['exchange_rate'] }}" 
                                   step="0.0001" 
                                   min="0.0001"
                                   {{ $code === 'USD' ? 'readonly' : 'required' }}>
                            @if($code === 'USD')
                                <small class="form-text text-muted">Base currency (cannot be changed)</small>
                            @else
                                <small class="form-text text-muted">1 USD = {{ $config['exchange_rate'] }} {{ $code }}</small>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div style="margin-top: 30px; text-align: right;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sync-alt"></i> Update Exchange Rates
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add New Currency -->
<div class="admin-data-card">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">Add New Currency</div>
    </div>
    <div class="admin-data-card-body">
        <form action="{{ route('admin.settings.currencies.add') }}" method="POST">
            @csrf
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div class="form-group">
                    <label for="code">Currency Code</label>
                    <input type="text" name="code" id="code" class="form-control" maxlength="3" placeholder="EUR" required style="text-transform: uppercase;">
                    <small class="form-text text-muted">3-letter ISO code (e.g., EUR, GBP)</small>
                </div>
                
                <div class="form-group">
                    <label for="name">Currency Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Euro" required>
                </div>
                
                <div class="form-group">
                    <label for="symbol">Symbol</label>
                    <input type="text" name="symbol" id="symbol" class="form-control" placeholder="€" required>
                </div>
                
                <div class="form-group">
                    <label for="symbol_position">Symbol Position</label>
                    <select name="symbol_position" id="symbol_position" class="form-control" required>
                        <option value="before">Before amount (€100)</option>
                        <option value="after">After amount (100€)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="decimal_places">Decimal Places</label>
                    <select name="decimal_places" id="decimal_places" class="form-control" required>
                        <option value="0">0 (¥100)</option>
                        <option value="2" selected>2 ($100.00)</option>
                        <option value="3">3 ($100.000)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="exchange_rate">Exchange Rate (1 USD =)</label>
                    <input type="number" name="exchange_rate" id="exchange_rate" class="form-control" step="0.0001" min="0.0001" placeholder="0.85" required>
                </div>
            </div>
            
            <div style="margin-top: 20px; text-align: right;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add Currency
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Current Currencies Table -->
<div class="admin-data-card">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">Manage Currencies</div>
    </div>
    <div class="admin-data-card-body">
        <div style="overflow-x: auto;">
            <table class="admin-table" style="min-width: 600px;">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Symbol</th>
                        <th>Position</th>
                        <th>Decimals</th>
                        <th>Rate (USD)</th>
                        <th>Example</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($currencies as $code => $config)
                        <tr>
                            <td>
                                <strong>{{ $code }}</strong>
                                @if($code === $defaultCurrency)
                                    <span class="status-badge active" style="margin-left: 5px; font-size: 0.7rem;">DEFAULT</span>
                                @endif
                            </td>
                            <td>{{ $config['name'] }}</td>
                            <td>{{ $config['symbol'] }}</td>
                            <td>{{ ucfirst($config['symbol_position']) }}</td>
                            <td>{{ $config['decimal_places'] }}</td>
                            <td>{{ number_format($config['exchange_rate'], 4) }}</td>
                            <td>
                                @if($config['symbol_position'] === 'before')
                                    {{ $config['symbol'] }}{{ number_format(100, $config['decimal_places']) }}
                                @else
                                    {{ number_format(100, $config['decimal_places']) }}{{ $config['symbol'] }}
                                @endif
                            </td>
                            <td>
                                @if($code !== 'USD' && $code !== $defaultCurrency)
                                    <form action="{{ route('admin.settings.currencies.remove', $code) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to remove {{ $code }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted" style="font-size: 0.8rem;">Protected</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-uppercase currency code input
    document.getElementById('code').addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase();
    });
</script>
@endsection