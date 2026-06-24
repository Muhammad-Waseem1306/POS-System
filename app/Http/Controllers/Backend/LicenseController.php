<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\License;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    public function index()
    {
        $license = License::first() ?? new License();
        return view('backend.license.index', compact('license'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'store_name'         => 'required|string|max:255',
            'store_address'      => 'nullable|string',
            'store_phone'        => 'nullable|string|max:30',
            'store_email'        => 'nullable|email',
            'license_key'        => 'nullable|string|max:255',
            'license_expires_at' => 'nullable|date',
            'license_type'       => 'required|in:standard,professional,enterprise',
        ]);

        License::updateOrCreate(['id' => 1], $request->only([
            'store_name', 'store_address', 'store_phone', 'store_email',
            'license_key', 'license_expires_at', 'license_type',
        ]));

        return back()->with('success', 'License information updated successfully.');
    }
}
