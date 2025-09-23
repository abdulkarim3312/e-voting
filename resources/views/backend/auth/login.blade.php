<!DOCTYPE html>
<html lang="en" class="layout-wide customizer-hide" dir="ltr" data-skin="default" data-assets-path="../../assets/" data-template="vertical-menu-template" data-bs-theme="light">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
        <meta name="robots" content="noindex, nofollow" />
        <link rel="icon" type="image/x-icon" href="{{ asset('lib/img/logo.webp') }}" />

        <title>Login</title>

        <!-- Canonical SEO -->

        <meta name="description" content="" />
        <meta name="keywords" content=" />
        <meta property="og:title" content="" />
        <meta property="og:type" content="product" />
        <meta property="og:url" content="" />
        <meta property="og:image" content="" />
        <meta property="og:description" content="" />
        <meta property="og:site_name" content="" />
        <link rel="canonical" href="" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap" rel="stylesheet" />

        <link rel="stylesheet" href="https://demos.themeselection.com/materio-bootstrap-html-admin-template/assets/vendor/fonts/iconify-icons.css" />

        <link rel="preconnect" href="https://fonts.googleapis.com/" />
        <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;ampdisplay=swap" rel="stylesheet" />

        <link rel="stylesheet" href="{{ asset('lib/css/iconify-icons.css') }}" />
        <link rel="stylesheet" href="{{ asset('lib/css/node-waves.css') }}" />
        <link rel="stylesheet" href="{{ asset('lib/css/pickr-themes.css') }}" />
        <link href="{{ asset('lib/css/datatables.min.css') }}" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('lib/css/core.css') }}" />
        <link rel="stylesheet" href="{{ asset('lib/css/demo.css') }}" />
        <link rel="stylesheet" href="{{ asset('lib/css/perfect-scrollbar.css') }}" />
        <link rel="stylesheet" href="{{ asset('lib/css/apex-charts.css') }}" />

        <script src="{{ asset('lib/js/helpers.js') }}"></script>
        <script src="{{ asset('lib/js/template-customizer.js') }}"></script>
        <script src="{{ asset('lib/js/config.js') }}"></script>
    </head>

    <body>
        <!-- Content -->

        <div class="position-relative">
            <div class="authentication-wrapper authentication-basic container-p-y">
                <div class="authentication-inner py-6 mx-4">
                    <!-- Login -->
                    <div class="card p-sm-7 p-2" style="max-width: 500px; margin: 0 auto;">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center mt-5">
                            <a href="/" class="app-brand-link gap-3">
                                <span class="app-brand-text demo text-heading fw-semibold" style="font-size: 40px;">Solve IT</span>
                            </a>
                        </div>
                        <!-- /Logo -->

                        <div class="card-body mt-1">
                            <p class="mb-5">Please sign-in to your account and start the adventure</p>

                            <form class="mb-5" action="{{ route('admin-login-post') }}" method="POST">
                                @csrf
                                <div class="form-floating form-floating-outline mb-5 form-control-validation">
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email or username" autofocus />
                                    <label for="email">Email or Username</label>
                                    @error('email')
                                        <span class="messages">
                                            <p class="text-danger error text-left">{{ $message }}</p>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-5">
                                    <div class="form-password-toggle form-control-validation">
                                        <div class="input-group input-group-merge">
                                            <div class="form-floating form-floating-outline">
                                                <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                                <label for="password">Password</label>
                                            </div>
                                            <span class="input-group-text cursor-pointer"><i class="icon-base ri ri-eye-off-line icon-20px"></i></span>
                                        </div>
                                        @error('password')
                                            <span class="messages">
                                                <p class="text-danger error text-left" style="margin-bottom: 0px !important">{{ $message }}</p>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-5 pb-2 d-flex justify-content-between pt-2 align-items-center">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" id="remember-me" />
                                        <label class="form-check-label" for="remember-me"> Remember Me </label>
                                    </div>
                                    <a href="auth-forgot-password-basic.html" class="float-end mb-1">
                                        <span>Forgot Password?</span>
                                    </a>
                                </div>
                                <div class="mb-5">
                                    <button class="btn btn-primary d-grid w-100" type="submit">login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /Login -->
                </div>
            </div>
        </div>

        <!-- / Content -->

        <!-- Core JS -->
        <!-- build:js assets/vendor/js/theme.js -->

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
    </body>
</html>

<!-- beautify ignore:end -->
