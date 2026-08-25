<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        $totalAdmins = User::where('role', 'admin')->count();
        $totalUsers = User::where('role', 'customer')->count();
        $totalProducts = Product::count();
        $totalCategories = Category::count();

        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();

        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $revenueThisMonth = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('total_amount');

        $lowStockProducts = Product::where('stock', '<=', 10)->orderBy('stock')->limit((int) Setting::get('limits.superadmin.low_stock', 10))->get();

        $monthlySales = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', $now->copy()->subMonths(11)->startOfMonth())
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month")
            ->selectRaw('SUM(total_amount) as total')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $salesLabels = collect();
        $salesRevenue = collect();
        $salesOrderCount = collect();
        for ($i = 11; $i >= 0; $i--) {
            $monthKey = $now->copy()->subMonths($i)->format('Y-m');
            $record = $monthlySales->firstWhere('month', $monthKey);
            $salesLabels->push(Carbon::createFromFormat('Y-m', $monthKey)->format('M Y'));
            $salesRevenue->push((float) ($record->total ?? 0));
            $salesOrderCount->push((int) ($record->count ?? 0));
        }

        $recentOrders = Order::with('user:id,name')
            ->latest()
            ->limit((int) Setting::get('limits.superadmin.recent_orders', 8))
            ->get();

        $recentUsers = User::where('role', 'customer')
            ->select('id', 'name', 'email', 'created_at')
            ->latest()
            ->limit((int) Setting::get('limits.superadmin.recent_users', 8))
            ->get();

        $topProducts = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->select('order_items.product_id', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('order_items.product_id')
            ->orderByDesc('total_sold')
            ->limit((int) Setting::get('limits.superadmin.top_products', 5))
            ->with('product:id,name')
            ->get();

        return view('superadmin.dashboard', compact(
            'totalAdmins',
            'totalUsers',
            'totalProducts',
            'totalCategories',
            'totalOrders',
            'pendingOrders',
            'deliveredOrders',
            'totalRevenue',
            'revenueThisMonth',
            'lowStockProducts',
            'salesLabels',
            'salesRevenue',
            'salesOrderCount',
            'recentOrders',
            'recentUsers',
            'topProducts',
        ));
    }
}
