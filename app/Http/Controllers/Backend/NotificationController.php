<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SystemNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = SystemNotification::latest();

        if ($request->filled('type'))     $query->where('type', $request->type);
        if ($request->filled('severity')) $query->where('severity', $request->severity);
        if ($request->filled('is_read'))  $query->where('is_read', $request->is_read);

        $notifications = $query->paginate(20);
        $unreadCount   = SystemNotification::unreadCount();
        $types         = SystemNotification::select('type')->distinct()->pluck('type');

        return view('backend.notifications.index', compact('notifications', 'unreadCount', 'types'));
    }

    public function markRead(int $id)
    {
        $notification = SystemNotification::findOrFail($id);
        $notification->update([
            'is_read' => true,
            'read_by' => auth()->id(),
            'read_at' => now(),
        ]);
        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllRead()
    {
        SystemNotification::where('is_read', false)->update([
            'is_read' => true,
            'read_by' => auth()->id(),
            'read_at' => now(),
        ]);
        return back()->with('success', 'All notifications marked as read.');
    }

    public function destroy(int $id)
    {
        SystemNotification::findOrFail($id)->delete();
        return back()->with('success', 'Notification deleted.');
    }

    public function getUnread()
    {
        $notifications = SystemNotification::where('is_read', false)
            ->latest()
            ->limit(10)
            ->get();

        return response()->json([
            'count'         => SystemNotification::unreadCount(),
            'notifications' => $notifications,
        ]);
    }
}
