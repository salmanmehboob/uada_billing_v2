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

        /* Reserve space and avoid layout shifts while chart initializes */
        #sectorBar {
            display: block;
            height: 300px !important;   /* match Outstanding Aging */
            max-height: 300px !important;
            width: 100% !important;
        }
        /* Optional: prevent card inner overflow from causing scrollbars during render */
        .chart-card {
            overflow: hidden;
        }

        #alloteePie {
            display: block;
            height: 220px !important;   /* matches your other chart heights */
            max-height: 220px !important;
            width: 100% !important;
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
            <div class="col-12 col-xxl-12">
                <div class="chart-card">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="chart-title m-0">
                            Monthly Collections (Last 12 Months)
                            <span class="small text-body-secondary ms-2">Sum of paid amounts per month</span>
                        </div>
                        <div class="text-body-secondary small">Line chart</div>
                    </div>
                    <canvas id="collectionsLine" height="110"></canvas>
                </div>
            </div>


        </div>

        <!-- Charts row 2 -->
        <div class="row g-3 mt-1">
            <div class="col-12 col-xl-6">
                <div class="chart-card">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="chart-title m-0">
                            Outstanding Aging
                            <span class="small text-body-secondary ms-2">Current dues grouped by days overdue</span>
                        </div>
                        <div class="text-body-secondary small">Bar chart</div>
                    </div>
                    <canvas id="agingBar" height="160"></canvas>
                </div>
            </div>
            <div class="col-12 col-xl-6">
                <div class="chart-card">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="chart-title m-0">
                            Bills by Sector
                            <span class="small text-body-secondary ms-2">Compare dues and paid amounts by sector</span>
                        </div>
                        <div class="text-body-secondary small">Line chart</div>
                    </div>
                    <canvas id="sectorBar" height="110"></canvas>
                </div>
            </div>

            <div class="col-12 col-xl-6">
                <div class="chart-card">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="chart-title m-0">
                            Outstanding by Size
                            <span class="small text-body-secondary ms-2">Share of total dues by plot size</span>
                        </div>
                        <div class="text-body-secondary small">Pie chart</div>
                    </div>
                    <canvas id="sizePie" height="100"></canvas>
                </div>
            </div>
        </div>

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
                                <th>Allotee</th>
                                <th class="text-end">Bill Total</th>
                                <th class="text-end">Total Payable</th>
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
                                    <td>
                                        <div class="fw-semibold">
                                            {{ $row->allotee_name }}
                                            @if(!empty($row->allotee_plot_no))
                                                ({{ $row->allotee_plot_no }})
                                            @endif
                                        </div>
                                        <div class="small text-body-secondary">
                                            {{ $row->sector_name }} {{ $row->size_name ? '• '.$row->size_name : '' }}
                                        </div>
                                    </td>
                                    <td class="text-end">{{ number_format((int)($row->bill_total ?? 0)) }}</td>
                                    <td class="text-end">{{ number_format((int)($row->total ?? 0)) }}</td>
                                    <td class="text-end text-danger">{{ number_format((int)($row->due_amount ?? 0)) }}</td>
                                    <td>{{ \Illuminate\Support\Carbon::parse($row->due_date)->format('d M Y') }}</td>
                                    <td class="text-end">{{ (int)$row->days_overdue }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-body-secondary">No overdue bills found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Charts row 3: Top 10 Allotees by Outstanding -->
        <div class="row g-3 mt-1">
            <div class="col-12 col-xl-12">
                <div class="chart-card">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="chart-title m-0">
                            Top 10 Allotees by Outstanding
                            <span class="small text-body-secondary ms-2">Allotee name, plot, sector and size</span>
                        </div>
                        <div class="text-body-secondary small">Pie chart</div>
                    </div>
                    <canvas id="alloteePie" height="120"></canvas>
                    <div class="mt-3">
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th>Allotee</th>
                                    <th>Sector</th>
                                    <th>Size</th>
                                    <th class="text-end">Outstanding</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($alloteeTopDues['items'] ?? [] as $row)
                                    <tr>
                                        <td>{{ $row->name }} {{ $row->plot_no ? '('.$row->plot_no.')' : '' }}</td>
                                        <td>{{ $row->sector_name }}</td>
                                        <td>{{ $row->size_name }}</td>
                                        <td class="text-end">{{ number_format((int) $row->due_total) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-body-secondary">No outstanding dues found.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
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
            dues: @json($billsBySector['dues']),
            paid: @json($billsBySector['paid']),
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

        // Bar: Bills by Sector — show Total Dues and Total Paid
        // Compute dynamic Y-axis range
        const sectorMax = Math.max(...(sectorData.dues || [0]), ...(sectorData.paid || [0]), 0);
        const niceMax = (v) => {
            if (v <= 0) return 10;
            const magnitude = Math.pow(10, Math.max(0, Math.floor(Math.log10(v)) - 1));
            return Math.ceil(v / magnitude) * magnitude;
        };
        const ySuggestedMax = niceMax(sectorMax);
        const yStep = Math.max(1, Math.round(ySuggestedMax / 5));

        // Ensure we don't create multiple charts on hot reloads
        let sectorChart;
        const renderSectorChart = () => {
            const canvas = document.getElementById('sectorBar');
            if (!canvas) return;

            if (sectorChart) {
                sectorChart.destroy();
                sectorChart = null;
            }

            // Defer to next frame to ensure layout is stable and the canvas has its final size
            requestAnimationFrame(() => {
                sectorChart = new Chart(canvas, {
                    type: 'line',
                    data: {
                        labels: sectorData.labels,
                        datasets: [
                            {
                                label: 'Total Dues',
                                data: sectorData.dues,
                                borderColor: '#dc3545',
                                backgroundColor: 'rgba(220, 53, 69, 0.15)',
                                tension: 0.3,
                                borderWidth: 2,
                                pointRadius: 3,
                                fill: true
                            },
                            {
                                label: 'Total Paid',
                                data: sectorData.paid,
                                borderColor: '#198754',
                                backgroundColor: 'rgba(25, 135, 84, 0.15)',
                                tension: 0.3,
                                borderWidth: 2,
                                pointRadius: 3,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false, // honor fixed canvas height
                        plugins: {
                            legend: { position: 'top' },
                            tooltip: {
                                callbacks: { label: (ctx) => ` ${currencyFmt(ctx.parsed.y)}` }
                            }
                        },
                        scales: {
                            x: { ticks: { autoSkip: true, maxRotation: 0 } },
                            y: {
                                beginAtZero: true,
                                suggestedMax: ySuggestedMax,
                                grace: '10%',
                                ticks: {
                                    stepSize: yStep,
                                    callback: (v) => currencyFmt(v)
                                },
                                grid: { drawBorder: false }
                            }
                        }
                    }
                });
            });
        };

        // Render on DOM ready and also on window resize (to keep it crisp without causing layout jumps)
        document.addEventListener('DOMContentLoaded', renderSectorChart);
        window.addEventListener('resize', () => {
            if (sectorChart) sectorChart.resize();
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


        // New dataset: Top 10 Allotee dues
        const alloteeTopRaw = {
            labels: @json($alloteeTopDues['labels'] ?? []),
            values: @json($alloteeTopDues['values'] ?? []),
        };

        // Normalize to ensure labels and values are aligned and at most 10
        (function normalizeAlloteeData() {
            const labels = Array.isArray(alloteeTopRaw.labels) ? alloteeTopRaw.labels : [];
            const values = Array.isArray(alloteeTopRaw.values) ? alloteeTopRaw.values : [];
            const n = Math.min(labels.length, values.length, 10);
            alloteeTopRaw.labels = labels.slice(0, n);
            alloteeTopRaw.values = values.slice(0, n);
        })();

        const piePaletteBase = [
            '#0d6efd', '#198754', '#dc3545', '#ffc107', '#20c997',
            '#6f42c1', '#0dcaf0', '#fd7e14', '#6c757d', '#6610f2'
        ];

        let alloteePieChart;
        function renderAlloteePie() {
            const el = document.getElementById('alloteePie');
            if (!el) return;

            // Cleanup previous chart instance if any
            if (alloteePieChart) {
                alloteePieChart.destroy();
                alloteePieChart = null;
            }

            // Prepare data; handle no/zero data to avoid rendering glitches
            const labels = alloteeTopRaw.labels;
            const values = alloteeTopRaw.values.map(v => Number(v) || 0);
            const hasData = values.some(v => v > 0);

            const chartLabels = hasData ? labels : ['No outstanding'];
            const chartValues = hasData ? values : [1];

            // Build color set matching slice count
            const colors = [];
            for (let i = 0; i < chartValues.length; i++) {
                colors.push(piePaletteBase[i % piePaletteBase.length]);
            }

            // Defer to next frame to ensure canvas size is final
            requestAnimationFrame(() => {
                alloteePieChart = new Chart(el, {
                    type: 'pie',
                    data: {
                        labels: chartLabels,
                        datasets: [{
                            data: chartValues,
                            backgroundColor: colors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false, // honor fixed CSS height
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    // Trim very long legends for readability
                                    generateLabels: (chart) => {
                                        const items = Chart.overrides.pie.plugins.legend.labels.generateLabels(chart);
                                        return items.map(it => {
                                            const maxLen = 40; // keep labels compact
                                            if (typeof it.text === 'string' && it.text.length > maxLen) {
                                                it.text = it.text.slice(0, maxLen - 1) + '…';
                                            }
                                            return it;
                                        });
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: (ctx) => {
                                        // Always show full label in tooltip
                                        const idx = ctx.dataIndex;
                                        const label = labels[idx] ?? ctx.label ?? '';
                                        const val = hasData ? (values[idx] ?? 0) : 0;
                                        return ` ${label}: ${currencyFmt(val)}`;
                                    }
                                }
                            }
                        }
                    }
                });
            });
        }

        document.addEventListener('DOMContentLoaded', renderAlloteePie);
        window.addEventListener('resize', () => {
            if (alloteePieChart) alloteePieChart.resize();
        });


    </script>
@endpush