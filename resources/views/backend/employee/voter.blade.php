@extends('backend.master')
@section('title','ভোটার তালিকা')

@section('styles')
<style>
#voterTable, #voterTable th, #voterTable td { border:1px solid #000; border-collapse:collapse; padding:5px; text-align:center;}
#voterTable th { background:#eee; }
#voterTable img { width:50px; height:50px; border:1px solid #ccc; }
.pdfHeader { text-align:center; margin-bottom:20px;}
.pdfHeader h3,h4 { text-decoration:none;}
@media print {
    body * { visibility: hidden; }
    #printBox, #printBox * { visibility: visible; }
    #printBox { position: absolute; left:0; top:0; width:100%; }
    #voterTable img { width:50px; height:50px; }
    table { page-break-inside: auto; }
    tr { page-break-inside: avoid; page-break-after: auto; }
}

h6,.h6,h5,.h5,h4,.h4,h3,.h3,h2,.h2,h1,.h1 {
    margin-top: 0;
    margin-bottom: 0rem;
    font-weight: 500;
    line-height: 1.375;
    color: var(--bs-heading-color)
}
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-2">
                <select id="limitSelect" class="form-control form-control-sm">
                    <option value="20">20</option>
                    <option value="30" selected>30</option>
                    <option value="100">100</option>
                    <option value="all">All</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search Name/NID">
            </div>
            <div class="col-md-4">
                <button id="searchBtn" class="btn btn-primary btn-sm">Search</button>
                <button id="printBtn" class="btn btn-success btn-sm mx-2">Print</button>
                {{-- <button id="pdfBtn" class="btn btn-info btn-sm">Download PDF</button> --}}
                
            </div>
        </div>
    </div>

    <div class="card-body" id="printBox">
        <div class="pdfHeader">
            <h4>বাংলাদেশ ইলেকশন কমিশন অফিসার্স এসোসিয়েশন</h4>
            <h5>খসড়া ভোটার তালিকা (জ্যেষ্ঠতার ভিত্তিতে নয়)</h5>
            <span>নির্বাচন কমিশন সচিবালয়</span>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="voterTable">
                <thead>
                    <tr>
                        <th>নং</th>
                        <th>সদস্য নং</th>
                        <th>নাম ও এনআইডি</th>
                        <th>জেলা</th>
                        <th>পদবী</th>
                        <th>কর্মস্থল</th>
                        <th>মোবাইল</th>
                        <th>ছবি</th>
                    </tr>
                </thead>
                <tbody id="voterBody"></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
function engToBnNumber(number){
    const en=['0','1','2','3','4','5','6','7','8','9'];
    const bn=['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
    return number.toString().split('').map(n=>bn[en.indexOf(n)]??n).join('');
}

function loadVoters(){
    let search = $('#searchInput').val();
    let limit = $('#limitSelect').val();

    $.ajax({
        url:"{{ route('employee.voter.fetch') }}",
        type:"GET",
        data:{ search: search, limit: limit },
        beforeSend:function(){ $('#voterBody').html('<tr><td colspan="8">Loading...</td></tr>'); },
        success:function(res){
            let html = '';
            let imgPromises = [];

            res.forEach((vot,i)=>{
                html += `<tr>
                    <td>${i+1}</td>
                    <td>${engToBnNumber(i+1)}</td>
                    <td>${vot.name}<br>${engToBnNumber(vot.nid)}</td>
                    <td>${vot.district_name}</td>
                    <td>${vot.designation_name}</td>
                    <td>${vot.working_place}</td>
                    <td>${engToBnNumber(vot.phone)}</td>
                    <td><img src="${vot.photo}" /></td>
                </tr>`;

                let img = new Image();
                img.src = vot.photo;
                imgPromises.push(new Promise((resolve)=>{img.onload=resolve;}));
            });

            $('#voterBody').html(html);
            return Promise.all(imgPromises);
        }
    });
}

loadVoters();

$('#searchBtn').click(()=>loadVoters());

// Print
$('#printBtn').click(function(){
    // Wait a short delay for all images
    setTimeout(()=>{ window.print(); }, 300);
});

document.getElementById('searchBtn').addEventListener('click', function () {
    let limit = document.getElementById('limit').value;
    fetch(`{{ route('employee.voter.fetch') }}?limit=${limit}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('voterList').innerHTML = html;
        });
});

// PDF Download (page-wise)
$('#pdfBtn').click(function(){
    let limit = $('#limitSelect').val();
    let search = $('#searchInput').val();
    let url = `{{ route('employee.voter.pdf') }}?limit=${limit}&search=${search}`;
    window.open(url, '_blank');
});
</script>
@endsection
