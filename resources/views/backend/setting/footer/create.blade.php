@extends('backend.master')

@section('title', 'product')
@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
<style>
    .big-checkbox {
        transform: scale(1.5);
        -webkit-transform: scale(1.5);
        margin: 5px;
        cursor: pointer;
    }
    .image-thumb {
        position: relative;
        display: inline-block;
        margin: 5px;
    }

    .image-thumb img {
        height: 80px;
        width: 80px;
        object-fit: cover;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .remove-btn {
        position: absolute;
        top: -6px;
        right: -6px;
        background: red;
        color: white;
        border: none;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 12px;
        cursor: pointer;
    }
    .feature-thumb {
        position: relative;
        display: inline-block;
    }

    .feature-thumb img {
        height: 100px;
        width: 100px;
        object-fit: cover;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .feature-remove-btn {
        position: absolute;
        top: -6px;
        right: -6px;
        background: red;
        color: white;
        border: none;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 12px;
        cursor: pointer;
    }
</style>
@endpush
@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-0">Footer Update</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('footer_update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 col-12 mb-2">
                            <div class="mb-3">
                                <label for="nameInput" class="form-label">Description</label>
                                <textarea name="description" class="form-control">{{ $footer->description ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-12 mb-2">
                            <div class="mb-3">
                                <label for="nameInput" class="form-label">Facebook link</label>
                                <input type="text" class="form-control form-control-sm" value="{{ $footer->facebook_link ?? '' }}" name="facebook_link">
                            </div>
                        </div>
                        <div class="col-md-6 col-12 mb-2">
                            <div class="mb-3">
                                <label for="nameInput" class="form-label">LinkedIn</label>
                                <input type="text" class="form-control form-control-sm" value="{{ $footer->linkedIn_link ?? '' }}" name="linkedIn_link">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-12 mb-2">
                            <div class="mb-3">
                                <label for="nameInput" class="form-label">Instagram link</label>
                                <input type="text" class="form-control form-control-sm" value="{{ $footer->instagram_link ?? '' }}" name="instagram_link">
                            </div>
                        </div>
                        <div class="col-md-6 col-12 mb-2">
                            <div class="mb-3">
                                <label for="nameInput" class="form-label">Twitter Link</label>
                                <input type="text" class="form-control form-control-sm" value="{{ $footer->twitter_link ?? '' }}" name="twitter_link">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-12 mb-2">
                            <div class="mb-3">
                                <label for="nameInput" class="form-label">Youtube Link</label>
                                <input type="text" class="form-control form-control-sm" value="{{ $footer->youtube_link ?? '' }}" name="youtube_link">
                            </div>
                        </div>
                        <div class="col-md-6 col-12 mb-2">
                            <div class="mb-3">
                                <label for="nameInput" class="form-label">Address One</label>
                                <input type="text" class="form-control form-control-sm" value="{{ $footer->address_one ?? '' }}" name="address_one">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-12 mb-2">
                            <div class="mb-3">
                                <label for="nameInput" class="form-label">Address Two</label>
                                <input type="text" class="form-control form-control-sm" value="{{ $footer->address_two ?? '' }}" name="address_two">
                            </div>
                        </div>
                        <div class="col-md-6 col-12 mb-2">
                            <div class="mb-3">
                                <label for="nameInput" class="form-label">Address Three</label>
                                <input type="text" class="form-control form-control-sm" value="{{ $footer->address_three ?? '' }}" name="address_three">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-12 mb-2">
                            <div class="mb-3">
                                <label for="nameInput" class="form-label">Site Logo</label>
                                <input type="file" class="form-control form-control-sm" name="logo_image" id="site_logo">

                                <div id="site-logo-preview" class="mt-2">
                                    @if(isset($footer) && !empty($footer->logo_image))
                                        <div class="position-relative d-inline-block" id="existing-feature-preview">
                                            <img src="{{ asset($footer->logo_image) }}"
                                                alt="Feature Image"
                                                class="img-thumbnail img_size"
                                                width="100">
                                            <button type="button" class="feature-remove-btn" onclick="removeFeatureImage()">&times;</button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12 mb-2">
                            <div class="mb-3">
                                <label for="nameInput" class="form-label">Other Text (Optional)</label>
                                <input type="text" class="form-control form-control-sm" value="{{ $footer->other_text ?? '' }}" name="other_text">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-start mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save me-1"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
@push('scripts')
<script>
    $(document).ready(function(){
        $('#site_logo').on('change', function (e) {
            const file = e.target.files[0];
            selectedFeatureImage = file;

            if (!file || !file.type.startsWith('image/')) return;

            const reader = new FileReader();
            reader.onload = function (event) {
                const html = `
                    <div class="feature-thumb">
                        <img src="${event.target.result}" alt="Feature Preview">
                        <button type="button" class="feature-remove-btn" onclick="removeFeatureImage()">&times;</button>
                    </div>
                `;
                $('#site-logo-preview').html(html);
            };
            reader.readAsDataURL(file);
        });
    });
    function removeFeatureImage() {
        selectedFeatureImage = null;

        const dt = new DataTransfer();
        document.getElementById('site_logo').files = dt.files;

        $('#site-logo-preview').empty();
    }
</script>
@endpush
