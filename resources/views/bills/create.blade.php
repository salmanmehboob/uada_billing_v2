@php use App\Helpers\GeneralHelper; @endphp
@extends('layouts.admin.app')
@push('page_css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}"/>

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}"/>

@endpush
@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Users List Table -->
        <div class="card">

            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">{{$title}} Setup</h5>

            </div>

            <div class="row gy-4 gx-6 mb-6">
                <div class="card-body">

                    <form id="bill-create-form" method="POST" action="{{ route('admin.bills.store') }}">
                        @csrf

                        <div class="row">

                            <div class="col-md-4 mb-6">
                                <label for="allotee_id" class="form-label">Allotee</label>
                                <select id="allotee_id" name="allotee_id"
                                        class="select2 form-select @error('allotee_id') is-invalid @enderror"
                                        data-allow-clear="true"
                                        data-placeholder="Select Allotee">
                                    <option value=""></option>
                                    @foreach($allotees as $key =>  $row)
                                        <option value="{{$row->id}}" {{ old('allotee_id') == $row->id ? 'selected' : '' }}>
                                            {{$row->name . ' ' . $row->plot_no .' ' . $row->size->name . ' ' . $row->sector->name}}
                                        </option>
                                    @endforeach
                                </select>
                                @error('allotee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-6">
                                <label for="bank_id" class="form-label">Bank</label>
                                <select id="bank_id" name="bank_id"
                                        class="select2 form-select @error('bank_id') is-invalid @enderror"
                                        data-allow-clear="true"
                                        data-placeholder="Select Bank">
                                    <option value=""></option>
                                    @foreach($banks as $key =>  $row)
                                        <option value="{{$row->id}}" {{ old('bank_id') == $row->id ? 'selected' : '' }}>
                                            {{$row->name .' '. $row->branch.' ' . $row->account_no}}
                                        </option>
                                    @endforeach
                                </select>
                                @error('bank_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-6">
                                <label for="year" class="form-label">Financial Year</label>
                                <select id="year" name="year"
                                        class="select2 form-select @error('year') is-invalid @enderror"
                                        data-allow-clear="true"
                                        data-placeholder="Select Financial Year">
                                    <option value=""></option>
                                    @php
                                        $currentYear = date('Y');
                                        $startYear = $currentYear - 29;
                                         $currentMonth = date('m');
                                            if ($currentMonth > 6) {
                                                $currentYear++;
                                                $startYear++;
                                            }
                                    @endphp
                                    @for ($year = $currentYear; $year >= $startYear; $year--)
                                        <option value="{{ $year }}" {{ (string)old('year') === (string)$year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endfor
                                </select>
                                @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-6">
                                <label for="from_month" class="form-label">From Month</label>
                                <select id="from_month" name="from_month"
                                        class="select2 form-select @error('from_month') is-invalid @enderror"
                                        data-allow-clear="true"
                                        data-placeholder="Select Month">
                                    <option value=""></option>
                                    @foreach($months as $key =>  $row)
                                        <option value="{{$row->id}}" {{ old('from_month') == $row->id ? 'selected' : '' }}>{{$row->name}}</option>
                                    @endforeach
                                </select>
                                @error('from_month')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-6">
                                <label for="to_month" class="form-label">To Month</label>
                                <select id="to_month" name="to_month"
                                        class="select2 form-select @error('to_month') is-invalid @enderror"
                                        data-allow-clear="true"
                                        data-placeholder="Select Month">
                                    <option value=""></option>
                                    @foreach($months as $key =>  $row)
                                        <option value="{{$row->id}}" {{ old('to_month') == $row->id ? 'selected' : '' }}>{{$row->name}}</option>
                                    @endforeach
                                </select>
                                @error('to_month')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            @foreach($charges as $key =>  $row)
                                <div class="col-md-6 mb-6">
                                    <label class="form-label" for="charge-name-{{$key}}">{{$row->name}}</label>
                                    <input type="hidden" name="charge_id[]" value="{{$row->id}}">
                                    <input type="hidden" name="charge_name[]" value="{{$row->name}}">
                                    <input
                                            type="text"
                                            class="form-control @error('charge_amount.'.$key) is-invalid @enderror"
                                            id="charge-name-{{$key}}"
                                            placeholder="Enter {{$row->name}} amount"
                                            name="charge_amount[]"
                                            value="{{ old('charge_amount.'.$key) }}"
                                            required/>
                                    @error('charge_amount.'.$key)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach

                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-6  ">
                                <label for="issue_date" class="form-label">Issue Date</label>
                                <input type="text"
                                       class="form-control flatpickr-date @error('issue_date') is-invalid @enderror"
                                       placeholder="YYYY-MM-DD" id="issue_date" name="issue_date"
                                       value="{{ old('issue_date', GeneralHelper::currentMonthStart()) }}"/>
                                @error('issue_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-6  ">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="text"
                                       class="form-control flatpickr-date @error('due_date') is-invalid @enderror"
                                       placeholder="YYYY-MM-DD" id="due_date" name="due_date"
                                       value="{{ old('due_date', GeneralHelper::currentMonthEnd()) }}"/>
                                @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-6">
                                <label for="sub_charges" class="form-label">Sub Charges (Manual)</label>
                                <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        class="form-control"
                                        id="sub_charges"
                                        name="sub_charges"
                                        value="{{GeneralHelper::getSettingValue('sub_charges')}}"
                                        placeholder="Enter sub charges to override (optional)"/>
                                <small class="text-muted">Leave blank to auto-calculate by percentage.</small>
                            </div>
                        </div>

                        <!-- New: Arrears (becomes editable after Calculate Bill) -->
                        <div class="col-md-4 mb-6">
                            <label for="arrears" class="form-label">Arrears</label>
                            <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="form-control @error('arrears') is-invalid @enderror"
                                    id="arrears"
                                    name="arrears"
                                    value="{{ old('arrears') }}"
                                    placeholder="0.00"
                                    readonly
                            />
                            <small id="arrearsHelp" class="text-muted">Click “Calculate Bill” to load arrears and make
                                it editable.</small>
                            @error('arrears')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <!-- Calculate Button and Preview -->
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-3">
                                <button type="button" id="btn-calc-bill" class="btn btn-outline-primary">
                                    Calculate Bill
                                </button>
                                <span id="calc-spinner" class="ms-2" style="display:none;">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                            </div>
                        </div>

                        <div id="calc-result" class="border rounded p-3 mb-4" style="display:none;">
                            <h6 class="mb-3">Bill Calculation Preview</h6>

                            <!-- Per-charge breakdown -->
                            <div class="table-responsive">
                                <table class="table table-sm table-striped mb-3">
                                    <thead>
                                    <tr>
                                        <th>Charge</th>
                                        <th class="text-end">Amount</th>
                                        <th class="text-center">×</th>
                                        <th class="text-end">Total Months</th>
                                        <th class="text-center">=</th>
                                        <th class="text-end">Line Total</th>
                                    </tr>
                                    </thead>
                                    <tbody id="r-breakdown"></tbody>
                                    <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-end">Bill Total</th>
                                        <th class="text-end" id="r-bill-total">0.00</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Summary -->
                            <div class="row g-2">
                                <div class="col-md-4"><strong>Total Months:</strong> <span id="r-total-months">0</span>
                                </div>
                                <div class="col-md-4"><strong>Arrears:</strong> <span id="r-arrears">0.00</span></div>
                                <div class="col-md-4"><strong>Sub Total (Bill Total + Arrears):</strong> <span
                                            id="r-base-total">0.00</span></div>
                            </div>
                            <div class="row g-2 mt-2">
                                <div class="col-md-4">
                                    <strong>Sub Charges (%):</strong>
                                    <span id="r-sub-percent">0</span>% = <span id="r-sub-charges">0.00</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Total Payable After Due Date:</strong> <span id="r-total">0.00</span>
                                </div>
                            </div>

                            <hr/>
                            <div>
                                <strong>Previous Pending Bills</strong>
                                <div id="r-prev-list" class="mt-2"></div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="submit" id="saveBtn"
                                        class="btn btn-primary" style="display:none;">Save
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <!-- /Account -->
        </div>
    </div>

    <!-- / Content -->
@endsection
@push('page_js')
    <script src="{{ asset('assets/vendor/libs/@form-validation/popular.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/auto-focus.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        (function () {
            const fields = ['allotee_id', 'year', 'from_month', 'to_month'];
            const form = document.getElementById('bill-create-form');
            const saveBtn = document.getElementById('saveBtn');

            async function checkPeriod() {
                const allotee_id = document.getElementById('allotee_id')?.value || '';
                const year = document.getElementById('year')?.value || '';
                const from_month = document.getElementById('from_month')?.value || '';
                const to_month = document.getElementById('to_month')?.value || '';

                if (!allotee_id || !year || !from_month || !to_month) {
                    return {ok: true, exists: false};
                }

                const res = await fetch('{{ route('admin.bills.check-period') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({allotee_id, year, from_month, to_month})
                });

                const json = await res.json();
                return json;
            }

            async function handleCheck(showAlert = true) {
                try {
                    console.log('Checking period...');
                    const result = await checkPeriod();
                    if (result && result.ok && result.exists) {
                        const conflicts = (result.conflicts || []).map(c => {
                            const fromLabel = c.from_month_name || c.from_month;
                            const toLabel = c.to_month_name || c.to_month;
                            return `#${c.bill_number} (${c.year}, ${fromLabel} - ${toLabel})`;
                        }).join('<br>');
                        if (showAlert) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Bill already generated',
                                html: 'A bill for this Allotee, Year and Month range already exists:<br><br>' + conflicts,
                                confirmButtonText: 'OK'
                            });
                        }
                        return true; // has conflicts
                    }
                    return false;
                } catch (e) {
                    console.error('Period check failed', e);
                    return false;
                }
            }

            // Expose for reuse (e.g., Calculate button also triggers this)
            window.billPeriod = {
                checkPeriod,
                handleCheck
            };

            // Trigger check on field changes
            fields.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    console.log('Adding change listener for', id);
                    el.addEventListener('change', () => handleCheck(true));
                }
            });

            // Guard submission
            if (form) {
                form.addEventListener('submit', async function (e) {
                    const hasConflict = await handleCheck(true);
                    if (hasConflict) {
                        e.preventDefault();
                    }
                });
            }
        })();
    </script>
    <script type="text/javascript">
        (function () {
            const btn = document.getElementById('btn-calc-bill');
            const spinner = document.getElementById('calc-spinner');
            const resultBox = document.getElementById('calc-result');
            const saveBtn = document.getElementById('saveBtn');
            const arrearsEl = document.getElementById('arrears');
            const arrearsHelp = document.getElementById('arrearsHelp');

            function csrfToken() {
                const el = document.querySelector('meta[name="csrf-token"]');
                return el ? el.getAttribute('content') : '';
            }

            function collectData() {
                const allotee_id = document.getElementById('allotee_id')?.value || '';
                const year = document.getElementById('year')?.value || '';
                const from_month = document.getElementById('from_month')?.value || '';
                const to_month = document.getElementById('to_month')?.value || '';

                const chargeNames = Array.from(document.querySelectorAll('input[name="charge_name[]"]'))
                    .map(i => (i.value ?? '').toString());
                const chargeAmounts = Array.from(document.querySelectorAll('input[name="charge_amount[]"]'))
                    .map(i => {
                        const v = parseFloat((i.value || '').toString().replace(/,/g, ''));
                        return isNaN(v) ? 0 : v;
                    });

                // Manual sub charges override (optional)
                const subChargesEl = document.getElementById('sub_charges');
                const sub_charges = subChargesEl && subChargesEl.value !== ''
                    ? (parseFloat(subChargesEl.value) || 0)
                    : null;

                return {
                    allotee_id,
                    year,
                    from_month,
                    to_month,
                    charge_name: chargeNames,
                    charge_amount: chargeAmounts,
                    sub_charges // nullable
                };
            }

            function showErrors(errors) {
                let msg = 'Please fix the following:\n';
                Object.keys(errors || {}).forEach(k => {
                    msg += `- ${k}: ${errors[k].join(', ')}\n`;
                });
                alert(msg);
            }

            function renderResult(data) {
                // Per-charge table
                const tbody = document.getElementById('r-breakdown');
                const rows = (data.charges_breakdown || []).map(row => {
                    const name = String(row.name || '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                    const amt = Number(row.amount || 0);
                    const months = Number(row.total_months || 0);
                    const line = Number(row.line_total || 0);
                    return `
                        <tr>
                            <td>${name}</td>
                            <td class="text-end">${amt.toFixed(2)}</td>
                            <td class="text-center">×</td>
                            <td class="text-end">${months}</td>
                            <td class="text-center">=</td>
                            <td class="text-end">${line.toFixed(2)}</td>
                        </tr>
                    `;
                }).join('');
                tbody.innerHTML = rows || '<tr><td colspan="6" class="text-center text-muted">No charges entered.</td></tr>';

                // Totals
                document.getElementById('r-total-months').textContent = data.total_months ?? 0;
                document.getElementById('r-bill-total').textContent = Number(data.bill_total ?? 0).toFixed(2);
                document.getElementById('r-arrears').textContent = Number(data.arrears ?? 0).toFixed(2);
                document.getElementById('r-base-total').textContent = Number(data.sub_total ?? data.base_total ?? 0).toFixed(2);

                // Show percent or 'manual' badge depending on source
                const subPercentEl = document.getElementById('r-sub-percent');
                const subChargesEl = document.getElementById('r-sub-charges');

                subPercentEl.textContent = data.sub_charges_percent ?? 0;

                subChargesEl.textContent = Number(data.sub_charges ?? 0).toFixed(2);

                document.getElementById('r-total').textContent = Number((data.sub_total ?? data.base_total ?? 0) + (data.sub_charges ?? 0)).toFixed(2);

                // Make arrears editable with calculated default
                if (arrearsEl) {
                    const calcArrears = Number(data.arrears ?? 0);
                    arrearsEl.value = calcArrears.toFixed(2);
                    arrearsEl.readOnly = false;
                    arrearsEl.classList.add('border-primary');
                    if (arrearsHelp) {
                        arrearsHelp.textContent = 'You can adjust arrears if needed before saving.';
                    }
                }

                // Previous list
                const prev = data.previous || [];
                const list = document.getElementById('r-prev-list');
                if (prev.length === 0) {
                    list.innerHTML = '<em>No previous unpaid bills found.</em>';
                } else {
                    list.innerHTML = prev.map(p => {
                        const safeDuration = (p.duration || '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                        const safeBillNo = (p.bill_number || '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                        return `<div class="small">
                            <strong>#${safeBillNo}</strong> — ${safeDuration} — ${p.year} — Due: <strong>${Number(p.due_amount || 0).toFixed(2)}</strong>
                        </div>`;
                    }).join('');
                }

                resultBox.style.display = 'block';
            }

            async function calc() {
                const payload = collectData();
                if (!payload.allotee_id || !payload.year || !payload.from_month || !payload.to_month) {
                    alert('Please select Allotee, Year, From Month and To Month before calculating.');
                    return;
                }

                // First run check-period and show alert if duplicates exist, then proceed
                try {
                    if (window.billPeriod && typeof window.billPeriod.handleCheck === 'function') {
                        await window.billPeriod.handleCheck(true);
                    }
                } catch (e) {
                    console.warn('check-period before calculate failed', e);
                }

                spinner.style.display = 'inline-block';
                try {
                    const res = await fetch('{{ route('admin.bills.calculate') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken(),
                        },
                        body: JSON.stringify(payload),
                    });

                    const json = await res.json();
                    if (!res.ok || !json.ok) {
                        if (json && json.errors) {
                            showErrors(json.errors);
                        } else {
                            alert('Failed to calculate bill. Please try again.');
                        }
                        return;
                    }

                    renderResult(json.data || {});
                    // Show submit after successful calculation render
                    if (saveBtn) saveBtn.style.display = '';
                } catch (e) {
                    console.error(e);
                    alert('Network error while calculating bill.');
                } finally {
                    spinner.style.display = 'none';
                }
            }

            if (btn) {
                btn.addEventListener('click', calc);
            }
        })();
    </script>




    @include($viewFolder .'._add_page')

@endpush
