<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>অনলাইন ভোটিং সিস্টেম</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Siyam Rupali', sans-serif;
    }

    .navbar {
      background-color: #25313d30;
      position: fixed;
      z-index: 10000;
      width: 100%;
      backdrop-filter: blur(4px);
    }

    .navbar .navbar-brand {
      text-shadow: 1px 1px 6px rgb(0 0 0 / 93%);
    }

    .navbar-brand, .nav-link {
      color: #fff !important;
    }

    .hero-section {
      background: url({{ asset('frontend/img/banner.webp') }}) no-repeat center center/cover;
      height: 100vh;
      position: relative;
    }

    .blur {
      backdrop-filter: blur(3px);
      background: #00000038;
      height: 100%;
      width: 100%;
      position: absolute;
      inset: 0;
    }

    .blur .container {
      position: relative;
      z-index: 1;
      color: #fff;
      text-align: center;
      padding-top: 20vh;
    }

    .blur h1 {
      font-weight: bold;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
      font-size: clamp(28px, 6vw, 70px);
      line-height: 1.4;
    }

    .blur h2 {
      color: #c3dbff;
      font-weight: bold;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
      font-size: clamp(18px, 4vw, 30px);
    }

    .countdown {
      margin-top: 20px;
      font-weight: bold;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 10px;
    }

    .countdown div {
      background: rgba(255, 255, 255, 0.7);
      padding: 10px 15px;
      border-radius: 5px;
      min-width: 70px;
      text-align: center;
    }

    .countdown div span {
      font-size: 24px;
      font-weight: bold;
      display: block;
    }

    .title {
      font-size: 24px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 30px;
      color: #333;
      background: #d7d7d9;
      padding: 10px;
      border-radius: 5px;
    }

    .candidate {
      display: flex;
      align-items: center;
      gap: 15px;
      background: #fff;
      border: 1px solid #e2dede;
      border-radius: 10px;
      padding: 10px;
      height: 100%;
    }

    .candidate img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 8px;
      flex-shrink: 0;
    }

    .candidate .body h3 {
      font-size: 18px;
    }

    .candidate .body h5, 
    .candidate .body h6 {
      font-size: 14px;
    }

    footer {
      background-color: #d7d7d9;
      color: #000;
      padding: 15px 0;
      text-align: center;
      margin-top: 30px;
    }
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
        <li class="nav-item">
          <a class="nav-link" href="#candidates">প্রার্থীরা</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">নির্বাচনের ফলাফল</a>
        </li>
        <li class="nav-item">
          <a class="btn btn-primary btn-sm" href="/user/login" target="_blank">কর্মকর্তার লগইন</a>
        </li>
        <li class="nav-item">
          <a class="btn btn-info btn-sm" href="/admin/login" target="_blank">এডমিন লগইন</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
  <div class="blur d-flex justify-content-center align-items-center">
    <div class="container">
      <h1>আপনার পছন্দের প্রার্থীকে বিজয়ী করতে ভোট দিন।</h1>
      <h2>ভোট শুরু হতে বাকি আছে</h2>
      <div id="countdown" class="countdown">
        <div><span id="days">00</span>দিন</div>
        <div><span id="hours">00</span>ঘণ্টা</div>
        <div><span id="minutes">00</span>মিনিট</div>
        <div><span id="seconds">00</span>সেকেন্ড</div>
      </div>
    </div>
  </div>
</section>

<!-- Candidates Section -->
<section id="candidates" class="py-5">
  <div class="container">
    <div class="title">সক্রিয় প্রার্থীগণ</div>

    @foreach ($categories as $categoryId => $items)
      <h4 class="election_category mb-3">{{ $items->first()->category_name }} প্রার্থী</h4>
      <div class="row g-3 mb-4">
        @foreach ($items as $candidate)
          @if ($candidate->emp_id) 
            <div class="col-12 col-md-6 col-lg-4">
              <div class="candidate">
                <img src="{{ asset($candidate->emp_photo) }}" alt="Candidate Image">
                <div class="body">
                  <h3>{{ $candidate->emp_name }}</h3>
                  <h5><b>পদবী:</b> {{ $candidate->designation_name ?? 'N/A' }}</h5>
                  <h6><b>কর্মস্থল:</b> {{ $candidate->office_name ?? 'N/A' }}</h6>
                </div>
              </div>
            </div>
          @endif
        @endforeach
      </div>
    @endforeach

  </div>
</section>

<!-- Footer -->
<footer>
  <div class="container">
    &copy; 2025 অনলাইন ভোটিং. ডিজাইন ও ডেভেলপমেন্ট পার্টনার <a href="https://www.solveitbd.com" target="_blank" class="text-decoration-none fw-bold">Solve IT</a> 
  </div>
</footer>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// === Countdown Config ===
const deadline = new Date("2025-10-05T23:59:59");

// Elements
const daysEl = document.getElementById("days");
const hoursEl = document.getElementById("hours");
const minutesEl = document.getElementById("minutes");
const secondsEl = document.getElementById("seconds");

function updateCountdown() {
  const now = new Date();
  const diff = deadline - now;

  if (diff <= 0) {
    document.getElementById("countdown").innerHTML = "<b>⏰ সময় শেষ!</b>";
    clearInterval(timer);
    return;
  }

  const totalSeconds = Math.floor(diff / 1000);
  const days = Math.floor(totalSeconds / (3600 * 24));
  const hours = Math.floor((totalSeconds % (3600 * 24)) / 3600);
  const minutes = Math.floor((totalSeconds % 3600) / 60);
  const seconds = totalSeconds % 60;

  daysEl.textContent = String(days).padStart(2, "0");
  hoursEl.textContent = String(hours).padStart(2, "0");
  minutesEl.textContent = String(minutes).padStart(2, "0");
  secondsEl.textContent = String(seconds).padStart(2, "0");
}

updateCountdown();
const timer = setInterval(updateCountdown, 1000);
</script>
</body>
</html>
