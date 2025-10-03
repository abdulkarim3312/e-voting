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
            <li class="breadcrumb-item active" aria-current="page">কর্মস্থল</li>
        </ol>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">কর্মস্থল যোগ করুন</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('office.update') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-12 col-lg-6 mb-5">
                    <div class="form-floating form-floating-outline">
                        <input type="text" class="form-control" id="floatingInput" placeholder="" name="officeName" required value="{{ $office->office_name }}">
                        <label for="floatingInput">অফিসের নাম</label>
                    </div>
                </div>

                <input type="hidden" name="office_id" value="{{ $office->id }}">

                <div class="col-md-12 col-lg-6 mb-5">
                    <select name="district_id" class="select2 form-control">
                        <option value="">--জেলা নির্বাচন করুন--</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district->id }}"
                                
                                @php
                                    if($district->id == $office->district_id){
                                        echo "selected";
                                    }
                                @endphp
                                
                                >{{ $district->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <center>
                <button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">
                    <i class="fas fa-save me-1"></i> আপডেট করুন
                </button>
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
