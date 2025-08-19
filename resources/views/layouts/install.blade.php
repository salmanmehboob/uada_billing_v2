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

    <title>{{ config('app.name', 'Laravel') }}</title>


    @include('layouts.login.header.header_files')

</head>
<body>

<!-- Content -->

<!-- Content -->

<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-6">
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mb-6">
                        <a href="/" class="app-brand-link">
                  <span class="app-brand-logo demo">
                    <span class="text-primary">
                     <img src="{{ asset('assets/img/favicon/favicon.png') }}" width="32" height="32" alt="App Logo">
                    </span>
                  </span>
                            <span class="app-brand-text demo text-heading fw-bold">{{env('APP_NAME')}}</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->


</body>
</html>
