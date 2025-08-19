@php use Carbon\Carbon; @endphp
@extends('layouts.admin.app')

@section('content')
    <div class="container-xxl py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">{{ $title }}</h5>
            <div>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Back</a>
                <button onclick="window.print()" class="btn btn-primary">Print</button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                @if(empty($bills) || $bills->isEmpty())
                    <div class="alert alert-info mb-0">No combined bills match the selected filters.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Bill No</th>
                                <th>Allotee</th>
                                <th>Plot</th>
                                <th>Sector</th>
                                <th>Size</th>
                                <th>Year</th>
                                <th>From</th>
                                <th>To</th>
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
                            @php
                                $sumBill = 0; $sumArr = 0; $sumSub = 0; $sumSC = 0; $sumTot = 0;
                            @endphp
                            @foreach($bills as $i => $b)
                                @php
                                    $sumBill += (float)($b->bill_total ?? 0);
                                    $sumArr  += (float)($b->arrears ?? 0);
                                    $sumSub  += (float)($b->sub_total ?? 0);
                                    $sumSC   += (float)($b->sub_charges ?? 0);
                                    $sumTot  += (float)($b->total ?? 0);
                                @endphp
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>{{ $b->bill_number }}</td>
                                    <td>{{ $b->allotee->name ?? '' }}</td>
                                    <td>{{ $b->allotee->plot_no ?? '' }}</td>
                                    <td>{{ $b->sector->name ?? '' }}</td>
                                    <td>{{ $b->size->name ?? '' }}</td>
                                    <td>{{ $b->year }}</td>
                                    <td>{{ $b->fromMonth->name ?? '' }}</td>
                                    <td>{{ $b->toMonth->name ?? '' }}</td>
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
                                <th colspan="9" class="text-end">Totals</th>
                                <th class="text-end">{{ number_format($sumBill, 2) }}</th>
                                <th class="text-end">{{ number_format($sumArr, 2) }}</th>
                                <th class="text-end">{{ number_format($sumSub, 2) }}</th>
                                <th class="text-end">{{ number_format($sumSC, 2) }}</th>
                                <th class="text-end">{{ number_format($sumTot, 2) }}</th>
                                <th colspan="2"></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        @media print {
            .navbar, .footer, .btn, a[href]:after {
                display: none !important;
            }

            .card {
                border: none !important;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
@endsection