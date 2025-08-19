@extends('layouts.error')

@section('content')

    <!-- Error -->
    <div class="container-xxl d-flex flex-column justify-content-center align-items-center vh-100 text-center">
        <div class="misc-wrapper">
            <h1 class="mb-2" style="line-height: 6rem; font-size: 6rem">404</h1>
            <h4 class="mb-2">Page Not Found️ ⚠️</h4>
            <p class="mb-6">We couldn't find the page you are looking for.</p>
            <a href="/" class="btn btn-primary mb-3">Back to Home</a>
            <div class="mt-4">
                <img src="{{ asset('assets/img/illustrations/page-misc-error.png') }}"
                     alt="page-misc-error-light" width="225" class="img-fluid"/>
            </div>
        </div>
    </div>
    

@endsection
