@extends('layouts.admin')

@section('title', 'Categories')

@section('styles')
<style>
    .datatable-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        padding: 20px;
        overflow-x: hidden; /* Prevent horizontal scrollbar */
    }

    /* Ensure table responsiveness without horizontal scroll */
    .table-responsive {
        overflow-x: visible !important;
        overflow-y: visible !important;
    }

    .datatable-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .datatable {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .datatable thead {
        background-color: #f8f9fa;
    }

    .datatable thead th {
        padding:5px 12px;
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #dee2e6;
        white-space: nowrap;
        text-align: left;
    }

    .datatable tbody td {
        padding: 5px 12px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .datatable tbody tr:hover {
        background-color: #f8f9fa;
    }

    .badge-status {
        padding: 6px 12px;
        border-radius: 4px;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 11px;
    }

    .badge-active {
        background-color: #d4edda;
        color: #155724;
    }

    .action-btn {
        background: transparent;
        color: #6c757d;
        border: 1px solid #6c757d;
        padding: 3px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        background: #c4c4c4;
        border-color: #c4c4c4;
        color: #fff;
    }

    .action-btn:focus {
        outline: none;
        box-shadow: none;
    }

    .dropdown {
        position: relative;
    }

    .dropdown-menu {
        position: absolute !important;
        margin: 0 !important;
        border: 1px solid #dee2e6;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        right: calc(100% + 5px) !important;
        left: auto !important;
        min-width: 140px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s ease, visibility 0.2s ease;
        transform: translate(0, 0) !important;
        will-change: opacity, visibility;
        max-height: 300px;
        overflow-y: auto;
    }

    .dropdown-menu.show {
        opacity: 1;
        visibility: visible;
    }

    /* Override Bootstrap's transform for all placements */
    .dropdown-menu[x-placement^="left"],
    .dropdown-menu[x-placement^="right"],
    .dropdown-menu[x-placement^="top"],
    .dropdown-menu[x-placement^="bottom"] {
        right: calc(100% + 5px) !important;
        left: auto !important;
        transform: translate(0, 0) !important;
    }

    /* Specific styling for dropdown when showing above */
    .dropdown-menu.dropup {
        bottom: 0 !important;
        top: auto !important;
    }

    /* Specific styling for dropdown when showing below (default) */
    .dropdown-menu:not(.dropup) {
        top: 0 !important;
        bottom: auto !important;
    }

    .dropdown-item {
        padding: 8px 16px;
        cursor: pointer;
        transition: background-color 0.15s ease;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    .category-icon {
        width: 40px;
        height: 40px;
        background: #e9ecef;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #6c757d;
    }

    .datatable-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding-bottom: 50px; /* Add space for dropdown of last row */
    }

    .showing-info {
        color: #6c757d;
        font-size: 14px;
    }

    .entries-select {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .search-box {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .page-link {
        color: #495057;
        border: 1px solid #dee2e6;
    }

    .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }

    .pagination {
        margin-bottom: 0;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Categories</h1>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#categoryModal">
            <i class="fas fa-plus"></i> Add New Category
        </button>
    </div>


    <!-- Categories Table Card -->
    <div class="datatable-container">
        <!-- Datatable Header: Show entries (left) and Search (right) -->
        <div class="datatable-header">
            <div class="entries-select">
                <label class="mb-0">Show</label>
                <select class="form-control form-control-sm" id="entriesSelect" style="width: 80px;">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
                <label class="mb-0">entries</label>
            </div>

            <div class="search-box">
                <label class="mb-0">Search:</label>
                <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Type to search..." style="width: 200px;">
            </div>
        </div>

        <!-- Categories Table -->
        <div class="table-responsive">
            <table class="table datatable" style="font-size:14px;">
                <thead>
                    <tr>
                        <th style="width: 60px">ID</th>
                        <th style="width: 80px">Image</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Description</th>
                        <th style="width: 120px">Parent</th>
                        <th style="width: 90px">Featured</th>
                        <th style="width: 80px">Auctions</th>
                        <th style="width: 60px">Actions</th>
                    </tr>
                </thead>
                <tbody id="categoriesTableBody">
                    @forelse($categories as $category)
                    <tr>
                        <td>{{ str_pad($loop->iteration + (($categories->currentPage() - 1) * $categories->perPage()), 6, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <div class="category-icon">
                                    <i class="fas fa-folder"></i>
                                </div>
                            @endif
                        </td>
                        <td><strong>{{ $category->name }}</strong></td>
                        <td><code>{{ $category->slug }}</code></td>
                        <td>{{ Str::limit($category->description ?? '-', 50) }}</td>
                        <td>
                            @if($category->parent)
                                <span class="badge badge-secondary">{{ $category->parent->name }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($category->featured)
                                <span class="badge badge-warning text-dark">
                                    <i class="fas fa-star"></i> Featured
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge badge-info">{{ $category->auctions_count ?? 0 }}</span>
                        </td>
                        <td>
                            <div class="dropdown" data-boundary="viewport">
                                <button class="action-btn" type="button" data-toggle="dropdown" data-display="static">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu" data-placement="left">
                                    <button type="button" class="dropdown-item edit-category"
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}"
                                            data-description="{{ $category->description }}"
                                            data-parent="{{ $category->parent_id }}"
                                            data-slug="{{ $category->slug }}"
                                            data-image="{{ $category->image }}"
                                            data-featured="{{ $category->featured ? '1' : '0' }}">
                                        <i class="fas fa-edit text-info"></i> Edit
                                    </button>
                                    <div class="dropdown-divider"></div>
                                    <button type="button" class="dropdown-item delete-category"
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}">
                                        <i class="fas fa-trash text-danger"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">No categories found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Datatable Footer: Showing info (left) and Pagination (right) -->
        <div class="datatable-footer">
            <div class="showing-info">
                Showing {{ $categories->firstItem() ?? 0 }} to {{ $categories->lastItem() ?? 0 }}
                of {{ $categories->total() }} entries
            </div>

            <div class="pagination-wrapper">
                {{ $categories->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalTitle">Add New Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="categoryForm" method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="categoryName">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="categoryName" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label for="categorySlug">Slug</label>
                        <input type="text" class="form-control" id="categorySlug" readonly>
                        <small class="text-muted">Auto-generated from name</small>
                    </div>

                    <div class="form-group">
                        <label for="categoryDescription">Description</label>
                        <textarea class="form-control" id="categoryDescription" name="description" rows="3" placeholder="Optional description"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="categoryImage">Category Image</label>
                        <input type="file" class="form-control-file" id="categoryImage" name="image" accept="image/*">
                        <small class="text-muted">Supported formats: JPEG, PNG, JPG, GIF, WEBP (Max: 2MB)</small>
                        <div class="mt-2" id="imagePreview" style="display: none;">
                            <img src="" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="categoryParent">Parent Category</label>
                        <select class="form-control" id="categoryParent" name="parent_id">
                            <option value="">-- No Parent (Top Level) --</option>
                            @foreach($categories->whereNull('parent_id') as $parent)
                                <option value="{{ $parent->id }}" data-category-id="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="categoryFeatured" name="featured" value="1">
                            <label class="custom-control-label" for="categoryFeatured">
                                <strong>Set as Featured Category</strong>
                                <br>
                                <small class="text-muted">Featured categories will be highlighted and shown prominently on the homepage</small>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalTitle">
                    <i class="fas fa-exclamation-triangle"></i> Confirm Delete
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the category: <strong id="deleteCategoryName"></strong>?</p>
                <p class="text-danger mb-0">
                    <i class="fas fa-info-circle"></i> This action cannot be undone!
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Configure dropdown positioning based on position in viewport
    $(document).on('show.bs.dropdown', '.dropdown', function(e) {
        var $dropdown = $(this);
        var $menu = $dropdown.find('.dropdown-menu');
        var $button = $dropdown.find('.action-btn');

        // Get the table row this dropdown belongs to
        var $row = $dropdown.closest('tr');
        var rowIndex = $row.index();
        var totalRows = $row.closest('tbody').find('tr').length;

        // Check if this is one of the last 2 rows
        var isNearBottom = (totalRows - rowIndex) <= 2;

        // Reset any previous positioning
        $menu.removeClass('dropup');

        // Make menu visible temporarily to calculate height
        $menu.css({
            'display': 'block',
            'visibility': 'hidden',
            'position': 'absolute'
        });

        // Get actual menu height
        var menuHeight = $menu.outerHeight();

        // Hide menu again
        $menu.css({
            'display': '',
            'visibility': '',
            'position': ''
        });

        // Get button position relative to viewport
        var buttonRect = $button[0].getBoundingClientRect();
        var windowHeight = window.innerHeight || $(window).height();

        // Calculate available space
        var spaceBelow = windowHeight - buttonRect.bottom;
        var spaceAbove = buttonRect.top;

        // Determine position - for last 2 rows or insufficient space, show above
        if (isNearBottom || (spaceBelow < menuHeight + 30)) {
            // Show above the button
            $menu.addClass('dropup');
            $menu.css({
                'position': 'absolute',
                'right': 'calc(100% + 5px)',
                'left': 'auto',
                'bottom': '0',
                'top': 'auto'
            });
        } else {
            // Show below (default)
            $menu.removeClass('dropup');
            $menu.css({
                'position': 'absolute',
                'right': 'calc(100% + 5px)',
                'left': 'auto',
                'top': '0',
                'bottom': 'auto'
            });
        }
    });

    // Ensure dropdown closes and resets when hidden
    $(document).on('hidden.bs.dropdown', '.dropdown', function() {
        var $menu = $(this).find('.dropdown-menu');
        $menu.removeClass('dropup');
    });

    // Auto-generate slug from name
    $('#categoryName').on('input', function() {
        var name = $(this).val();
        var slug = name.toLowerCase()
            .replace(/[^\w\s-]/g, '') // Remove special characters
            .replace(/\s+/g, '-')      // Replace spaces with -
            .replace(/--+/g, '-')      // Replace multiple - with single -
            .trim();                   // Trim - from start and end
        $('#categorySlug').val(slug);
    });

    // Edit category
    $(document).on('click', '.edit-category', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var description = $(this).data('description');
        var parent = $(this).data('parent');
        var slug = $(this).data('slug');
        var image = $(this).data('image');
        var featured = $(this).data('featured');

        // Update modal title
        $('#categoryModalTitle').text('Edit Category');

        // Fill form fields
        $('#categoryName').val(name);
        $('#categoryDescription').val(description);
        $('#categorySlug').val(slug);

        // Set featured checkbox
        if (featured == '1') {
            $('#categoryFeatured').prop('checked', true);
        } else {
            $('#categoryFeatured').prop('checked', false);
        }

        // Set parent category - hide current category from parent options
        $('#categoryParent option').show();
        $('#categoryParent option[data-category-id="' + id + '"]').hide();
        $('#categoryParent').val(parent || '');

        // Show existing image if available
        if (image) {
            $('#imagePreview img').attr('src', '/storage/' + image);
            $('#imagePreview').show();
        } else {
            $('#imagePreview').hide();
        }

        // Update form action and method
        $('#formMethod').val('PUT');
        $('#categoryForm').attr('action', '/admin/categories/' + id);

        // Show modal
        $('#categoryModal').modal('show');
    });

    // Reset form when modal is closed
    $('#categoryModal').on('hidden.bs.modal', function() {
        // Reset title
        $('#categoryModalTitle').text('Add New Category');

        // Reset form
        $('#categoryForm')[0].reset();
        $('#categorySlug').val('');
        $('#imagePreview').hide();
        $('#categoryFeatured').prop('checked', false);

        // Reset action and method
        $('#formMethod').val('POST');
        $('#categoryForm').attr('action', '{{ route("admin.categories.store") }}');

        // Show all parent options
        $('#categoryParent option').show();

        // Clear any validation errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    });

    // Delete category
    $(document).on('click', '.delete-category', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');

        $('#deleteCategoryName').text(name);
        $('#deleteForm').attr('action', '/admin/categories/' + id);
        $('#deleteModal').modal('show');
    });

    // Live search
    $('#searchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#categoriesTableBody tr').each(function() {
            var text = $(this).text().toLowerCase();
            $(this).toggle(text.indexOf(value) > -1);
        });
    });

    // Entries per page
    $('#entriesSelect').change(function() {
        var perPage = $(this).val();
        var url = new URL(window.location.href);
        url.searchParams.set('per_page', perPage);
        url.searchParams.delete('page'); // Reset to first page
        window.location.href = url.toString();
    });

    // Preview image before upload
    $('#categoryImage').on('change', function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview img').attr('src', e.target.result);
                $('#imagePreview').show();
            }
            reader.readAsDataURL(file);
        } else {
            $('#imagePreview').hide();
        }
    });

    // Handle form submission with AJAX
    $('#categoryForm').on('submit', function(e) {
        e.preventDefault();

        var form = $(this);
        var url = form.attr('action');
        var method = $('#formMethod').val();
        var formData = new FormData(form[0]);

        // If it's a PUT request, we need to use POST with _method field
        if (method === 'PUT') {
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Close modal
                $('#categoryModal').modal('hide');

                // Show success message
                toastr.success('Category ' + (method === 'PUT' ? 'updated' : 'created') + ' successfully!');

                // Reload page after short delay
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                if (errors) {
                    $.each(errors, function(key, value) {
                        toastr.error(value[0]);
                    });
                } else {
                    toastr.error('An error occurred. Please try again.');
                }
            }
        });
    });

    // Handle delete form submission with AJAX
    $('#deleteForm').on('submit', function(e) {
        e.preventDefault();

        var form = $(this);
        var url = form.attr('action');

        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                // Close modal
                $('#deleteModal').modal('hide');

                // Show success message
                toastr.success('Category deleted successfully!');

                // Reload page after short delay
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            },
            error: function(xhr) {
                $('#deleteModal').modal('hide');

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    toastr.error(xhr.responseJSON.message);
                } else {
                    toastr.error('An error occurred while deleting the category.');
                }
            }
        });
    });
});
</script>
@endsection