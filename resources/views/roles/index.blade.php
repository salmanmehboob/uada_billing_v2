@extends('layouts.admin.app')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-6">
            @foreach($roles as $role)
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="fw-normal mb-0 text-body">Total {{$role->users_count}} users</h6>
                        </div>
                        <div class="d-flex justify-content-between align-items-end">
                            <div class="role-heading">
                                <h5 class="mb-1">{{$role->name}}</h5>
                                <a
                                    href="javascript:;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editRoleModal{{$role->id}}"
                                    class="role-edit-modal"
                                ><span>Edit Role</span></a
                                >
                            </div>
                            @include('roles._edit_role_modal')
                            <a href="javascript:void(0);"><i class="icon-base ti tabler-copy icon-md text-heading"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card h-100">
                    <div class="row h-100">
                        <div class="col-sm-5">
                            <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-4">
                                <img
                                    src="../../assets/img/illustrations/add-new-roles.png"
                                    class="img-fluid"
                                    alt="Image"
                                    width="83"/>
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="card-body text-sm-end text-center ps-sm-0">
                                <button
                                    data-bs-target="#addRoleModal"
                                    data-bs-toggle="modal"
                                    class="btn btn-sm btn-primary mb-4 text-nowrap add-new-role">
                                    Add New Role
                                </button>
                                <p class="mb-0">
                                    Add new role, <br/>
                                    if it doesn't exist.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('roles._add_role_modal')
{{--        <div class="row g-6">--}}
{{--            <!-- Users List Table -->--}}
{{--            <div class="card">--}}
{{--                <div class="card-header border-bottom">--}}
{{--                    <h5 class="card-title mb-0">Filters</h5>--}}
{{--                    <div class="d-flex justify-content-between align-items-center row pt-4 gap-4 gap-md-0">--}}
{{--                        <div class="col-md-12 text-end">--}}
{{--                            <button class="btn add-new btn-primary" tabindex="0" aria-controls="DataTables_Table_0"--}}
{{--                                    type="button" data-bs-toggle="offcanvas"--}}
{{--                                    data-bs-target="#offcanvasAddUser"><span><span--}}
{{--                                        class="d-flex align-items-center gap-2"><i--}}
{{--                                            class="icon-base ti tabler-plus icon-xs"></i> <span--}}
{{--                                            class="d-none d-sm-inline-block">Add New Record</span></span></span>--}}
{{--                            </button>--}}
{{--                        </div>--}}

{{--                        <!-- Offcanvas for Add User -->--}}

{{--                        @include($viewFolder . '.create')--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="card-datatable">--}}
{{--                    <table class="datatable table">--}}
{{--                        <thead class="border-top">--}}
{{--                        <tr>--}}
{{--                            <th>#</th>--}}
{{--                            <th>Name</th>--}}
{{--                            <th>Actions</th>--}}
{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody>--}}
{{--                        @foreach($roles as $row)--}}
{{--                            <tr>--}}
{{--                                <td>{{$row->id}}</td>--}}
{{--                                <td>{{$row->name}}</td>--}}
{{--                                <td>--}}
{{--                                    <button class="btn btn-icon btn-outline-primary waves-effect"--}}
{{--                                            tabindex="0" aria-controls="DataTables_Table_0"--}}
{{--                                            type="button" data-bs-toggle="offcanvas"--}}
{{--                                            data-bs-target="#offcanvasEditUser{{$row->id}}">--}}
{{--                                <span>--}}
{{--                                <span class="d-flex align-items-center gap-2">--}}
{{--                                 <i class="icon-base ti tabler-edit icon-22px"></i>--}}
{{--                                 </span>--}}
{{--                                 </span>--}}
{{--                                    </button>--}}

{{--                                    <button data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $row->id }}"--}}
{{--                                            class="btn btn-icon btn-outline-danger waves-effect delete-record">--}}
{{--                                        <i class="icon-base ti tabler-trash icon-22px"></i>--}}
{{--                                    </button>--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <!-- Offcanvas for Edit User -->--}}
{{--                            @include($viewFolder .'.edit')--}}
{{--                            @include('layouts.admin._delete')--}}
{{--                        @endforeach--}}
{{--                        </tbody>--}}
{{--                    </table>--}}

{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
    <!-- / Content -->
@endsection
@push('page_js')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/popular.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/auto-focus.js') }}"></script>
    @include($viewFolder .'._datatable')
    @include($viewFolder .'._role_validation')
@endpush
