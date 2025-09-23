@extends('backend.master')

@section('title', 'Category List')
@push('styles')
<style>
    .big-checkbox {
        transform: scale(1.5);
        margin: 5px;
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Category List</h5>
        <button type="button" id="addNew" class="btn btn-primary btn-sm">
            <i class="fa fa-plus-circle"></i> Add New
        </button>
    </div>
    <div class="card-body">
        <table id="responsive-datatable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Meta keyword</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@include('backend.category.partials.form') 
@endsection

@push('scripts')
<script>
$(function () {
    let table = $('#responsive-datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("categories.index") }}',
        columns: [
            { data: 'image', name: 'image', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'slug', name: 'slug' },
            { data: 'meta_keywords', name: 'meta_keywords' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $('#addNew').click(function () {
        $('#modalForm')[0].reset();
        $('#rowId').val('');
        $('.modal-title').text('Add New Category');
        $('#formModal').modal('show');
        resetFormFeedback();
        $('#hide_image').hide();
    });

    $('#image').on('change', function (e) {
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
        const url = '{{ route("categories.edit", ":id") }}'.replace(':id', id);

        $.get(url, function (data) {
            $('#modalForm')[0].reset();
            $('#rowId').val(data.id);
            $('[name="name"]').val(data.name);
            $('[name="description"]').val(data.description);
            $('[name="meta_keywords"]').val(data.meta_keywords || '');
            $('[name="status"]').val(data.status);
            $('.modal-title').text('Edit Category');
            resetFormFeedback();

            if (data.image) {
                $('#previewImage').attr('src', '/' + data.image);
                $('#hide_image').show();
            } else {
                $('#previewImage').attr('src', '');
                $('#hide_image').hide();
            }

            $('#formModal').modal('show');
        });
    });

    $('#modalForm').submit(function (e) {
        e.preventDefault();
        let form = $(this)[0];
        let formData = new FormData(form);
        let id = $('#rowId').val();
        let url = id
            ? '{{ route("categories.update", ":id") }}'.replace(':id', id)
            : '{{ route("categories.store") }}';

        if (id) {
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                $('#formModal').modal('hide');
                table.ajax.reload();
                toastr.success(res.message || 'Saved successfully');
            },
            error: function (xhr) {
                resetFormFeedback();
                let errors = xhr.responseJSON?.errors;
                if (errors) {
                    $.each(errors, function (field, messages) {
                        let input = $('[name="' + field + '"]');
                        input.addClass('is-invalid');
                        input.after('<div class="invalid-feedback">' + messages[0] + '</div>');
                    });
                } else {
                    toastr.error('Unexpected error occurred.');
                }
            }
        });
    });

    $('#formModal').on('hidden.bs.modal', function () {
        $('#modalForm')[0].reset();
        $('#rowId').val('');
        resetFormFeedback();
        $('#hide_image').hide();
        $('#previewImage').attr('src', '');
    });

    $(document).on('submit', '.delete-form', function (e) {
        e.preventDefault();
        const form = this;
        const id = $(form).data('id');
        const name = $(form).data('name') || 'this category';
        const url = '{{ route("categories.destroy", ":id") }}'.replace(':id', id);

        Swal.fire({
            title: 'Are you sure?',
            html: `You are about to delete <b>${name}</b>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        table.ajax.reload();
                        toastr.success(response.message || 'Deleted successfully');
                    },
                    error: function () {
                        Swal.fire('Error', 'Failed to delete.', 'error');
                    }
                });
            }
        });
    });

    $(document).on('change', '.status-toggle', function () {
        let id = $(this).data('id');
        let newStatus = $(this).is(':checked') ? 1 : 0;
        const url = '{{ route("update_status_category", ":id") }}'.replace(':id', id);

        $.post(url, {
            status: newStatus,
            _token: '{{ csrf_token() }}'
        })
        .done(function (res) {
            table.ajax.reload(null, false);
            toastr.success(res.message || 'Status updated');
        })
        .fail(function () {
            toastr.error('Failed to update status');
        });
    });

    function resetFormFeedback() {
        $('.invalid-feedback').remove();
        $('.is-invalid').removeClass('is-invalid');
    }
});
</script>
@endpush
