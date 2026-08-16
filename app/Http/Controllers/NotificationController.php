<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markRead(Request $request)
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }

    public function redirect(Request $request, string $notificationId)
    {
        $notification = Auth::user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();

        $orderId = $notification->data['order_id'] ?? null;

        if ($orderId) {
            $orderExists = \App\Models\Order::query()->whereKey($orderId)->exists();

            if (!$orderExists) {
                $notification->delete();

                if (auth()->user()->role === 'admin') {
                    return redirect()->route('admin.orders.index')
                        ->with('info', 'The order for that notification no longer exists.');
                }

                return redirect()->route('customer.orders.index')
                    ->with('info', 'The order for that notification no longer exists.');
            }

            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.orders.show', $orderId);
            }

            return redirect()->route('customer.orders.show', $orderId);
        }

        return redirect()->back();
    }
}
