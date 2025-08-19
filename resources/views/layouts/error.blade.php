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

<!-- /Logo -->
@yield('content')

<!-- / Content -->


</body>
</html>
