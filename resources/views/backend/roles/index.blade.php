@extends('backend.master')

@section('title', 'Role')

@push('styles')
<style>
    .big-checkbox {
        transform: scale(1.5);
        -webkit-transform: scale(1.5);
        margin: 5px;
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Role List</h5>
        <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm">
            <i class="fa fa-plus-circle"></i> Add New
        </a>
    </div>
    <div class="card-body">
        <table id="responsive-datatable" class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Permission</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    let table = $('#responsive-datatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        responsive: true,
        ajax: '{{ route("roles.index") }}',
        columns: [
            { data: 'name', name: 'name' },
            { data: 'permissions', name: 'permissions', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false, width: "15%" }
        ]
    });

    $(document).on('submit', '.delete-form', function (e) {
        e.preventDefault();
        let form = this;

        Swal.fire({
            title: 'Are you sure?',
            html: 'You are about to delete this item.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: $(form).attr('action'),
                    type: 'POST',
                    data: $(form).serialize(),
                    success: function (response) {
                        table.ajax.reload(null, false);
                        toastr.success(response.message ?? 'Role deleted successfully');
                    },
                    error: function (xhr) {
                        let message = 'Failed to delete the Role.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        else if (xhr.responseText) {
                            message = xhr.responseText;
                        }

                        Swal.fire('Error', message, 'error');
                    }
                });
            }
        });
    });
});
</script>
@endpush
