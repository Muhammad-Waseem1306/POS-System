<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\InstallmentPlan;
use Illuminate\Http\Request;

class InstallmentController extends Controller
{
    public function index(Request $request)
    {
        $plans = InstallmentPlan::with('customer', 'guarantor')->latest()->paginate(25);
        return view('backend.installments.index', compact('plans'));
    }

    public function show($id)
    {
        $plan = InstallmentPlan::with('customer', 'guarantor', 'schedules', 'paymentAllocations.installmentSchedule')->findOrFail($id);
        return view('backend.installments.show', compact('plan'));
    }
}
