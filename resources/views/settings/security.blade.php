@extends('layouts.admin.app')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-align-top">
                @include('settings.header')
                </div>
                <!-- Change Password -->
                <div class="card mb-6">
                    <h5 class="card-header">Change Password</h5>
                    <div class="card-body pt-1">
                        <form id="formAccountSettings" method="POST" action="{{ route('admin.settings.updateSecurity') }}">
                            @csrf
                            @method('PUT')

                            <div class="row mb-sm-6 mb-2">
                                <div class="col-md-6 form-password-toggle form-control-validation">
                                    <label class="form-label" for="currentPassword">Current Password</label>
                                    <div class="input-group input-group-merge">
                                        <input
                                            class="form-control @error('currentPassword') is-invalid @enderror"
                                            type="password"
                                            name="currentPassword"
                                            id="currentPassword"
                                            placeholder="•••••••••••" />
                                        <span class="input-group-text cursor-pointer">
                    <i class="icon-base ti tabler-eye-off icon-xs"></i>
                </span>
                                    </div>
                                    @error('currentPassword')
                                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row gy-sm-6 gy-2 mb-sm-0 mb-2">
                                <div class="mb-6 col-md-6 form-password-toggle form-control-validation">
                                    <label class="form-label" for="newPassword">New Password</label>
                                    <div class="input-group input-group-merge">
                                        <input
                                            class="form-control @error('newPassword') is-invalid @enderror"
                                            type="password"
                                            id="newPassword"
                                            name="newPassword"
                                            placeholder="•••••••••••" />
                                        <span class="input-group-text cursor-pointer">
                    <i class="icon-base ti tabler-eye-off icon-xs"></i>
                </span>
                                    </div>
                                    @error('newPassword')
                                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                                    @enderror
                                </div>

                                <div class="mb-6 col-md-6 form-password-toggle form-control-validation">
                                    <label class="form-label" for="confirmPassword">Confirm New Password</label>
                                    <div class="input-group input-group-merge">
                                        <input
                                            class="form-control @error('newPassword_confirmation') is-invalid @enderror"
                                            type="password"
                                            name="newPassword_confirmation"
                                            id="confirmPassword"
                                            placeholder="•••••••••••" />
                                        <span class="input-group-text cursor-pointer">
                    <i class="icon-base ti tabler-eye-off icon-xs"></i>
                </span>
                                    </div>
                                    @error('newPassword_confirmation')
                                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                                    @enderror
                                </div>
                            </div>

                            <h6 class="text-body">Password Requirements:</h6>
                            <ul class="ps-4 mb-0">
                                <li class="mb-4">Minimum 8 characters long - the more, the better</li>
                                <li class="mb-4">At least one lowercase character</li>
                                <li>At least one number, symbol, or whitespace character</li>
                            </ul>

                            <div class="mt-6">
                                <button type="submit" class="btn btn-primary me-3">Save changes</button>
                                <button type="reset" class="btn btn-label-secondary">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!--/ Change Password -->
            </div>
        </div>
    </div>
    <!-- / Content -->
@endsection
