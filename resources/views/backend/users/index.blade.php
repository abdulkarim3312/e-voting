@extends('backend.master')

@section('title', 'User')

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
        <h5 class="card-title mb-0">User List</h5>
        <button type="button" id="addNew" class="btn btn-primary btn-sm">
            <i class="fa fa-plus-circle"></i> Add New
        </button>
    </div>
    <div class="card-body">
        <table id="responsive-datatable" class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Photo</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">role</th>
                    <th scope="col">Status</th>
                    <th scope="col">Last Login</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@include('backend.users.partials.form')
@endsection

@push('scripts')
<script>
$(function () {
    let table = $('#responsive-datatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        responsive: true,
        ajax: '{{ route("users.index") }}',
        columns: [
            { data: 'photo', name: 'photo' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'role', name: 'role' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'last_login', name: 'last_login' },
            { data: 'action', name: 'action', orderable: false, searchable: false, width:"15%" }
        ]
    });

    $('#addNew').click(function () {
        $('#modalForm')[0].reset();
        $('#rowId').val('');
        $('.modal-title').text('Add New User');
        $('#formModal').modal('show');

        $('.invalid-feedback').remove();
        $('.is-invalid').removeClass('is-invalid');
        $('#hide_image').hide();
        $('#previewImage').attr('src', '');
    });

    $('#photo').on('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const previewURL = URL.createObjectURL(file);
            $('#previewImage').attr('src', previewURL);
            $('#hide_image').show();
        }
    });

    $(document).on('click', '#removeImage', function () {
        $('#previewImage').attr('src', '');
        $('#image').val('');
        $('#hide_image').hide();
    });

    $(document).on('click', '.editBtn', function () {
        const id = $(this).data('id');
        const url = '{{ route("users.edit", ":id") }}'.replace(':id', id);

        $.get(url, function (data) {
            $('#modalForm')[0].reset();
            $('#rowId').val(data.id);
            $('[name="role_id"]').val(data.role_id).trigger('change');
            $('[name="name"]').val(data.name);
            $('[name="email"]').val(data.email);
            $('[name="status"]').val(data.status ? '1' : '0').trigger('change');
            $('.modal-title').text('Edit User');
            $('#formModal').modal('show');
            $('.invalid-feedback').remove();
            $('.is-invalid').removeClass('is-invalid');

            if (data.photo) {
                $('#previewImage').attr('src', '/' + data.photo);
                $('#hide_image').show();
            } else {
                $('#previewImage').attr('src', '');
                $('#hide_image').hide();
            }
        });
    });

    $('#modalForm').submit(function (e) {
        e.preventDefault();
        const form = $(this)[0];
        const formData = new FormData(form);
        const id = $('#rowId').val();
        const url = id
            ? '{{ route("users.update", ":id") }}'.replace(':id', id)
            : '{{ route("users.store") }}';

        if (id) {
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('#formModal').modal('hide');
                $('#modalForm')[0].reset();
                $('#imagePreviewContainer').hide();
                $('#previewImage').attr('src', '');
                table.ajax.reload(null, false);
                toastr.success(response.message);
            },
            error: function (xhr) {
                let errors = xhr.responseJSON?.errors;
                $('.invalid-feedback').remove();
                $('.is-invalid').removeClass('is-invalid');
                if (errors) {
                    $.each(errors, function (field, messages) {
                        let input = $('[name="' + field + '"]');
                        input.addClass('is-invalid');
                        if (input.next('.invalid-feedback').length === 0) {
                            input.after('<div class="invalid-feedback">' + messages[0] + '</div>');
                        }
                    });
                } else {
                    toastr.error('An unexpected error occurred.');
                }
            }
        });
    });

    $('#formModal').on('hidden.bs.modal', function () {
        $('#modalForm')[0].reset();
        $('#rowId').val('');
        $('.invalid-feedback').remove();
        $('.is-invalid').removeClass('is-invalid');
        $('#hide_image').hide();
        $('#previewImage').attr('src', '');
    });

    $(document).on('submit', '.delete-form', function (e) {
        e.preventDefault();
        const form = this;
        const id = $(form).data('id');
        const url = '{{ route("users.destroy", ":id") }}'.replace(':id', id);

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
                    url: url,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        table.ajax.reload(null, false);
                        toastr.success(response.message);
                    },
                    error: function () {
                        Swal.fire('Error', 'Failed to delete the item.', 'error');
                    }
                });
            }
        });
    });

    $(document).on('change', '.status-toggle', function () {
        const rowId = $(this).data('id');
        const newStatus = $(this).is(':checked') ? 1 : 0;
        const url = '{{ route("user_status_update", ":id") }}'.replace(':id', rowId);

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                status: newStatus,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                table.ajax.reload(null, false);
                toastr.success(response.message || 'Status updated successfully');
            },
            error: function () {
                toastr.error('Failed to update status');
            }
        });
    });
});
</script>
@endpush
