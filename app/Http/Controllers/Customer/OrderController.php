<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items.product')
            ->latest()
            ->paginate(10);

        $stats = [
            'total_orders' => Order::where('user_id', Auth::id())->count(),
            'completed_orders' => Order::where('user_id', Auth::id())->where('status', 'completed')->count(),
            'total_spent' => (float) Order::where('user_id', Auth::id())
                ->where('payment_status', 'paid')
                ->sum('total_amount'),
            'last_order' => Order::where('user_id', Auth::id())->latest()->first(),
        ];

        return view('customer.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.product', 'payments']);

        return view('customer.orders.show', compact('order'));
    }
}
