<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Support\OrderStatuses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $orders = Order::where('user_id', Auth::id())
            ->when($status && in_array($status, OrderStatuses::all(), true), fn ($q) => $q->where('status', $status))
            ->with('items.product')
            ->latest()
            ->paginate(10);

        $stats = [
            'total_orders' => Order::where('user_id', Auth::id())->count(),
            'delivered_orders' => Order::where('user_id', Auth::id())->where('status', 'delivered')->count(),
            'total_spent' => (float) Order::where('user_id', Auth::id())
                ->where('payment_status', 'paid')
                ->sum('total_amount'),
            'last_order' => Order::where('user_id', Auth::id())->latest()->first(),
        ];

        return view('customer.orders.index', compact('orders', 'stats', 'status'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.product', 'payments', 'statusHistory.changedBy']);

        return view('customer.orders.show', compact('order'));
    }
}
