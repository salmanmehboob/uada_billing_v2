@extends('layouts.admin.app')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
        <div class="card-datatable">
            <table class="datatable table">
                <thead class="border-top">
                <tr>
                    <th>Module</th>
                    <th>Permission</th>
                    @foreach($roles as $role)
                        <th>{{ $role->name }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($groupedPermissions as $module)
                    @foreach($module['permissions'] as $permission)
                        <tr>
                            <td>{{ $module['module_name'] }}</td>
                            <td>{{ $permission['name'] }}</td>
                            @foreach($roles as $role)
                                <td>
                                    @if($permission['roles'][$role->id])
                                        ✅
                                    @else
                                        ❌
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>

        </div>
        </div>
    </div>

    <!-- / Content -->
@endsection
@push('page_js')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/popular.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/auto-focus.js') }}"></script>
    @include($viewFolder .'._datatable')
 @endpush
