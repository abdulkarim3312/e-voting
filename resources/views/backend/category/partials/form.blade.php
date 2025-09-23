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
                    <div class="mb-3">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" class="form-control form-control-sm">
                    </div>

                    <div class="mb-3">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="meta_keywords">Meta Keyword</label>
                        <input type="text" id="meta_keywords" name="meta_keywords" class="form-control form-control-sm">
                    </div>

                    <div class="mb-3">
                        <label for="image">Image</label>
                        <input type="file" id="image" name="image" accept="image/*" class="form-control form-control-sm mb-2">

                        <div id="hide_image">
                            <div id="imagePreviewContainer" class="position-relative d-inline-block border rounded mt-2" style="width: 100px; height: 100px; display: none; background-color: #f8f9fa;">
                                <img id="previewImage" src="" class="img-fluid w-100 h-100" style="object-fit: cover;" />
                                <button type="button" id="removeImage" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle shadow" style="width: 24px; height: 24px; line-height: 10px; padding: 0;">
                                    &times;
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
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