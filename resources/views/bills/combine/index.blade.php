@php use App\Helpers\GeneralHelper; @endphp
@extends('layouts.admin.app')

@push('page_css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}"/>
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">{{ $title }}</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="combine-bill-form" method="POST" action="{{ route($route . '.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="sector_id" class="form-label">Sector (optional)</label>
                            <select id="sector_id" name="sector_id" class="select2 form-select" data-allow-clear="true" data-placeholder="Select Sector">
                                <option value=""></option>
                                @foreach($sectors as $row)
                                    <option value="{{ $row->id }}" {{ old('sector_id') == $row->id ? 'selected' : '' }}>
                                        {{ $row->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">At least one filter (Sector/Size/Type) is required.</small>
                        </div>

                        <div class="col-md-4">
                            <label for="size_id" class="form-label">Size (optional)</label>
                            <select id="size_id" name="size_id" class="select2 form-select" data-allow-clear="true" data-placeholder="Select Size">
                                <option value=""></option>
                                @foreach($sizes as $row)
                                    <option value="{{ $row->id }}" {{ old('size_id') == $row->id ? 'selected' : '' }}>
                                        {{ $row->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="type_id" class="form-label">Type (optional)</label>
                            <select id="type_id" name="type_id" class="select2 form-select" data-allow-clear="true" data-placeholder="Select Type">
                                <option value=""></option>
                                @foreach($types as $row)
                                    <option value="{{ $row->id }}" {{ old('type_id') == $row->id ? 'selected' : '' }}>
                                        {{ $row->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="bank_id" class="form-label">Bank</label>
                            <select id="bank_id" name="bank_id" class="select2 form-select" data-allow-clear="true" data-placeholder="Select Bank" required>
                                <option value=""></option>
                                @foreach($banks as $row)
                                    <option value="{{ $row->id }}" {{ old('bank_id') == $row->id ? 'selected' : '' }}>
                                        {{ $row->name .' '. $row->branch.' ' . $row->account_no }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="year" class="form-label">Financial Year</label>
                            <select id="year" name="year" class="select2 form-select" data-allow-clear="true" data-placeholder="Select Financial Year" required>
                                <option value=""></option>
                                @php
                                    $currentYear = date('Y');
                                    $startYear = $currentYear - 29;
                                    $currentMonth = date('m');
                                    if ($currentMonth > 6) { $currentYear++; $startYear++; }
                                @endphp
                                @for ($year = $currentYear; $year >= $startYear; $year--)
                                    <option value="{{ $year }}" {{ (string)old('year') === (string)$year ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="from_month" class="form-label">From Month</label>
                            <select id="from_month" name="from_month" class="select2 form-select" data-allow-clear="true" data-placeholder="Select Month" required>
                                <option value=""></option>
                                @foreach($months as $row)
                                    <option value="{{ $row->id }}" {{ old('from_month') == $row->id ? 'selected' : '' }}>{{ $row->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="to_month" class="form-label">To Month</label>
                            <select id="to_month" name="to_month" class="select2 form-select" data-allow-clear="true" data-placeholder="Select Month" required>
                                <option value=""></option>
                                @foreach($months as $row)
                                    <option value="{{ $row->id }}" {{ old('to_month') == $row->id ? 'selected' : '' }}>{{ $row->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="issue_date" class="form-label">Issue Date</label>
                            <input type="text" class="form-control flatpickr-date" placeholder="YYYY-MM-DD" id="issue_date" name="issue_date" value="{{ old('issue_date', GeneralHelper::currentMonthStart()) }}" required/>
                        </div>

                        <div class="col-md-4">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="text" class="form-control flatpickr-date" placeholder="YYYY-MM-DD" id="due_date" name="due_date" value="{{ old('due_date', GeneralHelper::currentMonthEnd()) }}" required/>
                        </div>
                    </div>

                    <hr class="my-4"/>

                    <div class="row g-3">
                        @foreach($charges as $key => $row)
                            <div class="col-md-6">
                                <label class="form-label" for="charge-{{ $key }}">{{ $row->name }}</label>
                                <input type="hidden" name="charge_id[]" value="{{ $row->id }}">
                                <input type="hidden" name="charge_name[]" value="{{ $row->name }}">
                                <input type="number" step="0.01" min="0" class="form-control" id="charge-{{ $key }}" placeholder="Enter {{ $row->name }} amount" name="charge_amount[]" value="{{ old('charge_amount.'.$key) }}" required/>
                            </div>
                        @endforeach
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-4">
                            <label for="sub_charges" class="form-label">Sub Charges (Manual)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="sub_charges" name="sub_charges" value="{{ old('sub_charges', GeneralHelper::getSettingValue('sub_charges')) }}" placeholder="Enter sub charges to override (optional)"/>
                            <small class="text-muted">Leave blank to set zero (or adapt to your percentage logic later).</small>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">Generate Bills</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('page_js')
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script type="text/javascript">
        (function () {
            const fpOpts = { dateFormat: 'Y-m-d' };
            window.flatpickr && flatpickr('.flatpickr-date', fpOpts);
            if (window.jQuery && $.fn.select2) {
                $('.select2').select2({ allowClear: true, width: '100%' });
            }
        })();
    </script>
@endpush