@extends('layouts.dashboard')

@section('dashboard-title', 'Account Settings')

@section('dashboard-content')
<div class="dashboard-panel active">
    <div class="panel-header">
        <h2>Account Settings</h2>
        <p>Manage your profile and preferences</p>
    </div>

    <div class="settings-container">
        <!-- Profile Settings -->
        <div class="settings-section">
            <h3 class="settings-section-title">Profile Information</h3>
            <form class="settings-form" action="{{ url('/dashboard/settings/profile') }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ $user->name ?? 'John Doe' }}">
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ $user->email ?? 'john.doe@example.com' }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="form-control" value="{{ $user->phone ?? '+1 (555) 123-4567' }}">
                    </div>
                    <div class="form-group">
                        <label for="country">Country</label>
                        <select id="country" name="country" class="form-control">
                            <option value="US" {{ ($user->country ?? '') == 'US' ? 'selected' : '' }}>United States</option>
                            <option value="CA" {{ ($user->country ?? '') == 'CA' ? 'selected' : '' }}>Canada</option>
                            <option value="UK" {{ ($user->country ?? '') == 'UK' ? 'selected' : '' }}>United Kingdom</option>
                            <option value="AU" {{ ($user->country ?? '') == 'AU' ? 'selected' : '' }}>Australia</option>
                            <option value="DE" {{ ($user->country ?? '') == 'DE' ? 'selected' : '' }}>Germany</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>

        <!-- Security Settings -->
        <div class="settings-section">
            <h3 class="settings-section-title">Security</h3>
            <form class="settings-form" action="{{ url('/dashboard/settings/password') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="form-control">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="new_password_confirmation">Confirm New Password</label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </div>
            </form>
        </div>

        <!-- Notification Settings -->
        <div class="settings-section">
            <h3 class="settings-section-title">Notification Preferences</h3>
            <form class="notification-settings" action="{{ url('/dashboard/settings/notifications') }}" method="POST">
                @csrf
                <div class="notification-option">
                    <div class="notification-option-label">
                        <span>Auction Outbid Alerts</span>
                        <p>Get notified when someone outbids you</p>
                    </div>
                    <div class="toggle-switch">
                        <input type="checkbox" id="outbid_notification" name="outbid_notification" checked>
                        <label for="outbid_notification"></label>
                    </div>
                </div>
                <div class="notification-option">
                    <div class="notification-option-label">
                        <span>Auction Ending Reminders</span>
                        <p>Get notified when auctions you're watching are about to end</p>
                    </div>
                    <div class="toggle-switch">
                        <input type="checkbox" id="ending_notification" name="ending_notification" checked>
                        <label for="ending_notification"></label>
                    </div>
                </div>
                <div class="notification-option">
                    <div class="notification-option-label">
                        <span>New Auction Alerts</span>
                        <p>Get notified when new auctions matching your interests are added</p>
                    </div>
                    <div class="toggle-switch">
                        <input type="checkbox" id="new_notification" name="new_notification" checked>
                        <label for="new_notification"></label>
                    </div>
                </div>
                <div class="notification-option">
                    <div class="notification-option-label">
                        <span>Order Status Updates</span>
                        <p>Get notified about changes to your order status</p>
                    </div>
                    <div class="toggle-switch">
                        <input type="checkbox" id="order_notification" name="order_notification" checked>
                        <label for="order_notification"></label>
                    </div>
                </div>
                <div class="notification-option">
                    <div class="notification-option-label">
                        <span>Promotional Emails</span>
                        <p>Receive special offers, promotions, and news</p>
                    </div>
                    <div class="toggle-switch">
                        <input type="checkbox" id="promo_notification" name="promo_notification">
                        <label for="promo_notification"></label>
                    </div>
                </div>
                <div class="form-group" style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">Save Preferences</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle switches
        const toggles = document.querySelectorAll('.toggle-switch input');
        toggles.forEach(toggle => {
            toggle.addEventListener('change', function() {
                console.log(`${this.id} is now ${this.checked ? 'enabled' : 'disabled'}`);
            });
        });
    });
</script>
@endsection