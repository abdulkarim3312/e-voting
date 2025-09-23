<div class="modal fade" id="formModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="modalForm">
            @csrf
            <input type="hidden" name="id" id="rowId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="name">Email</label>
                            <input type="email" id="email" name="email" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="name">Password</label>
                            <input type="password" id="password" name="password" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="role_id">Role</label>
                            <select name="role_id" id="role_id" class="form-select form-select-sm">
                                <option value="">--Select--</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="photo">Photo</label>
                            <input type="file" id="photo" name="photo" accept="image/*" class="form-control form-control-sm mb-2">

                            <div id="hide_image">
                                <div id="imagePreviewContainer" class="position-relative d-inline-block border rounded mt-2" style="width: 100px; height: 100px; display: none; background-color: #f8f9fa;">
                                    <img id="previewImage" src="" class="img-fluid w-100 h-100" style="object-fit: cover;" />
                                    <button type="button" id="removeImage" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle shadow" style="width: 24px; height: 24px; line-height: 10px; padding: 0;">
                                        &times;
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-sm btn-primary" type="submit">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>