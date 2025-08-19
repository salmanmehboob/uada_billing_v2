@extends('layouts.admin.app')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Users List Table -->
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">{{$title}} Record</h5>
                <div class="d-flex justify-content-between align-items-center row pt-4 gap-4 gap-md-0">
                    <div class="col-md-12 text-end">
                        <button class="btn add-new btn-primary" tabindex="0" aria-controls="DataTables_Table_0"
                                type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddUser"><span><span
                                    class="d-flex align-items-center gap-2"><i
                                        class="icon-base ti tabler-plus icon-xs"></i> <span
                                        class="d-none d-sm-inline-block">Add New Record</span></span></span>
                        </button>
                    </div>

                    <!-- Offcanvas for Add User -->

                    @include($viewFolder . '.create')

                </div>
            </div>
            <div class="card-datatable">
                <table class="datatable table">
                    <thead class="border-top">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Status</th>
                         <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        use App\Helpers\GeneralHelper;
                    @endphp
                    @foreach($types as $row)
                        <tr>
                            <td>
                                {{$row->id}}
                            </td>

                            <td>{{$row->name}}</td>
                            <td>{{ GeneralHelper::showStatus($row->is_active) }}</td>
                            <td>


                                <button
                                    class="btn btn-icon btn-outline-primary waves-effect btn-edit"
                                    type="button"
                                    data-bs-toggle="offcanvas"
                                    data-bs-target="#offcanvasEdit"
                                    data-id="{{ $row->id }}"
                                    data-name="{{ $row->name }}"
                                    data-url="{{ route($route.'.update', $row->id) }}"
                                >
                                    <span><i class="icon-base ti tabler-edit icon-22px"></i></span>
                                </button>


                                <button data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $row->id }}"
                                        class="btn btn-icon btn-outline-danger waves-effect delete-record">
                                    <i class="icon-base ti tabler-trash icon-22px"></i>
                                </button>
                            </td>
                        </tr>
                        <!-- Offcanvas for Edit User -->
                        @include('layouts.admin._delete')
                    @endforeach
                    </tbody>
                </table>
                @include($viewFolder .'.edit')

            </div>
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
    @include($viewFolder .'._form_validation')
@endpush
