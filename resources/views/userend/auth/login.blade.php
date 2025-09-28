<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Log In</title>
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
                    <a href="index.html" class="auth-brand mb-3">
                        <img src="{{ asset('logo.webp') }}" alt="dark logo" height="90" class="logo-dark">
                        <img src="{{ asset('logo.webp') }}" alt="logo light" height="90" class="logo-light">
                    </a>

                    <h3 class="fw-semibold mb-2">অ্যাকাউন্টে লগইন করুন  </h3>

                    <form action="{{ route('user.login') }}" method="POST" class="text-start mb-3">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="example-email">ই-মেইল</label>
                            <input type="email" id="example-email" name="emp_email" class="form-control" placeholder="Enter your email" >
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="example-password">পাসওয়ার্ড</label>
                            <input type="password" id="example-password" name="emp_password" class="form-control" placeholder="Enter your password">
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="checkbox-signin">
                                <label class="form-check-label" for="checkbox-signin">মনে রেখো</label>
                            </div>

                            <a href="#" class="text-muted border-bottom border-dashed">পাসওয়ার্ড ভুলে গেছেন</a>
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-primary" type="submit">লগইন করুন</button>
                        </div>
                    </form>

                    <p class="text-danger fs-14 mb-4">কোন অ্যাকাউন্ট নেই? 
                        <a href="/user/registration" class="fw-semibold text-dark ms-1">নিবন্ধন করুন !</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

</body>
</html>