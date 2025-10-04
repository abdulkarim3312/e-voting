@extends('userend.main')
@section('css')
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
@endsection
@section('body')
    
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-header border-bottom border-dashed d-flex align-items-center">
                <h4 class="header-title">প্রোফাইল আপডেট করুন</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="container">
                        <div class="title">সক্রিয় প্রার্থীগণ</div>
                        @foreach ($categories as $categoryId => $items)
                            <h4 class="election_category mb-3">{{ $items->first()->category_name }} প্রার্থী</h4>
                            <div class="row g-3 mb-4 category-{{ $categoryId }}" data-max-vote="{{ $items->first()->max_votes ?? 1 }}">
                                @foreach ($items as $candidate)
                                    @if ($candidate->emp_id)
                                        <div class="col-12 col-md-6 col-lg-4">
                                            <div class="candidate">
                                                <img src="{{ asset($candidate->emp_photo) }}" alt="Candidate Image">
                                                <div class="body">
                                                    <h3>{{ $candidate->emp_name }}</h3>
                                                    <h5><b>পদবী:</b> {{ $candidate->designation_name ?? 'N/A' }}</h5>
                                                    <h6><b>কর্মস্থল:</b> {{ $candidate->office_name ?? 'N/A' }}</h6>

                                                    <button class="btn btn-primary vote-btn {{ $candidate->voted_id ? 'voted' : '' }}"
                                                            data-category="{{ $categoryId }}"
                                                            data-candidate="{{ $candidate->emp_id }}"
                                                            {{ $candidate->voted_id ? 'disabled' : '' }}>
                                                        {{ $candidate->voted_id ? 'ভোট হয়েছে' : 'ভোট দিন' }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div> 
        </div> 
    </div>
</div>

@endsection
@section('script')
<script>
  $('.vote-btn').on('click', function() {
      const btn = $(this);
      const categoryId = btn.data('category');
      const candidateId = btn.data('candidate');
      const categoryDiv = $(`.category-${categoryId}`);
      const maxVote = categoryDiv.data('max-vote');
      const employeeId = "{{ session('employee_id') }}";

      $.ajax({
          url: "{{ route('vote.store') }}",
          type: "POST",
          data: {
              category_id: categoryId,
              candidate_id: candidateId,
              employee_id: employeeId,
              _token: "{{ csrf_token() }}"
          },
          success: function(data) {
              if (data.status) {
                  btn.addClass('voted')
                    .text('ভোট হয়েছে')
                    .prop('disabled', true);

                  let votedCount = categoryDiv.find('.vote-btn.voted').length;
                  if (votedCount >= maxVote) {
                      categoryDiv.find('.vote-btn:not(.voted)').prop('disabled', true);
                  }

                  alert(data.message);
              } else {
                  alert(data.message);
              }
          }
      });
  });
</script>
@endsection