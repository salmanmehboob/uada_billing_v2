@extends('layouts.admin.app')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-align-top">
{{--                    @include('settings.header')--}}
                </div>
                <div class="card mb-6">
                    <!-- Account -->
                    <div class="card-body">
                        <div class="d-flex align-items-start align-items-sm-center gap-6">
                            @if($settings->dept_logo)
                                <img src="{{ asset('storage/'.$settings->dept_logo) }}" alt="Department Logo"
                                     class="mt-2"
                                     width="100">
                            @else
                                <img
                                        src="../../assets/img/avatars/1.png"
                                        alt="user-avatar"
                                        class="d-block w-px-100 h-px-100 rounded"
                                        id="uploadedAvatar"/>
                            @endif
                            <div class="row gy-4 gx-6 mb-6">
                                <div class="col-md-6">
                                    <label class="form-label">Department Name:</label>
                                    <div class="fw-bold">{{ $settings->name }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Department Email:</label>
                                    <div class="text-muted">{{ $settings->email }}</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Address:</label>
                                    <div class="text-muted">{{ $settings->address }}</div>
                                </div>

                            </div>

                                @if($settings->dept_logo)
                                    <img src="{{ asset('storage/'.$settings->govt_logo) }}" alt="Department Logo"
                                         class="mt-2"
                                         width="100">
                                @else
                                    <img
                                            src="../../assets/img/avatars/1.png"
                                            alt="user-avatar"
                                            class="d-block w-px-100 h-px-100 rounded"
                                            id="uploadedAvatar"/>
                                @endif

                        </div>

                    </div>
                    <div class="card-body pt-4">
                        <div class="row gy-4 gx-6 mb-6">
                            <form id="formAccountSettings" method="POST"
                                  action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row gy-4 gx-6 mb-6">
                                    <!--   Name -->
                                    <div class="col-md-6 form-control-validation">
                                        <label for="name" class="form-label">Department Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name"
                                               value="{{ old('name', $settings->name) }}">
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                                        @enderror
                                    </div>

                                    <!--   Email -->
                                    <div class="col-md-6">
                                        <label for="email" class="form-label"> Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               id="email" name="email"
                                               value="{{ old('email', $settings->email) }}">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    

                                    <!--   Logo -->
                                    <div class="col-md-6 form-control-validation">
                                        <label for="dept_logo" class="form-label">Department Logo</label>
                                        <input type="file" class="form-control @error('dept_logo') is-invalid @enderror"
                                               id="dept_logo" name="dept_logo">
                                        @error('dept_logo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror

                                    </div>
                                    
                                    <!--   Logo -->
                                    <div class="col-md-6 form-control-validation">
                                        <label for="govt_logo" class="form-label">Govt Logo</label>
                                        <input type="file" class="form-control @error('govt_logo') is-invalid @enderror"
                                               id="govt_logo" name="govt_logo">
                                        @error('govt_logo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror

                                    </div>



                                    <!--   Phone -->
                                    <div class="col-md-6">
                                        <label for="phone-number-mask" class="form-label">Phone</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text">PAK </span>
                                            <input type="text"
                                                   class="form-control phone-number-mask @error('phone_no') is-invalid @enderror"
                                                   placeholder="0000 000000" id="phone-number-mask" name="phone_no"
                                                   value="{{ old('phone_no', $settings->phone_no) }}">
                                        </div>
                                        @error('phone_no')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <!--   Address -->
                                    <div class="col-md-6">
                                        <label for="address" class="form-label">Address</label>
                                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                                               id="address"
                                               name="address" value="{{ old('address', $settings->address) }}">
                                        @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <!-- Tax Rate -->
                                    <div class="col-md-6">
                                        <label for="sub_charges" class="form-label">Sub Charges (%)</label>
                                        <input type="number"
                                               class="form-control @error('sub_charges') is-invalid @enderror"
                                               id="sub_charges" name="sub_charges"
                                               value="{{ old('sub_charges', $settings->sub_charges) }}">
                                        @error('sub_charges')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>


                                    <!-- Invoice Prefix -->
                                    <div class="col-md-6">
                                        <label for="invoice_prefix" class="form-label">Invoice Prefix</label>
                                        <input type="text"
                                               class="form-control @error('invoice_prefix') is-invalid @enderror"
                                               id="invoice_prefix"
                                               name="invoice_prefix"
                                               value="{{ old('invoice_prefix', $settings->invoice_prefix) }}">
                                        @error('invoice_prefix')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <!-- Invoice Footer -->
                                    <div class="col-md-12">
                                        <label for="receipt_footer" class="form-label">Invoice Footer</label>
                                        <textarea class="form-control @error('receipt_footer') is-invalid @enderror"
                                                  id="receipt_footer"
                                                  name="receipt_footer">{{ old('receipt_footer', $settings->receipt_footer) }}</textarea>
                                        @error('receipt_footer')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <button type="submit" class="btn btn-primary me-3">Save changes</button>
                                    <button type="reset" class="btn btn-label-secondary">Cancel</button>
                                </div>
                            </form>

                        </div>
                        <!-- /Account -->
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->
@endsection
@push('page_js')

    <script src="{{ asset('assets/js/forms-selects.js') }}"></script>

@endpush
