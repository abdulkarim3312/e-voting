<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Sign UP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Theme Config Js -->
    <script src="{{ asset('userend/js/config.js') }}"></script>

    <!-- Vendor css -->
    <link href="{{ asset('userend/css/vendor.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="{{ asset('userend/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    
</head>

<body>

    <div class="auth-bg d-flex min-vh-100 justify-content-center align-items-center">
        <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
            <div class="col-xl-4 col-lg-5 col-md-6">
                <div class="card overflow-hidden text-center  p-xxl-4 p-3 mb-0">
                    <a href="/" class="auth-brand mb-3">
                        <img src="{{ asset('logo.webp') }}" alt="dark logo" height="90" class="logo-dark">
                        <img src="{{ asset('logo.webp') }}" alt="logo light" height="90" class="logo-light">
                    </a>

                    <h3 class="fw-semibold mb-2">নতুন একাউন্ট করুন</h3>

                        <form action="/user/registration/process" method="POST" class="text-start mb-3" id="myForm">
                            @csrf
                        <div class="mb-3">
                            <label class="form-label" for="example-name">আপনার নাম</label>
                            <input type="text" id="example-name" name="emp_name" class="form-control" placeholder="Enter your name" required value="{{ old('emp_name') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="example-email">ই-মেইল</label>
                            <input type="email" id="example-email" name="emp_email" class="form-control" placeholder="Enter your email" required value="{{ old('emp_email') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="example-password">পাসওয়ার্ড </label>
                            <input type="password" id="password" class="form-control" placeholder="Enter your password" name="emp_password" required value="{{ old('emp_password') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="example-password">পাসওয়ার্ড </label>
                            <input type="password" id="conf_password" class="form-control" placeholder="Enter your password" name="password_confirmation" required value="{{ old('emp_password_confirmation') }}">
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <div class="form-check">
                                <input type="checkbox" required class="form-check-input" id="checkbox-signin">
                                <label class="form-check-label" for="checkbox-signin">আমি সকল <a href="#">শর্তাবলীতে</a> সম্মত।  </label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-primary" type="submit">একাউন্ট করুন</button>
                        </div>
                    </form>

                    <p class="text-danger fs-14 mb-4">ইতিমধ্যে একাউন্ট আছে? 
                        <a href="/user/login" class="fw-semibold text-dark ms-1">লগইন করুন !</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $('#myForm').on('submit', function(e) {
        e.preventDefault(); // prevent default form submission

        var password = $('#password').val();
        var confirmPassword = $('#conf_password').val();

        if(password !== confirmPassword) {
            // SweetAlert warning
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Passwords do not match!',
            });
            return false; // stop form submission
        }

        // passwords match, submit the form
        this.submit();
    });
});


@if ($errors->any())
        Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '{{ $errors->first() }}',
    });
@endif

@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
    });
@endif
</script>
</body>
</html>