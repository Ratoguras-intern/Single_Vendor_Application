<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\OrderStatusUpdatedNotification;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
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
        $order->load(['user', 'items.product']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $field = $request->input('field');
        $value = $request->input('value');

        $validFields = [
            'status' => ['pending', 'processing', 'shipped', 'completed', 'cancelled'],
            'payment_status' => ['pending', 'paid', 'failed', 'cod'],
        ];

        if (!array_key_exists($field, $validFields) || !in_array($value, $validFields[$field])) {
            return response()->json(['message' => 'Invalid field or value.'], 422);
        }

        $oldValue = $order->$field;

        if ($oldValue === $value) {
            return response()->json(['message' => 'Status unchanged.', 'value' => $value]);
        }

        $order->update([$field => $value]);

        $adminName = auth()->user()->name;
        $order->user->notify(new OrderStatusUpdatedNotification($order, $oldValue, $value, $adminName));

        return response()->json([
            'message' => ucfirst(str_replace('_', ' ', $field)) . ' updated to ' . ucfirst($value) . '.',
            'value' => $value,
        ]);
    }
}
