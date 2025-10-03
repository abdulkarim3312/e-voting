@extends('backend.master')

@section('title', 'কর্মস্থল তালিকা')

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
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">কর্মস্থল তালিকা</h4>
            <a href="{{ route('office.create') }}" class="btn btn-primary btn-sm waves-effect waves-light"><i class="fa fa-plus-circle mr-1"></i> নতুন যোগ করুন </a>
        </div>
        <div class="card-body">
            <table id="office-table" class="table table-bordered text-nowrap mb-0">
                <thead>
                    <tr>
                        <th>ক্রমিক</th>
                        <th>অফিসের নাম</th>
                        <th>জেলা</th>
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
    $('#office-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('office.manage') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'office_name', name: 'office_name' },   // অফিসের নাম
            { data: 'district_name', name: 'districts.name' }, // জেলার নাম (join থেকে আসবে)
            { data: 'created_at', name: 'created_at' }, // তৈরি সময়
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]

    });
});
</script>
@endsection
