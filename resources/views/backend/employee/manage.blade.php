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
                <table id="employee-table" class="table table-bordered table-striped table-hover text-nowrap mb-0 w-100">
                    <thead class="thead-light">
                        <tr>
                            <th>ক্রমিক</th>
                            <th>নাম</th>
                            <th>পদবী</th>
                            <th>এনআইডি</th>
                            <th>মোবাইল</th>
                            <th>ইমেইল</th>
                            <th>তৈরির সময়</th>
                            <th>অ্যাকশন</th>
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
    $('#employee-table').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true, 
        ajax: "{{ route('employee.manage') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width:"8%" },
            { data: 'name', name: 'name', width:"15%" },
            { data: 'designation_name', name: 'designation_name', width:"20%" },
            { data: 'nid', name: 'nid', width:"12%" },
            { data: 'phone', name: 'phone', width:"12%" },
            { data: 'email', name: 'email', width:"18%" },
            { data: 'created_at', name: 'created_at', width:"15%" },
            { data: 'action', name: 'action', orderable: false, searchable: false, width:"15%" }
        ],
    });
});
</script>
@endsection
