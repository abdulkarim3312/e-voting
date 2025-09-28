<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Sales Dashboard | Osen - Responsive Bootstrap 5 Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />

    <!-- Theme Config Js -->
    <script src="{{ asset('userend/js/config.js') }}"></script>

    <!-- Vendor css -->
    <link href="{{ asset("userend/css/vendor.min.css") }}" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="{{ asset('userend/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
    
    <!-- Icons css -->
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.7.2/css/all.css"/>
    


    @yield('css')
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        
        <!-- Sidenav Menu Start -->
            @include('userend.common.sidebar')
        <!-- Sidenav Menu End -->
        

        <!-- Topbar Start -->
             @include('userend.common.header')
        <!-- Topbar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <div class="page-content">
            <div class="page-container">
                @php
                    $user = DB::table('employees')->where('id', session('employee_id'))->first(['photo']);
                @endphp

                @if(empty($user->photo))
                    <div class="alert alert-danger mt-3 fs-20" role="alert">
                        <i class="fa-duotone fa-thin fa-triangle-exclamation"></i>
                        আপনার প্রোফাইল সম্পন্ন করুন অন্যথায় আপনি কিছুই ব্যবহার করতে পারবেন না। <a href="/" class="alert-link" style="color: #6f80f6">এখানে ক্লিক করুন</a>
                    </div>
                @endif
                
                @yield('body')
            </div> 

            <!-- Footer Start -->
                @include('userend.common.footer')
            <!-- end Footer -->

        </div>

    </div>
    <!-- END wrapper -->

    <!-- Theme Settings -->
        @include('userend.common.theme')

    <!-- Vendor js -->
    <script src="{{ asseT('userend/js/vendor.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('userend/js/app.js') }}"></script>

    <!-- Apex Chart js -->
    <script src="{{ asset('userend/vendor/apexcharts/apexcharts.min.js') }}"></script>

    <!-- Projects Analytics Dashboard App js -->
    <script src="{{ asset('userend/js/pages/dashboard-sales.js') }}"></script>

    @yield('script')
</body>
</html>