@extends('backend.master')

@section('title', 'জেলা যোগ করুন')

@section('content')

        <div class="page-header">
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">হোম</a></li>
                    <li class="breadcrumb-item active" aria-current="page">নির্বাচনী পদ</li>
                </ol>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">নির্বাচনী পদ যুক্ত করুন</h3>
            </div>
            <div class="card-body">

                <form action="{{ route('category.upload') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 col-12 mb-5">
                            <label for="status">Status</label>
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="floatingInput" placeholder="" aria-describedby="floatingInputHelp" name="name" required>
                                <label for="floatingInput">নাম</label>
                            </div>
                        </div>
                        <div class="col-md-6 col-12 mb-5">
                            <label for="status">Status</label>
                            <select name="status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <center>
                        <button type="submit" class="btn btn-primary btn-sm waves-effect waves-light"><i class="fas fa-save me-1"></i> যুক্ত করুন </button>
                    </center>
                </form>

            </div>
        </div>
@endsection
