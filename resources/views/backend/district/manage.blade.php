@extends('backend.master')

@section('title', 'জেলা তালিকা')

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
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">জেলা তালিকা</h4>
            <a href="{{ route('district.create') }}" class="btn btn-primary btn-sm waves-effect waves-light"><i class="fa fa-plus-circle mr-1"></i> নতুন যোগ করুন </a>
        </div>
        <div class="card-body">
            <table id="district-table" class="table table-bordered text-nowrap mb-0">
                <thead>
                    <tr>
                        <th>ক্রমিক</th>
                        <th>জেলার নাম</th>
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

@push('scripts')
<script>
$(function () {
    $('#district-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('district.manage') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
