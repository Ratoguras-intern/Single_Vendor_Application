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

        $data = $notification->data;
        $returnId = $data['return_id'] ?? null;
        $orderId = $data['order_id'] ?? null;

        if ($returnId) {
            $returnExists = \App\Models\OrderReturn::query()->whereKey($returnId)->exists();

            if (!$returnExists) {
                $notification->delete();
                return redirect()->route(auth()->user()->isStaff() ? 'admin.returns.index' : 'customer.returns.index')
                    ->with('info', 'The return request for that notification no longer exists.');
            }

            if (auth()->user()->isStaff()) {
                return redirect()->route('admin.returns.show', $returnId);
            }

            return redirect()->route('customer.returns.show', $returnId);
        }

        if ($orderId) {
            $orderExists = \App\Models\Order::query()->whereKey($orderId)->exists();

            if (!$orderExists) {
                $notification->delete();

                if (auth()->user()->isStaff()) {
                    return redirect()->route('admin.orders.index')
                        ->with('info', 'The order for that notification no longer exists.');
                }

                return redirect()->route('customer.orders.index')
                    ->with('info', 'The order for that notification no longer exists.');
            }

            if (auth()->user()->isStaff()) {
                return redirect()->route('admin.orders.show', $orderId);
            }

            return redirect()->route('customer.orders.show', $orderId);
        }

        return redirect()->back();
    }
}
