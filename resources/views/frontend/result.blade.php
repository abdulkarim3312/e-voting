<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>অনলাইন ভোটিং সিস্টেম - ফলাফল</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body { background-color: #f8f9fa; font-family: 'Siyam Rupali', sans-serif; }
    .navbar { background-color: #161e267d; position: fixed; top:0; width:100%; z-index:1000; backdrop-filter: blur(4px); border-radius:0; }
    .navbar-brand, .nav-link { color:#fff !important; }
    .title { font-size:32px; font-weight:700; text-align:center; margin-top:100px; margin-bottom:50px; color:#1a1a1a; }
    .category-card { background:#fff; border-radius:15px; padding:20px; margin-bottom:50px; box-shadow:0 12px 25px rgba(0,0,0,0.08); }
    .category-card h4 { margin-bottom:25px; font-weight:600; }
    .candidate-grid { display:flex; flex-wrap:wrap; gap:20px; justify-content:flex-start; }
    .candidate { background:#fff; border:1px solid #e2dede; border-radius:12px; padding:15px; flex:1 1 calc(33.333% - 20px); box-sizing:border-box; transition:transform 0.3s, box-shadow 0.3s; text-align:center; position:relative; }
    .candidate:hover { transform: translateY(-5px); box-shadow:0 10px 25px rgba(0,0,0,0.15); }
    .candidate img { width:100px; height:100px; object-fit:cover; border-radius:50%; margin-bottom:10px; transition:transform 0.3s; }
    .candidate:hover img { transform:scale(1.05); }
    .candidate h3 { font-size:18px; font-weight:600; margin-bottom:5px; }
    .candidate h5, .candidate h6 { font-size:14px; margin-bottom:3px; color:#555; }
    .progress { height:22px; border-radius:15px; overflow:hidden; margin-top:8px; }
    .progress-bar { transition:width 1s ease-in-out; font-weight:600; color:#fff; display:flex; align-items:center; justify-content:center; font-size:0.9rem; }
    .winner-badge { position:absolute; top:-10px; right:-10px; background:gold; color:#000; font-weight:bold; padding:5px 12px; border-radius:50px; box-shadow:0 0 10px gold; display:flex; align-items:center; gap:5px; z-index:10; }
    .footerGroup { border:1px solid #e2dede; border-radius:10px; padding:10px; margin-top:10px; }
    footer { background-color:#d7d7d9; color:#000; padding:25px 0; text-align:center; font-weight:600; margin-top:50px; }
    @media(max-width:991px){ .container {min-width: 100%;} .candidate { flex:1 1 calc(50% - 20px); } }
    @media(max-width:550px){ .candidate { flex:1 1 100%; } }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/">বাংলাদেশ নির্বাচন কমিশন</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav align-items-lg-center gap-2">
        <li class="nav-item"><a class="nav-link" href="/">হোম</a></li>
        <li class="nav-item"><a class="nav-link active" href="/results">নির্বাচনের ফলাফল</a></li>
        <li class="nav-item"><a class="btn btn-primary btn-sm" href="/user/login" target="_blank">কর্মকর্তার লগইন</a></li>
        <li class="nav-item"><a class="btn btn-info btn-sm" href="/admin/login" target="_blank">এডমিন লগইন</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Results Section -->
<section id="results" class="py-5">
  <div class="container">
    <div class="title">নির্বাচনের ফলাফল</div>

    @foreach($categories as $categoryId => $candidates)
      @php
        // highest vote calculation for this category
        $maxVotes = $candidates->max(function($cand){
          return $cand->vote_count ?? 0;
        });
      @endphp

      <div class="category-card">
        <h4>{{ $candidates->first()->category_name }}</h4>
        <div class="candidate-grid">


          @foreach($candidates as $candidate)
            @php
                $emp = DB::table('employees')->where('id', $candidate->employee_id)->first();
                $deg = DB::table('designations')->where('id', $emp->designation)->first();

                $catWiseTotalVote = DB::table('votes')->where('category_id', $categoryId)->count();
                $catWiseCandiVote = DB::table('votes')->where('category_id', $categoryId)->where('candidate_id', $candidate->employee_id)->count();

                $percentage = $catWiseTotalVote > 0 ? round(($catWiseCandiVote / $catWiseTotalVote) * 100, 2) : 0;

            @endphp


            <div class="candidate">
              {{-- @if($isWinner)
                <div class="winner-badge"><i class="bi bi-star-fill"></i> বিজয়ী</div>
              @endif --}}
              <img src="{{ asset($emp->photo) }}" alt="{{ $emp->name }}">
              <h3>{{ $emp->name }} </h3>
              <h5><b>পদবী:</b> {{ $deg->name ?? 'N/A' }}</h5>
              <h6><b>কর্মস্থল:</b> {{ $emp->working_place ?? 'N/A' }}</h6>

              <div class="footerGroup">
                <div class="d-flex justify-content-between">
                  <span>ভোট: {{ $catWiseCandiVote }}</span>
                  <span>{{ $percentage }}%</span>
                </div>
                <div class="progress rounded-pill mt-1">
                  <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%; background: linear-gradient(90deg,#6a11cb,#2575fc);" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">{{ $percentage }}%</div>
                </div>
              </div>
            </div>

          @endforeach
        </div>
        
           
      </div>

    @endforeach

  </div>
</section>

<footer>
  &copy; 2025 অনলাইন ভোটিং. ডিজাইন ও ডেভেলপমেন্ট পার্টনার 
  <a href="https://www.solveitbd.com" target="_blank" class="text-decoration-none fw-bold">Solve IT</a>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
