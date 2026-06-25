<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CashRegister;
use App\Models\OrderTransaction;
use App\Support\TableActions;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CashRegisterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $registers = CashRegister::with(['openedBy', 'closedBy'])->latest('register_date');
            return DataTables::of($registers)
                ->addIndexColumn()
                ->addColumn('date', fn($r) => $r->register_date->format('d M Y'))
                ->addColumn('opening_cash', fn($r) => number_format($r->opening_cash, 2))
                ->addColumn('closing_cash', fn($r) => $r->closing_cash !== null ? number_format($r->closing_cash, 2) : '-')
                ->addColumn('expected_cash', fn($r) => $r->expected_cash !== null ? number_format($r->expected_cash, 2) : '-')
                ->addColumn('variance', fn($r) => $r->variance !== null
                    ? '<span class="' . $r->variance_color . '">' . number_format($r->variance, 2) . '</span>'
                    : '-')
                ->addColumn('status', fn($r) => $r->status === 'open'
                    ? '<span class="badge bg-success">Open</span>'
                    : '<span class="badge bg-secondary">Closed</span>')
                ->addColumn('opened_by', fn($r) => $r->openedBy->name ?? '-')
                ->addColumn('action', fn($r) => $r->status === 'closed'
                    ? TableActions::inlineButton(
                        route('backend.admin.cash-register.edit', $r->id),
                        'fas fa-edit',
                        'Edit',
                        'warning'
                    )
                    : '-')
                ->rawColumns(['variance', 'status', 'action'])
                ->toJson();
        }

        $today    = CashRegister::whereDate('register_date', today())->first();
        return view('backend.cash-register.index', compact('today'));
    }

    public function open(Request $request)
    {
        $request->validate([
            'opening_cash' => 'required|numeric|min:0',
            'notes'        => 'nullable|string',
        ]);

        $existing = CashRegister::whereDate('register_date', today())->first();
        if ($existing) {
            return back()->with('error', 'Cash register already opened for today.');
        }

        CashRegister::create([
            'register_date' => today(),
            'opening_cash'  => $request->opening_cash,
            'opening_notes' => $request->notes,
            'opened_by'     => auth()->id(),
            'opened_at'     => now(),
            'status'        => 'open',
        ]);

        return back()->with('success', 'Cash register opened for today.');
    }

    public function edit(CashRegister $cashRegister)
    {
        return view('backend.cash-register.edit', compact('cashRegister'));
    }

    public function update(Request $request, CashRegister $cashRegister)
    {
        $request->validate([
            'opening_cash' => 'required|numeric|min:0',
            'closing_cash' => 'required|numeric|min:0',
            'notes'        => 'nullable|string',
        ]);

        $cashIn = OrderTransaction::whereDate('paid_at', $cashRegister->register_date)
            ->where('paid_by', 'cash')
            ->sum('amount');

        $expected = $request->opening_cash + $cashIn;
        $variance = $request->closing_cash - $expected;

        $cashRegister->update([
            'opening_cash'  => $request->opening_cash,
            'closing_cash'  => $request->closing_cash,
            'expected_cash' => $expected,
            'variance'      => $variance,
            'closing_notes' => $request->notes,
        ]);

        return redirect()->route('backend.admin.cash-register.index')
            ->with('success', 'Cash register entry updated. New variance: ' . number_format($variance, 2));
    }

    public function close(Request $request)
    {
        $request->validate([
            'closing_cash' => 'required|numeric|min:0',
            'notes'        => 'nullable|string',
        ]);

        $register = CashRegister::whereDate('register_date', today())
            ->where('status', 'open')
            ->firstOrFail();

        // Calculate expected cash: opening + all cash transactions today
        $cashIn = OrderTransaction::whereDate('paid_at', today())
            ->where('paid_by', 'cash')
            ->sum('amount');

        $expected = $register->opening_cash + $cashIn;
        $variance = $request->closing_cash - $expected;

        $register->update([
            'closing_cash'  => $request->closing_cash,
            'expected_cash' => $expected,
            'variance'      => $variance,
            'closing_notes' => $request->notes,
            'closed_by'     => auth()->id(),
            'closed_at'     => now(),
            'status'        => 'closed',
        ]);

        return back()->with('success', 'Cash register closed. Variance: ' . number_format($variance, 2));
    }
}
