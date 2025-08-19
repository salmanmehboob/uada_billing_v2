<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralHelper;
use App\Models\Allotee;
use App\Models\Bank;
use App\Models\Bill;
use App\Models\BillCharge;
use App\Models\Charge;
use App\Models\Month;
use App\Models\Sector;
use App\Models\Size;
use App\Models\Type;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class CombineBillController extends Controller
{
    protected string $route = 'admin.bills.combine';
    protected string $viewFolder = 'bills.combine';
    protected string $title = 'Combine Bill Generation';

    // Small helper to register routes neatly from web.php
    public static function routes(): void
    {
        Route::get('/', [self::class, 'index'])->name('.index');
        Route::post('/store', [self::class, 'store'])->name('.store');
        Route::get('/print-combine', [self::class, 'printCombine'])->name('.print-combine');
        // NEW: list + data + multi-invoice view
        Route::get('/list', [self::class, 'list'])->name('.list');
        Route::get('/list/data', [self::class, 'listData'])->name('.list.data');
        Route::get('/invoices', [self::class, 'invoices'])->name('.invoices');
    }

    public function list(Request $request)
    {
        $data['title'] = 'Combine Bills — Listing';
        $data['route'] = $this->route;
        $data['viewFolder'] = $this->viewFolder;

        // Filter dropdowns
        $data['sectors'] = Sector::all();
        $data['sizes'] = Size::all();
        $data['types'] = Type::all();
        $data['banks'] = Bank::all();
        $data['months'] = Month::all();

        // Server-rendered listing on GET
        $bills = collect();
        if ($request->query()) {
            $q = Bill::with(['allotee','sector','size','fromMonth','toMonth','transaction'])
                ->where('is_generated_combine', 1);

            if ($request->filled('sector_id'))  { $q->where('sector_id', $request->input('sector_id')); }
            if ($request->filled('size_id'))    { $q->where('size_id', $request->input('size_id')); }
            if ($request->filled('type_id'))    { $q->where('type_id', $request->input('type_id')); }
            if ($request->filled('bank_id'))    { $q->where('bank_id', $request->input('bank_id')); }
            if ($request->filled('year'))       { $q->where('year', $request->input('year')); }
            if ($request->filled('from_month')) { $q->where('from_month', $request->input('from_month')); }
            if ($request->filled('to_month'))   { $q->where('to_month', $request->input('to_month')); }
            if ($request->filled('is_active')) {
                $v = $request->input('is_active');
                if ($v === '1' || $v === 1) { $q->where('is_active', 1); }
                elseif ($v === '0' || $v === 0) { $q->where('is_active', 0); }
            }

            $bills = $q->orderBy('id','desc')->get();
        }

        $data['bills'] = $bills;
        $data['filters'] = $request->all();

        return view($this->viewFolder . '.list', $data);
    }


    // NEW: server-side DataTables JSON for combined bills listing
    public function listData(Request $request)
    {
        $q = Bill::query()
            ->with(['allotee', 'sector', 'size', 'fromMonth', 'toMonth', 'transaction'])
            ->where('is_generated_combine', 1);

        // Filters
        if ($request->filled('sector_id')) $q->where('sector_id', $request->input('sector_id'));
        if ($request->filled('size_id')) $q->where('size_id', $request->input('size_id'));
        if ($request->filled('type_id')) $q->where('type_id', $request->input('type_id'));
        if ($request->filled('bank_id')) $q->where('bank_id', $request->input('bank_id'));
        if ($request->filled('year')) $q->where('year', $request->input('year'));
        if ($request->filled('from_month')) $q->where('from_month', $request->input('from_month'));
        if ($request->filled('to_month')) $q->where('to_month', $request->input('to_month'));
        if ($request->filled('is_active')) {
            $v = $request->input('is_active');
            if ($v === '1' || $v === 1) $q->where('is_active', 1);
            elseif ($v === '0' || $v === 0) $q->where('is_active', 0);
        }

        return DataTables::of($q)
            ->addColumn('consumer', function ($b) {
                $parts = [
                    $b->allotee->name ?? '',
                    $b->allotee->plot_no ?? '',
                ];
                return trim(implode(' - ', array_filter($parts)));
            })
            ->addColumn('period', function ($b) {
                return ($b->fromMonth->name ?? '') . ' - ' . ($b->toMonth->name ?? '') . ' ' . (string)$b->year;
            })
            ->addColumn('status', function ($b) {
                if ((int)$b->is_paid === 1) {
                    if ($b->transaction && (float)$b->transaction->due_amount > 0) {
                        return '<span class="badge bg-warning">Partial</span>';
                    }
                    return '<span class="badge bg-success">Paid</span>';
                }
                return '<span class="badge bg-danger">Unpaid</span>';
            })
            ->addColumn('combine', fn($b) => '<span class="badge bg-info">Combine</span>')
            ->addColumn('actions', function ($b) {
                $viewUrl = route('admin.bills.show', $b->id); // reuse single-bill invoice page
                return '<a href="' . e($viewUrl) . '" class="btn btn-sm btn-outline-primary">View</a>';
            })
            ->rawColumns(['status', 'combine', 'actions'])
            ->toJson();
    }

    // NEW: render all matching combined invoices one by one on a single printable page
    public function invoices(Request $request)
    {
        $data['title'] = 'Combine Bills — Invoices';
        $data['route'] = $this->route;
        $data['viewFolder'] = $this->viewFolder;

        $q = Bill::with(['allotee', 'sector', 'size', 'fromMonth', 'toMonth', 'bank', 'billCharges', 'transaction'])
            ->where('is_generated_combine', 1);

        // Filters (same as listing)
        if ($request->filled('sector_id')) $q->where('sector_id', $request->input('sector_id'));
        if ($request->filled('size_id')) $q->where('size_id', $request->input('size_id'));
        if ($request->filled('type_id')) $q->where('type_id', $request->input('type_id'));
        if ($request->filled('bank_id')) $q->where('bank_id', $request->input('bank_id'));
        if ($request->filled('year')) $q->where('year', $request->input('year'));
        if ($request->filled('from_month')) $q->where('from_month', $request->input('from_month'));
        if ($request->filled('to_month')) $q->where('to_month', $request->input('to_month'));
        if ($request->filled('is_active')) {
            $v = $request->input('is_active');
            if ($v === '1' || $v === 1) $q->where('is_active', 1);
            elseif ($v === '0' || $v === 0) $q->where('is_active', 0);
        }

        // Recommended deterministic ordering
        $data['bills'] = $q->orderBy('sector_id')->orderBy('size_id')->orderBy('allotee_id')->orderBy('id')->get();
        $data['filters'] = $request->all();

        // Logos from settings -> convert to public URLs
        $deptLogoPath = (string)(GeneralHelper::getSettingValue('dept_logo') ?? '');
        $govtLogoPath = (string)(GeneralHelper::getSettingValue('govt_logo') ?? '');

        $deptLogoUrl = GeneralHelper::getImageUrl($deptLogoPath, 'default.png');
        $govtLogoUrl = GeneralHelper::getImageUrl($govtLogoPath, 'default.png');


        // Pass logo URLs to the view
        $data['deptLogoUrl'] = $deptLogoUrl;
        $data['govtLogoUrl'] = $govtLogoUrl;

        return view($this->viewFolder . '.invoices', $data);
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
                // NEW: filter by combine flag
                if ($request->filled('is_generated_combine')) {
                    $v = $request->input('is_generated_combine');
                    if ($v === '1' || $v === 1) {
                        $query->where('is_generated_combine', 1);
                    } elseif ($v === '0' || $v === 0) {
                        $query->where('is_generated_combine', 0);
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
                    // NEW: filter on flag inside DataTables filter as well
                    if ($request->filled('is_generated_combine')) {
                        $v = $request->input('is_generated_combine');
                        if ($v === '1' || $v === 1) {
                            $instance->where('is_generated_combine', 1);
                        } elseif ($v === '0' || $v === 0) {
                            $instance->where('is_generated_combine', 0);
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
                ->addColumn('is_generated_combine', function ($bill) {
                    return (int)($bill->is_generated_combine ?? 0) === 1
                        ? '<span class="badge bg-info">Combine</span>'
                        : '<span class="badge bg-secondary">Single</span>';
                })
                ->addColumn('is_active_raw', function ($bill) {
                    return (int)$bill->is_active;
                })
                ->rawColumns(['checkBill', 'is_active', 'status', 'action', 'is_generated_combine'])
                ->setRowId('id')
                ->with(['summary' => $summary])
                ->make(true);
        }

        // Non-AJAX: provide dropdown data for filters
        $data['sectors'] = Sector::all();
        $data['sizes'] = Size::all();
        $data['banks'] = Bank::all();
        $data['types'] = Type::all();
        $data['charges'] = Charge::all();
        $data['months'] = Month::all();
        $data['allotees'] = Allotee::with(['sector', 'size'])->orderBy('name')->get();

        return view($this->viewFolder . '.index', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Filters: require at least one of sector/size/type
            'sector_id' => ['nullable', 'integer', 'exists:sectors,id'],
            'size_id' => ['nullable', 'integer', 'exists:sizes,id'],
            'type_id' => ['nullable', 'integer', 'exists:types,id'],
            // Required core fields
            'bank_id' => ['required', 'integer', 'exists:banks,id'],
            'year' => ['required', 'integer', 'min:2000', 'max:2099'],
            'from_month' => ['required', 'integer', Rule::exists('months', 'id')],
            'to_month' => ['required', 'integer', Rule::exists('months', 'id')],
            'issue_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:issue_date'],
            // Charges (same pattern as single create)
            'charge_id' => ['required', 'array', 'min:1'],
            'charge_id.*' => ['required', 'integer', 'exists:charges,id'],
            'charge_name' => ['required', 'array', 'min:1'],
            'charge_name.*' => ['required', 'string'],
            'charge_amount' => ['required', 'array', 'min:1'],
            'charge_amount.*' => ['required', 'numeric', 'min:0'],
            // Optional manual sub_charges
            'sub_charges' => ['nullable', 'numeric', 'min:0'],
        ]);

        // Ensure at least one filter is provided
        if (empty($validated['sector_id']) && empty($validated['size_id']) && empty($validated['type_id'])) {
            return back()->withErrors(['filters' => 'Please select at least one filter: Sector, Size, or Type.'])->withInput();
        }

        // Find all matched Allotees
        $alloteesQuery = Allotee::query()->with(['sector', 'size', 'type'])
            ->when(!empty($validated['sector_id']), fn($q) => $q->where('sector_id', $validated['sector_id']))
            ->when(!empty($validated['size_id']), fn($q) => $q->where('size_id', $validated['size_id']))
            ->when(!empty($validated['type_id']), fn($q) => $q->where('type_id', $validated['type_id']))
            ->where('is_active', 1);


        $allotees = $alloteesQuery->get();

        if ($allotees->isEmpty()) {
            return back()->withErrors(['filters' => 'No active allotees found for selected filter(s).'])->withInput();
        }

        // Compute months count (supports wrap across year if to < from)
        $from = (int)$validated['from_month'];
        $to = (int)$validated['to_month'];
        $monthsCount = $this->countMonthsInclusive($from, $to);

        // Charges total per month
        $perMonthTotal = 0.0;
        foreach ($validated['charge_amount'] as $amt) {
            $perMonthTotal += (float)$amt;
        }

        // Totals for summary
        $summary = [
            'requested_filters' => [
                'sector_id' => $validated['sector_id'] ?? null,
                'size_id' => $validated['size_id'] ?? null,
                'type_id' => $validated['type_id'] ?? null,
            ],
            'months_count' => $monthsCount,
            'count_allotees' => $allotees->count(),
            'total_bills_created' => 0,
            'sum_bill_total' => 0.0,
            'sum_arrears' => 0.0,
            'sum_sub_total' => 0.0,
            'sum_sub_charges' => 0.0,
            'sum_total' => 0.0,
            'skipped_duplicates' => 0, // track duplicates skipped

        ];

        $createdBills = [];

        DB::beginTransaction();
        try {
            foreach ($allotees as $allotee) {

                // Skip if a bill already exists for this Allotee with the same period (year + from/to month)
                $alreadyExists = Bill::where('allotee_id', $allotee->id)
                    ->where('year', (int)$validated['year'])
                    ->where('from_month', $from)
                    ->where('to_month', $to)
                    ->exists();

                if ($alreadyExists) {
                    // optionally track skipped duplicates (add to summary if you want to display)
                    $summary['skipped_duplicates']++;
                    continue;
                }

                
                // Gather arrears for this allotee: field + previous pending bills
                $arrearsFromProfile = (float)($allotee->arrears ?? 0);
                $arrearsFromBills = (float)Bill::where('allotee_id', $allotee->id)
                    ->where('due_amount', '>', 0)
                    ->where('is_active', 1)
                    ->sum('due_amount');
                $arrears = round($arrearsFromProfile + $arrearsFromBills, 2);

                // arrears from previous unpaid/partially paid bills
                $previousBills = Bill::with(['transaction', 'fromMonth', 'toMonth'])
                    ->where('allotee_id', $allotee->id)
                    ->where('due_amount', '>', 0)
                    ->where('is_active', 1)
                    ->orderBy('id', 'desc')
                    ->get();
                
                // Deactivate all previous unpaid/partially paid bills so they can't be edited further
                $idsToDeactivate = $previousBills->pluck('id')->filter()->values();
                if ($idsToDeactivate->isNotEmpty()) {
                    Bill::whereIn('id', $idsToDeactivate)->update(['is_active' => 0]);
                }


                $billTotal = round($perMonthTotal * $monthsCount, 2);
                $subTotal = round($billTotal + $arrears, 2);

                // Sub charges: use user-provided if set, otherwise 0 here (you can switch to percentage if available in settings)
                $subCharges = isset($validated['sub_charges']) && $validated['sub_charges'] !== null
                    ? (float)$validated['sub_charges']
                    : 0.0;

                $grandTotal = round($subTotal + $subCharges, 2);

                $bill = new Bill();
                $bill->allotee_id = $allotee->id;
                $bill->sector_id = $allotee->sector_id;
                $bill->size_id = $allotee->size_id;
                $bill->bank_id = (int)$validated['bank_id'];
                $bill->year = (int)$validated['year'];
                $bill->from_month = $from;
                $bill->to_month = $to;
                $bill->issue_date = Carbon::parse($validated['issue_date'])->toDateString();
                $bill->due_date = Carbon::parse($validated['due_date'])->toDateString();
                $bill->bill_number = GeneralHelper::generateBillNumber($allotee->id);
                $bill->bill_total = $billTotal;
                $bill->arrears = $arrears;      // keep for reference in bill
                $bill->sub_total = $subTotal;
                $bill->sub_charges = $subCharges;
                $bill->total = $grandTotal;
                $bill->due_amount = $grandTotal;   // full amount due at generation
                $bill->is_active = 1;
                $bill->is_paid = 0;
                $bill->is_generated_combine = 1; // <-- identify as combine bill

                $bill->save();

                // If you have bill-charges detail table/model, insert each line here
                // Example (pseudo):
                foreach ($validated['charge_id'] as $i => $cid) {
                    BillCharge::create([
                        'bill_id' => $bill->id,
                        'charge_id' => $cid,
                        'from_month' => $from,
                        'from_year' => $validated['year'],
                        'to_month' => $to,
                        'to_year' => $validated['year'],
                        'amount' => (float)$validated['charge_amount'][$i],
                        'months' => $monthsCount,
                        'total' => (float)$validated['charge_amount'][$i] * $monthsCount,
                    ]);
                }

                // allotee: update arrears
                $allotee->arrears  =0;
                $allotee->save();
                
                $summary['total_bills_created']++;
                $summary['sum_bill_total'] += $billTotal;
                $summary['sum_arrears'] += $arrears;
                $summary['sum_sub_total'] += $subTotal;
                $summary['sum_sub_charges'] += $subCharges;
                $summary['sum_total'] += $grandTotal;

                $createdBills[] = $bill->load('size', 'sector', 'allotee', 'fromMonth', 'toMonth', 'transaction');
            }

            // Round summary values
            $summary['sum_bill_total'] = round($summary['sum_bill_total'], 2);
            $summary['sum_arrears'] = round($summary['sum_arrears'], 2);
            $summary['sum_sub_total'] = round($summary['sum_sub_total'], 2);
            $summary['sum_sub_charges'] = round($summary['sum_sub_charges'], 2);
            $summary['sum_total'] = round($summary['sum_total'], 2);


            // Build a professional banner message for the summary screen
            $generated = (int)$summary['total_bills_created'];
            $skipped   = (int)$summary['skipped_duplicates'];

            if ($generated === 0 && $skipped > 0) {
                $bannerMessage = "No new bills were generated. All matching bills already exist for the selected time period.";
            } elseif ($generated === 0 && $skipped === 0) {
                $bannerMessage = "No bills were generated for the selected filters and time period.";
            } else {
                $genLabel = $generated === 1 ? 'bill' : 'bills';
                $parts = ["Successfully generated {$generated} {$genLabel}"];
                if ($skipped > 0) {
                    $dupLabel = $skipped === 1 ? 'duplicate bill' : 'duplicate bills';
                    $parts[] = "skipped {$skipped} {$dupLabel} that already exist";
                }
                $bannerMessage = implode(' · ', $parts) . " for the selected time period and filters.";
            }


            DB::commit();

            return view($this->viewFolder . '.summary', [
                'title' => $this->title,
                'route' => $this->route,
                'viewFolder' => $this->viewFolder,
                'bills' => $createdBills,
                'summary' => $summary,
                'message' => $bannerMessage, // pass to blade
                'filters' => [
                    'sector' => !empty($validated['sector_id']) ? Sector::find($validated['sector_id']) : null,
                    'size' => !empty($validated['size_id']) ? Size::find($validated['size_id']) : null,
                    'type' => !empty($validated['type_id']) ? Type::find($validated['type_id']) : null,
                ],
                'period' => [
                    'year' => (int)$validated['year'],
                    'from' => Month::find($from),
                    'to' => Month::find($to),
                ],
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to generate combined bills: ' . $e->getMessage()])->withInput();
        }
    }

    protected function countMonthsInclusive(int $from, int $to): int
    {
        if ($from === $to) {
            return 1;
        }
        if ($to > $from) {
            return ($to - $from) + 1;
        }
        // wrap-around (e.g., from 11 to 2 => 11,12,1,2 => 4 months)
        return (12 - $from + 1) + $to;
    }

    public function printCombine(Request $request)
    {
        $data['title'] = $this->title;
        $data['route'] = $this->route;
        $data['viewFolder'] = $this->viewFolder;

        $q = Bill::with(['allotee', 'sector', 'size', 'type', 'fromMonth', 'toMonth', 'bank'])
            ->where('is_generated_combine', 1);

        // Allow the same filters as index()
        if ($request->filled('sector_id')) $q->where('sector_id', $request->input('sector_id'));
        if ($request->filled('size_id')) $q->where('size_id', $request->input('size_id'));
        if ($request->filled('type_id')) $q->where('type_id', $request->input('type_id'));
        if ($request->filled('bank_id')) $q->where('bank_id', $request->input('bank_id'));
        if ($request->filled('year')) $q->where('year', $request->input('year'));
        if ($request->filled('from_month')) $q->where('from_month', $request->input('from_month'));
        if ($request->filled('to_month')) $q->where('to_month', $request->input('to_month'));

        // Optional: active/unpaid/paid filters as well
        if ($request->filled('is_active')) {
            $v = $request->input('is_active');
            if ($v === '1' || $v === 1) $q->where('is_active', 1);
            elseif ($v === '0' || $v === 0) $q->where('is_active', 0);
        }

        $data['bills'] = $q->orderBy('sector_id')->orderBy('size_id')->orderBy('allotee_id')->get();
        $data['filters'] = $request->all();

        return view($this->viewFolder . '.print', $data);
    }
}