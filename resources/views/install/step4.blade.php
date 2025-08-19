@extends('layouts.install')

@section('content')
    <div class="container">
        <h5>Step 4: Create Admin Account</h5>

        <p>Set up your administrator account to access the system.</p>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('install.storeAdmin') }}" method="POST">
            @csrf
            <div class="mb-3 form-control-validation">
                <label for="name" class="form-label">Admin Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3 form-control-validation">
                <label for="email" class="form-label">Admin Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3 form-control-validation">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3 form-control-validation">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Create Admin & Continue</button>
        </form>
    </div>
@endsection
