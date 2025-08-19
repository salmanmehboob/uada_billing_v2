@extends('layouts.app')

@section('content')
    <!-- Reset Password -->
    <div class="d-flex col-12 col-xl-4 align-items-center authentication-bg p-6 p-sm-12">
        <div class="w-px-400 mx-auto mt-12 pt-5">
            <h4 class="mb-1">{{ __('Reset Password') }} 🔒</h4>
            <p class="mb-6">
                <span class="fw-medium">Your new password must be different from previously used passwords</span>
            </p>
            <form id="formAuthentication" class="mb-6" method="POST" action="{{ route('password.update') }}">
                @csrf
                <div class="mb-6 form-password-toggle form-control-validation">
                    <label class="form-label" for="password">New Password</label>
                    <div class="input-group input-group-merge">

                    </div>
                    <input
                        type="password"
                        id="password"
                        class="form-control @error('password') is-invalid @enderror"
                        name="password"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                        aria-describedby="password"/>
                    <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>

                <div class="mb-6 form-password-toggle form-control-validation">
                    <label class="form-label" for="confirm-password">Confirm Password</label>
                    <div class="input-group input-group-merge">
                        <input
                            type="password"
                            id="confirm-password"
                            class="form-control @error('password_confirmation') is-invalid @enderror"
                            name="password_confirmation"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="password"/>
                        <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>
                        @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary d-grid w-100 mb-6">Set new password</button>
                <div class="text-center">
                    <a href="/" class="d-flex justify-content-center">
                        <i class="icon-base ti tabler-chevron-left scaleX-n1-rtl me-1_5"></i>
                        Back to login
                    </a>
                </div>
            </form>
        </div>
    </div>
    <!-- /Reset Password -->


@endsection
