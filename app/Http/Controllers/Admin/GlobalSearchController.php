<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Job;
use App\Models\Order;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = trim((string) $request->input('q', ''));

        if (strlen($query) < 2) {
            return response()->json(['html' => '']);
        }

        $term = '%' . $query . '%';
        $results = collect();

        // Products
        $products = Product::query()
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('sku', 'like', $term)
                    ->orWhere('slug', 'like', $term);
            })
            ->limit(5)
            ->get(['id', 'name', 'slug', 'price', 'sku', 'status']);
        if ($products->isNotEmpty()) {
            $results->put('products', $products);
        }

        // Orders
        $orders = Order::query()
            ->with('user:id,name')
            ->where(function ($q) use ($term) {
                $q->where('order_number', 'like', $term);
            })
            ->orWhereHas('user', function ($q) use ($term) {
                $q->where('name', 'like', $term);
            })
            ->limit(5)
            ->get(['id', 'order_number', 'total_amount', 'status', 'user_id']);
        if ($orders->isNotEmpty()) {
            $results->put('orders', $orders);
        }

        // Customers
        $customers = User::query()
            ->where('role', 'customer')
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('phone', 'like', $term);
            })
            ->limit(5)
            ->get(['id', 'name', 'email', 'phone', 'status', 'is_frozen']);
        if ($customers->isNotEmpty()) {
            $results->put('customers', $customers);
        }

        // Categories
        $categories = Category::query()
            ->where('name', 'like', $term)
            ->limit(5)
            ->get(['id', 'name', 'slug', 'status']);
        if ($categories->isNotEmpty()) {
            $results->put('categories', $categories);
        }

        // Brands
        $brands = Brand::query()
            ->where('name', 'like', $term)
            ->limit(5)
            ->get(['id', 'name', 'slug', 'status']);
        if ($brands->isNotEmpty()) {
            $results->put('brands', $brands);
        }

        // Pages
        $pages = Page::query()
            ->where('title', 'like', $term)
            ->limit(5)
            ->get(['id', 'title', 'slug', 'status']);
        if ($pages->isNotEmpty()) {
            $results->put('pages', $pages);
        }

        if ($results->isEmpty()) {
            return response()->json(['html' => view('admin.search._no-results', ['query' => $query])->render()]);
        }

        $html = view('admin.search._results', ['results' => $results, 'query' => $query])->render();

        return response()->json(['html' => $html]);
    }
}
