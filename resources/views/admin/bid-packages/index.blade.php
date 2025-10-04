@extends('layouts.admin')

@section('title', 'Bid Packages')
@section('page-title', 'Bid Packages')
@section('page-subtitle', 'Manage bid packages for users to purchase')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
@endsection

@section('content')

<style>
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
.badge-inactive {
    background-color: #f8d7da;
    color: #721c24;
}

/* DataTable Styling */
#packagesTable {
    border-collapse: separate !important;
    border-spacing: 0;
    border: 1px solid #dee2e6 !important;
}

#packagesTable thead th {
    background-color: #f8f9fa !important;
    border: 1px solid #dee2e6 !important;
    padding: 12px !important;
    font-weight: 600;
    color: #495057;
    vertical-align: middle;
}

#packagesTable tbody td {
    border: 1px solid #dee2e6 !important;
    padding: 12px !important;
    vertical-align: middle;
    background-color: #fff;
}

#packagesTable tbody tr {
    background-color: #fff !important;
}

#packagesTable tbody tr:hover {
    background-color: #f8f9fa !important;
}

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    margin-bottom: 15px;
}

.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate {
    margin-top: 15px;
}

/* Modal Styling */
.modal {
    display: none;
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
}
.modal.show {
    display: block;
}
.modal-dialog {
    position: relative;
    width: auto;
    max-width: 500px;
    margin: 1.75rem auto;
}
.modal-content {
    position: relative;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}
.modal-header {
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.modal-title {
    margin: 0;
    font-size: 1.25rem;
}
.modal-header .close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
}
.modal-body {
    padding: 1rem;
}
.modal-footer {
    padding: 1rem;
    border-top: 1px solid #dee2e6;
    text-align: right;
}
</style>


<div class="admin-data-card">
    <div class="admin-data-card-header">
        <div class="admin-data-card-title">All Bid Packages</div>
        <div class="admin-data-card-actions">
            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#createPackageModal">
                <i class="fas fa-plus"></i> Add New Package
            </button>
        </div>
    </div>
    <div class="admin-data-card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive" >
            <table id="packagesTable" class="datatable" style="width:100%;">
                <thead>
                    <tr>
                        <th>Sl.No</th>
                        <th>Name</th>
                        <th>Bid Amount</th>
                        <th>Price (AED)</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @if(!$packages->isEmpty())

                @foreach($packages as $index => $package)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $package->name }}</td>
                        <td>{{ number_format($package->bidAmount) }} bids</td>
                        <td>AED {{ number_format($package->price, 2) }}</td>
                        <td>{{ $package->description ?? '-' }}</td>
                        <td>
                            <span class="badge-status {{ $package->isActive ? 'badge-active' : 'badge-inactive' }}">
                                {{ $package->isActive ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary edit-package-btn"
                                data-id="{{ $package->id }}"
                                data-name="{{ $package->name }}"
                                data-bidamount="{{ $package->bidAmount }}"
                                data-price="{{ $package->price }}"
                                data-description="{{ $package->description }}"
                                data-isactive="{{ $package->isActive ? '1' : '0' }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button type="button" class="btn btn-sm btn-danger delete-package-btn" data-id="{{ $package->id }}">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
                @else
                <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: #999;">
                            No bid packages found. Create your first package to get started.
                        </td>
                    </tr>

                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Package Modal -->
<div class="modal fade" id="createPackageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Bid Package</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="createPackageForm" method="POST" action="{{ route('admin.bid-packages.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="create_name">Package Name <span style="color: red;">*</span></label>
                        <input type="text" name="name" id="create_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="create_bidAmount">Bid Amount <span style="color: red;">*</span></label>
                        <input type="number" name="bidAmount" id="create_bidAmount" class="form-control" required min="1">
                    </div>
                    <div class="form-group">
                        <label for="create_price">Price (AED) <span style="color: red;">*</span></label>
                        <input type="number" name="price" id="create_price" class="form-control" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="create_description">Description</label>
                        <textarea name="description" id="create_description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="isActive" id="create_isActive" class="form-check-input" value="1" checked>
                            <label for="create_isActive" class="form-check-label">Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Package</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Package Modal -->
<div class="modal fade" id="editPackageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Bid Package</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="editPackageForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Package Name <span style="color: red;">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_bidAmount">Bid Amount <span style="color: red;">*</span></label>
                        <input type="number" name="bidAmount" id="edit_bidAmount" class="form-control" required min="1">
                    </div>
                    <div class="form-group">
                        <label for="edit_price">Price (AED) <span style="color: red;">*</span></label>
                        <input type="number" name="price" id="edit_price" class="form-control" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="isActive" id="edit_isActive" class="form-check-input" value="1">
                            <label for="edit_isActive" class="form-check-label">Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Package</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    // Check if table has data rows (not just the empty message)
    var hasData = $('#packagesTable tbody tr').length > 0 && !$('#packagesTable tbody tr td[colspan]').length;

    // Only initialize DataTable if there's data
    if (hasData) {
        var table = $('#packagesTable').DataTable({
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "order": [[2, "asc"]], // Sort by Bid Amount ascending (column index 2)
            "columnDefs": [
                { "orderable": false, "targets": [0, 6] }, // Disable sorting on Sl.No and Actions columns
                { "searchable": false, "targets": [0] } // Disable search on Sl.No column
            ],
            "language": {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ packages",
                "infoEmpty": "No packages available",
                "infoFiltered": "(filtered from _MAX_ total packages)",
                "zeroRecords": "No matching packages found",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Previous"
                }
            },
            "searching": true,
            "autoWidth": false
        });
    }

    // Create modal toggle
    const createModal = document.getElementById('createPackageModal');
    document.querySelectorAll('[data-target="#createPackageModal"]').forEach(btn => {
        btn.addEventListener('click', () => {
            createModal.classList.add('show');
        });
    });

    // Edit package buttons
    document.querySelectorAll('.edit-package-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const bidAmount = this.dataset.bidamount;
            const price = this.dataset.price;
            const description = this.dataset.description;
            const isActive = this.dataset.isactive === '1';

            document.getElementById('edit_name').value = name;
            document.getElementById('edit_bidAmount').value = bidAmount;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_description').value = description || '';
            document.getElementById('edit_isActive').checked = isActive;

            const form = document.getElementById('editPackageForm');
            form.action = '/admin/bid-packages/' + id;

            document.getElementById('editPackageModal').classList.add('show');
        });
    });

    // Delete package buttons
    document.querySelectorAll('.delete-package-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this bid package?')) {
                const id = this.dataset.id;
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/admin/bid-packages/' + id;
                form.innerHTML = '@csrf @method("DELETE")';
                document.body.appendChild(form);
                form.submit();
            }
        });
    });

    // Close modals
    document.querySelectorAll('[data-dismiss="modal"]').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.modal').classList.remove('show');
        });
    });

    // Close modal on outside click
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            e.target.classList.remove('show');
        }
    });
});
</script>
@endsection
