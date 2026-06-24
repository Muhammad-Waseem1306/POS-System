<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\AuditService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = StockMovement::with(['product', 'user'])->latest();

            if ($request->filled('product_id')) $query->where('product_id', $request->product_id);
            if ($request->filled('type'))       $query->where('type', $request->type);
            if ($request->filled('date_from'))  $query->whereDate('created_at', '>=', $request->date_from);
            if ($request->filled('date_to'))    $query->whereDate('created_at', '<=', $request->date_to);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('product', fn($r) => $r->product->name ?? '-')
                ->addColumn('type_badge', fn($r) => $r->type_badge)
                ->addColumn('qty_before', fn($r) => $r->quantity_before)
                ->addColumn('qty_change', fn($r) => ($r->quantity_change >= 0 ? '+' : '') . $r->quantity_change)
                ->addColumn('qty_after', fn($r) => $r->quantity_after)
                ->addColumn('reason', fn($r) => $r->reason ?? '-')
                ->addColumn('user', fn($r) => $r->user->name ?? 'System')
                ->addColumn('date', fn($r) => $r->created_at->format('d M Y H:i'))
                ->rawColumns(['type_badge'])
                ->toJson();
        }

        $products = Product::select('id', 'name')->get();
        $types    = ['purchase', 'sale', 'adjustment', 'return', 'damage'];

        return view('backend.stock-movements.index', compact('products', 'types'));
    }

    public function adjust(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'adjustment' => 'required|integer|not_in:0',
            'reason'     => 'required|string|max:255',
            'notes'      => 'nullable|string',
        ]);

        $product        = Product::findOrFail($request->product_id);
        $quantityBefore = $product->quantity;

        StockService::recordMovement(
            $request->product_id,
            'adjustment',
            $request->adjustment,
            $request->reason,
            $request->notes
        );

        $product->increment('quantity', $request->adjustment);

        AuditService::logInventoryAdjustment(
            $product->id,
            $quantityBefore,
            $quantityBefore + $request->adjustment,
            $request->reason
        );

        return back()->with('success', 'Stock adjusted successfully.');
    }
}
