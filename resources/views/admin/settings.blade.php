@extends('layouts.admin')

@section('title', 'Admin Settings')
@section('page-title', 'Settings')
@section('page-subtitle', 'Manage your account and site settings')

@section('content')
<div class="row">
    <!-- Profile Settings -->
    <div class="col-lg-6">
        <div class="admin-data-card">
            <div class="admin-data-card-header">
                <div class="admin-data-card-title">Profile Settings</div>
            </div>
            <div class="admin-data-card-body">
                <form action="{{ route('admin.settings.profile') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                    </div>
                    
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="admin-data-card mt-4">
            <div class="admin-data-card-header">
                <div class="admin-data-card-title">Change Password</div>
            </div>
            <div class="admin-data-card-body">
                <form action="{{ route('admin.settings.password') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <small class="form-text text-muted">Password must be at least 8 characters long.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation">Confirm New Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Site Settings -->
    <div class="col-lg-6">
        <div class="admin-data-card">
            <div class="admin-data-card-header">
                <div class="admin-data-card-title">Site Settings</div>
            </div>
            <div class="admin-data-card-body">
                <form action="{{ route('admin.settings.site') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="site_name">Site Name</label>
                        <input type="text" class="form-control" id="site_name" name="site_name" value="{{ old('site_name', $siteSettings['site_name']) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="site_email">Site Contact Email</label>
                        <input type="email" class="form-control" id="site_email" name="site_email" value="{{ old('site_email', $siteSettings['site_email']) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="bid_increment_default">Default Bid Increment</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="number" class="form-control" id="bid_increment_default" name="bid_increment_default" value="{{ old('bid_increment_default', $siteSettings['bid_increment_default']) }}" min="0.01" step="0.01" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="auction_extension_time">Auction Extension Time (seconds)</label>
                        <input type="number" class="form-control" id="auction_extension_time" name="auction_extension_time" value="{{ old('auction_extension_time', $siteSettings['auction_extension_time']) }}" min="0" required>
                        <small class="form-text text-muted">Time to extend an auction when a bid is placed near the end time.</small>
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="enable_notifications" name="enable_notifications" value="1" {{ old('enable_notifications', $siteSettings['enable_notifications']) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="enable_notifications">Enable Email Notifications</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="maintenance_mode" name="maintenance_mode" value="1" {{ old('maintenance_mode', $siteSettings['maintenance_mode']) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="maintenance_mode">Maintenance Mode</label>
                            <small class="form-text text-muted">When enabled, the site will show a maintenance page to all non-admin users.</small>
                        </div>
                    </div>
                    
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">Save Site Settings</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="admin-data-card mt-4">
            <div class="admin-data-card-header">
                <div class="admin-data-card-title">System Information</div>
            </div>
            <div class="admin-data-card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>PHP Version</th>
                            <td>{{ phpversion() }}</td>
                        </tr>
                        <tr>
                            <th>Laravel Version</th>
                            <td>{{ app()->version() }}</td>
                        </tr>
                        <tr>
                            <th>Server</th>
                            <td>{{ $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' }}</td>
                        </tr>
                        <tr>
                            <th>Database</th>
                            <td>{{ config('database.connections.' . config('database.default') . '.driver') }}</td>
                        </tr>
                        <tr>
                            <th>Environment</th>
                            <td>{{ config('app.env') }}</td>
                        </tr>
                        <tr>
                            <th>Debug Mode</th>
                            <td>{{ config('app.debug') ? 'Enabled' : 'Disabled' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: var(--secondary);
        border-color: var(--secondary);
    }
    
    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }
    
    .col-lg-6 {
        flex: 0 0 100%;
        max-width: 100%;
        padding-right: 15px;
        padding-left: 15px;
    }
    
    @media (min-width: 992px) {
        .col-lg-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }
    
    .mt-4 {
        margin-top: 1.5rem !important;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .table {
        width: 100%;
        margin-bottom: 1rem;
        color: var(--dark);
        border-collapse: collapse;
    }
    
    .table th, .table td {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 1px solid var(--light-gray);
    }
    
    .table-bordered {
        border: 1px solid var(--light-gray);
    }
    
    .table-bordered th, .table-bordered td {
        border: 1px solid var(--light-gray);
    }
    
    .custom-control {
        position: relative;
        display: block;
        min-height: 1.5rem;
        padding-left: 1.5rem;
    }
    
    .custom-switch {
        padding-left: 2.25rem;
    }
    
    .custom-control-label {
        position: relative;
        margin-bottom: 0;
        vertical-align: top;
    }
    
    .custom-control-label::before {
        position: absolute;
        top: 0.25rem;
        left: -1.5rem;
        display: block;
        width: 1rem;
        height: 1rem;
        content: "";
        background-color: #fff;
        border: 1px solid var(--gray);
        transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    
    .custom-switch .custom-control-label::before {
        left: -2.25rem;
        width: 1.75rem;
        pointer-events: all;
        border-radius: 0.5rem;
    }
    
    .custom-switch .custom-control-label::after {
        top: calc(0.25rem + 2px);
        left: calc(-2.25rem + 2px);
        width: calc(1rem - 4px);
        height: calc(1rem - 4px);
        background-color: var(--gray);
        border-radius: 0.5rem;
        transition: transform 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    
    .custom-control-input:checked ~ .custom-control-label::after {
        background-color: #fff;
        transform: translateX(0.75rem);
    }
    
    .input-group {
        position: relative;
        display: flex;
        flex-wrap: wrap;
        align-items: stretch;
        width: 100%;
    }
    
    .input-group-prepend {
        margin-right: -1px;
        display: flex;
    }
    
    .input-group-text {
        display: flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        margin-bottom: 0;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        text-align: center;
        white-space: nowrap;
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        border-radius: 0.25rem 0 0 0.25rem;
    }
    
    .input-group > .form-control:not(:first-child) {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
</style>
@endsection