@extends('layouts.admin')

@section('title', 'Create Bid Package')
@section('page-title', 'Create Bid Package')
@section('page-subtitle', 'Add a new bid package')

@section('content')
<div class="admin-data-card">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">New Bid Package</div>
        <div class="admin-data-card-actions">
            <a href="{{ route('admin.bid-packages.index') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-arrow-left"></i> Back to Packages
            </a>
        </div>
    </div>
    <div class="admin-data-card-body">
        <form action="{{ route('admin.bid-packages.store') }}" method="POST">
            @csrf

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin-bottom: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <label for="name">Package Name <span style="color: red;">*</span></label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g., Starter Package">
            </div>

            <div class="form-group">
                <label for="bidAmount">Bid Amount <span style="color: red;">*</span></label>
                <input type="number" name="bidAmount" id="bidAmount" class="form-control" value="{{ old('bidAmount') }}" required min="1" placeholder="e.g., 50">
                <small class="form-text text-muted">Number of bid credits included in this package.</small>
            </div>

            <div class="form-group">
                <label for="price">Price (AED) <span style="color: red;">*</span></label>
                <input type="number" name="price" id="price" class="form-control" step="0.01" min="0" value="{{ old('price') }}" required placeholder="e.g., 10.00">
                <small class="form-text text-muted">Price of the package in AED.</small>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="3" placeholder="Optional description for this package">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" name="isActive" id="isActive" class="form-check-input" value="1" {{ old('isActive', true) ? 'checked' : '' }}>
                    <label for="isActive" class="form-check-label">Active</label>
                    <small class="form-text text-muted">Only active packages are visible to users.</small>
                </div>
            </div>

            <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px; text-align: right;">
                <a href="{{ route('admin.bid-packages.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Package</button>
            </div>
        </form>
    </div>
</div>
@endsection
