@extends('backend.master')

@section('title', 'কর্মস্থল যোগ করুন')

@section('styles')
<style>
/* =========================
   DARK THEME (data-bs-theme="dark")
========================= */
:root[data-bs-theme="dark"] .select2-container--classic .select2-selection--single {
    background-color: #2a2540 !important;
    border: 1px solid #6c5ce7 !important;
    color: #fff !important;
    height: 48px !important;
    display: flex;
    align-items: center;
    border-radius: 6px !important;
    font-size: 14px;
    padding: 0 12px !important;
}

:root[data-bs-theme="dark"] .select2-container--classic .select2-selection--single .select2-selection__rendered {
    background-color: #312d4b !important;
    color: #fff !important;
    line-height: 46px !important;
    width: 99%;
    left: 2px;
    border-radius: 5px;
    position: absolute;
}

:root[data-bs-theme="dark"] .select2-container--classic .select2-selection--single .select2-selection__placeholder {
    color: #aaa !important;
}

:root[data-bs-theme="dark"] .select2-container--classic .select2-dropdown {
    background-color: #2a2540 !important;
    border: 1px solid #6c5ce7 !important;
    color: #fff !important;
}

:root[data-bs-theme="dark"] .select2-container--classic .select2-results__option {
    padding: 8px 12px;
    color: #fff !important;
}

:root[data-bs-theme="dark"] .select2-container--classic .select2-results__option--highlighted {
    background-color: #6c5ce7 !important;
    color: #fff !important;
}


/* =========================
   LIGHT THEME (data-bs-theme="light")
========================= */
:root[data-bs-theme="light"] .select2-container--classic .select2-selection--single {
    background-color: #fff !important;
    border: 1px solid #6c5ce7 !important;
    color: #000 !important;
    height: 48px !important;
    display: flex;
    align-items: center;
    border-radius: 6px !important;
    font-size: 14px;
    padding: 0 12px !important;
}

:root[data-bs-theme="light"] .select2-container--classic .select2-selection--single .select2-selection__rendered {
    background-color: #fff !important;
    color: #000 !important;
    line-height: 46px !important;
    width: 99%;
    left: 2px;
    border-radius: 5px;
    position: absolute;
}

:root[data-bs-theme="light"] .select2-container--classic .select2-selection--single .select2-selection__placeholder {
    color: #555 !important;
}

:root[data-bs-theme="light"] .select2-container--classic .select2-dropdown {
    background-color: #fff !important;
    border: 1px solid #6c5ce7 !important;
    color: #000 !important;
}

:root[data-bs-theme="light"] .select2-container--classic .select2-results__option {
    padding: 8px 12px;
    color: #000 !important;
}

:root[data-bs-theme="light"] .select2-container--classic .select2-results__option--highlighted {
    background-color: #6c5ce7 !important;
    color: #fff !important;
}


.select2-selection__arrow {
    display: none;
}
</style>
@endsection


@section('content')
<div class="page-header">
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">হোম</a></li>
            <li class="breadcrumb-item active" aria-current="page">কর্মকর্তা</li>
        </ol>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">কর্মকর্তা পরিবর্তন করুন</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('employee.update', $employee->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="row">
                <div class="col-md-4 mb-5">
                    <div class="">
                        <label for="nameInput" class="">নাম</label>
                        <input type="text" class="form-control" required name="name" value="{{ old('name', $employee->name ?? '') }}" placeholder="নাম">
                    </div>
                </div>
                <div class="col-md-4 mb-5">
                    <label for="">মোবাইল</label>
                    <input type="text" class="form-control" required name="phone" value="{{ old('phone', $employee->phone ?? '') }}" placeholder="মোবাইল">
                </div>
                <div class="col-md-4 mb-5">
                    <label for="">ইমেইল</label>
                    <input type="text" class="form-control" required name="email" value="{{ old('email', $employee->email ?? '') }}" placeholder="ইমেইল">
                </div>
                <div class="col-md-4 mb-5">
                    <label for="">এনআইডি</label>
                    <input type="text" class="form-control" required name="nid" value="{{ old('nid', $employee->nid ?? '') }}" placeholder="এনআইডি">
                </div>
                <div class="col-md-4 mb-5">
                    <label for="">পদবী</label>
                     <select name="designation" class="select2 form-control form-select-sm">
                        <option value=""  selected disabled>--পদবী--</option>
                        @foreach ($designations as $designation)
                            <option value="{{ $designation->id }}" {{ $designation->id == $employee->designation ? 'selected': '' }}>{{ $designation->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-5">
                    <label for="">জেলা</label>
                    <select name="district" class="select2 form-control form-select-sm">
                        <option value=""  selected disabled>--জেলা--</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district->id }}" {{ $district->id == $employee->district ? 'selected': '' }}>{{ $district->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-5">
                    <label for="">ছবি </label>
                    <input type="file" class="form-control" name="photo" placeholder="ছবি">
                </div>
                <div class="col-md-6 mb-5">
                    <label for="">কর্মস্থল </label>
                    <input type="text" class="form-control" value="{{ old('working_place', $employee->working_place ?? '') }}" required name="working_place" placeholder="কর্মস্থল">
                </div>
            </div>
            <center>
                <button type="submit" class="btn btn-primary btn-sm waves-effect waves-light"><i class="fa-solid fa-retweet"></i> পরিবর্তন করুন </button>
            </center>
        </form>
    </div>
</div>
@endsection


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: "classic"
        });
    });
</script>
@endsection
