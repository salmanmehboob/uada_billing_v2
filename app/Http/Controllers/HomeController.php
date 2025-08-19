<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// ... existing code ...
use App\Models\Bill;
use App\Models\BillTransaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // High-level KPIs
        $totalBills = Bill::count();
        $totalBilledAmount = (int) Bill::sum('total');
        $totalPaidAmount = (int) BillTransaction::where('is_paid', 1)->sum('paid_amount');
        $totalOutstanding = (int) Bill::sum('due_amount');
        $paidRate = $totalBilledAmount > 0 ? round(($totalPaidAmount / $totalBilledAmount) * 100, 1) : 0.0;

        // Paid vs Unpaid counts
        $paidBillsCount = Bill::where('is_paid', 1)->count();
        $unpaidBillsCount = $totalBills - $paidBillsCount;

        // Aging buckets (based on bill due_date)
        $today = Carbon::today()->toDateString();
        $aging = Bill::selectRaw("
                SUM(CASE WHEN due_amount > 0 AND DATEDIFF(?, due_date) BETWEEN 0 AND 30 THEN due_amount ELSE 0 END) AS b0_30,
                SUM(CASE WHEN due_amount > 0 AND DATEDIFF(?, due_date) BETWEEN 31 AND 60 THEN due_amount ELSE 0 END) AS b31_60,
                SUM(CASE WHEN due_amount > 0 AND DATEDIFF(?, due_date) BETWEEN 61 AND 90 THEN due_amount ELSE 0 END) AS b61_90,
                SUM(CASE WHEN due_amount > 0 AND DATEDIFF(?, due_date) > 90 THEN due_amount ELSE 0 END) AS b90p
            ", [$today, $today, $today, $today])
            ->first();

        // Collections - last 12 months (by payment_date)
        $start = Carbon::now()->subMonths(11)->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        $rawCollections = BillTransaction::selectRaw("DATE_FORMAT(payment_date, '%Y-%m') AS ym, SUM(paid_amount) AS total")
            ->where('is_paid', 1)
            ->whereNotNull('payment_date')
            ->whereBetween('payment_date', [$start, $end])
            ->groupBy('ym')
            ->orderBy('ym')
            ->get()
            ->keyBy('ym');

        $monthLabels = [];
        $monthValues = [];
        $cursor = $start->copy();
        while ($cursor <= $end) {
            $key = $cursor->format('Y-m');
            $label = $cursor->format('M Y');
            $monthLabels[] = $label;
            $monthValues[] = (int) ($rawCollections[$key]->total ?? 0);
            $cursor->addMonth();
        }

        // Bills by Sector (Bar)
        $billsBySector = Bill::select('sectors.name AS sector', DB::raw('COUNT(bills.id) AS total'))
            ->join('sectors', 'sectors.id', '=', 'bills.sector_id')
            ->groupBy('sectors.name')
            ->orderByDesc('total')
            ->get();

        // Outstanding by Size (Pie)
        $duesBySize = Bill::select('sizes.name AS size', DB::raw('SUM(bills.due_amount) AS due_total'))
            ->join('sizes', 'sizes.id', '=', 'bills.size_id')
            ->where('bills.due_amount', '>', 0)
            ->groupBy('sizes.name')
            ->orderByDesc('due_total')
            ->get();

        // Top overdue bills (table)
        $topOverdue = Bill::select([
            'bills.id',
            'bills.bill_number',
            'bills.due_amount',
            'bills.due_date',
            DB::raw("DATEDIFF(?, bills.due_date) AS days_overdue"),
        ])
            ->addBinding($today, 'select')
            ->where('bills.due_amount', '>', 0)
            ->whereDate('bills.due_date', '<', $today)
            ->orderByDesc(DB::raw("DATEDIFF('$today', bills.due_date)"))
            ->limit(10)
            ->get();

        return view('home', [
            'kpis' => [
                'totalBills' => $totalBills,
                'totalBilledAmount' => $totalBilledAmount,
                'totalPaidAmount' => $totalPaidAmount,
                'totalOutstanding' => $totalOutstanding,
                'paidRate' => $paidRate,
                'paidBillsCount' => $paidBillsCount,
                'unpaidBillsCount' => $unpaidBillsCount,
            ],
            'aging' => [
                'b0_30' => (int) ($aging->b0_30 ?? 0),
                'b31_60' => (int) ($aging->b31_60 ?? 0),
                'b61_90' => (int) ($aging->b61_90 ?? 0),
                'b90p' => (int) ($aging->b90p ?? 0),
            ],
            'collectionsChart' => [
                'labels' => $monthLabels,
                'values' => $monthValues,
            ],
            'billsBySector' => [
                'labels' => $billsBySector->pluck('sector')->toArray(),
                'values' => $billsBySector->pluck('total')->map(fn($v) => (int) $v)->toArray(),
            ],
            'duesBySize' => [
                'labels' => $duesBySize->pluck('size')->toArray(),
                'values' => $duesBySize->pluck('due_total')->map(fn($v) => (int) $v)->toArray(),
            ],
            'topOverdue' => $topOverdue,
        ]);
    }

}