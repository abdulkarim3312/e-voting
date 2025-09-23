@extends('backend.master')

@section('title', 'জেলা যোগ করুন')

@section('content')

        <div class="page-header">
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">হোম</a></li>
                    <li class="breadcrumb-item active" aria-current="page">জেলা</li>
                </ol>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">জেলা যোগ করুন</h3>
            </div>
            <div class="card-body">

                <form action="{{ route('district.upload') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 col-12 mb-5">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="floatingInput" placeholder="" aria-describedby="floatingInputHelp" name="distName" required>
                                <label for="floatingInput">জেলার নাম</label>
                            </div>
                        </div>
                    </div>
                    <center>
                        <button type="submit" class="btn btn-primary btn-sm waves-effect waves-light"><i class="fas fa-save me-1"></i> যুক্ত করুন </button>
                    </center>
                </form>

            </div>
        </div>









@endsection
