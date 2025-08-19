@extends('layouts.admin.app')

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Suppliers List Table -->
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">{{ $title }} Record</h5>
                <div class="d-flex justify-content-between align-items-center row pt-4 gap-4 gap-md-0">
                    <div class="col-md-12 text-end">
                        <button class="btn add-new btn-primary" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddUser">
                            <span class="d-flex align-items-center gap-2">
                                <i class="icon-base ti tabler-plus icon-xs"></i>
                                <span class="d-none d-sm-inline-block">Add New Record</span>
                            </span>
                        </button>
                    </div>
                    @include($viewFolder . '.create')
                </div>
            </div>

            <div class="card-datatable">
                <table class="datatable table">
                    <thead class="border-top">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Plot NO</th>
                        <th>Plot Size</th>
                        <th>Sector</th>
                        <th>Plot Type</th>

                         <th>Phone</th>
                         <th>Arrears</th>
{{--                        <th>Guardian</th>--}}
                        <th>Actions</th>

                    </tr>
                    </thead>
                    <tbody>
{{--                    @php--}}
{{--                        use App\Helpers\GeneralHelper;--}}
{{--                    @endphp--}}
{{--                    @foreach($allotees as $row)--}}
{{--                        <tr>--}}
{{--                            <td>{{$row->id}}</td>--}}
{{--                            <td>{{$row->name}}</td>--}}
{{--                            <td>{{$row->plot_no}}</td>--}}
{{--                            <td>{{$row->sector->name ?? ''}}</td>--}}
{{--                            <td>{{$row->size->name  ?? ''}}</td>--}}
{{--                            <td>{{$row->type->name  ?? ''}}</td>--}}
{{--                            <td>{{ GeneralHelper::showStatus($row->is_active) }}</td>--}}


{{--                            <td>--}}
{{--                                <button type="button"--}}
{{--                                        class="btn btn-icon btn-outline-primary btn-edit"--}}
{{--                                        data-bs-toggle="offcanvas"--}}
{{--                                        data-bs-target="#offcanvasEdit"--}}
{{--                                        data-id="{{ $row->id }}"--}}
{{--                                        data-name="{{ $row->name }}"--}}
{{--                                        data-plot_no="{{ $row->plot_no }}"--}}
{{--                                        data-email="{{ $row->email }}"--}}
{{--                                        data-phone_no="{{ $row->phone_no }}"--}}
{{--                                        data-contact_person_name="{{ $row->contact_person_name }}"--}}
{{--                                        data-address="{{ $row->address }}"--}}
{{--                                        data-sector_id="{{ $row->sector_id }}"--}}
{{--                                        data-size_id="{{ $row->size_id }}"--}}
{{--                                        data-type_id="{{ $row->type_id }}"--}}
{{--                                        data-is_active="{{ $row->is_active }}"--}}
{{--                                        data-arrears="{{ $row->arrears }}"--}}
{{--                                        data-guardian_name="{{ $row->guardian_name }}"--}}
{{--                                        data-url="{{ route($route . '.update', $row->id) }}">--}}
{{--                                    <i class="icon-base ti tabler-edit icon-22px"></i>--}}
{{--                                </button>--}}


{{--                                <button data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $row->id }}"--}}
{{--                                        class="btn btn-icon btn-outline-danger delete-record">--}}
{{--                                    <i class="icon-base ti tabler-trash icon-22px"></i>--}}
{{--                                </button>--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                        @include('layouts.admin._delete')--}}
{{--                    @endforeach--}}
                    </tbody>
                </table>

                @include($viewFolder . '.edit')
            </div>

        </div>
    </div>
@endsection

@push('page_js')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/popular.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/auto-focus.js') }}"></script>
    @include($viewFolder . '._datatable')
    @include($viewFolder . '._form_validation')
@endpush
