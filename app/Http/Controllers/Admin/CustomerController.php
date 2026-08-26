<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')
            ->withCount('orders')
            ->withSum('orders', 'total_amount');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('is_frozen')) {
            $query->where('is_frozen', $request->is_frozen === '1');
        }

        $perPage = $request->input('per_page', '25');
        $allowedPerPage = ['10', '25', '50', '100', 'all'];
        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = '25';
        }

        $query->latest();

        $customers = $perPage === 'all'
            ? $query->get()
            : $query->paginate((int) $perPage)->withQueryString();

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
            'completed_orders' => $customer->deliveredOrdersCount(),
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

    public function freeze(User $customer, Request $request)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        if ($customer->id === Auth::id()) {
            return back()->with('error', 'You cannot freeze your own account.');
        }

        $validated = $request->validate([
            'frozen_reason' => ['nullable', 'string', 'max:500'],
        ]);

        $customer->freeze($validated['frozen_reason'] ?? null);

        return back()->with('success', 'Customer account frozen.');
    }

    public function unfreeze(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $customer->unfreeze();

        return back()->with('success', 'Customer account unfrozen.');
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'customer_ids' => ['required', 'array'],
            'customer_ids.*' => ['integer', 'exists:users,id'],
        ]);

        $ids = array_values(array_filter($validated['customer_ids'], fn ($id) => (int) $id !== Auth::id()));

        if (empty($ids)) {
            return redirect()->route('admin.customers.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $count = User::where('role', 'customer')->whereIn('id', $ids)->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', "$count customers deleted successfully.");
    }
}
