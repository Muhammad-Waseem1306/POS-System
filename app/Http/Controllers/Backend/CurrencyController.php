<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\DataTables;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        abort_if(!auth()->user()->can('currency_view'), 403);
        if ($request->ajax()) {
            $currencies = Currency::latest()->get();
            return DataTables::of($currencies)
                ->addIndexColumn()
                ->addColumn('name', fn($data) => $data->name)
                ->addColumn('code', fn($data) => $data->code)
                ->addColumn('symbol', fn($data) => $data->symbol
                    . ($data->active ? ' (Default Currency)' : ''))
                ->addColumn('action', function ($data) {
                    return table_actions()
                        ->link(route('backend.admin.currencies.edit', $data->id), 'Edit', 'fas fa-edit')
                        ->delete(route('backend.admin.currencies.destroy', $data->id))
                        ->confirmLink(
                            route('backend.admin.currencies.setDefault', $data->id),
                            'Set Default',
                            'fas fa-star',
                            'This currency will become the default for all transactions.',
                            'Set default currency',
                            'warning',
                            'Set as default'
                        )
                        ->render();
                })
                ->rawColumns(['name', 'code', 'symbol', 'action'])
                ->toJson();
        }
        return view('backend.settings.currencies.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        abort_if(!auth()->user()->can('currency_create'), 403);
        return view('backend.settings.currencies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        abort_if(!auth()->user()->can('currency_create'), 403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:currencies,code',
            'symbol' => 'required|string'
        ]);
        $currency = Currency::create($request->only(['name', 'code', 'symbol']));

        return redirect()->route('backend.admin.currencies.index')->with('success', 'Currency created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        abort_if(!auth()->user()->can('currency_update'), 403);

        $currency = Currency::findOrFail($id);
        return view('backend.settings.currencies.edit', compact('currency'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        abort_if(!auth()->user()->can('currency_update'), 403);
        $currency = Currency::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:currencies,code,' . $currency->id,
            'symbol' => 'required|string'
        ]);
        $currency->update($request->only(['name', 'code', 'symbol']));
        return redirect()->route('backend.admin.currencies.index')->with('success', 'Currency updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        abort_if(!auth()->user()->can('currency_delete'), 403);
        $currency = Currency::findOrFail($id);
        $currency->delete();
        return redirect()->back()->with('success', 'Currency Deleted Successfully');
    }
    public function setDefault($id)
    {
        Currency::where('active', true)->update(['active' => false]);
        $currency = Currency::findOrFail($id);
        $currency->active = true;
        $currency->save();
        Cache::put('default_currency', $currency, 60 * 24);
        return redirect()->back()->with('success', 'Currency Set Default Successfully');
    }
}
