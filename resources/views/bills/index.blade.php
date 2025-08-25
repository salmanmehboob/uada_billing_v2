@extends('layouts.admin.app')
@push('page_css')

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}"/>

@endpush
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ $title }} Record</h5>
                <a href="{{ route('admin.bills.create') }}" class="btn btn-primary">
                    <i class="icon-base ti tabler-plus icon-xs"></i>
                    Add New Bill
                </a>
            </div>

            <div class="p-3 border-bottom">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Allotee</label>
                        <select id="f-allotee" class="form-select select2">
                            <option value="">All</option>
                            @foreach(($allotees ?? []) as $a)
                                <option value="{{ $a->id }}">{{ $a->name }} {{ $a->plot_no }} {{ $a->sector->name ?? '' }} {{ $a->size->name ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sector</label>
                        <select id="f-sector" class="form-select select2">
                            <option value="">All</option>
                            @foreach(($sectors ?? []) as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Size</label>
                        <select id="f-size" class="form-select select2">
                            <option value="">All</option>
                            @foreach(($sizes ?? []) as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Bank</label>
                        <select id="f-bank" class="form-select select2">
                            <option value="">All</option>
                            @foreach(($banks ?? []) as $b)
                                <option value="{{ $b->id }}">{{ $b->name }} {{ $b->branch }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Year</label>
                        <select id="f-year" class="form-select select2">
                            <option value="">All</option>
                            @foreach(($years ?? []) as $y)
                                <option value="{{ is_object($y) ? ($y->year ?? '') : $y }}">{{ is_object($y) ? ($y->year ?? '') : $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">From Month</label>
                        <select id="f-from" class="form-select select2">
                            <option value="">All</option>
                            @foreach(($months ?? []) as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To Month</label>
                        <select id="f-to" class="form-select select2">
                            <option value="">All</option>
                            @foreach(($months ?? []) as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Active</label>
                        <select id="f-active" class="form-select select2">
                            <option value="">All</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Payment Status</label>
                        <select id="f-status" class="form-select">
                            <option value="">All</option>
                            <option value="paid">Paid</option>
                            <option value="partial">Partially Paid</option>
                            <option value="unpaid">Unpaid</option>
                        </select>
                    </div>
                    <div class="col-md-3 text-center">
                        <button id="btn-apply" class="btn btn-primary me-2" type="button">Filter</button>
                        <button id="btn-reset" class="btn btn-dark" type="button">Reset</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-datatable table-responsive p-3">
                <div id="summary" class="mb-3" style="display:none;"></div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        
                        <button id="btn-delete-selected" type="button" class="btn btn-danger btn-sm" disabled>
                            <i class="icon-base ti tabler-plus icon-xs me-1"></i> Delete Selected
                        </button>
                    </div>
                </div>
                <table class="table table-striped table-bordered" id="bills-table" width="100%">
                    <thead>
                    <tr>
                        <th class="text-center" style="width:42px">
                            <input type="checkbox" id="select-all">
                        </th>
{{--                        <th>Consumer ID</th>--}}
                        <th>Bill No</th>
                        <th>Allotee</th>
                        <th>Year</th>
                        <th>Duration</th>
                        <th>Bill Total</th>
                        <th>Arrears</th>
                        <th>Sub Total</th>
                        <th>Sub Charges</th>
                        <th>Total Payable</th>
                        <th>Due Amount</th>
                        <!-- <th>Active</th> -->
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
@push('page_js')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            const table = $('#bills-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: false, // keep action column visible; use horizontal scroll instead
                scrollX: true,
                scrollCollapse: true,
                autoWidth: false,
                pageLength: 50, // default rows per page
                lengthMenu: [[50, 100, 200, 500, 1000, 2000], [50, 100, 200, 500, 1000, 2000]], // allow up to 2000
                
                order: [[2, 'desc']], // order by Bill No by default if desired
                columnDefs: [
                    {targets: -1, orderable: false, searchable: false, width: 160}, // Actions
                    {targets: '_all', className: 'text-nowrap'},
                ],
                ajax: {
                    url: "{{ route('admin.bills.index') }}",
                    type: 'GET',
                    data: function (d) {
                        d.sector_id = $('#f-sector').val() || '';
                        d.size_id = $('#f-size').val() || '';
                        d.allotee_id = $('#f-allotee').val() || '';
                        d.bank_id = $('#f-bank').val() || '';
                        d.year = $('#f-year').val() || '';
                        d.from_month = $('#f-from').val() || '';
                        d.to_month = $('#f-to').val() || '';
                        d.is_active = $('#f-active').val() || '';
                        d.status_filter = $('#f-status').val() || '';
                    }
                },
                columns: [
                    { data: 'checkBill', name: 'checkBill', orderable: false, searchable: false },
                    // {data: 'consumer_id', name: 'consumer_id'},
                    {data: 'bill_number', name: 'bill_number'},
                    {data: 'name', name: 'name'},
                    {data: 'year', name: 'year'},
                    {data: 'duration', name: 'duration', orderable: false, searchable: false},
                    {data: 'bill_total', name: 'bill_total'},
                    {data: 'arrears', name: 'arrears', orderable: false, searchable: false},
                    {data: 'sub_total', name: 'sub_total'},
                    {data: 'sub_charges', name: 'sub_charges'},
                    {data: 'total', name: 'total', orderable: false, searchable: false},
                    {data: 'due_amount', name: 'due_amount', orderable: false, searchable: false},
                    // { data: 'is_active', name: 'is_active', orderable: false, searchable: false },
                    {data: 'status', name: 'status', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'},
                ]   ,
                drawCallback: function () {
                    // Uncheck "select all" on every draw
                    $('#select-all').prop('checked', false);
                    updateDeleteSelectedButton();
                }
            });

            function getSelectedBillIds() {
                const ids = [];
                $('#bills-table tbody input.checkBill:checked').each(function () {
                    const nameAttr = this.getAttribute('name') || '';
                    // name pattern: checkBill[ID]
                    const match = nameAttr.match(/\[(\d+)\]/);
                    if (match) ids.push(parseInt(match[1], 10));
                });
                return ids;
            }

            function updateDeleteSelectedButton() {
                const count = getSelectedBillIds().length;
                const btn = document.getElementById('btn-delete-selected');
                btn.disabled = count === 0;
                const label = count > 0 ? `Delete Selected (${count})` : 'Delete Selected';
                // preserve icon while updating text
                btn.innerHTML = `<i class="icon-base ti tabler-trash icon-xs me-1"></i> ${label}`;
                btn.setAttribute('aria-label', label);
            }


            // Select/Deselect all
            $(document).on('change', '#select-all', function () {
                const checked = $(this).is(':checked');
                $('#bills-table tbody input.checkBill').prop('checked', checked);
                updateDeleteSelectedButton();
            });

            // Row checkbox change
            $(document).on('change', '#bills-table tbody input.checkBill', function () {
                // If any row unchecked, uncheck header
                if (!$(this).is(':checked')) {
                    $('#select-all').prop('checked', false);
                }
                updateDeleteSelectedButton();
            });

            // Bulk delete click
            $('#btn-delete-selected').on('click', function () {
                const ids = getSelectedBillIds();
                if (ids.length === 0) return;

                Swal.fire({
                    icon: 'warning',
                    title: 'Delete selected bills?',
                    html: `<div class="text-start">You are about to delete <strong>${ids.length}</strong> bill(s). This action cannot be undone.</div>`,
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#d33',
                    showLoaderOnConfirm: true,
                    preConfirm: async () => {
                        try {
                            const res = await fetch(`{{ route('admin.bills.bulk-destroy') }}`, {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrf,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({ ids })
                            });
                            if (!res.ok) throw new Error('Request failed');
                            return await res.json();
                        } catch (e) {
                            Swal.showValidationMessage(`Delete failed: ${e.message}`);
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed && result.value?.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted',
                            text: result.value.message || 'Bills deleted',
                            timer: 1200,
                            showConfirmButton: false
                        });
                        table.ajax.reload(null, false);
                    }
                });
            });

            // Single delete from action button (delegated)
            $(document).on('click', '.btn-delete-bill', function (e) {
                e.preventDefault();
                const id = $(this).data('id');
                const url = $(this).data('url'); // expected to be route('admin.bills.destroy', id)
                if (!id || !url) return;

                Swal.fire({
                    icon: 'warning',
                    title: 'Delete this bill?',
                    text: 'This action cannot be undone.',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#d33',
                    showLoaderOnConfirm: true,
                    preConfirm: async () => {
                        try {
                            const res = await fetch(url, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': csrf,
                                    'Accept': 'application/json',
                                }
                            });
                            if (!res.ok) throw new Error('Request failed');
                            return await res.json().catch(() => ({}));
                        } catch (e) {
                            Swal.showValidationMessage(`Delete failed: ${e.message}`);
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted',
                            timer: 1000,
                            showConfirmButton: false
                        });
                        table.ajax.reload(null, false);
                    }
                });
            });

            // Filters
            $('#btn-apply').on('click', () => table.ajax.reload());
            $('#btn-reset').on('click', function () {
                $('.select2').val('').trigger('change');
                $('#f-status').val('');
                table.ajax.reload();
            });
            

            // Payment modal via SweetAlert with auto-populated amount (based on due date)
            window.openPayModal = function (billId, billNo, dueDateStr, subTotalStr, totalStr) {
                const parseAmount = (s) => {
                    if (s === undefined || s === null) return NaN;
                    const num = parseFloat(String(s).toString().replace(/,/g, ''));
                    return isNaN(num) ? NaN : num;
                };

                const toDateOnly = (s) => {
                    if (!s) return null;
                    // Expecting yyyy-mm-dd; fallback to Date parsing
                    const d = new Date(s);
                    if (isNaN(d.getTime())) return null;
                    // Normalize to local midnight for comparison
                    return new Date(d.getFullYear(), d.getMonth(), d.getDate());
                };

                const today = new Date();
                const todayOnly = new Date(today.getFullYear(), today.getMonth(), today.getDate());

                const dueDate = toDateOnly(dueDateStr);
                const isPastDue = dueDate ? (todayOnly > dueDate) : false;

                const subTotal = parseAmount(subTotalStr);
                const total = parseAmount(totalStr);

                // Rule:
                // - If due date is passed -> use Total
                // - Else -> use Sub Total (without sub charges)
                let defaultAmount = 0;
                if (isPastDue && !isNaN(total)) {
                    defaultAmount = total;
                } else if (!isNaN(subTotal)) {
                    defaultAmount = subTotal;
                } else if (!isNaN(total)) {
                    // Fallback if subTotal missing
                    defaultAmount = total;
                }

                const todayIso = new Date().toISOString().slice(0, 10);
                Swal.fire({
                    title: `Record Payment — ${billNo}`,
                    html: `
                <div class="mb-2 text-start">
                    <label class="form-label">Amount</label>
                    <input id="swal-paid" type="number" step="0.01" min="0.01" class="form-control" value="${defaultAmount.toFixed ? defaultAmount.toFixed(2) : defaultAmount}">
                </div>
                <div class="text-start">
                    <label class="form-label">Payment Date</label>
                    <input id="swal-date" type="date" class="form-control" value="${todayIso}">
                </div>
            `,
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: 'Save Payment',
                    showLoaderOnConfirm: true,
                    preConfirm: async () => {
                        const paidVal = parseFloat(document.getElementById('swal-paid').value || '0');
                        const dateVal = document.getElementById('swal-date').value;
                        if (isNaN(paidVal) || paidVal <= 0) {
                            Swal.showValidationMessage('Please enter a valid amount (> 0).');
                            return false;
                        }
                        if (!dateVal) {
                            Swal.showValidationMessage('Please select a payment date.');
                            return false;
                        }
                        try {
                            const res = await fetch(`{{ url('admin/bills') }}/${billId}/pay`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                },
                                body: JSON.stringify({paid_amount: paidVal, payment_date: dateVal})
                            });
                            if (!res.ok) {
                                const text = await res.text();
                                throw new Error(text || 'Failed to record payment');
                            }
                        } catch (e) {
                            Swal.showValidationMessage(e.message || 'Payment failed');
                            return false;
                        }
                        return {ok: true};
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({icon: 'success', title: 'Payment recorded', timer: 1200, showConfirmButton: false});
                        table.ajax.reload(null, false);
                    }
                });
            }
            // Payment history modal via SweetAlert
            window.openHistoryModal = async function (billId, billNo) {
                try {
                    const res = await fetch(`{{ url('admin/bills') }}/${billId}/transactions`, {headers: {'Accept': 'application/json'}});
                    const json = await res.json();
                    if (!res.ok || !json.ok) throw new Error('Failed to load transactions');
                    const rows = (json.transactions || []).map(t => `
                <tr>
                    <td>${t.payment_date ?? ''}</td>
                    <td class="text-end">${Number(t.paid_amount || 0).toFixed(2)}</td>
                    <td class="text-end">${Number(t.due_amount || 0).toFixed(2)}</td>
                    <td class="text-end">${Number(t.total || 0).toFixed(2)}</td>
                    <td>${t.is_paid ? 'Yes' : 'No'}</td>
                </tr>
            `).join('');
                    const html = `
                <div class="table-responsive text-start">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th class="text-end">Paid</th>
                                <th class="text-end">Due</th>
                                <th class="text-end">Total</th>
                                <th>Is Paid</th>
                            </tr>
                        </thead>
                        <tbody>${rows || '<tr><td colspan="5" class="text-center text-muted">No payments yet.</td></tr>'}</tbody>
                    </table>
                </div>`;
                    Swal.fire({title: `Payment History — ${billNo}`, html, width: 700, confirmButtonText: 'Close'});
                } catch (e) {
                    Swal.fire({icon: 'error', title: 'Failed to load history'});
                }
            }

            function renderSummary(summary) {
                const el = document.getElementById('summary');
                if (!summary) {
                    el.style.display = 'none';
                    el.innerHTML = '';
                    return;
                }
                el.style.display = '';
                const metrics = [
                    {title: 'Total Bills', value: summary.total_bills},
                    {title: 'Paid', value: summary.total_paid},
                    {title: 'Partial', value: summary.total_partial},
                    {title: 'Unpaid', value: summary.total_unpaid},
                    // {title: 'Bill Total', value: Number(summary.sum_bill_total || 0).toFixed(2)},
                    // {title: 'Sub Total', value: Number(summary.sum_sub_total || 0).toFixed(2)},
                    // {title: 'Sub Charges', value: Number(summary.sum_sub_charges || 0).toFixed(2)},
                    {title: 'Grand Total', value: Number(summary.sum_total || 0).toFixed(2)},
                    {title: 'Total Paid', value: Number(summary.sum_paid_total || 0).toFixed(2)},
                    {title: 'Total Dues', value: Number(summary.sum_due_amount || 0).toFixed(2)},

                ];
                const cards = metrics.map(m => `
            <div class="col-6 col-md-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body py-3">
                        <div class="small text-muted">${m.title}</div>
                        <div class="fs-5 fw-semibold">${m.value}</div>
                    </div>
                </div>
            </div>
        `).join('');
                el.innerHTML = `<div class="row g-3">${cards}</div>`;
            }

            table.on('xhr.dt', function () {
                const json = table.ajax.json();
                // Add quick pay buttons after draw
                setTimeout(() => {
                    document.querySelectorAll('#bills-table tbody tr').forEach((tr, idx) => {
                        const row = table.row(tr).data();
                        if (!row) return;
                        const actionsCell = tr.querySelector('td:last-child');
                        if (!actionsCell) return;
                        const billId = row.id || row.DT_RowId || null;
                        if (!billId) return;

                        // Clean previously injected buttons to avoid duplicates on redraw
                        actionsCell.querySelectorAll('.btn-pay, .btn-history').forEach(el => el.remove());

                        function toActive(v) {
                            return v === 1 || v === '1' || v === true;
                        }
                        function toBool(v) {
                            return v === true || v === 1 || v === '1';
                        }
                        function toNum(v) {
                            if (v === null || v === undefined) return 0;
                            const s = String(v).replace(/,/g, '');
                            const n = parseFloat(s);
                            return isNaN(n) ? 0 : n;
                        }

                        const isActive = toActive(row?.is_active_raw ?? row?.is_active);
                        const isPaid = toBool(row?.is_paid);
                        const due = toNum(row?.due_amount_raw ?? row?.due_amount);

                        // 1) History link shown only when bill is paid
                        if (isPaid) {
                            const aHist = document.createElement('a');
                            aHist.href = 'javascript:void(0)';
                            aHist.title = 'History';
                            aHist.className = 'btn btn-sm btn-icon btn-outline-info btn-history  m-2';
                            aHist.innerHTML = '<i class="icon-base ti tabler-history icon-22px"></i>';
                            aHist.addEventListener('click', () => openHistoryModal(billId, row.bill_number));
                            actionsCell.appendChild(aHist);
                        }

                        // 2) Pay link shown only when bill is active (is_active == 1) and due_amount > 0
                        if (isActive && due > 0) {
                            const aPay = document.createElement('a');
                            aPay.href = 'javascript:void(0)';
                            aPay.title = 'Pay';
                            aPay.className = 'btn btn-icon btn-outline-success btn-pay  m-2';
                            aPay.innerHTML = '<i class="icon-base ti tabler-cash icon-22px"></i>';
                            aPay.addEventListener('click', () => openPayModal(
                                billId,
                                row.bill_number,
                                // Keep passing amounts/dates as your modal expects
                                row?.due_date,
                                toNum(row?.sub_total),
                                toNum(row?.total)
                            ));
                            actionsCell.appendChild(aPay);
                        }
                    });
                }, 0);
                renderSummary(json && json.summary ? json.summary : null);
            });

            document.getElementById('btn-apply').addEventListener('click', function () {
                table.ajax.reload();
            });
            
            document.getElementById('btn-reset').addEventListener('click', function () {
                $('#f-allotee').val('');
                $('#f-sector').val('');
                $('#f-size').val('');
                $('#f-bank').val('');
                $('#f-year').val('');
                $('#f-from').val('');
                $('#f-to').val('');
                $('#f-active').val('');
                $('#f-status').val('');
                table.ajax.reload();
            });


        });
    </script>

    @include($viewFolder .'._add_page')

@endpush
