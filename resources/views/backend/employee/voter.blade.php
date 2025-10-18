@extends('backend.master')

@section('title', 'ভোটার তালিকা')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<style>
    /* Normal table view */
    table.dataTable {
        width: 100% !important;
    }

    #DataTable img {
        width: 70px;
        height: 70px;
        border: 1px solid #ccc;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .pdfHeader {
        margin-bottom: 20px;
    }

    .pdfHeader h3, .pdfHeader h4 {
        text-align: center;
        text-decoration: underline;
    }

    .pdfHeader span {
        text-align: left;
        text-decoration: underline;
        font-weight: bold;
    }

    /* PDF mode temporary overrides */
    #printBox.pdf-mode #DataTable,
    #printBox.pdf-mode #DataTable td,
    #printBox.pdf-mode #DataTable th {
        color: #000 !important;
        border: 1px solid #000 !important;
        text-align: center !important;
        page-break-inside: avoid; /* prevent row splitting */
    }

    #printBox.pdf-mode #DataTable th {
        background-color: #f0f0f0 !important;
        font-weight: bold;
    }

    #printBox.pdf-mode .pdfHeader h3,
    #printBox.pdf-mode .pdfHeader h4,
    #printBox.pdf-mode .pdfHeader span {
        color: #000 !important;
    }

    /* Hide DataTable controls (pagination, search, length menu) in PDF mode */
    #printBox.pdf-mode .dataTables_length,
    #printBox.pdf-mode .dataTables_filter,
    #printBox.pdf-mode .dataTables_info,
    #printBox.pdf-mode .dataTables_paginate {
        display: none !important;
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">হোম</a></li>
            <li class="breadcrumb-item active" aria-current="page">ভোটার তালিকা</li>
        </ol>

        <button class="btn btn-info" onclick="generatePDF()">Print</button>
    </div>
</div>

<hr>

<div class="card">
    @php
        function engToBnNumber($num) {
            $en = ['0','1','2','3','4','5','6','7','8','9'];
            $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
            return str_replace($en, $bn, $num);
        }
    @endphp

    <div class="card-body">
        <div id="printBox">
            <div class="pdfHeader">
                <h3>বাংলাদেশ ইলেকশন কমিশন অফিসার এসোসিয়েশন</h3>
                <h4>খসড়া ভোটার তালিকা</h4>
                <span>নির্বাচন কমিশন সচিবালয়</span>
            </div>
            <div class="table-responsive">
                <table id="DataTable" class="table table-bordered text-nowrap mb-0 dataTable no-footer table-striped">
                    <thead>
                        <tr>
                            <th>নং</th>
                            <th>সদস্য নং</th>
                            <th>সদস্যের নাম ও এনআইডি নং</th>
                            <th>জেলা</th>
                            <th>পদবী</th>
                            <th>কর্মস্থল</th>
                            <th>মোবাইল নম্বর</th>
                            <th>ছবি</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($voters as $i=>$vot)
                            <tr>
                                <th scope="row">{{ $i+1 }}</th>
                                <td>{{ engToBnNumber($i+1) }}</td>
                                <td>
                                    {{ $vot->name }}<br>
                                    {{ engToBnNumber($vot->nid) }}
                                </td>
                                <td>
                                    @php
                                        $dist = DB::table('districts')->where('id', $vot->district)->first('name');
                                        echo $dist->name;
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        $desg = DB::table('designations')->where('id', $vot->designation)->first('name');
                                        echo $desg->name;
                                    @endphp
                                </td>
                                <td>{{ $vot->working_place }}</td>
                                <td>{{ engToBnNumber($vot->phone) }}</td>
                                <td><img src="{{ asset($vot->photo) }}" alt=""></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas-pro@1.5.12/dist/html2canvas-pro.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#DataTable').DataTable({
        responsive: true,
        pageLength: 10
    });
});

async function generatePDF() {
    $('.processing').show();

    const printBox = document.getElementById("printBox");
    printBox.classList.add('pdf-mode');

    // Wait for table redraw
    await new Promise(r => setTimeout(r, 500));

    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF("l", "mm", "a4"); // Portrait
    const pageWidth = pdf.internal.pageSize.getWidth();
    const pageHeight = pdf.internal.pageSize.getHeight();

    // Capture canvas
    const canvas = await html2canvas(printBox, { scale: 3, useCORS: true });
    const imgData = canvas.toDataURL("image/png");

    // Scale to PDF width
    const pdfWidth = pageWidth - 20;
    const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

    let heightLeft = pdfHeight;
    let position = 10;

    // প্রথম page
    pdf.addImage(imgData, 'webp', 10, position, pdfWidth, 180);

    pdf.save("voters_list.pdf");

    printBox.classList.remove('pdf-mode');
    $('.processing').hide();
}
</script>
@endsection
