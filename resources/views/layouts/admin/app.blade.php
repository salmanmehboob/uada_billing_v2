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

    <title>{{ config('app.name', 'UADA') }}</title>


    @include('layouts.admin.header.header_files')

</head>
<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
    @include('layouts.admin.sidebar.sidebar')
    <!-- Layout container -->
        <div class="layout-page">
        @include('layouts.admin.header.header_nav')

        <!-- Content wrapper -->
            <div class="content-wrapper">
                @yield('content')
                @include('layouts.admin.footer.footer')
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
<!-- / Layout wrapper -->

@include('layouts.admin.footer.footer_files')
</body>
</html>
