@extends('layouts.install')

@section('content')

    <!-- Step 1: Server Requirements -->

    <h4 class="mb-1">Step 1: Server Requirements 👋</h4>
    <form id="formAuthentication" class="mb-4" action="{{ route('install.step1') }}" method="POST">
    @csrf

    <!-- Step 1: Server Requirements -->
        <p>PHP Version: {{ $phpVersion }}</p>

        @if(count($missingExtensions) > 0)
            <div class="alert alert-danger">
                <h6>Missing Extensions:</h6>
                <ul>
                    @foreach($missingExtensions as $ext)
                        <li>{{ $ext }}</li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="alert alert-success">
                <p>All required extensions are installed.</p>
            </div>
    @endif

    <!-- Purchase Key Input -->
        <div class="mb-6 form-control-validation">
            <label for="purchase_key" class="form-label">Enter Purchase Key</label>
            <input type="text"
                   name="purchase_key"
                   id="purchase_key"
                   class="form-control  @error('purchase_key') is-invalid @enderror"
                   placeholder="Enter Purchase Key">
            @error('purchase_key')
            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="mb-6">
            <button class="btn btn-primary d-grid w-100" type="submit">Next</button>
        </div>
    </form>

    <!-- /Step 1: Server Requirements -->

@endsection
