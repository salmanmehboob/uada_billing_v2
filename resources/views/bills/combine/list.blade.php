@extends('layouts.admin.app')

@push('page_css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}"/>
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ $title }}</h5>
                <div class="d-flex gap-2">
                    <a id="btn-view-invoices" class="btn btn-outline-primary" target="_blank">  <i class="icon-base ti tabler-eye icon-xs me-2"></i> View Invoices</a>
                    <a id="btn-print-invoices" class="btn btn-outline-primary" target="_blank">  <i class="icon-base ti tabler-printer icon-xs me-2"></i> Print Invoices</a>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route($route . '.list') }}" id="filter-form" class="mb-3">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Sector</label>
                            <select name="sector_id" class="form-select select2">
                                <option value="">All</option>
                                @foreach($sectors as $s)
                                    <option value="{{ $s->id }}" {{ (string)request('sector_id')===(string)$s->id?'selected':'' }}>
                                        {{ $s->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Size</label>
                            <select name="size_id" class="form-select select2">
                                <option value="">All</option>
                                @foreach($sizes as $s)
                                    <option value="{{ $s->id }}" {{ (string)request('size_id')===(string)$s->id?'selected':'' }}>
                                        {{ $s->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Type</label>
                            <select name="type_id" class="form-select select2">
                                <option value="">All</option>
                                @foreach($types as $t)
                                    <option value="{{ $t->id }}" {{ (string)request('type_id')===(string)$t->id?'selected':'' }}>
                                        {{ $t->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Bank</label>
                            <select name="bank_id" class="form-select select2">
                                <option value="">All</option>
                                @foreach($banks as $b)
                                    <option value="{{ $b->id }}" {{ (string)request('bank_id')===(string)$b->id?'selected':'' }}>
                                        {{ $b->name }} {{ $b->branch }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Year</label>
                            <input type="number" name="year" class="form-control" placeholder="YYYY" value="{{ request('year') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">From Month</label>
                            <select name="from_month" class="form-select select2">
                                <option value="">Any</option>
                                @foreach($months as $m)
                                    <option value="{{ $m->id }}" {{ (string)request('from_month')===(string)$m->id?'selected':'' }}>{{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">To Month</label>
                            <select name="to_month" class="form-select select2">
                                <option value="">Any</option>
                                @foreach($months as $m)
                                    <option value="{{ $m->id }}" {{ (string)request('to_month')===(string)$m->id?'selected':'' }}>{{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Active</label>
                            <select name="is_active" class="form-select">
                                <option value="">All</option>
                                <option value="1" {{ request('is_active')==='1'?'selected':'' }}>Active</option>
                                <option value="0" {{ request('is_active')==='0'?'selected':'' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Filter Results</button>
                        <a href="{{ route($route . '.list') }}" class="btn btn-dark">Reset Filter</a>
                    </div>
                </form>

                @if(($filters ?? []) && ($bills ?? collect())->isEmpty())
                    <div class="alert alert-info">
                        No combined bills found for selected filters.
                    </div>
                @endif

                @if(($bills ?? collect())->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Bill #</th>
                                <th>Consumer</th>
                                <th>Sector</th>
                                <th>Size</th>
                                <th>Year</th>
                                <th>Period</th>
                                <th class="text-end">Total</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bills as $b)
                                <tr>
                                    <td>{{ $b->bill_number }}</td>
                                    <td>
                                        {{ $b->allotee->name ?? '' }}
                                        @if(!empty($b->allotee->plot_no)) - {{ $b->allotee->plot_no }} @endif
                                    </td>
                                    <td>{{ $b->sector->name ?? '' }}</td>
                                    <td>{{ $b->size->name ?? '' }}</td>
                                    <td>{{ $b->year }}</td>
                                    <td>{{ $b->fromMonth->name ?? '' }} - {{ $b->toMonth->name ?? '' }}</td>
                                    <td class="text-end">{{ number_format((float)$b->total, 2) }}</td>
                                    <td>
                                        @if((int)$b->is_paid === 1)
                                            @if($b->transaction && (float)($b->transaction->due_amount ?? 0) > 0)
                                                <span class="badge bg-warning">Partial</span>
                                            @else
                                                <span class="badge bg-success">Paid</span>
                                            @endif
                                        @else
                                            <span class="badge bg-danger">Unpaid</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.bills.show', $b->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection

@push('page_js')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script>
        (function () {
            if (window.jQuery && $.fn.select2) {
                $('.select2').select2({ allowClear: true, width: '100%' });
            }

            // Build query from current form to keep buttons in sync
            const form = document.getElementById('filter-form');
            const btnView = document.getElementById('btn-view-invoices');
            const btnPrint = document.getElementById('btn-print-invoices');
            const baseInvoices = @json(route($route . '.invoices'));

            const buildQuery = () => {
                const params = new URLSearchParams(new FormData(form));
                // Remove empty values
                [...params.keys()].forEach(k => { if (!params.get(k)) params.delete(k); });
                return params.toString();
            };

            const refreshLinks = () => {
                const qs = buildQuery();
                btnView.href = baseInvoices + (qs ? ('?' + qs) : '');
                btnPrint.href = baseInvoices + (qs ? ('?' + qs + '&print=1') : '?print=1');
            };

            form.addEventListener('change', refreshLinks);
            refreshLinks();
        })();
    </script>
@endpush