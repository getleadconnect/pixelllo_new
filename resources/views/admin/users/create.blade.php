@extends('layouts.admin')

@section('title', 'Create User')
@section('page-title', 'Create New User')
@section('page-subtitle', 'Add a new user to the system')

@section('content')
<div class="admin-data-card">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">User Information</div>
        <div class="admin-data-card-actions">
            <a href="{{ url('/admin/users') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>
        </div>
    </div>
    <div class="admin-data-card-body">
        <form action="{{ url('/admin/users') }}" method="POST">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <!-- Name -->
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Phone -->
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                    @error('phone')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Country -->
                <div class="form-group">
                    <label for="country">Country</label>
                    <select id="country" name="country" class="form-control @error('country') is-invalid @enderror" required>
                        <option value="US" {{ old('country') == 'US' ? 'selected' : '' }}>United States</option>
                        <option value="CA" {{ old('country') == 'CA' ? 'selected' : '' }}>Canada</option>
                        <option value="UK" {{ old('country') == 'UK' ? 'selected' : '' }}>United Kingdom</option>
                        <option value="AU" {{ old('country') == 'AU' ? 'selected' : '' }}>Australia</option>
                        <option value="DE" {{ old('country') == 'DE' ? 'selected' : '' }}>Germany</option>
                    </select>
                    @error('country')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Bid Balance -->
                <div class="form-group">
                    <label for="bid_balance">Bid Balance</label>
                    <input type="number" id="bid_balance" name="bid_balance" class="form-control @error('bid_balance') is-invalid @enderror" value="{{ old('bid_balance', 0) }}" min="0" required>
                    @error('bid_balance')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Role -->
                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" class="form-control @error('role') is-invalid @enderror" required>
                        <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Status -->
                <div class="form-group">
                    <label for="active">Status</label>
                    <select id="active" name="active" class="form-control @error('active') is-invalid @enderror" required>
                        <option value="1" {{ old('active', 1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('active') == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('active')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-group" style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Create User</button>
                <a href="{{ url('/admin/users') }}" class="btn btn-danger">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection