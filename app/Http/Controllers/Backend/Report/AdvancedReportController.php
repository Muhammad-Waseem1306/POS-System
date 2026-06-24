<?php

namespace App\Http\Controllers\Backend\Report;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderTransaction;
use App\Models\InstallmentSchedule;
use App\Models\InstallmentPlan;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdvancedReportController extends Controller
{
    // Sales by day
    public function salesByDay(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date', now()->subDays(29)))->startOfDay();
        $endDate   = Carbon::parse($request->input('end_date', now()))->endOfDay();

        $sales = Order::selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total) as total, SUM(paid) as paid, SUM(due) as due')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('backend.reports.advanced.sales-by-day', compact('sales', 'startDate', 'endDate'));
    }

    // Sales by month
    public function salesByMonth(Request $request)
    {
        $year = $request->input('year', now()->year);

        $sales = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as orders, SUM(total) as total, SUM(paid) as paid, SUM(due) as due')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $months = collect(range(1, 12))->map(fn($m) => [
            'month'  => $m,
            'name'   => Carbon::create($year, $m, 1)->format('F'),
            'orders' => $sales[$m]->orders ?? 0,
            'total'  => $sales[$m]->total ?? 0,
            'paid'   => $sales[$m]->paid ?? 0,
            'due'    => $sales[$m]->due ?? 0,
        ]);

        return view('backend.reports.advanced.sales-by-month', compact('months', 'year'));
    }

    // Sales by product
    public function salesByProduct(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date', now()->subDays(29)))->startOfDay();
        $endDate   = Carbon::parse($request->input('end_date', now()))->endOfDay();

        $products = OrderProduct::with('product')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('product_id', DB::raw('SUM(quantity) as qty_sold, SUM(total) as revenue, SUM(total - (quantity * purchase_price)) as profit'))
            ->groupBy('product_id')
            ->orderByDesc('revenue')
            ->paginate(20);

        return view('backend.reports.advanced.sales-by-product', compact('products', 'startDate', 'endDate'));
    }

    // Sales by employee
    public function salesByEmployee(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date', now()->subDays(29)))->startOfDay();
        $endDate   = Carbon::parse($request->input('end_date', now()))->endOfDay();

        $employees = Order::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('user_id', DB::raw('COUNT(*) as orders, SUM(total) as total, SUM(paid) as paid'))
            ->groupBy('user_id')
            ->get();

        return view('backend.reports.advanced.sales-by-employee', compact('employees', 'startDate', 'endDate'));
    }

    // Installment collections report
    public function installmentCollections(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date', now()->subDays(29)))->startOfDay();
        $endDate   = Carbon::parse($request->input('end_date', now()))->endOfDay();

        $collections = OrderTransaction::with(['order.installmentPlan.customer', 'user'])
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->whereHas('order', fn($q) => $q->where('sale_type', 'installment'))
            ->paginate(20);

        $totalCollected = $collections->sum('amount');

        return view('backend.reports.advanced.installment-collections', compact('collections', 'totalCollected', 'startDate', 'endDate'));
    }

    // Outstanding balances
    public function outstandingBalances(Request $request)
    {
        $plans = InstallmentPlan::with('customer')
            ->where('status', 'active')
            ->withSum('schedules as total_remaining', 'remaining_amount')
            ->paginate(20);

        $totalOutstanding = InstallmentPlan::where('status', 'active')
            ->join('installment_schedules', 'installment_plans.id', '=', 'installment_schedules.installment_plan_id')
            ->whereIn('installment_schedules.status', ['pending', 'overdue'])
            ->sum('installment_schedules.remaining_amount');

        return view('backend.reports.advanced.outstanding-balances', compact('plans', 'totalOutstanding'));
    }

    // Export handlers - delegated to existing export infrastructure
    public function export(Request $request, string $type)
    {
        // Exports handled via individual report pages with PDF/Excel download buttons
        return redirect()->route('backend.admin.reports.advanced.' . $type)
            ->with('export', $request->format ?? 'excel');
    }
}
