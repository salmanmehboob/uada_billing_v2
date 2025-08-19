@extends('layouts.install')

@section('content')
    <h4 class="mb-1">Step 3: Generate App Key 🔑</h4>
    <p class="mb-4">Click below to generate the <strong>APP_KEY</strong> and finalize the installation.</p>

    <a href="{{ route('install.generateAppKey') }}" class="btn btn-primary d-grid w-100">Generate Key & Next</a>

@endsection
