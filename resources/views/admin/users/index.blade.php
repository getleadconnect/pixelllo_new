@extends('layouts.admin')

@section('title', 'Users Management')
@section('page-title', 'Users Management')
@section('page-subtitle', 'View and manage all users')

@section('content')
<div class="admin-data-card">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">All Users</div>
        <div class="admin-data-card-actions">
            <a href="{{ url('/admin/users/create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Add New User
            </a>
        </div>
    </div>
    <div class="admin-data-card-body">
        <!-- Filter Form -->
        <form action="{{ url('/admin/users') }}" method="GET" class="mb-4">
            <div style="display: flex; gap: 10px; margin-bottom: 20px;">
                <div style="flex: 1;">
                    <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
                </div>
                <div style="width: 150px;">
                    <select name="role" class="form-control">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                    </select>
                </div>
                <div style="width: 150px;">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
        
        <div style="overflow-x: auto;">
            <table class="admin-table" style="min-width: 800px;">
                <thead>
                    <tr>
                        <th style="width: 15%; min-width: 100px;">ID</th>
                        <th style="width: 15%; min-width: 120px;">Name</th>
                        <th style="width: 20%; min-width: 150px;">Email</th>
                        <th style="width: 10%; min-width: 80px;">Role</th>
                        <th style="width: 10%; min-width: 80px;">Status</th>
                        <th style="width: 10%; min-width: 100px;">Bid Balance</th>
                        <th style="width: 12%; min-width: 120px;">Registration Date</th>
                        <th style="width: 8%; min-width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td style="word-break: break-all;">{{ substr($user->id, 0, 8) }}...</td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px;">{{ $user->name }}</td>
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">{{ $user->email }}</td>
                            <td>
                                <span class="status-badge {{ $user->role == 'admin' ? 'active' : 'pending' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge {{ $user->active ? 'active' : 'inactive-red' }}">
                                    {{ $user->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ number_format($user->bid_balance) }}</td>
                            <td style="white-space: nowrap;">{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <div style="display: flex; gap: 5px; justify-content: center;">
                                    <a href="{{ url('/admin/users/' . $user->id) }}" class="btn btn-sm btn-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ url('/admin/users/' . $user->id . '/edit') }}" class="btn btn-sm btn-success" title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if (Auth::id() != $user->id)
                                        <form action="{{ url('/admin/users/' . $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete User">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center;">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="admin-data-card-footer">
        <div style="display: flex; justify-content: center; align-items: center;">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Inactive badge styling */
    .status-badge.inactive-red {
        background-color: #fee2e2;
        color: #dc2626;
        border: 1px solid #fca5a5;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
</style>
@endsection