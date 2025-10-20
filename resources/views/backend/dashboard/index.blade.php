@extends('backend.master')
@section('title', 'ই-ভোটিং ড্যাশবোর্ড')

@section('styles')
<style>
.card {
  border: none;
  border-radius: 12px;
  box-shadow: 0 3px 10px rgba(0,0,0,0.05);
}
.card-title {
  font-weight: 600;
}
.stat-icon {
  font-size: 28px;
  line-height: 1;
}
.bg-gradient-primary {
  background: linear-gradient(45deg, #007bff, #00bcd4);
  color: #fff;
}
.bg-gradient-success {
  background: linear-gradient(45deg, #28a745, #20c997);
  color: #fff;
}
.bg-gradient-warning {
  background: linear-gradient(45deg, #ffc107, #ff9800);
  color: #fff;
}
.bg-gradient-info {
  background: linear-gradient(45deg, #17a2b8, #00bcd4);
  color: #fff;
}
.progress {
  height: 6px;
  border-radius: 4px;
}
.table thead {
  background: #f8f9fa;
}
</style>
@endsection

@section('content')
<div class="row gy-4">

  <!-- Welcome Card -->
  <div class="col-md-12 col-lg-4">
    <div class="card bg-gradient-primary text-white position-relative overflow-hidden">
      <div class="card-body">
        <h5 class="mb-1">স্বাগতম, অফিসার!</h5>
        <p class="mb-2">বাংলাদেশ ইলেকশন কমিশন অফিসার্স এসোসিয়েশন</p>
        <h4 class="fw-bold">ই-ভোটিং ড্যাশবোর্ড</h4>
        <a href="#" class="btn btn-light btn-sm mt-3">ভোট দেখুন</a>
      </div>
      <img src="https://cdn-icons-png.flaticon.com/512/1040/1040231.png" 
           class="position-absolute end-0 bottom-0 me-4 mb-4" width="90" alt="e-voting" />
    </div>
  </div>

  <!-- Summary Stats -->
  <div class="col-lg-8">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">ভোটিং সংক্ষিপ্ত তথ্য</h5>
        <button class="btn btn-sm btn-outline-primary">Refresh</button>
      </div>
      <div class="card-body">
        <div class="row text-center">
          <div class="col-md-3 col-6">
            <div class="p-3">
              <div class="stat-icon text-primary mb-2"><i class="ri-bar-chart-box-line"></i></div>
              <h5 class="mb-0">৩৪৫</h5>
              <small>মোট ভোটার</small>
            </div>
          </div>
          <div class="col-md-3 col-6">
            <div class="p-3">
              <div class="stat-icon text-success mb-2"><i class="ri-thumb-up-line"></i></div>
              <h5 class="mb-0">২৯৮</h5>
              <small>ভোট প্রদান করেছেন</small>
            </div>
          </div>
          <div class="col-md-3 col-6">
            <div class="p-3">
              <div class="stat-icon text-warning mb-2"><i class="ri-user-voice-line"></i></div>
              <h5 class="mb-0">৮৭%</h5>
              <small>ভোট প্রদানের হার</small>
            </div>
          </div>
          <div class="col-md-3 col-6">
            <div class="p-3">
              <div class="stat-icon text-info mb-2"><i class="ri-trophy-line"></i></div>
              <h5 class="mb-0">প্রার্থী-৫</h5>
              <small>প্রতিদ্বন্দ্বী</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Result Chart -->
  <div class="col-xl-4 col-md-6">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">বর্তমান ভোট ফলাফল</h5>
        {{-- <div class="dropdown">
          <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="ri-more-2-line"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow">
            <li>
              <a class="dropdown-item text-dark" href="#">
                রিফ্রেশ
              </a>
            </li>
          </ul>
        </div> --}}
      </div>
      <div class="card-body">
        <canvas id="voteResultChart" height="220"></canvas>
        <p class="text-muted small mt-3">* রিয়েল-টাইম আপডেট</p>
      </div>
    </div>
  </div>

  <!-- Total Participation -->
  <div class="col-xl-4 col-md-6">
    <div class="card h-100">
      <div class="card-header">
        <h5 class="mb-0">ভোটার অংশগ্রহণ</h5>
      </div>
      <div class="card-body text-center">
        <h3 class="text-success fw-bold">৮৭%</h3>
        <p class="mb-2">এই বছর অংশগ্রহণ বৃদ্ধি পেয়েছে</p>
        <div class="progress mb-3">
          <div class="progress-bar bg-success" style="width: 87%"></div>
        </div>
        <button class="btn btn-sm btn-success">বিস্তারিত দেখুন</button>
      </div>
    </div>
  </div>

  <!-- Recent Votes Table -->
  <div class="col-xl-4 col-md-12">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">সর্বশেষ ভোট</h5>
        <button class="btn btn-sm btn-outline-secondary">View All</button>
      </div>
      <div class="card-body">
        <table class="table table-sm align-middle">
          <thead>
            <tr>
              <th>ভোটার</th>
              <th>প্রার্থী</th>
              <th>সময়</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>মোঃ রফিক</td>
              <td>জনাব আজাদ</td>
              <td>10:45 AM</td>
            </tr>
            <tr>
              <td>সালমা খাতুন</td>
              <td>জনাব রিয়াজ</td>
              <td>10:42 AM</td>
            </tr>
            <tr>
              <td>মোহনা বেগম</td>
              <td>জনাব আজাদ</td>
              <td>10:40 AM</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('voteResultChart').getContext('2d');
new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ['জনাব আজাদ', 'জনাব রিয়াজ', 'জনাব সুমন', 'জনাব রুবেল', 'জনাব রাশেদ'],
    datasets: [{
      data: [120, 90, 65, 50, 40],
      backgroundColor: ['#007bff', '#28a745', '#ffc107', '#17a2b8', '#6c757d']
    }]
  },
  options: {
    plugins: {
      legend: { position: 'bottom' }
    }
  }
});
</script>
@endsection
