<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Helpers\GeneralHelper;
use App\Models\Allotee;
use App\Models\Bank;
use App\Models\Bill;
use App\Models\BillCharge;
use App\Models\BillTransaction;
use App\Models\Charge;
use App\Models\Month;
use App\Models\Sector;
use App\Models\Size;
use App\Models\Year;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;
use Yajra\DataTables\DataTables;

#[AllowDynamicProperties]
class BillController extends Controller
{
    protected $route;
    protected $viewFolder;

    public function __construct()
    {
        $this->title = 'Bill';
        $this->route = 'admin.bills';
        $this->viewFolder = 'bills';
    }

    public function index(Request $request)
    {
        $data['title'] = $this->title;
        $data['route'] = $this->route;
        $data['viewFolder'] = $this->viewFolder;

        if ($request->ajax()) {
            $bills = Bill::with('size', 'sector', 'allotee', 'fromMonth', 'toMonth', 'transaction');

            // Build a base query for summary that mirrors the table filters
            $summaryBase = Bill::with('transaction');

            // Helper to apply filters to a query builder
            $applyFilters = function ($query) use ($request) {
                if ($request->filled('sector_id')) {
                    $query->where('sector_id', $request->input('sector_id'));
                }
                if ($request->filled('size_id')) {
                    $query->where('size_id', $request->input('size_id'));
                }
                if ($request->filled('allotee_id')) {
                    $query->where('allotee_id', $request->input('allotee_id'));
                }
                if ($request->filled('bank_id')) {
                    $query->where('bank_id', $request->input('bank_id'));
                }
                if ($request->filled('year')) {
                    $query->where('year', $request->input('year'));
                }
                if ($request->filled('from_month')) {
                    $query->where('from_month', $request->input('from_month'));
                }
                if ($request->filled('to_month')) {
                    $query->where('to_month', $request->input('to_month'));
                }
                if ($request->filled('is_active')) {
                    $isActive = $request->input('is_active');
                    if ($isActive === '1' || $isActive === 1) {
                        $query->where('is_active', 1);
                    } elseif ($isActive === '0' || $isActive === 0) {
                        $query->where('is_active', 0);
                    }
                }
                if ($request->filled('status_filter')) {
                    $status = $request->input('status_filter');
                    if ($status === 'paid') {
                        $query->where('is_paid', 1)
                            ->where(function ($q) {
                                $q->whereHas('transaction', function ($tq) {
                                    $tq->where('due_amount', '<=', 0);
                                })->orWhereDoesntHave('transaction');
                            });
                    } elseif ($status === 'unpaid') {
                        $query->where('is_paid', 0);
                    } elseif ($status === 'partial') {
                        $query->where('is_paid', 1)
                            ->whereHas('transaction', function ($tq) {
                                $tq->where('due_amount', '>', 0);
                            });
                    }
                }
            };

            // Apply filters to summary base
            $applyFilters($summaryBase);

            // Compute summary metrics for the filtered set
            $totalBills = (clone $summaryBase)->count();
            $totalActive = (clone $summaryBase)->where('is_active', 1)->count();
            $totalInactive = (clone $summaryBase)->where('is_active', 0)->count();
            $totalPaid = (clone $summaryBase)->where('is_paid', 1)
                ->where(function ($q) {
                    $q->whereHas('transaction', function ($tq) {
                        $tq->where('due_amount', '<=', 0);
                    })->orWhereDoesntHave('transaction');
                })->count();
            $totalPartial = (clone $summaryBase)->where('is_paid', 1)
                ->whereHas('transaction', function ($tq) {
                    $tq->where('due_amount', '>', 0);
                })->count();
            $totalUnpaid = (clone $summaryBase)->where('is_paid', 0)->count();

            $sumBillTotal = (clone $summaryBase)->sum('bill_total');
            $sumSubTotal = (clone $summaryBase)->sum('sub_total');
            $sumSubCharges = (clone $summaryBase)->sum('sub_charges');
            $sumTotal = (clone $summaryBase)->sum('total');
            $sumDueAmount = (clone $summaryBase)->sum('due_amount');

            $summary = [
                'total_bills' => $totalBills,
                'total_active' => $totalActive,
                'total_inactive' => $totalInactive,
                'total_paid' => $totalPaid,
                'total_partial' => $totalPartial,
                'total_unpaid' => $totalUnpaid,
                'sum_bill_total' => round((float)$sumBillTotal, 2),
                'sum_sub_total' => round((float)$sumSubTotal, 2),
                'sum_sub_charges' => round((float)$sumSubCharges, 2),
                'sum_total' => round((float)$sumTotal, 2),
                'sum_due_amount' => round((float)$sumDueAmount, 2),
                'sum_paid_total' => round(max(0, (float)$sumTotal - (float)$sumDueAmount), 2),

            ];

            return DataTables::of($bills)
                ->filter(function ($instance) use ($request) {
                    // IMPORTANT: always pull the string value, not the whole search array
                    $searchTerm = (string)$request->input('search.value', '');

                    if ($searchTerm !== '') {
                        $instance->where(function ($query) use ($searchTerm) {
                            $query->whereHas('allotee', function ($query) use ($searchTerm) {
                                $query->where('plot_no', 'like', '%' . $searchTerm . '%')
                                    ->orWhere('id', $searchTerm);
                            })
                                ->orWhereHas('allotee', function ($query) use ($searchTerm) {
                                    $query->where('name', 'like', '%' . $searchTerm . '%');
                                })
                                ->orWhereHas('sector', function ($query) use ($searchTerm) {
                                    $query->where('name', 'like', '%' . $searchTerm . '%');
                                })
                                ->orWhereHas('size', function ($query) use ($searchTerm) {
                                    $query->where('name', 'like', '%' . $searchTerm . '%');
                                })
                                ->orWhere('bill_number', 'like', '%' . $searchTerm . '%');
                        });
                    }

                    if ($request->filled('sector_id')) {
                        $instance->where('sector_id', $request->input('sector_id'));
                    }
                    if ($request->filled('size_id')) {
                        $instance->where('size_id', $request->input('size_id'));
                    }
                    if ($request->filled('allotee_id')) {
                        $instance->where('allotee_id', $request->input('allotee_id'));
                    }
                    if ($request->filled('bank_id')) {
                        $instance->where('bank_id', $request->input('bank_id'));
                    }
                    if ($request->filled('year')) {
                        $instance->where('year', $request->input('year'));
                    }
                    if ($request->filled('from_month')) {
                        $instance->where('from_month', $request->input('from_month'));
                    }
                    if ($request->filled('to_month')) {
                        $instance->where('to_month', $request->input('to_month'));
                    }
                    if ($request->filled('is_active')) {
                        $isActive = $request->input('is_active');
                        if ($isActive === '1' || $isActive === 1) {
                            $instance->where('is_active', 1);
                        } elseif ($isActive === '0' || $isActive === 0) {
                            $instance->where('is_active', 0);
                        }
                    }
                    if ($request->filled('status_filter')) {
                        $status = $request->input('status_filter');
                        if ($status === 'paid') {
                            $instance->where('is_paid', 1)
                                ->where(function ($q) {
                                    $q->whereHas('transaction', function ($tq) {
                                        $tq->where('due_amount', '<=', 0);
                                    })->orWhereDoesntHave('transaction');
                                });
                        } elseif ($status === 'unpaid') {
                            $instance->where('is_paid', 0);
                        } elseif ($status === 'partial') {
                            $instance->where('is_paid', 1)
                                ->whereHas('transaction', function ($tq) {
                                    $tq->where('due_amount', '>', 0);
                                });
                        }
                    }
                })
                ->addColumn('is_active_raw', function ($bill) {
                    return (int)$bill->is_active;
                })
                ->addColumn('can_pay', function ($bill) {
                    // Normalize to a strict boolean and then to 0/1 for the datatable
                    $isActive = (int)($bill->is_active ?? 0) === 1;

                    // Prefer transaction if present
                    if ($bill->transaction) {
                        $due = (float)($bill->transaction->due_amount ?? 0);
                        $paid = (int)($bill->transaction->is_paid ?? 0) === 1;

                        // Fully paid if paid flag true OR due is zero/negative
                        $canPay = $isActive && !($paid || $due <= 0);
                        return $canPay ? 1 : 0;
                    }

                    // Fallback to bill columns (when no transaction created yet)
                    $billPaid = (int)($bill->is_paid ?? 0) === 1;
                    $billDue = (float)($bill->due_amount ?? 0);

                    $canPay = $isActive && !($billPaid || $billDue <= 0);
                    return $canPay ? 1 : 0;
                })
                ->addColumn('checkBill', function ($bill) {
                    $id = (int)$bill->id;
                    // return a plain string
                    return '<input type="checkbox" class="form-check-right checkBill" name="checkBill[' . $id . ']" value="1">';
                })
                ->addColumn('consumer_id', function ($bill) {
                    return $bill->allotee ? (string)$bill->allotee->id : '';
                })
                ->addColumn('bill_number', function ($bill) {
                    return (string)$bill->bill_number;
                })
                ->addColumn('name', function ($bill) {
                    $parts = [
                        $bill->allotee->name ?? '',
                        $bill->allotee->plot_no ?? '',
                        $bill->sector->name ?? '',
                        $bill->size->name ?? '',
                    ];
                    return trim(implode(' ', array_filter($parts, fn($p) => $p !== '')));
                })
                ->addColumn('year', function ($bill) {
                    return (string)$bill->year;
                })
                ->addColumn('duration', function ($bill) {
                    $from = $bill->fromMonth->name ?? '';
                    $to = $bill->toMonth->name ?? '';
                    return $from !== '' || $to !== '' ? ($from . '-' . $to) : '';
                })
                ->addColumn('total', function ($bill) {
                    return $bill->total !== null ? number_format((float)$bill->total, 2) : '';
                })
                ->addColumn('sub_charges', function ($bill) {
                    return $bill->sub_charges !== null ? number_format((float)$bill->sub_charges, 2) : '';
                })
                ->addColumn('sub_total', function ($bill) {
                    return $bill->sub_total !== null ? number_format((float)$bill->sub_total, 2) : '';
                })
                ->addColumn('due_amount', function ($bill) {
                    return $bill->due_amount ? number_format((float)$bill->due_amount, 2) : '';
                })
                ->addColumn('is_active', function ($bill) {
                    return (int)$bill->is_active === 1
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-warning">Inactive</span>';
                })
                ->addColumn('status', function ($bill) {
                    if (isset($bill->due_amount) && $bill->due_amount > 0 && (int)$bill->is_paid === 1) {
                        return '<span class="badge bg-warning">Partially Paid</span>';
                    }
                    return (int)$bill->is_paid === 1
                        ? '<span class="badge bg-success">Paid</span>'
                        : '<span class="badge bg-danger">Unpaid</span>';
                })
                ->addColumn('action', function ($bill) {
                    $editUrl = route('admin.bills.edit', $bill->id);
                    $viewUrl = route('admin.bills.show', $bill->id);

                    $hideClass = '';
                    if ((int)$bill->is_paid === 1 || ((int)$bill->is_paid === 0 && (int)$bill->is_active === 0)) {
                        $hideClass = 'd-none';
                    }

                    $html = '<div >';
                    $html .= '<a title="View" href="' . htmlspecialchars($viewUrl, ENT_QUOTES) . '" class="btn btn-sm btn-icon btn-outline-success btn-view m-2 "><i class="icon-base ti tabler-eye icon-22px"></i></a>';
                    $html .= '<a title="Edit" href="' . htmlspecialchars($editUrl, ENT_QUOTES) . '" class="btn btn-sm btn-icon btn-outline-primary btn-edit  m-2 ' . $hideClass . '"><i class="icon-base ti tabler-edit icon-22px"></i></a>';

                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['checkBill', 'is_active', 'status', 'action'])
                ->setRowId('id')
                ->with(['summary' => $summary])
                ->make(true);
        }

        // Non-AJAX: provide dropdown data for filters
        $data['sectors'] = Sector::all();
        $data['sizes'] = Size::all();
        $data['banks'] = Bank::all();
        $data['months'] = Month::all();
        $data['allotees'] = Allotee::with(['sector', 'size'])->orderBy('name')->get();
        // Years: prefer Year model if it exists; fallback to distinct years from bills
        if (class_exists('App\\Models\\Year')) {
            try {
                $data['years'] = Year::orderBy('year', 'desc')->pluck('year');
            } catch (Throwable $e) {
                $data['years'] = Bill::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');
            }
        } else {
            $data['years'] = Bill::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');
        }
        return view($this->viewFolder . '.index', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'allotee_id' => 'required|exists:allotees,id',
            'bank_id' => 'required|exists:banks,id',
            'year' => 'required|integer',
            'from_month' => 'required|integer|min:1|max:12',
            'to_month' => 'required|integer|min:1|max:12',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'charge_id' => 'required|array',
            'charge_id.*' => 'nullable|exists:charges,id',
            'charge_name' => 'required|array',
            'charge_name.*' => 'nullable|string',
            'charge_amount' => 'required|array',
            'charge_amount.*' => 'nullable|numeric|min:0',
        ];

        $validated = Validator::make($request->all(), $rules)->validate();

        // Server-side guard against duplicate period bills
        $periodCheck = $this->checkPeriod(new Request([
            'allotee_id' => $validated['allotee_id'],
            'year' => $validated['year'],
            'from_month' => $validated['from_month'],
            'to_month' => $validated['to_month'],
        ]));
        if ($periodCheck->getStatusCode() === 200) {
            $payload = $periodCheck->getData(true);
            if (!empty($payload['exists'])) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'from_month' => 'Bill already generated for the selected Allotee/Year/Months.',
                        'to_month' => 'Conflicts with: ' . collect($payload['conflicts'])->pluck('bill_number')->implode(', ')
                    ]);
            }
        }


        DB::beginTransaction();
        try {

            $allotee = Allotee::with(['sector', 'size'])->findOrFail($validated['allotee_id']);
            $year = (int)$validated['year'];
            $fromMonth = (int)$validated['from_month'];
            $toMonth = (int)$validated['to_month'];

            // total months (inclusive; supports wrap-around across year end)
            if ($toMonth >= $fromMonth) {
                $totalMonths = ($toMonth - $fromMonth) + 1;
            } else {
                $totalMonths = ((12 - $fromMonth) + 1) + $toMonth;
            }
            if ($totalMonths < 1) {
                $totalMonths = 1;
            }

            // Pair ids, names and amounts; compute per-line totals
            $ids = $validated['charge_id'] ?? [];
            $names = $validated['charge_name'] ?? [];
            $amounts = collect($validated['charge_amount'] ?? [])->map(fn($v) => (float)($v ?? 0))->values();

            $len = min(count($ids), count($names), $amounts->count());
            $lines = [];
            for ($i = 0; $i < $len; $i++) {
                $amount = (float)$amounts[$i];
                $lineTotal = round($amount * $totalMonths, 2);
                $lines[] = [
                    'charge_id' => (int)($ids[$i] ?? null),
                    'name' => (string)($names[$i] ?? ''),
                    'amount' => $amount,
                    'line_total' => $lineTotal,
                ];
            }


            // bill_total
            $billTotal = round(collect($lines)->sum('line_total'), 2);

//            // arrears from previous unpaid/partially paid bills
            $previousBills = Bill::with(['transaction', 'fromMonth', 'toMonth'])
                ->where('allotee_id', $allotee->id)
                ->where('due_amount', '>', 0)
                ->where('is_active', 1)
                ->orderBy('id', 'desc')
                ->get();
//
//
//            $arrears = 0.0;
//            foreach ($previousBills as $pb) {
//                if ($pb->transaction && $pb->transaction->due_amount > 0) {
//                    $arrears += (float)$pb->transaction->due_amount;
//                } elseif ((int)$pb->is_paid === 0) {
//                    $arrears += (float)($pb->total ?? $pb->sub_total ?? $pb->bill_total ?? 0);
//                }
//            }
//            $arrears =


            // Gather arrears for this allotee: field + previous pending bills
            $arrearsFromProfile = (float)($allotee->arrears ?? 0);
            $arrearsFromBills = (float)Bill::where('allotee_id', $allotee->id)
                ->where('due_amount', '>', 0)
                ->where('is_active', 1)
                ->sum('due_amount');
            $arrears = round($arrearsFromProfile + $arrearsFromBills, 2);
            

            // Deactivate all previous unpaid/partially paid bills so they can't be edited further
            $idsToDeactivate = $previousBills->pluck('id')->filter()->values();
            if ($idsToDeactivate->isNotEmpty()) {
                Bill::whereIn('id', $idsToDeactivate)->update(['is_active' => 0]);
            }

            // base = bill_total + arrears
            $baseTotal = round($billTotal + $arrears, 2);

            // sub charges from settings (percentage)
//            $percent    = (float) (GeneralHelper::getSettingValue('sub_charges_percent') ?? 0);
            $percent = (float)($request->input('sub_charges') ?? 0);
            $subCharges = round(($baseTotal * $percent) / 100, 2);

            // final total
            $total = round($baseTotal + $subCharges, 2);

//            dd(Bill::generateBillNumber());
            // Create Bill
            $bill = Bill::create([
                'bill_number' => GeneralHelper::generateBillNumber($allotee->id),
                'allotee_id' => $allotee->id,
                'bank_id' => $validated['bank_id'],
                'sector_id' => $allotee->sector->id ?? null,
                'size_id' => $allotee->size->id ?? null,
                'year' => $year,
                'from_month' => $fromMonth,
                'to_month' => $toMonth,
                'total_months' => $totalMonths,
                'issue_date' => $validated['issue_date'],
                'due_date' => $validated['due_date'],
                'is_paid' => 0,
                'generated_by' => auth()->id(),
                'bill_total' => $billTotal,
                'arrears' => $arrears,
                'sub_total' => $baseTotal,   // bill_total + arrears
                'sub_charges' => $subCharges,  // percentage of base
                'total' => $total,
                'due_amount' => $total,
                'is_active' => 1,
            ]);

            // Create BillCharge rows with charge_id
            foreach ($lines as $line) {
                BillCharge::create([
                    'bill_id' => $bill->id,
                    'charge_id' => $line['charge_id'] ?: null,
                    'from_month' => $fromMonth,
                    'from_year' => $year,
                    'to_month' => $toMonth,
                    'to_year' => $year,
                    'total_months' => $totalMonths,
                    'amount' => (int)round($line['amount'], 0),      // schema: integer
                    'total' => (int)round($line['line_total'], 0),  // schema: integer

                ]);
            }

            // allotee: update arrears
            $allotee->arrears  =0;
            $allotee->save();

            DB::commit();
            return redirect()->route($this->route . '.index')->with('success', 'Bill created successfully.');
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create bill: ' . $e->getMessage()]);
        }
    }

    public function checkPeriod(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'allotee_id' => ['required', 'integer', 'exists:allotees,id'],
            'year' => ['required', 'integer'],
            'from_month' => ['required', 'integer', 'min:1', 'max:12'],
            'to_month' => ['required', 'integer', 'min:1', 'max:12'],
            // optionally ignore a bill id when editing
            'exclude_bill_id' => ['nullable', 'integer', Rule::exists('bills', 'id')],
        ]);

        if ($validator->fails()) {
            return response()->json(['ok' => false, 'errors' => $validator->errors()], 422);
        }

        $aid = (int)$request->allotee_id;
        $year = (int)$request->year;
        $from = (int)$request->from_month;
        $to = (int)$request->to_month;
        $excludeId = $request->input('exclude_bill_id');

        // Build month sets handling wrap-around (e.g., Nov..Feb)
        $requestedSet = $this->buildMonthSet($from, $to);

        $query = Bill::query()
            ->select(['id', 'bill_number', 'from_month', 'to_month', 'year'])
            ->where('allotee_id', $aid)
            ->where('year', $year);

        if (!empty($excludeId)) {
            $query->where('id', '!=', $excludeId);
        }

        $existing = $query->get();

        // Map month id -> name for pretty display in conflicts
        $monthsMap = Month::query()->pluck('name', 'id')->all();

        $conflicts = [];
        foreach ($existing as $b) {
            $existingSet = $this->buildMonthSet((int)$b->from_month, (int)$b->to_month);
            // Check intersection
            $overlap = array_intersect($requestedSet, $existingSet);
            if (!empty($overlap)) {
                $fromId = (int)$b->from_month;
                $toId = (int)$b->to_month;
                $conflicts[] = [
                    'id' => $b->id,
                    'bill_number' => $b->bill_number,
                    'year' => $b->year,
                    'from_month' => $fromId,
                    'to_month' => $toId,
                    'from_month_name' => (string)($monthsMap[$fromId] ?? ''),
                    'to_month_name' => (string)($monthsMap[$toId] ?? ''),
                ];
            }
        }

        return response()->json([
            'ok' => true,
            'exists' => count($conflicts) > 0,
            'conflicts' => $conflicts,
        ]);
    }

    /**
     * Helper: build an inclusive set of month numbers between from..to (1-12),
     * supporting wrap-around when to < from (e.g., 11..2 => 11,12,1,2).
     */
    private function buildMonthSet(int $from, int $to): array
    {
        $from = max(1, min(12, $from));
        $to = max(1, min(12, $to));

        $set = [];
        if ($to >= $from) {
            for ($m = $from; $m <= $to; $m++) {
                $set[] = $m;
            }
        } else {
            for ($m = $from; $m <= 12; $m++) {
                $set[] = $m;
            }
            for ($m = 1; $m <= $to; $m++) {
                $set[] = $m;
            }
        }
        return $set;
    }

    public function update(Request $request, Bill $bill)
    {
        // Disallow updating inactive bills
        if ((int)$bill->is_active !== 1) {
            return redirect()->route($this->route . '.index')->with('error', 'Inactive bills cannot be updated.');
        }
        $rules = [
            'allotee_id' => 'required|exists:allotees,id',
            'bank_id' => 'required|exists:banks,id',
            'year' => 'required|integer',
            'from_month' => 'required|integer|min:1|max:12',
            'to_month' => 'required|integer|min:1|max:12',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'charge_id' => 'required|array',
            'charge_id.*' => 'nullable|exists:charges,id',
            'charge_name' => 'required|array',
            'charge_name.*' => 'nullable|string',
            'charge_amount' => 'required|array',
            'charge_amount.*' => 'nullable|numeric|min:0',
        ];

        $validated = Validator::make($request->all(), $rules)->validate();

        // Server-side guard for update: ignore current bill id
        $periodCheck = $this->checkPeriod(new Request([
            'allotee_id' => $validated['allotee_id'],
            'year' => $validated['year'],
            'from_month' => $validated['from_month'],
            'to_month' => $validated['to_month'],
            'exclude_bill_id' => $bill->id,
        ]));
        if ($periodCheck->getStatusCode() === 200) {
            $payload = $periodCheck->getData(true);
            if (!empty($payload['exists'])) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'from_month' => 'Bill already exists for this Allotee/Year/Months.',
                        'to_month' => 'Conflicts with: ' . collect($payload['conflicts'])->pluck('bill_number')->implode(', ')
                    ]);
            }
        }


        DB::beginTransaction();
        try {

//            dd($request->all());
            $allotee = Allotee::with(['sector', 'size'])->findOrFail($validated['allotee_id']);
            $year = (int)$validated['year'];
            $fromMonth = (int)$validated['from_month'];
            $toMonth = (int)$validated['to_month'];

            // total months (inclusive; supports wrap-around across year end)
            if ($toMonth >= $fromMonth) {
                $totalMonths = ($toMonth - $fromMonth) + 1;
            } else {
                $totalMonths = ((12 - $fromMonth) + 1) + $toMonth;
            }
            if ($totalMonths < 1) {
                $totalMonths = 1;
            }

            // Pair ids, names and amounts; compute per-line totals
            $ids = $validated['charge_id'] ?? [];
            $names = $validated['charge_name'] ?? [];
            $amounts = collect($validated['charge_amount'] ?? [])->map(fn($v) => (float)($v ?? 0))->values();

            $len = min(count($ids), count($names), $amounts->count());
            $lines = [];
            for ($i = 0; $i < $len; $i++) {
                $amount = (float)$amounts[$i];
                $lineTotal = round($amount * $totalMonths, 2);
                $lines[] = [
                    'charge_id' => (int)($ids[$i] ?? null),
                    'name' => (string)($names[$i] ?? ''),
                    'amount' => $amount,
                    'line_total' => $lineTotal,
                ];
            }

            // bill_total
            $billTotal = round(collect($lines)->sum('line_total'), 2);

            // SKIP recalculating arrears on edit: keep the current bill's arrears as-is
            $arrears = (float)($bill->arrears ?? 0);

            // base = bill_total + existing arrears
            $baseTotal = round($billTotal + $arrears, 2);

            // sub charges from settings (percentage only)
//            $percent    = (float) (GeneralHelper::getSettingValue('sub_charges_percent') ?? 0);
            $percent = (float)($request->input('sub_charges') ?? 0);
            $subCharges = round(($baseTotal * $percent) / 100, 2);

            // final total
            $total = round($baseTotal + $subCharges, 2);

            // Update Bill
            $bill->update([
                'allotee_id' => $allotee->id,
                'bank_id' => $validated['bank_id'],
                'sector_id' => $allotee->sector->id ?? null,
                'size_id' => $allotee->size->id ?? null,
                'year' => $year,
                'from_month' => $fromMonth,
                'to_month' => $toMonth,
                'total_months' => $totalMonths,
                'issue_date' => $validated['issue_date'],
                'due_date' => $validated['due_date'],
                'bill_total' => $billTotal,
                // arrears stays unchanged
                'sub_total' => $baseTotal,   // bill_total + EXISTING arrears
                'sub_charges' => $subCharges,  // percentage of base
                'total' => $total,
                'due_amount' => $total,
            ]);

            // Refresh BillCharge rows with charge_id (remove and recreate)
            $bill->billCharges()->delete();
            foreach ($lines as $line) {
                if ($line['amount'] > 0) {
                    BillCharge::create([
                        'bill_id' => $bill->id,
                        'charge_id' => $line['charge_id'] ?: null,
                        'from_month' => $fromMonth,
                        'from_year' => $year,
                        'to_month' => $toMonth,
                        'to_year' => $year,
                        'total_months' => $totalMonths,
                        'amount' => (int)round($line['amount'], 0),
                        'total' => (int)round($line['line_total'], 0),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route($this->route . '.index')->with('success', 'Bill updated successfully.');
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return back()->with(['error' => 'Failed to update bill: ' . $e->getMessage()]);
        }
    }

    public function create()
    {
        $data['title'] = $this->title;
        $data['route'] = $this->route;
        $data['viewFolder'] = $this->viewFolder;

        $data['allotees'] = Allotee::with('size', 'sector')->where('is_active', 1)
            ->orderBy('name','asc')->get();
        $data['banks'] = Bank::orderBy('name','asc')->get();
        $data['months'] = Month::all();
        $data['charges'] = Charge::all();

        $currentYear = date('Y');
        $startYear = $currentYear - 29;
        $yearArray = [];
        for ($year = $currentYear; $year >= $startYear; $year--) {
            $yearArray[] = $year;
        }
        $data['years'] = $yearArray;

        return view($this->viewFolder . '.create', $data);
    }

    public function edit(Bill $bill)
    {
        // Disallow editing inactive bills
        if ((int)$bill->is_active !== 1) {
            return redirect()->route($this->route . '.index')
                ->withErrors(['error' => 'Inactive bills cannot be edited.']);
        }
        $data['title'] = $this->title;
        $data['route'] = $this->route;
        $data['viewFolder'] = $this->viewFolder;

        $bill->load([
            'allotee.sector',
            'allotee.size',
            'sector',
            'size',
            'bank',
            'fromMonth',
            'toMonth',
            'billCharges.charge',
        ]);

        // Dropdowns
        $data['allotees'] = Allotee::with('size', 'sector')->where('is_active', 1)->get();
        $data['banks'] = Bank::all();
        $data['months'] = Month::all();
        $data['charges'] = Charge::all();

        // Map current bill charges by charge_id for prefill
        $chargeAmountsById = $bill->billCharges->keyBy('charge_id')->map(function ($bc) {
            return (float)$bc->amount;
        })->toArray();
        $data['chargeAmountsById'] = $chargeAmountsById;

        $data['bill'] = $bill;

        return view($this->viewFolder . '.edit', $data);
    }

    // Return all transactions for a bill

    public function destroy(Bill $bill)
    {
        try {
            DB::beginTransaction();
            $bill->delete();
            DB::commit();
            return redirect()->route($this->route . '.index')->with('success', 'Bill deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to delete bill: ' . $e->getMessage()]);
        }
    }

    public function pay(Request $request, Bill $bill)
    {
        if ((int)$bill->is_active !== 1) {
            return back()->withErrors(['error' => 'Only active bills can be paid.']);
        }
        $validated = Validator::make($request->all(), [
            'paid_amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date'],
        ])->validate();

        DB::beginTransaction();
        try {
            // Ensure bill has baseline totals
            $billTotal = (float)($bill->bill_total ?? 0);
            $arrears = (float)($bill->arrears ?? 0);
            $subTotal = (float)($bill->sub_total ?? ($billTotal + $arrears));
            $subCharges = (float)($bill->sub_charges ?? 0);
            $grandTotal = (float)($bill->total ?? ($subTotal + $subCharges));

            $paid = (float)$validated['paid_amount'];
            $currentDue = (float)($bill->due_amount ?? $grandTotal);
            $newDue = max(0, round($currentDue - $paid, 2));

            // Always create a new transaction entry (payment ledger)
            BillTransaction::create([
                'bill_id' => $bill->id,
                'total' => $grandTotal,
                'is_paid' => $newDue <= 0 ? 1 : 0,
                'paid_amount' => round($paid, 2),
                'due_amount' => $newDue,
                'payment_date' => $validated['payment_date'],
            ]);

            // Set bill paid flag if fully paid; also update due_amount on bill
            $bill->update([
                'is_paid' => $newDue <= 0 ? 1 : ($paid > 0 ? 1 : 0), // 1 with due > 0 means partial
                'due_amount' => $newDue,
            ]);

            DB::commit();
            return back()->with('success', 'Payment recorded successfully.');
        } catch (Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to record payment: ' . $e->getMessage()]);
        }
    }

    public function transactions(Bill $bill)
    {
        $bill->load(['transaction']);
        $items = BillTransaction::where('bill_id', $bill->id)
            ->orderBy('payment_date', 'desc')
            ->orderBy('id', 'desc')
            ->get(['id', 'paid_amount', 'due_amount', 'total', 'payment_date', 'is_paid']);
        return response()->json([
            'ok' => true,
            'bill' => [
                'id' => $bill->id,
                'bill_number' => $bill->bill_number,
                'total' => (float)($bill->total ?? 0),
                'due_amount' => (float)($bill->due_amount ?? 0),
            ],
            'transactions' => $items,
        ]);
    }

    public function show(Bill $bill)
    {
        // Load relations required by the invoice view
        $bill->load([
            'allotee.sector',
            'allotee.size',
            'sector',
            'size',
            'bank',
            'fromMonth',
            'toMonth',
            'transaction',
            'billCharges.charge',
            'billCharges.fromMonth',
            'billCharges.toMonth',
        ]);

        // Build per-charge breakdown from billCharges
        $chargesBreakdown = $bill->billCharges->map(function ($bc) {
            return [
                'charge_name' => (string)($bc->charge->name ?? ''),
                'amount' => (float)($bc->amount ?? 0),
                'total_months' => (int)($bc->total_months ?? 0),
                'line_total' => (float)($bc->total ?? 0),
                'from_month' => (string)($bc->fromMonth->name ?? ''),
                'to_month' => (string)($bc->toMonth->name ?? ''),
                'from_year' => (string)($bc->from_year ?? ''),
                'to_year' => (string)($bc->to_year ?? ''),
            ];
        })->values();

        // Derive/confirm invoice totals
        $bill_total = (float)($bill->bill_total ?? 0);      // sum of charge lines
        $arrears = (float)($bill->arrears ?? 0);
        $base_total = (float)($bill->sub_total ?? ($bill_total + $arrears)); // your sub_total stores base (bill_total + arrears)
        $sub_percent = (float)(GeneralHelper::getSettingValue('sub_charges_percent') ?? 0);
        $sub_charges = (float)($bill->sub_charges ?? round(($base_total * $sub_percent) / 100, 2));
        $grand_total = (float)($bill->total ?? ($base_total + $sub_charges));

        // Pre-format numbers for direct display (optional; keep raw too if your view formats itself)
        $fmt = fn($n) => number_format((float)$n, 2);
        $formatted = [
            'bill_total' => $fmt($bill_total),
            'arrears' => $fmt($arrears),
            'base_total' => $fmt($base_total),
            'sub_charges' => $fmt($sub_charges),
            'total' => $fmt($grand_total),
        ];


        // Logos from settings -> convert to public URLs
        $deptLogoPath = (string)(GeneralHelper::getSettingValue('dept_logo') ?? '');
        $govtLogoPath = (string)(GeneralHelper::getSettingValue('govt_logo') ?? '');

        $deptLogoUrl = GeneralHelper::getImageUrl($deptLogoPath, 'default.png');
        $govtLogoUrl = GeneralHelper::getImageUrl($govtLogoPath, 'default.png');


        $data = [
            'title' => $this->title,
            'route' => $this->route,
            'viewFolder' => $this->viewFolder,
            'bill' => $bill,
            'chargesBreakdown' => $chargesBreakdown,
            'bill_total' => $bill_total,
            'arrears' => $arrears,
            'base_total' => $base_total,              // bill_total + arrears
            'sub_charges_percent' => $sub_percent,             // from settings
            'sub_charges' => $sub_charges,
            'total' => $grand_total,
            'formatted' => $formatted,               // for easy direct rendering

            // Pass logo URLs to the view
            'deptLogoUrl' => $deptLogoUrl,
            'govtLogoUrl' => $govtLogoUrl,

        ];

        return view($this->viewFolder . '.show', $data);
    }

    public function calculate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'allotee_id' => 'required|exists:allotees,id',
            'year' => 'required|integer',
            'from_month' => 'required|integer|min:1|max:12',
            'to_month' => 'required|integer|min:1|max:12',
            'charge_name' => 'required|array',
            'charge_name.*' => 'nullable|string',
            'charge_amount' => 'required|array',
            'charge_amount.*' => 'nullable|numeric|min:0',
            // optional flags for edit context
            'skip_arrears' => 'sometimes|boolean',
            'current_arrears' => 'sometimes|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $alloteeId = (int)$request->input('allotee_id');
        $year = (int)$request->input('year');
        $from = (int)$request->input('from_month');
        $to = (int)$request->input('to_month');

        $names = $request->input('charge_name', []);
        $amounts = collect($request->input('charge_amount', []))
            ->map(fn($v) => (float)($v ?? 0))
            ->values();

        $len = min(count($names), $amounts->count());
        $charges = [];
        for ($i = 0; $i < $len; $i++) {
            $charges[] = [
                'name' => (string)($names[$i] ?? ''),
                'amount' => (float)$amounts[$i],
            ];
        }

        if ($to >= $from) {
            $totalMonths = ($to - $from) + 1;
        } else {
            $totalMonths = ((12 - $from) + 1) + $to;
        }
        if ($totalMonths < 1) {
            $totalMonths = 1;
        }

        $chargesBreakdown = collect($charges)->map(function ($c) use ($totalMonths) {
            $line = round(((float)$c['amount']) * $totalMonths, 2);
            return [
                'name' => (string)$c['name'],
                'amount' => (float)$c['amount'],
                'total_months' => $totalMonths,
                'line_total' => $line,
            ];
        })->values()->all();

        $billTotal = round(collect($chargesBreakdown)->sum('line_total'), 2);

        // Respect edit context: if skip_arrears is set, use provided current_arrears instead of recalculating
        if ($request->boolean('skip_arrears')) {

            $arrears = round((float)$request->input('current_arrears', 0), 2);
            $previousDetails = []; // none when skipping
        } else {
            $previousBills = Bill::with(['transaction', 'fromMonth', 'toMonth'])
                ->where('allotee_id', $alloteeId)
                ->where('due_amount', '>', 0)
                ->where('is_active', 1)
                ->orderBy('id', 'desc')
                ->get();

          
            $arrears = 0.0;
            $previousDetails = [];
            $allotee = Allotee::find($alloteeId);
            if(count($previousBills) > 0){
                foreach ($previousBills as $pb) {
                    // Gather arrears for this allotee: field + previous pending bills
                    $arrearsFromProfile = (float)($allotee->arrears ?? 0);
                    $arrearsFromBills = (float)Bill::where('allotee_id', $allotee->id)
                        ->where('due_amount', '>', 0)
                        ->where('is_active', 1)
                        ->sum('due_amount');
                    $arrears = round($arrearsFromProfile + $arrearsFromBills, 2);

                    if ($arrears > 0) {
                        $previousDetails[] = [
                            'bill_id' => $pb->id,
                            'bill_number' => $pb->bill_number,
                            'year' => $pb->year,
                            'duration' => trim(($pb->fromMonth->name ?? '') . ' - ' . ($pb->toMonth->name ?? '')),
                            'is_paid' => (int)$pb->is_paid,
                            'due_amount' => round($arrears, 2),
                        ];
                    }
                }
            } else{
                // Gather arrears for this allotee: field + previous pending bills
                $arrearsFromProfile = (float)($allotee->arrears ?? 0);
                $arrearsFromBills = (float)Bill::where('allotee_id', $allotee->id)
                    ->where('due_amount', '>', 0)
                    ->where('is_active', 1)
                    ->sum('due_amount');
                $arrears = round($arrearsFromProfile + $arrearsFromBills, 2);
            }

            $arrears = round($arrears, 2);
        }

        $baseTotal = round($billTotal + $arrears, 2);

//        $percent = (float) (GeneralHelper::getSettingValue('sub_charges_percent') ?? 0);
        $percent = (float)($request->input('sub_charges') ?? 0);
        $subCharges = round(($baseTotal * $percent) / 100, 2);

        $total = round($baseTotal + $subCharges, 2);

        return response()->json([
            'ok' => true,
            'data' => [
                'total_months' => $totalMonths,
                'charges_breakdown' => $chargesBreakdown,
                'bill_total' => $billTotal,
                'arrears' => $arrears,
                'base_total' => $baseTotal,
                'sub_total' => $baseTotal,
                'sub_charges_percent' => $percent,
                'sub_charges' => $subCharges,
                'total' => $total,
                'previous' => $previousDetails ?? [],
            ],
        ]);
    }

    // Bulk delete selected bills
    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids'   => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'distinct'],
        ]);

        $ids = array_unique(array_map('intval', $data['ids']));

        DB::transaction(function () use ($ids) {
            $bills = Bill::with(['billCharges', 'transaction'])->whereIn('id', $ids)->get();

            foreach ($bills as $bill) {
                // Delete dependent records first (if any)
                if (method_exists($bill, 'billCharges')) {
                    $bill->billCharges()->delete();
                }
                if (method_exists($bill, 'transaction')) {
                    $bill->transaction()->delete();
                }
                $bill->delete();
            }
        });

        return response()->json([
            'status'  => true,
            'message' => 'Selected bills deleted successfully.',
            'count'   => count($ids),
        ]);
    }
}
