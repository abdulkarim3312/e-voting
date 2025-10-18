@extends('backend.master')

@section('title', 'নির্বাচনী ব্যবস্থাপনা')

@section('content')

        <div class="page-header">
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">হোম</a></li>
                    <li class="breadcrumb-item active" aria-current="page">নির্বাচনী ব্যবস্থাপনা</li>
                </ol>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">নির্বাচনী ব্যবস্থাপনা</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('election.update') }}" method="post">
                    @csrf
                    <div class="row">

                        <div class="col-md-12 col-12 mb-5">
                            <div class="form-floating form-floating-outline">
                                <input type="date" class="form-control" id="floatingInput" placeholder="" aria-describedby="floatingInputHelp" name="election_date" required value="{{ $data->election_date ?? '' }}">
                                <label for="floatingInput">নির্বাচনের তারিখ</label>
                            </div>
                        </div>

                        <div class="col-md-6 col-6 mb-5">
                            <div class="form-floating form-floating-outline">
                                <input type="time" class="form-control" id="floatingInput" placeholder="" aria-describedby="floatingInputHelp" name="voat_start_time" required value="{{ $data->election_start_time ?? '' }}">
                                <label for="floatingInput">ভোট শুরুর সময়</label>
                            </div>
                        </div>

                        <div class="col-md-6 col-6 mb-5">
                            <div class="form-floating form-floating-outline">
                                <input type="time" class="form-control" id="floatingInput" placeholder="John Doe" aria-describedby="floatingInputHelp" name="voat_end_time" required value="{{ $data->election_end_time ?? '' }}">
                                <label for="floatingInput">ভোট শেষে সময়</label>
                            </div>
                        </div>

                        

                        <div class="col-12">
                            <button type="submit" class="btn btn-info"> সংরক্ষণ করুন </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>











@endsection


@section('scripts')
<script>
    
</script>
@endsection
