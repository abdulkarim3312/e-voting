@extends('backend.master')

@section('title', 'নির্বাচনী জোন তালিকা')

@section('content')

    <div class="page-header">
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">হোম</a></li>
                <li class="breadcrumb-item active" aria-current="page">নির্বাচনী জোন</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">নির্বাচনী জোন তালিকা</h4>
            <a href="{{ route('zone.create') }}" class="btn btn-primary btn-sm waves-effect waves-light"><i class="fa fa-plus-circle mr-1"></i> জোন যোগ করুন </a>
        </div>
        <div class="card-body">
            <table id="zoneTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Zone Name</th>
                        <th>Districts</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

@endsection

@section('scripts')
<script>
$(function() {
    console.log('JS loaded'); // check হচ্ছে কি না

    $('#zoneTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('zone.manage') }}",
            dataSrc: function(json) {
                console.log(json); // Response দেখাবে
                return json.data;  // DataTables কে data array দিতে হবে
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, searchable:false },
            { data: 'name', name: 'name' },
            { data: 'districts', name: 'districts' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable:false, searchable:false },
        ]
    });
});

</script>
@endsection
