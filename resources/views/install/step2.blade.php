@extends('layouts.install')

@section('content')

    <!-- Database Configuration -->
    <h4 class="mb-1">Step 2: Database Configuration</h4>
    <p class="mb-6">Enter your database details to continue installation.</p>

    <form id="formAuthentication" class="mb-4" action="{{ route('install.step2') }}" method="POST">
    @csrf

    <!-- Database Host -->
        <div class="mb-3 form-control-validation">
            <label class="form-label">Database Host <span class="text-danger">*</span></label>
            <input type="text" name="db_host" class="form-control  @error('db_host') is-invalid @enderror"
                   placeholder="e.g., 127.0.0.1">
            @error('db_host')
            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
            @enderror
        </div>

        <!-- Database Name -->
        <div class="mb-3 form-control-validation">
            <label class="form-label">Database Name <span class="text-danger">*</span></label>
            <input type="text" name="db_name" class="form-control  @error('db_name') is-invalid @enderror"
                   placeholder="Enter database name">
            @error('db_name')
            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
            @enderror
        </div>

        <!-- Database Username -->
        <div class="mb-3 form-control-validation">
            <label class="form-label">Database Username <span class="text-danger">*</span></label>
            <input type="text" name="db_user" class="form-control  @error('db_user') is-invalid @enderror"
                   placeholder="Enter database username">
            @error('db_user')
            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
            @enderror
        </div>

        <!-- Database Password -->
        <div class="mb-3 ">
            <label class="form-label">Database Password</label>
            <input type="password" name="db_pass" class="form-control" placeholder="Enter database password">
        </div>

        <!-- Submit Button -->
        <div class="mb-6">
            <button class="btn btn-primary d-grid w-100" type="submit">Next</button>
        </div>
    </form>

    <!-- /Database Configuration -->

@endsection
