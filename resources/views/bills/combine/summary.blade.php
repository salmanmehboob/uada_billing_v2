@php use Carbon\Carbon; @endphp
@extends('layouts.admin.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">{{ $title }} — Summary</h5>
            </div>
            <div class="card-body">
                @if(!empty($message))
                    <div class="alert alert-success text-black mt-3">{{ $message }}</div>
                @endif


                <div class="mb-3">
                    <a href="{{ route($route . '.index') }}" class="btn btn-primary">Back to Combine Form</a>
                    <a href="{{ route('admin.bills.index') }}" class="btn btn-primary">Go to Bills List</a>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-3"><strong>Total Allotees Matched:</strong> {{ $summary['count_allotees'] }}
                    </div>
                    <div class="col-md-3"><strong>Total Bills Created:</strong> {{ $summary['total_bills_created'] }}
                    </div>
                    <div class="col-md-3"><strong>Months Count:</strong> {{ $summary['months_count'] }}</div>
                    <div class="col-md-3">
                        <strong>Period:</strong>
                        {{ $period['from']->name ?? '' }} - {{ $period['to']->name ?? '' }} ({{ $period['year'] }})
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-3"><strong>Sum Bill
                            Total:</strong> {{ number_format($summary['sum_bill_total'], 2) }}</div>
                    <div class="col-md-3"><strong>Sum Arrears:</strong> {{ number_format($summary['sum_arrears'], 2) }}
                    </div>
                    <div class="col-md-3"><strong>Sum Sub
                            Total:</strong> {{ number_format($summary['sum_sub_total'], 2) }}</div>
                    <div class="col-md-3"><strong>Sum Sub
                            Charges:</strong> {{ number_format($summary['sum_sub_charges'], 2) }}</div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-3"><strong>Grand Total:</strong> {{ number_format($summary['sum_total'], 2) }}
                    </div>
                    <div class="col-md-9">
                        <strong>Filters Applied:</strong>
                        @php
                            $parts = [];
                            if (!empty($filters['sector'])) $parts[] = 'Sector: ' . $filters['sector']->name;
                            if (!empty($filters['size'])) $parts[] = 'Size: ' . $filters['size']->name;
                            if (!empty($filters['type'])) $parts[] = 'Type: ' . $filters['type']->name;
                            echo implode(' | ', $parts) ?: 'None';
                        @endphp
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Allotee</th>
                            <th>Plot</th>
                            <th>Sector</th>
                            <th>Size</th>
                            <th>Type</th>
                            <th>Bill No</th>
                            <th class="text-end">Bill Total</th>
                            <th class="text-end">Arrears</th>
                            <th class="text-end">Sub Total</th>
                            <th class="text-end">Sub Charges</th>
                            <th class="text-end">Total</th>
                            <th>Issue</th>
                            <th>Due</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($bills as $i => $b)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ $b->allotee->name ?? '' }}</td>
                                <td>{{ $b->allotee->plot_no ?? '' }}</td>
                                <td>{{ $b->sector->name ?? '' }}</td>
                                <td>{{ $b->size->name ?? '' }}</td>
                                <td>{{ $b->type->name ?? '' }}</td>
                                <td>{{ $b->bill_number }}</td>
                                <td class="text-end">{{ number_format((float)$b->bill_total, 2) }}</td>
                                <td class="text-end">{{ number_format((float)($b->arrears ?? 0), 2) }}</td>
                                <td class="text-end">{{ number_format((float)$b->sub_total, 2) }}</td>
                                <td class="text-end">{{ number_format((float)$b->sub_charges, 2) }}</td>
                                <td class="text-end">{{ number_format((float)$b->total, 2) }}</td>
                                <td>{{ Carbon::parse($b->issue_date)->format('Y-m-d') }}</td>
                                <td>{{ Carbon::parse($b->due_date)->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="7" class="text-end">Totals</th>
                            <th class="text-end">{{ number_format($summary['sum_bill_total'], 2) }}</th>
                            <th class="text-end">{{ number_format($summary['sum_arrears'], 2) }}</th>
                            <th class="text-end">{{ number_format($summary['sum_sub_total'], 2) }}</th>
                            <th class="text-end">{{ number_format($summary['sum_sub_charges'], 2) }}</th>
                            <th class="text-end">{{ number_format($summary['sum_total'], 2) }}</th>
                            <th colspan="2"></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection