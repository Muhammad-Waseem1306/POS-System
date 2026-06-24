<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\InstallmentSchedule;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class InstallmentDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'overdue'      => InstallmentSchedule::where('status', 'overdue')->count(),
            'due_today'    => InstallmentSchedule::whereDate('due_date', today())->where('status', 'pending')->count(),
            'upcoming_7'   => InstallmentSchedule::whereBetween('due_date', [today()->addDay(), today()->addDays(7)])->where('status', 'pending')->count(),
            'upcoming_30'  => InstallmentSchedule::whereBetween('due_date', [today()->addDay(), today()->addDays(30)])->where('status', 'pending')->count(),
            'overdue_amount'  => InstallmentSchedule::where('status', 'overdue')->sum('remaining_amount'),
            'due_today_amount'=> InstallmentSchedule::whereDate('due_date', today())->where('status', 'pending')->sum('amount'),
        ];
        return view('backend.installment-dashboard.index', compact('stats'));
    }

    public function overdue(Request $request)
    {
        if ($request->ajax()) {
            $schedules = InstallmentSchedule::with(['installmentPlan.customer'])
                ->where('status', 'overdue')
                ->orderBy('due_date')
                ->get();
            return $this->buildDatatable($schedules);
        }
        return view('backend.installment-dashboard.overdue');
    }

    public function dueToday(Request $request)
    {
        if ($request->ajax()) {
            $schedules = InstallmentSchedule::with(['installmentPlan.customer'])
                ->whereDate('due_date', today())
                ->where('status', 'pending')
                ->get();
            return $this->buildDatatable($schedules);
        }
        return view('backend.installment-dashboard.due-today');
    }

    public function upcoming(Request $request)
    {
        if ($request->ajax()) {
            $days  = $request->input('days', 7);
            $schedules = InstallmentSchedule::with(['installmentPlan.customer'])
                ->whereBetween('due_date', [today()->addDay(), today()->addDays($days)])
                ->where('status', 'pending')
                ->orderBy('due_date')
                ->get();
            return $this->buildDatatable($schedules);
        }
        return view('backend.installment-dashboard.upcoming');
    }

    private function buildDatatable($schedules)
    {
        return DataTables::of($schedules)
            ->addIndexColumn()
            ->addColumn('customer', fn($r) => $r->installmentPlan->customer->name ?? '-')
            ->addColumn('customer_phone', fn($r) => $r->installmentPlan->customer->phone ?? '-')
            ->addColumn('plan_id', fn($r) => '#' . $r->installment_plan_id)
            ->addColumn('installment_no', fn($r) => $r->installment_number)
            ->addColumn('due_date', fn($r) => $r->due_date)
            ->addColumn('amount', fn($r) => number_format($r->amount, 2))
            ->addColumn('remaining', fn($r) => number_format($r->remaining_amount, 2))
            ->addColumn('days_overdue', fn($r) => now()->diffInDays($r->due_date, false) < 0
                ? abs(now()->diffInDays($r->due_date)) . ' days'
                : '-')
            ->addColumn('action', fn($r) => '<a href="' . route('backend.admin.installments.show', $r->installment_plan_id) . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> View</a>')
            ->rawColumns(['action'])
            ->toJson();
    }
}
