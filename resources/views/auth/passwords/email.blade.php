@extends('layouts.app')

@section('content')
    <!-- Forgot Password -->
    <div class="d-flex col-12 col-xl-4 align-items-center authentication-bg p-sm-12 p-6">
        <div class="w-px-400 mx-auto mt-12 mt-5">
            <h4 class="mb-1">{{ __('Reset Password') }}? 🔒</h4>
            <p class="mb-6">Enter your email and we'll send you instructions to reset your password</p>
            <form id="formAuthentication" class="mb-6" method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-6 form-control-validation">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary d-grid w-100">Send Reset Link</button>
            </form>
            <div class="text-center">
                <a href="/" class="d-flex justify-content-center">
                    <i class="icon-base ti tabler-chevron-left scaleX-n1-rtl me-1_5"></i>
                    Back to login
                </a>
            </div>
        </div>
    </div>
    <!-- /Forgot Password -->
@endsection
