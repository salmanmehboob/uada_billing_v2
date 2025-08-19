<!doctype html>
<html
    lang="en"
    class="layout-wide customizer-hide"
    dir="ltr"
    data-skin="default"
    data-assets-path="{{ asset('assets') }}/"
    data-template="vertical-menu-template-no-customizer"
    data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AppFlex POS') }}</title>


    @include('layouts.login.header.header_files')

</head>
<body>

<!-- Content -->

<div class="authentication-wrapper authentication-cover">
    <!-- Logo -->
    <a href="/" class="app-brand auth-cover-brand">
        <span class="app-brand-logo demo">
          <span class="text-primary">
<img src="{{ asset('assets/img/favicon/favicon.png') }}" width="32" height="32" alt="App Logo">
          </span>
        </span>
        <span class="app-brand-text demo text-heading fw-bold">{{ config('app.name', 'AppFlex POS') }}</span>
    </a>
    <!-- /Logo -->

    <div class="authentication-inner row m-0">
        <!-- /Left Text -->
        <div class="d-none d-xl-flex col-xl-8 p-0">
            <div class="auth-cover-bg d-flex justify-content-center align-items-center">
                <img
                    src="{{ ('/illustrations/auth-login-illustration-light.png') }}"
                    alt="auth-login-cover"
                    class="my-5 auth-illustration"
                    data-app-light-img="{{ ('/illustrations/auth-login-illustration-light.png') }}"
                    data-app-dark-img="{{ ('/illustrations/auth-login-illustration-dark.png') }}" />

                <img
                    src="{{ ('/illustrations/bg-shape-image-light.png') }}"
                    alt="auth-login-cover"
                    class="platform-bg"
                    data-app-light-img="{{ ('/illustrations/bg-shape-image-light.png') }}"
                    data-app-dark-img="{{ ('/illustrations/bg-shape-image-dark.png') }}" />
            </div>
        </div>

        <!-- /Left Text -->

        <!-- Login -->
        @yield('content')
        <!-- /Login -->
        @include('layouts.login.footer.footer_files')
    </div>
</div>

<!-- / Content -->


</body>
</html>
