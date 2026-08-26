<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\OrderStatusUpdatedNotification;
use App\Support\OrderStatuses;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('month')) {
            $date = \Carbon\Carbon::parse($request->month . '-01');
            $query->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load([
            'user',
            'items.product',
            'statusHistory.changedBy',
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $field = $request->input('field');
        $value = $request->input('value');

        $validFields = [
            'status' => OrderStatuses::all(),
            'payment_status' => ['pending', 'paid', 'failed', 'cod'],
        ];

        if (!array_key_exists($field, $validFields) || !in_array($value, $validFields[$field])) {
            return response()->json(['message' => 'Invalid field or value.'], 422);
        }

        if ($field === 'status' && $order->status === OrderStatuses::DELIVERED) {
            return response()->json(['message' => 'This order has been delivered. Its status is locked and can no longer be changed.'], 422);
        }

        $oldValue = $order->$field;

        if ($oldValue === $value) {
            return response()->json(['message' => 'Status unchanged.', 'value' => $value]);
        }

        $order->update([$field => $value]);

        if ($field === 'status') {
            $order->statusHistory()->create([
                'status' => $value,
                'comment' => $request->input('comment'),
                'changed_by_user_id' => auth()->id(),
            ]);
        }

        if ($order->user_id !== auth()->id()) {
            $adminName = auth()->user()->name;
            $order->user->notify(new OrderStatusUpdatedNotification($order, $oldValue, $value, $adminName));
        }

        return response()->json([
            'message' => ucfirst(str_replace('_', ' ', $field)) . ' updated to ' . ucfirst($value) . '.',
            'value' => $value,
            'status' => $order->status,
        ]);
    }

    public function updateTracking(Request $request, Order $order)
    {
        $validated = $request->validate([
            'tracking_carrier' => 'nullable|string|max:255',
            'tracking_number' => 'nullable|string|max:255',
        ]);

        $order->update($validated);

        return response()->json([
            'message' => 'Tracking information saved.',
            'tracking_carrier' => $order->tracking_carrier,
            'tracking_number' => $order->tracking_number,
        ]);
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted.');
    }

    public function receipt(Order $order)
    {
        $order->load('items.product', 'user');

        return view('customer.orders.receipt', compact('order'));
    }
}
