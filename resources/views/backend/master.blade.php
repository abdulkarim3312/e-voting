<!doctype html>
<html
  lang="en"
  class=" layout-navbar-fixed layout-menu-fixed layout-compact "
  dir="ltr"
  data-skin="default"
  data-assets-path="../../assets/"
  data-template="vertical-menu-template"
  data-bs-theme="light">
 <head>
    <meta charset="utf-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />

      <title>@yield('title') | অনলাইন ভোটার</title>



      <!-- Canonical SEO -->

        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <meta property="og:title" content="" />
        <meta property="og:type" content="product" />
        <meta property="og:url" content="" />
        <meta property="og:image" content="" />
        <meta property="og:description" content="" />
        <meta property="og:site_name" content="" />
        <link rel="canonical" href="" />


    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('lib/img/logo.webp') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;ampdisplay=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('lib/css/iconify-icons.css') }}"/>
    <link rel="stylesheet" href="{{ asset('lib/css/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('lib/css/pickr-themes.css') }}" />
    <link href="{{ asset('lib/css/datatables.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('lib/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('lib/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('lib/css/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('lib/css/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('lib/css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/css/select2.min.css') }}">


        <script src="{{ asset('lib/js/helpers.js') }}"></script>
        <script src="{{ asset('lib/js/template-customizer.js') }}"></script>
        <script src="{{ asset('lib/js/config.js') }}"></script>
        @yield('styles')
  </head>

  <body>

<div class="layout-wrapper layout-content-navbar  ">
  <div class="layout-container">
    <!-- Menu -->
    @include('backend.inc.sidebar')
    <!-- / Menu -->
    <!-- Layout container -->
    <div class="layout-page">
        <!-- Navbar -->
            @include('backend.inc.header')
        <!-- / Navbar -->
      <!-- Content wrapper -->
      <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            @yield('content')
        </div>
        <!-- / Content -->
        <!-- Footer -->
            @include('backend.inc.footer')
        <!-- / Footer -->


    <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->
</div>
<!-- / Layout page -->
</div>



  <!-- Overlay -->
  <div class="layout-overlay layout-menu-toggle"></div>


  <!-- Drag Target Area To SlideIn Menu On Small Screens -->
  <div class="drag-target"></div>

</div>

      <script src="{{ asset('lib/js/jquery.js') }}"></script>
      <script src="{{ asset('lib/js/popper.js') }}"></script>
      <script src="{{ asset('lib/js/bootstrap.js') }}"></script>
      <script src="{{ asset('lib/js/node-waves.js') }}"></script>
      <script src="{{ asset('lib/js/autocomplete-js.js') }}"></script>
      <script src="{{ asset('lib/js/pickr.js') }}"></script>
      <script src="{{ asset('lib/js/perfect-scrollbar.js') }}"></script>
      <script src="{{ asset('lib/js/hammer.js') }}"></script>
      <script src="{{ asset('lib/js/i18n.js') }}"></script>
      <script src="{{ asset('lib/js/menu.js') }}"></script>
      <script src="{{ asset('lib/js/apexcharts.js') }}"></script>
      <script src="{{ asset('lib/js/main.js') }}"></script>
      <script src="{{ asset('lib/js/dashboards-analytics.js') }}"></script>
      <script src="{{ asset('lib/js/datatables.min.js') }}"></script>
      <script src="{{ asset('lib/js/toastr.min.js') }}"></script>
      <script src="{{ asset('lib/js/sweetalert2@11.js') }}"></script>
      <script>
        
      </script>
      @include('backend.inc.toastr')
      @yield('scripts')
  </body>
</html>

