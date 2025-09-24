@extends('layouts.admin')

@section('title', 'Manage Categories')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-gray-800">Manage Categories</h1>
                <a href="{{ route('admin.categories') }}" class="btn btn-secondary">
                    <i class="fas fa-list"></i> View All Categories
                </a>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Left Column - Add/Edit Category Form (25%) -->
        <div class="col-md-3">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        @if(request('edit'))
                            Edit Category
                        @else
                            Add New Category
                        @endif
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $editCategory = null;
                        if(request('edit')) {
                            $editCategory = $categories->where('id', request('edit'))->first();
                        }
                    @endphp

                    <form method="POST" action="{{ $editCategory ? route('admin.categories.update', $editCategory->id) : route('admin.categories.store') }}">
                        @csrf
                        @if($editCategory)
                            @method('PUT')
                        @endif

                        <div class="form-group">
                            <label for="name">Category Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $editCategory ? $editCategory->name : '') }}"
                                   placeholder="Enter category name"
                                   required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="3"
                                      placeholder="Enter category description">{{ old('description', $editCategory ? $editCategory->description : '') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="parent_id">Parent Category</label>
                            <select class="form-control @error('parent_id') is-invalid @enderror"
                                    id="parent_id"
                                    name="parent_id">
                                <option value="">-- No Parent (Top Level) --</option>
                                @foreach($parentCategories as $parent)
                                    @if(!$editCategory || $parent->id !== $editCategory->id)
                                        <option value="{{ $parent->id }}"
                                                {{ old('parent_id', $editCategory ? $editCategory->parent_id : '') == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('parent_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save"></i>
                                {{ $editCategory ? 'Update Category' : 'Add Category' }}
                            </button>
                            @if($editCategory)
                                <a href="{{ route('admin.categories.manage') }}" class="btn btn-secondary btn-block mt-2">
                                    <i class="fas fa-times"></i> Cancel Edit
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Category Statistics -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">Total Categories</small>
                        <h5 class="mb-0">{{ $categories->count() }}</h5>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Parent Categories</small>
                        <h5 class="mb-0">{{ $categories->whereNull('parent_id')->count() }}</h5>
                    </div>
                    <div>
                        <small class="text-muted">Total Auctions</small>
                        <h5 class="mb-0">{{ $categories->sum('auctions_count') }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Categories List (75%) -->
        <div class="col-md-9">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Categories List</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 25%;">Name</th>
                                    <th style="width: 15%;">Slug</th>
                                    <th style="width: 25%;">Description</th>
                                    <th style="width: 15%;">Parent</th>
                                    <th style="width: 8%;">Auctions</th>
                                    <th style="width: 12%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                <tr class="{{ request('edit') == $category->id ? 'table-warning' : '' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $category->name }}</strong>
                                        @if(request('edit') == $category->id)
                                            <span class="badge badge-warning ml-2">Editing</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $category->slug }}</span>
                                    </td>
                                    <td>
                                        @if($category->description)
                                            {{ Str::limit($category->description, 50) }}
                                        @else
                                            <span class="text-muted">No description</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($category->parent)
                                            <span class="badge badge-info">{{ $category->parent->name }}</span>
                                        @else
                                            <span class="badge badge-success">Top Level</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-primary">{{ $category->auctions_count }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.categories.manage') }}?edit={{ $category->id }}"
                                               class="btn btn-sm btn-info"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($category->auctions_count == 0)
                                                <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                                      method="POST"
                                                      style="display: inline-block;"
                                                      onsubmit="return confirm('Are you sure you want to delete \'{{ $category->name }}\'? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button"
                                                        class="btn btn-sm btn-danger"
                                                        title="Cannot delete - Has auctions"
                                                        disabled>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <p class="mb-0">No categories found.</p>
                                        <p class="text-muted">Add your first category using the form on the left.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($categories->count() > 0)
                        <div class="mt-3">
                            <p class="text-muted mb-0">
                                <i class="fas fa-info-circle"></i>
                                Categories with auctions cannot be deleted. You must first reassign or delete all auctions in that category.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Auto-generate slug from name
    $('#name').on('keyup', function() {
        let name = $(this).val();
        // You could add slug preview here if needed
    });

    // Highlight edited row
    if (window.location.search.includes('edit=')) {
        $('html, body').animate({
            scrollTop: $('.table-warning').offset().top - 100
        }, 500);
    }

    // Form validation
    $('form').on('submit', function() {
        let name = $('#name').val().trim();
        if (name === '') {
            alert('Category name is required!');
            return false;
        }
        return true;
    });
});
</script>
@endsection