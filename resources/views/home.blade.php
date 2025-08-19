@extends('layouts.admin.app')

@section('title', 'Dashboard')

@push('page_css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/chartjs/chartjs.css') }}">
    <style>
        .kpi-card {
            border: 1px solid #e9ecef;
            border-radius: 0.75rem;
            padding: 1rem;
            background: #fff;
            height: 100%
        }

        .kpi-title {
            font-size: .75rem;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: #6c757d;
            margin-bottom: .25rem
        }

        .kpi-value {
            font-size: 1.375rem;
            font-weight: 700
        }

        .kpi-sub {
            font-size: .8rem;
            color: #6c757d
        }

        .chart-card {
            border: 1px solid #e9ecef;
            border-radius: 0.75rem;
            background: #fff;
            padding: 1rem
        }

        .chart-title {
            font-size: .95rem;
            font-weight: 600;
            margin-bottom: .5rem
        }

        .table-card {
            border: 1px solid #e9ecef;
            border-radius: 0.75rem;
            background: #fff
        }

        .table-card .table {
            margin: 0
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-3">

        <!-- KPIs -->
        <div class="row g-3">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="kpi-card h-100">
                    <div class="kpi-title">Total Billed</div>
                    <div class="d-flex align-items-end justify-content-between">
                        <div class="kpi-value">{{ number_format($kpis['totalBilledAmount']) }}</div>
                        <span class="badge text-bg-primary-subtle text-white">Bills: {{ number_format($kpis['totalBills']) }}</span>
                    </div>
                    <div class="kpi-sub mt-1">Sum of bill totals</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="kpi-card h-100">
                    <div class="kpi-title">Total Paid</div>
                    <div class="d-flex align-items-end justify-content-between">
                        <div class="kpi-value text-success">{{ number_format($kpis['totalPaidAmount']) }}</div>
                        <span class="badge text-bg-success-subtle text-white">Paid: {{ number_format($kpis['paidBillsCount']) }}</span>
                    </div>
                    <div class="kpi-sub mt-1">Confirmed collections</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="kpi-card h-100">
                    <div class="kpi-title">Outstanding</div>
                    <div class="d-flex align-items-end justify-content-between">
                        <div class="kpi-value text-danger">{{ number_format($kpis['totalOutstanding']) }}</div>
                        <span class="badge text-bg-danger-subtle text-white">Unpaid: {{ number_format($kpis['unpaidBillsCount']) }}</span>
                    </div>
                    <div class="kpi-sub mt-1">Current due across bills</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="kpi-card h-100">
                    <div class="kpi-title">Paid Rate</div>
                    <div class="d-flex align-items-end justify-content-between">
                        <div class="kpi-value">{{ $kpis['paidRate'] }}%</div>
                        <span class="badge text-bg-secondary-subtle text-white">Performance</span>
                    </div>
                    <div class="kpi-sub mt-1">Paid / Total billed</div>
                </div>
            </div>
        </div>

        <!-- Charts row 1 -->
        <div class="row g-3 mt-1">
            <div class="col-12 col-xxl-8">
                <div class="chart-card">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="chart-title m-0">Monthly Collections (Last 12 Months)</div>
                        <div class="text-body-secondary small">Line chart</div>
                    </div>
                    <canvas id="collectionsLine" height="110"></canvas>
                </div>
            </div>
            <div class="col-12 col-xxl-4">
                <div class="chart-card">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="chart-title m-0">Outstanding Aging</div>
                        <div class="text-body-secondary small">Bar chart</div>
                    </div>
                    <canvas id="agingBar" height="110"></canvas>
                </div>
            </div>
        </div>

        <!-- Charts row 2 -->
        <div class="row g-3 mt-1">
            <div class="col-12 col-xl-6">
                <div class="chart-card">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="chart-title m-0">Bills by Sector</div>
                        <div class="text-body-secondary small">Bar chart</div>
                    </div>
                    <canvas id="sectorBar" height="120"></canvas>
                </div>
            </div>
            <div class="col-12 col-xl-6">
                <div class="chart-card">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="chart-title m-0">Outstanding by Size</div>
                        <div class="text-body-secondary small">Pie chart</div>
                    </div>
                    <canvas id="sizePie" height="120"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Overdue -->
        <div class="row g-3 mt-1">
            <div class="col-12">
                <div class="table-card">
                    <div class="p-3 d-flex justify-content-between align-items-center">
                        <div class="chart-title m-0">Top 10 Overdue Bills</div>
                        <a href="{{ route('admin.bills.index') }}" class="btn btn-sm btn-outline-primary">
                            View All Bills
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>Bill #</th>
                                <th class="text-end">Due Amount</th>
                                <th>Due Date</th>
                                <th class="text-end">Days Overdue</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($topOverdue as $row)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.bills.show', $row->id) }}"
                                           class="link-primary link-underline-opacity-0 link-underline-opacity-100-hover">
                                            {{ $row->bill_number }}
                                        </a>
                                    </td>
                                    <td class="text-end">{{ number_format($row->due_amount) }}</td>
                                    <td>{{ \Illuminate\Support\Carbon::parse($row->due_date)->format('d M Y') }}</td>
                                    <td class="text-end">{{ (int)$row->days_overdue }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-body-secondary">No overdue bills
                                        found
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('page_js')

        <script src="{{ asset('assets/vendor/libs/chartjs/chartjs.js') }}"></script>
    {{--    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>--}}
    <script>
        const currencyFmt = (v) => new Intl.NumberFormat().format(v);

        // Data from controller
        const collections = {
            labels: @json($collectionsChart['labels']),
            values: @json($collectionsChart['values']),
        };
        const aging = @json($aging);
        const sectorData = {
            labels: @json($billsBySector['labels']),
            values: @json($billsBySector['values']),
        };
        const sizeData = {
            labels: @json($duesBySize['labels']),
            values: @json($duesBySize['values']),
        };

        // Line: Monthly Collections
        new Chart(document.getElementById('collectionsLine'), {
            type: 'line',
            data: {
                labels: collections.labels,
                datasets: [{
                    label: 'Collected',
                    data: collections.values,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13,110,253,0.15)',
                    tension: 0.3,
                    fill: true,
                    borderWidth: 2,
                    pointRadius: 3
                }]
            },
            options: {
                plugins: {
                    legend: {display: false},
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ' ' + currencyFmt(ctx.parsed.y)
                        }
                    }
                },
                scales: {
                    y: {ticks: {callback: (v) => currencyFmt(v)}, beginAtZero: true}
                }
            }
        });

        // Bar: Aging buckets
        new Chart(document.getElementById('agingBar'), {
            type: 'bar',
            data: {
                labels: ['0-30', '31-60', '61-90', '90+'],
                datasets: [{
                    label: 'Outstanding',
                    data: [aging.b0_30, aging.b31_60, aging.b61_90, aging.b90p],
                    backgroundColor: ['#20c997', '#ffc107', '#fd7e14', '#dc3545']
                }]
            },
            options: {
                plugins: {legend: {display: false}},
                scales: {
                    y: {ticks: {callback: (v) => currencyFmt(v)}, beginAtZero: true}
                }
            }
        });

        // Bar: Bills by Sector (horizontal if many)
        new Chart(document.getElementById('sectorBar'), {
            type: 'bar',
            data: {
                labels: sectorData.labels,
                datasets: [{
                    label: 'Bills',
                    data: sectorData.values,
                    backgroundColor: '#6f42c1'
                }]
            },
            options: {
                indexAxis: sectorData.labels.length > 6 ? 'y' : 'x',
                plugins: {legend: {display: false}},
                scales: {
                    x: {ticks: {autoSkip: true, maxTicksLimit: 10}},
                    y: {beginAtZero: true}
                }
            }
        });

        // Pie: Outstanding by Size
        const pieColors = ['#0d6efd', '#198754', '#dc3545', '#ffc107', '#6c757d', '#6610f2', '#fd7e14', '#20c997', '#0dcaf0', '#adb5bd'];
        new Chart(document.getElementById('sizePie'), {
            type: 'pie',
            data: {
                labels: sizeData.labels,
                datasets: [{
                    data: sizeData.values,
                    backgroundColor: pieColors.slice(0, sizeData.labels.length)
                }]
            },
            options: {
                plugins: {
                    legend: {position: 'bottom'},
                    tooltip: {
                        callbacks: {
                            label: (ctx) => {
                                const total = ctx.dataset.data.reduce((a, b) => a + b, 0) || 1;
                                const val = ctx.parsed;
                                const pct = ((val / total) * 100).toFixed(1);
                                return ` ${ctx.label}: ${currencyFmt(val)} (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush