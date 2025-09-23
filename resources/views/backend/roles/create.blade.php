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
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Create Role</h5>
        <a href="{{ route('roles.index') }}" class="btn btn-dark waves-effect waves-light text-end btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            @method('POST')
            <!-- Role Name -->
            <div class="row mb-3">
                <label for="roleName" class="col-12 col-md-2 col-form-label">Name</label>
                <div class="col-12 col-md-10">
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control form-control-sm" id="roleName" placeholder="Enter Role Name">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Global Check All -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="form-check mt-1">
                        <input type="checkbox" id="checkAll" class="form-check-input">
                        <label class="form-check-label" for="checkAll">Check All Permissions</label>
                    </div>
                </div>
            </div>

            <!-- Permissions Module Wise -->
            <div class="row gy-3 mt-1">
                @php
                    $groupedPermissions = $permissions->groupBy('module'); // Module-wise grouping
                @endphp

                @foreach ($groupedPermissions as $module => $modulePermissions)
                    <div class="col-12 border rounded p-2 mb-3">
                        <div class="row align-items-start">
                            <!-- Module Name -->
                            <div class="col-12 col-md-2 fw-bold mb-2 mb-md-0">
                                {{ ucfirst($module) }}
                            </div>

                            <!-- Permissions -->
                            <div class="col-12 col-md-10">
                                <div class="row">
                                    @foreach ($modulePermissions as $permission)
                                        <div class="col-6 col-md-3">
                                            <div class="form-check mt-1">
                                                <input type="checkbox"
                                                    name="permissions[]"
                                                    class="form-check-input permission-checkbox"
                                                    id="permission-{{ $permission->id }}"
                                                    value="{{ $permission->name }}"
                                                    {{ (old('permissions') && in_array($permission->name, old('permissions'))) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission-{{ $permission->id }}">
                                                    {{ str_replace('_', ' ', $permission->name) }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-start mt-3 d-flex gap-2 flex-wrap">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save me-1"></i> Save
                </button>
                <a href="{{ route('roles.index') }}" class="btn btn-dark btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
@push('scripts')
<script>
    document.getElementById('checkAll').addEventListener('change', function () {
        const isChecked = this.checked;
        const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
        checkboxes.forEach(function (checkbox) {
            checkbox.checked = isChecked;
        });
    });
</script>
@endpush
