@extends('userend.main')
@section('css')
    <style>

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

                    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <img src="{{ asset($user->photo) }}" alt="" id="profileImage" style="max-width: 150px; max-height: 150px; object-fit: cover; display: block; margin-bottom: 10px;border-radius: 10px;">
                                    <label for="userPhoto" class="form-label">আপনার ছবি যুক্ত করুন</label>
                                    <input type="file" id="userPhoto" name="photo" class="form-control" accept="image/*">
                                </div>
                            </div>
                            
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">নাম</label>
                                    <input type="text" name="name" id="simpleinput" class="form-control" value="{{ $user->name }}" required>
                                </div>
                            </div>
                            
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">ইমেইল</label>
                                    <input type="text" name="email" id="simpleinput" class="form-control" value="{{ $user->email }}" required>
                                </div>
                            </div>
                            
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">জাতীয় পরিচয়পত্র নম্বর</label>
                                    <input type="text" name="nid" id="simpleinput" class="form-control" value="{{ $user->nid }}" required>
                                </div>
                            </div>
                            
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">ফোন নম্বর</label>
                                    <input type="text" name="phone" id="simpleinput" class="form-control" value="{{ $user->phone }}" required>
                                </div>
                            </div>
                            

                            <div class="col-6 mb-3">
                                <label for="simpleinput" class="form-label">পদবী</label>
                                <select class="select2 form-control  select2-hidden-accessible" data-toggle="select2" name="designation" data-select2-id="1" tabindex="-1" aria-hidden="true" required>
                                    <option value="" selected disabled>সিলেক্ট করুন</option>
                                    @foreach ($designations as $desg)
                                        <option value="{{ $desg->id }}" {{ $user->designation == $desg->id ? 'selected' : '' }}>{{ $desg->name }}</option>
                                    @endforeach
                                    
                                </select>
                            </div>
                            

                            <div class="col-6 mb-3">
                                <label for="simpleinput" class="form-label">জেলা</label>
                                <select class="select2 form-control  select2-hidden-accessible" data-toggle="select2" data-select2-id="2" tabindex="-1" aria-hidden="true" name="district" required>
                                <option value="" disabled selected>সিলেক্ট করুন</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->id }}" {{ $user->district == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                    @endforeach
                                    
                                </select>
                            </div>

                            
                            <div class="col-12 mb-3">
                                <label for="simpleinput" class="form-label">কর্মস্থান</label>
                                <select class="form-control select2 select2-hidden-accessible" data-toggle="select2" data-select2-id="3" tabindex="-1" name="officeLoc" aria-hidden="true" required>
                                    <option value="" disabled selected>সিলেক্ট করুন</option>
                                    @foreach ($offices as $office)
                                        <option value="{{ $office->id }}" {{ $user->working_place == $office->id ? 'selected' : '' }}>{{ $office->office_name }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-12">
                                <div class="mb-3">
                                    <center>
                                        <button type="submit" class="btn btn-primary">আপডেট করুন</button>
                                    </center>
                                </div>
                            </div>

                        </div>

                    </form>

                </div>
                <!-- end row-->
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div><!-- end col -->
</div>

@endsection
@section('script')
<script>
    $('#userPhoto').on('change', function(e){
        let reader = new FileReader();
        reader.onload = e => $('#profileImage').attr('src', e.target.result);
        reader.readAsDataURL(this.files[0]);
    });
</script>
@endsection