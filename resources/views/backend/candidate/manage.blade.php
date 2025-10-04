@extends('backend.master')

@section('title', 'জেলা তালিকা')
@section('content')
    <div class="page-header">
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">হোম</a></li>
                <li class="breadcrumb-item active" aria-current="page">নির্বাচনী প্রার্থী</li>
            </ol>
        </div>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">নির্বাচনী প্রার্থী সমূহ</h4>
            <a href="{{ route('candidate.create') }}" class="btn btn-primary btn-sm waves-effect waves-light"><i class="fa fa-plus-circle mr-1"></i> নতুন যোগ করুন </a>
        </div>
        <div class="card-body">
            <table id="district-table" class="table table-bordered">
                <thead>
                    <tr>
                        <th>ক্রমিক</th>
                        <th>ক্যাটাগরি</th>
                        <th>প্রার্থী</th>
                        <th>নির্বাচনের সন</th>
                        <th>তৈরির সময়</th>
                        <th>অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script>
$(function () {
    $('#district-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('candidate.manage') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'category', name: 'category', width:'25%' },
            { data: 'employee', name: 'employee', width:'30%' },
            { data: 'election_year', name: 'election_year' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endsection
