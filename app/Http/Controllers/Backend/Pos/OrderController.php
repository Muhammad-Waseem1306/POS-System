<?php

namespace App\Http\Controllers\Backend\Pos;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderTransaction;
use App\Models\PosCart;
use App\Models\Product;
use App\Models\InstallmentPlan;
use App\Models\InstallmentSchedule;
use App\Models\CustomerGuarantor;
use App\Services\AuditService;
use App\Services\StockService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $orders = Order::with('customer')->get();
            return DataTables::of($orders)
                ->addIndexColumn()
                ->addColumn('saleId', fn($data) => "#" . $data->id)
                ->addColumn('customer', fn($data) => $data->customer->name ?? '-')
                ->addColumn('item', fn($data) => $data->total_item)
                ->addColumn('sub_total', fn($data) => number_format($data->sub_total, 2, '.', ','))
                ->addColumn('discount', fn($data) => number_format($data->discount, 2, '.', ','))
                ->addColumn('total', fn($data) => number_format($data->total, 2, '.', ','))
                ->addColumn('paid', fn($data) => number_format($data->paid, 2, '.', ','))
                ->addColumn('due', fn($data) => number_format($data->due, 2, '.', ','))
                ->addColumn('status', fn($data) => $data->status
                    ? '<span class="badge bg-primary">Paid</span>'
                    : '<span class="badge bg-danger">Due</span>')
                ->addColumn('action', function ($data) {
                    $actions = table_actions()
                        ->link(route('backend.admin.orders.invoice', $data->id), 'Invoice', 'fas fa-file-invoice')
                        ->link(route('backend.admin.orders.pos-invoice', $data->id), 'POS Invoice', 'fas fa-receipt')
                        ->link(route('backend.admin.orders.transactions', $data->id), 'Transactions', 'fas fa-exchange-alt')
                        ->link(route('backend.admin.orders.show', $data->id), 'Details', 'fas fa-file-alt');

                    if (!$data->status) {
                        $actions->link(route('backend.admin.due.collection', $data->id), 'Due Collection', 'fas fa-hand-holding-usd');
                    }

                    return $actions->render();
                })
                ->rawColumns(['saleId', 'customer', 'item', 'sub_total', 'discount', 'total', 'paid', 'due', 'status', 'action'])
                ->toJson();
        }
        return view('backend.orders.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => [
                'required',
                'exists:customers,id',
                'integer',
            ],
            'order_discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'paid' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'sale_type' => [
                'nullable',
                'string',
                'in:cash,installment',
            ],
            'installment_months' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'guarantor_id' => [
                'nullable',
                'integer',
                'exists:customer_guarantors,id'
            ],
        ], [
            'customer_id.required' => 'Please select a customer.',
            'customer_id.exists' => 'The selected customer does not exist.',
            'order_discount.numeric' => 'The order discount must be a number.',
            'paid.numeric' => 'The amount paid must be a number.',
            'sale_type.in' => 'The sale type must be either cash or installment.',
            'installment_months.integer' => 'Installment months must be a whole number.',
            'installment_months.min' => 'Installment months must be at least 1.',
            'guarantor_id.exists' => 'Selected guarantor is invalid.',
        ]);
        $carts = PosCart::with('product')->where('user_id', auth()->id())->get();
        $saleType = $request->sale_type === Order::SALE_TYPE_INSTALLMENT ? Order::SALE_TYPE_INSTALLMENT : Order::SALE_TYPE_CASH;
        $installmentMonths = $saleType === Order::SALE_TYPE_INSTALLMENT ? (int) $request->installment_months : null;
        $downPayment = round((float) $request->paid, 2);

        if ($saleType === Order::SALE_TYPE_INSTALLMENT && $installmentMonths <= 0) {
            return response()->json(['message' => 'Installment months are required for installment sales.'], 422);
        }

        $order = Order::create([
            'customer_id' => $request->customer_id,
            'user_id' => $request->user()->id,
            'sale_type' => $saleType,
        ]);
        $totalAmountOrder = 0;
        $orderDiscount = $request->order_discount;
        foreach ($carts as $cart) {
            $mainTotal = $cart->product->price * $cart->quantity;
            $totalAfterDiscount = $cart->product->discounted_price * $cart->quantity;
            $discount = $mainTotal - $totalAfterDiscount;
            $totalAmountOrder += $totalAfterDiscount;
            $order->products()->create([
                'quantity' => $cart->quantity,
                'price' => $cart->product->price,
                'purchase_price' => $cart->product->purchase_price,
                'sub_total' => $mainTotal,
                'discount' => $discount,
                'total' => $totalAfterDiscount,
                'product_id' => $cart->product->id,
            ]);
            // Record stock movement for this sale
            StockService::recordMovement(
                $cart->product->id,
                'sale',
                -$cart->quantity,
                'Sale order #' . $order->id,
                null,
                Order::class,
                $order->id,
                $cart->product->purchase_price
            );
            $cart->product->quantity = $cart->product->quantity - $cart->quantity;
            $cart->product->save();
        }
        $total = $totalAmountOrder - $orderDiscount;

        if ($request->paid > $total) {
            return response()->json(['message' => 'Paid amount cannot exceed order total.'], 422);
        }

        $order->sub_total = $totalAmountOrder;
        $order->discount = $orderDiscount;
        $order->paid = round((float) $request->paid, 2);
        $order->total = round((float) $total, 2);
        $order->due = round((float)($total - $downPayment), 2);
        $order->status = round((float)($total - $downPayment), 2) <= 0;
        $order->save();

        if ($downPayment > 0) {
            $orderTransaction = $order->transactions()->create([
                'amount' => $downPayment,
                'customer_id' => $order->customer_id,
                'user_id' => auth()->id(),
                'paid_by' => $saleType === Order::SALE_TYPE_INSTALLMENT ? 'down_payment' : 'cash',
                'paid_at' => Carbon::now(),
            ]);
        }

        if ($saleType === Order::SALE_TYPE_INSTALLMENT) {
            // guarantor must be provided for installment sales
            $guarantorId = $request->input('guarantor_id');
            if (empty($guarantorId) || !CustomerGuarantor::where('id', $guarantorId)->where('customer_id', $order->customer_id)->exists()) {
                return response()->json(['message' => 'A valid guarantor must be selected for installment sales.'], 422);
            }

            $financedAmount = round($total - $downPayment, 2);
            $installmentPrice = $total;
            $cashPrice = $total;

            $installmentPlan = $order->installmentPlan()->create([
                'customer_id' => $order->customer_id,
                'guarantor_id' => $guarantorId,
                'cash_price' => $cashPrice,
                'installment_price' => $installmentPrice,
                'total_amount' => $total,
                'down_payment' => $downPayment,
                'financed_amount' => $financedAmount,
                'installment_months' => $installmentMonths,
                'monthly_installment' => $financedAmount > 0 ? round($financedAmount / $installmentMonths, 2) : 0,
                'start_date' => Carbon::today(),
                'end_date' => Carbon::today()->addMonths($installmentMonths),
                'status' => $financedAmount > 0 ? 'active' : 'completed',
                'notes' => $request->notes,
            ]);

            if ($financedAmount > 0) {
                $monthlyAmount = round($financedAmount / $installmentMonths, 2);
                $allocated = 0;
                for ($i = 1; $i <= $installmentMonths; $i++) {
                    $amount = $i === $installmentMonths
                        ? round($financedAmount - $allocated, 2)
                        : $monthlyAmount;
                    $allocated += $amount;

                    $installmentPlan->schedules()->create([
                        'sale_id' => $order->id,
                        'customer_id' => $order->customer_id,
                        'installment_number' => $i,
                        'due_date' => Carbon::today()->addMonths($i),
                        'amount' => $amount,
                        'paid_amount' => 0,
                        'remaining_amount' => $amount,
                        'status' => 'pending',
                    ]);
                }
            }
        }

        $carts = PosCart::where('user_id', auth()->id())->delete();

        // Audit log the sale
        AuditService::logCreate('orders', $order->id,
            "New {$saleType} sale created for customer #{$order->customer_id}. Total: {$order->total}",
            ['total' => $order->total, 'sale_type' => $saleType, 'paid' => $order->paid]
        );

        return response()->json(['message' => 'Order completed successfully', 'order' => $order], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with(['customer', 'products.product', 'transactions', 'installmentPlan.schedules.paymentAllocations.transaction'])
            ->findOrFail($id);

        return view('backend.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function invoice($id)
    {
        $order = Order::with(['customer', 'products.product'])->findOrFail($id);
        return view('backend.orders.print-invoice', compact('order'));
    }
    public function collection(Request $request, $id)
    {
        $order = Order::with(['installmentPlan.schedules'])->findOrFail($id);
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'amount' => 'required|numeric|min:1',
            ]);

            $collectionAmount = round((float)$data['amount'], 2);
            $due = round((float)$order->due - $collectionAmount, 2);
            $paid = round((float)$order->paid + $collectionAmount, 2);

            $orderTransaction = $order->transactions()->create([
                'amount' => $collectionAmount,
                'customer_id' => $order->customer_id,
                'user_id' => auth()->id(),
                'paid_by' => $order->sale_type === Order::SALE_TYPE_INSTALLMENT ? 'installment_payment' : 'cash',
                'paid_at' => Carbon::now(),
            ]);

            if ($order->sale_type === Order::SALE_TYPE_INSTALLMENT && $order->installmentPlan) {
                $remainingPayment = $collectionAmount;
                $schedules = $order->installmentPlan->schedules()
                    ->whereIn('status', [InstallmentSchedule::STATUS_PENDING, InstallmentSchedule::STATUS_PARTIAL])
                    ->orderBy('due_date')
                    ->orderBy('installment_number')
                    ->get();

                foreach ($schedules as $schedule) {
                    if ($remainingPayment <= 0) {
                        break;
                    }
                    $toAllocate = min($remainingPayment, $schedule->remaining_amount);
                    if ($toAllocate <= 0) {
                        continue;
                    }

                    $schedule->paid_amount = round((float)$schedule->paid_amount + $toAllocate, 2);
                    $schedule->remaining_amount = round((float)$schedule->remaining_amount - $toAllocate, 2);
                    $schedule->status = $schedule->remaining_amount <= 0
                        ? InstallmentSchedule::STATUS_PAID
                        : InstallmentSchedule::STATUS_PARTIAL;
                    $schedule->paid_at = Carbon::now();
                    $schedule->save();

                    $order->installmentPlan->paymentAllocations()->create([
                        'order_transaction_id' => $orderTransaction->id,
                        'installment_plan_id' => $order->installmentPlan->id,
                        'installment_schedule_id' => $schedule->id,
                        'amount' => $toAllocate,
                        'allocated_at' => Carbon::now(),
                    ]);

                    $remainingPayment = round((float)$remainingPayment - $toAllocate, 2);
                }

                $planRemaining = $order->installmentPlan->schedules()->sum('remaining_amount');
                $order->installmentPlan->status = $planRemaining <= 0
                    ? InstallmentPlan::STATUS_COMPLETED
                    : InstallmentPlan::STATUS_ACTIVE;
                $order->installmentPlan->save();
            }

            $order->due = max($due, 0);
            $order->paid = $paid;
            $order->status = $order->due <= 0;
            $order->save();

            // Audit log payment collection
            AuditService::logPayment(
                $order->id,
                $collectionAmount,
                $orderTransaction->paid_by,
                "Payment of {$collectionAmount} collected for order #{$order->id} by " . auth()->user()->name
            );

            return to_route('backend.admin.collectionInvoice', $orderTransaction->id);
        }
        return view('backend.orders.collection.create', compact('order'));
    }

    //collection invoice by order_transaction id
    public function collectionInvoice($id)
    {
        $transaction = OrderTransaction::findOrFail($id);
        $collection_amount = $transaction->amount;
        $order = $transaction->order;
        return view('backend.orders.collection.invoice', compact('order', 'collection_amount', 'transaction'));
    }
    //transactions by order id
    public function transactions($id)
    {
        $order = Order::with('transactions')->findOrFail($id);
        return view('backend.orders.collection.index', compact('order'));
    }

    public function posInvoice($id)
    {
        $order = Order::with(['customer', 'products.product'])->findOrFail($id);
        $maxWidth = readConfig('receiptMaxwidth')??'300px';
        return view('backend.orders.pos-invoice', compact('order', 'maxWidth'));
    }
}
