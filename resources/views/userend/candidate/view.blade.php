@extends('userend.main')
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.1/dist/sweetalert2.min.css">
    <style>
   

    .title {
      font-size: 24px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 30px;
      padding: 10px;
      border-radius: 5px;
      background: var(--osen-body-bg);
      border: 1px dashed;
    }

    .candidate {
      display: flex;
      align-items: center;
      gap: 15px;
      background: var(--osen-body-bg);
      border: 1px dashed;
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


    .vote-btn {
      width: 100%;
      padding: 4px;
  }

  </style>
@endsection
@section('body')
    
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-header border-bottom border-dashed d-flex align-items-center">
                <h4 class="header-title"> আপনার ভোট দিন </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="container">
                        <div class="title">সক্রিয় প্রার্থীগণ</div>

                        @foreach ($categories as $categoryId => $items)
                            <h4 class="election_category mb-3">{{ $items->first()->category_name }} প্রার্থী</h4>

                            <div class="row g-3 mb-4 category-{{ $categoryId }}"
                                data-max-vote="{{ $items->first()->max_votes ?? 1 }}"
                                data-voted-count="{{ $items->first()->voted_count ?? 0 }}">
                                
                                @foreach ($items as $candidate)
                                    @if ($candidate->emp_id)
                                        <div class="col-12 col-md-6 col-lg-4">
                                            <div class="candidate">
                                                <img src="{{ asset($candidate->emp_photo) }}" alt="Candidate Image">
                                                <div class="body">
                                                    <h3>{{ $candidate->emp_name }}</h3>
                                                    <h5><b>পদবী:</b> {{ $candidate->designation_name ?? 'N/A' }}</h5>
                                                    <h6><b>কর্মস্থল:</b> {{ $candidate->office_name ?? 'N/A' }}</h6>
                                                    
                                                    @if($time_now->lt($vote_start))
                                                        <div class="alert alert-warning">🕒 ভোটগ্রহণ এখনও শুরু হয়নি।</div>
                                                    @elseif($time_now->between($vote_start, $vote_end))
                                                        <button class="btn btn-primary vote-btn {{ $candidate->voted_id ? 'voted' : '' }}"
                                                                data-category="{{ $categoryId }}"
                                                                data-candidate="{{ $candidate->emp_id }}"
                                                                {{ $candidate->voted_id ? 'disabled' : '' }}>
                                                            {{ $candidate->voted_id ? 'ভোট হয়েছে' : 'ভোট দিন' }}
                                                        </button>
                                                    @else
                                                        <div class="alert alert-danger">❌ ভোটগ্রহণ শেষ হয়েছে।</div>
                                                    @endif

                                                    
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  $(document).ready(function() {

    $('.row[data-max-vote]').each(function() {
        const categoryDiv = $(this);
        const maxVote = parseInt(categoryDiv.data('max-vote'));
        const votedCount = parseInt(categoryDiv.data('voted-count'));

        if (votedCount >= maxVote) {
            categoryDiv.find('.vote-btn:not(.voted)').prop('disabled', true);
        }
    });

    $('.vote-btn').on('click', function() {
        const btn = $(this);
        const categoryId = btn.data('category');
        const candidateId = btn.data('candidate');
        const categoryDiv = $(`.category-${categoryId}`);
        const maxVote = parseInt(categoryDiv.data('max-vote'));
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
                      .prop('disabled', true)
                      .css('background', 'green');

                    if (data.voted_count >= maxVote) {
                        categoryDiv.find('.vote-btn:not(.voted)').prop('disabled', true);
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'সফল!',
                        text: data.message,
                        confirmButtonText: 'ঠিক আছে',
                        timer: 2500,
                        timerProgressBar: true
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'দুঃখিত!',
                        text: data.message,
                        confirmButtonText: 'ঠিক আছে'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'ত্রুটি!',
                    text: 'ভোট দেওয়ার সময় সমস্যা হয়েছে। আবার চেষ্টা করুন।',
                    confirmButtonText: 'ঠিক আছে'
                });
            }
        });
    });

});


</script>
@endsection