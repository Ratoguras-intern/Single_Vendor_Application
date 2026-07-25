<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->withCount('orders')
            ->withSum('orders', 'total_amount')
            ->latest()
            ->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $orders = $customer->orders()
            ->with('items.product')
            ->latest()
            ->paginate(10);

        $stats = [
            'total_orders' => $customer->orders()->count(),
            'completed_orders' => $customer->completedOrdersCount(),
            'total_spent' => $customer->totalSpent(),
            'last_order' => $customer->lastOrder(),
        ];

        return view('admin.customers.show', compact('customer', 'orders', 'stats'));
    }

    public function orders(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $orders = $customer->orders()
            ->with('items.product')
            ->latest()
            ->paginate(15);

        return view('admin.customers.orders', compact('customer', 'orders'));
    }

    public function destroy(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        if ($customer->id === Auth::id()) {
            return redirect()->route('admin.customers.index')
                ->with('error', 'You cannot delete your own account.');
        }

        DB::transaction(function () use ($customer) {
            $customer->delete();
        });

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
