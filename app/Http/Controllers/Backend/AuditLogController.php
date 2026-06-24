<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = AuditLog::with('user')->latest();

            if ($request->filled('action'))     $query->where('action', $request->action);
            if ($request->filled('module'))     $query->where('module', $request->module);
            if ($request->filled('user_id'))    $query->where('user_id', $request->user_id);
            if ($request->filled('date_from'))  $query->whereDate('created_at', '>=', $request->date_from);
            if ($request->filled('date_to'))    $query->whereDate('created_at', '<=', $request->date_to);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('user_name', fn($r) => $r->user_name ?? '-')
                ->addColumn('action_badge', fn($r) => $r->action_badge)
                ->addColumn('module', fn($r) => ucfirst($r->module ?? '-'))
                ->addColumn('description', fn($r) => $r->description ?? '-')
                ->addColumn('ip_address', fn($r) => $r->ip_address ?? '-')
                ->addColumn('created_at', fn($r) => $r->created_at->format('d M Y H:i:s'))
                ->rawColumns(['action_badge'])
                ->toJson();
        }

        $users   = User::select('id', 'name')->get();
        $actions = AuditLog::select('action')->distinct()->pluck('action');
        $modules = AuditLog::select('module')->distinct()->whereNotNull('module')->pluck('module');

        return view('backend.audit-logs.index', compact('users', 'actions', 'modules'));
    }

    public function show(int $id)
    {
        $log = AuditLog::with('user')->findOrFail($id);
        return view('backend.audit-logs.show', compact('log'));
    }
}
