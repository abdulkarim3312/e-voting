@extends('backend.master')

@section('title', 'কর্মকর্তা তালিকা')
@section('content')
    <div class="page-header">
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">হোম</a></li>
                <li class="breadcrumb-item active" aria-current="page">কর্মকর্তা তালিকা</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">কর্মকর্তা তালিকা সমূহ</h4>
            <a href="{{ route('employee.create') }}" class="btn btn-primary btn-sm waves-effect waves-light">
                <i class="fa fa-plus-circle mr-1"></i> নতুন যোগ করুন 
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="data-table" class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>নাম</th>
                            <th width="15%">পদবী</th>
                            <th>এনআইডি</th>
                            <th>মোবাইল</th>
                            <th width="15%">ইমেইল</th>
                            <th width="13%">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
$(function () {
    $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        scrollX: false, 
        ajax: "{{ route('employee.manage') }}",
        columns: [
            { data: 'name', name: 'name', width:"15%" },
            { data: 'designation_name', name: 'designation_name', width:"14%" },
            { data: 'nid', name: 'nid', width:"12%" },
            { data: 'phone', name: 'phone' },
            { data: 'email', name: 'email' },
            { data: 'action', name: 'action', orderable: false, searchable: false, width:"15%" }
        ],
    });
});
</script>
@endsection
