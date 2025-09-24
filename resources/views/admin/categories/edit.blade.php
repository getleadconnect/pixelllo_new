@extends('admin.layouts.app')

@section('title', 'Edit Category - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Category: {{ $category->name }}</h1>
        <a href="{{ route('admin.categories') }}" class="d-none d-sm-inline-block btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Categories
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Category Details</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="name">Category Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="slug">Slug</label>
                    <input type="text" class="form-control" id="slug" value="{{ $category->slug }}" disabled>
                    <small class="form-text text-muted">The slug is automatically generated from the category name and used in URLs.</small>
                </div>
                
                <div class="form-group">
                    <label for="parent_id">Parent Category</label>
                    <select class="form-control @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                        <option value="">None (Top Level Category)</option>
                        @foreach($categories as $otherCategory)
                            <option value="{{ $otherCategory->id }}" {{ (old('parent_id', $category->parent_id) == $otherCategory->id) ? 'selected' : '' }}>
                                {{ $otherCategory->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">You cannot select this category as its own parent or any of its descendants.</small>
                </div>
                
                <button type="submit" class="btn btn-primary">Update Category</button>
            </form>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Category Statistics</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Active Auctions</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $category->auctions()->where('status', 'active')->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-gavel fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Upcoming Auctions</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $category->auctions()->where('status', 'upcoming')->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Ended Auctions</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $category->auctions()->where('status', 'ended')->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-trophy fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($category->auctions()->count() > 0)
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Manage Category Auctions</h6>
            <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="collapse" data-target="#manageAuctionsCollapse" aria-expanded="false" aria-controls="manageAuctionsCollapse">
                <i class="fas fa-chevron-down"></i> Toggle View
            </button>
        </div>
        <div class="collapse" id="manageAuctionsCollapse">
            <div class="card-body">
                <h5 class="mb-3">Reassign Auctions</h5>
                <p>Use this form to move all auctions from this category to another category. This is useful before deleting a category.</p>

                <form action="{{ route('admin.categories.reassign-auctions', $category->id) }}" method="POST" id="reassignForm">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="target_category_id">Move all auctions to:</label>
                        <select class="form-control" id="target_category_id" name="target_category_id" required>
                            <option value="">-- Select Target Category --</option>
                            @foreach($categories as $otherCategory)
                                <option value="{{ $otherCategory->id }}">{{ $otherCategory->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> This action will move <strong>{{ $category->auctions()->count() }}</strong> auctions to the selected category.
                    </div>

                    <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to reassign all auctions from this category to the selected category?')">
                        <i class="fas fa-exchange-alt"></i> Reassign All Auctions
                    </button>
                </form>

                <hr class="my-4">

                <h5 class="mb-3">Current Auctions</h5>
                <div class="table-responsive">
                    <table class="table table-bordered" id="categoryAuctionsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->auctions()->limit(10)->get() as $auction)
                            <tr>
                                <td>{{ $auction->id }}</td>
                                <td>{{ $auction->title }}</td>
                                <td>
                                    <span class="badge badge-{{ $auction->status == 'active' ? 'success' : ($auction->status == 'upcoming' ? 'primary' : 'secondary') }}">
                                        {{ ucfirst($auction->status) }}
                                    </span>
                                </td>
                                <td>{{ $auction->startTime }}</td>
                                <td>{{ $auction->endTime }}</td>
                                <td>
                                    <a href="{{ route('admin.auctions.edit', $auction->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($category->auctions()->count() > 10)
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.auctions') }}?category={{ $category->id }}" class="btn btn-outline-primary">
                                View All {{ $category->auctions()->count() }} Auctions
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Update slug field when name changes
        $('#name').on('keyup', function() {
            const name = $(this).val();
            const slug = name.toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
            $('#slug').val(slug);
        });
    });
</script>
@endsection