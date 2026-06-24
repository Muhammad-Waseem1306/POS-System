<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BackupLog;
use App\Services\BackupService;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    public function __construct(private BackupService $backupService) {}

    public function index()
    {
        $backups = BackupLog::latest()->paginate(20);
        $health  = $this->backupService->getHealthStatus();
        return view('backend.backup.index', compact('backups', 'health'));
    }

    public function run(Request $request)
    {
        $type   = $request->input('type', 'manual');
        $result = $this->backupService->run($type, auth()->id());

        if ($result['success']) {
            return back()->with('success', 'Backup completed: ' . $result['filename']);
        }
        return back()->with('error', 'Backup failed: ' . $result['error']);
    }

    public function download(int $id)
    {
        $path = $this->backupService->download($id);
        if (!$path) {
            return back()->with('error', 'Backup file not found.');
        }
        $log = BackupLog::findOrFail($id);
        return response()->download($path, $log->filename);
    }

    public function restore(Request $request, int $id)
    {
        $request->validate(['confirm' => 'required|in:RESTORE']);

        $result = $this->backupService->restore($id);

        if ($result['success']) {
            return redirect()->route('backend.admin.backup.index')
                ->with('success', $result['message']);
        }
        return back()->with('error', $result['error']);
    }

    public function destroy(int $id)
    {
        $result = $this->backupService->delete($id);
        return back()->with('success', 'Backup deleted.');
    }
}
