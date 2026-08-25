<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\OrderReturn;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // --- Stat Cards ---
        $totalCustomers = User::where('role', 'customer')->count();
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalBrands = Brand::count();

        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $packedOrders = Order::where('status', 'packed')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $revenueToday = Order::where('payment_status', 'paid')
            ->whereDate('created_at', $now->toDateString())
            ->sum('total_amount');
        $revenueThisMonth = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('total_amount');
        $averageOrderValue = Order::where('payment_status', 'paid')->avg('total_amount') ?? 0;

        $lowStockProducts = Product::where('stock', '<=', 10)->orderBy('stock')->limit((int) Setting::get('limits.admin.low_stock', 10))->get();

        // --- Return Stats ---
        $pendingReturns = OrderReturn::whereIn('status', ['requested', 'pending_review'])->count();
        $returnsAwaitingReceipt = OrderReturn::where('status', 'return_shipped')->count();
        $returnsAwaitingRefund = OrderReturn::where('status', 'received')->count();

        // --- Monthly Sales (last 12 months) ---
        $monthlySales = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', $now->copy()->subMonths(11)->startOfMonth())
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month")
            ->selectRaw('SUM(total_amount) as total')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $months->push($now->copy()->subMonths($i)->format('Y-m'));
        }

        $salesData = $months->mapWithKeys(function ($month) use ($monthlySales) {
            $record = $monthlySales->firstWhere('month', $month);
            return [$month => [
                'revenue' => (float) ($record->total ?? 0),
                'orders' => (int) ($record->count ?? 0),
            ]];
        });

        $salesLabels = $months->map(function ($m) {
            return Carbon::createFromFormat('Y-m', $m)->format('M Y');
        });
        $salesRevenue = $salesData->pluck('revenue');
        $salesOrderCount = $salesData->pluck('orders');

        // --- Top 10 Best Selling Products ---
        $topProducts = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->select('order_items.product_id', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('order_items.product_id')
            ->orderByDesc('total_sold')
            ->limit((int) Setting::get('limits.admin.top_products', 10))
            ->with('product:id,name')
            ->get();

        // --- Top 10 Customers by Spending ---
        $topCustomers = User::where('role', 'customer')
            ->select('id', 'name', 'email', 'avatar_path', 'created_at')
            ->withCount('orders')
            ->selectRaw('(SELECT COALESCE(SUM(o.total_amount), 0) FROM orders o WHERE o.user_id = users.id AND o.payment_status = ?) as total_spent', ['paid'])
            ->orderByDesc('total_spent')
            ->limit((int) Setting::get('limits.admin.top_customers', 10))
            ->get();

        // --- Latest 10 Orders ---
        $latestOrders = Order::with('user:id,name,avatar_path')
            ->latest()
            ->limit((int) Setting::get('limits.admin.latest_orders', 10))
            ->get();

        // --- Latest 10 Registered Customers ---
        $latestCustomers = User::where('role', 'customer')
            ->select('id', 'name', 'email', 'avatar_path', 'created_at')
            ->withCount('orders')
            ->selectRaw('(SELECT COALESCE(SUM(o.total_amount), 0) FROM orders o WHERE o.user_id = users.id AND o.payment_status = ?) as total_spent', ['paid'])
            ->selectRaw('(SELECT MAX(o2.created_at) FROM orders o2 WHERE o2.user_id = users.id) as last_order_date')
            ->latest()
            ->limit((int) Setting::get('limits.admin.latest_customers', 10))
            ->get();

        return view('admin.dashboard.index', compact(
            'totalCustomers',
            'totalProducts',
            'totalCategories',
            'totalBrands',
            'totalOrders',
            'pendingOrders',
            'packedOrders',
            'deliveredOrders',
            'cancelledOrders',
            'totalRevenue',
            'revenueToday',
            'revenueThisMonth',
            'averageOrderValue',
            'lowStockProducts',
            'pendingReturns',
            'returnsAwaitingReceipt',
            'returnsAwaitingRefund',
            'salesLabels',
            'salesData',
            'salesRevenue',
            'salesOrderCount',
            'topProducts',
            'topCustomers',
            'latestOrders',
            'latestCustomers',
        ));
    }
}
