<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->notifications()->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('data', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $typeMap = [
                'order_new' => 'App\\Notifications\\NewOrderNotification',
                'order_status' => 'App\\Notifications\\OrderStatusUpdatedNotification',
                'order_cancelled' => 'App\\Notifications\\OrderCancelledNotification',
                'return_request' => 'App\\Notifications\\ReturnRequestNotification',
                'return_status' => 'App\\Notifications\\ReturnStatusUpdatedNotification',
                'section_order' => 'App\\Notifications\\HomepageSectionOrderChangedNotification',
            ];
            if (isset($typeMap[$request->type])) {
                $query->where('type', $typeMap[$request->type]);
            }
        }

        if ($request->filled('read_status')) {
            if ($request->read_status === 'unread') {
                $query->whereNull('read_at');
            } elseif ($request->read_status === 'read') {
                $query->whereNotNull('read_at');
            }
        }

        $notifications = $query->paginate(20)->withQueryString();
        $unreadCount = Auth::user()->unreadNotifications()->count();

        return view('admin.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markRead(Request $request, string $notificationId)
    {
        $notification = Auth::user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }

    public function destroy(Request $request, string $notificationId)
    {
        $notification = Auth::user()->notifications()->findOrFail($notificationId);
        $notification->delete();

        return back()->with('success', 'Notification deleted.');
    }

    public function destroyAll()
    {
        Auth::user()->notifications()->delete();

        return back()->with('success', 'All notifications deleted.');
    }
}
