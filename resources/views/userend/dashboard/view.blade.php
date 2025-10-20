@extends('userend.main')
@section('title', 'ভোটার ড্যাশবোর্ড')

@section('css')
<style>
    .candidate-card img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #ddd;
    }
    .vote-log-table th, .vote-log-table td {
        text-align: center;
        vertical-align: middle;
    }
    .vote-summary h4 {
        font-weight: 700;
        margin-bottom: 0;
    }
</style>
@endsection

@section('body')

<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        
    </div>
</div>

<div class="row text-center vote-summary">
    <div class="col-md-3 col-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-1">মোট ভোট</h6>
                <h4 class="text-primary">12,458</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-1">মোট প্রার্থী</h6>
                <h4 class="text-success">8</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-1">মোট ভোটার</h6>
                <h4 class="text-info">20,000</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-1">ভোটিং স্ট্যাটাস</h6>
                <h4 class="text-danger">চলমান</h4>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-xxl-8 col-lg-7 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="m-0">ভোটের সারসংক্ষেপ</h5>
                <button class="btn btn-sm btn-light"><i class="ri-refresh-line"></i></button>
            </div>
            <div class="card-body">
                <canvas id="voteChart" height="160"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xxl-4 col-lg-5 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0">শীর্ষ প্রার্থী</h5>
            </div>
            <div class="card-body">
                <div class="d-flex candidate-card align-items-center mb-3">
                    <img src="https://i.pravatar.cc/60?img=1" alt="Candidate">
                    <div class="ms-3">
                        <h6 class="mb-0">জনাব রফিকুল ইসলাম</h6>
                        <small class="text-muted">ভোট: 5,342</small>
                    </div>
                </div>
                <div class="d-flex candidate-card align-items-center mb-3">
                    <img src="https://i.pravatar.cc/60?img=2" alt="Candidate">
                    <div class="ms-3">
                        <h6 class="mb-0">জনাব কামরুল হাসান</h6>
                        <small class="text-muted">ভোট: 4,812</small>
                    </div>
                </div>
                <div class="d-flex candidate-card align-items-center">
                    <img src="https://i.pravatar.cc/60?img=3" alt="Candidate">
                    <div class="ms-3">
                        <h6 class="mb-0">জনাব সোহেল আহমেদ</h6>
                        <small class="text-muted">ভোট: 2,304</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="m-0">সাম্প্রতিক ভোটিং লগ</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover vote-log-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>ভোটার নাম</th>
                            <th>প্রার্থী</th>
                            <th>সময়</th>
                            <th>স্ট্যাটাস</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>আরিফুল ইসলাম</td>
                            <td>রফিকুল ইসলাম</td>
                            <td>10:35 AM</td>
                            <td><span class="badge bg-success">সফল</span></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>মেহেদী হাসান</td>
                            <td>কামরুল হাসান</td>
                            <td>10:32 AM</td>
                            <td><span class="badge bg-success">সফল</span></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>শাহিন মিয়া</td>
                            <td>সোহেল আহমেদ</td>
                            <td>10:28 AM</td>
                            <td><span class="badge bg-success">সফল</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('voteChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['রফিকুল', 'কামরুল', 'সোহেল', 'জাকির', 'তানভীর'],
            datasets: [{
                label: 'ভোট সংখ্যা',
                data: [5342, 4812, 2304, 1740, 1250],
                backgroundColor: ['#3b82f6','#22c55e','#f59e0b','#ef4444','#8b5cf6'],
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
