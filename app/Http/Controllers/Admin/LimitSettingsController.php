<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class LimitSettingsController extends Controller
{
    public function edit()
    {
        $values = [
            'search_products' => (int) Setting::get('limits.search_products', 8),
            'search_categories' => (int) Setting::get('limits.search_categories', 4),
            'popular_searches' => (int) Setting::get('limits.popular_searches', 6),
            'subcategories' => (int) Setting::get('limits.subcategories', 10),
            'featured_collection_products' => (int) Setting::get('limits.featured_collection_products', 4),
            'admin_low_stock' => (int) Setting::get('limits.admin.low_stock', 10),
            'admin_top_products' => (int) Setting::get('limits.admin.top_products', 10),
            'admin_top_customers' => (int) Setting::get('limits.admin.top_customers', 10),
            'admin_latest_orders' => (int) Setting::get('limits.admin.latest_orders', 10),
            'admin_latest_customers' => (int) Setting::get('limits.admin.latest_customers', 10),
            'superadmin_low_stock' => (int) Setting::get('limits.superadmin.low_stock', 10),
            'superadmin_recent_orders' => (int) Setting::get('limits.superadmin.recent_orders', 8),
            'superadmin_recent_users' => (int) Setting::get('limits.superadmin.recent_users', 8),
            'superadmin_top_products' => (int) Setting::get('limits.superadmin.top_products', 5),
        ];

        return view('admin.limit-settings.edit', compact('values'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'search_products' => 'required|integer|min:1|max:50',
            'search_categories' => 'required|integer|min:1|max:50',
            'popular_searches' => 'required|integer|min:1|max:50',
            'subcategories' => 'required|integer|min:1|max:50',
            'featured_collection_products' => 'required|integer|min:1|max:20',
            'admin_low_stock' => 'required|integer|min:1|max:100',
            'admin_top_products' => 'required|integer|min:1|max:100',
            'admin_top_customers' => 'required|integer|min:1|max:100',
            'admin_latest_orders' => 'required|integer|min:1|max:100',
            'admin_latest_customers' => 'required|integer|min:1|max:100',
            'superadmin_low_stock' => 'required|integer|min:1|max:100',
            'superadmin_recent_orders' => 'required|integer|min:1|max:100',
            'superadmin_recent_users' => 'required|integer|min:1|max:100',
            'superadmin_top_products' => 'required|integer|min:1|max:100',
        ]);

        Setting::set('limits.search_products', $validated['search_products']);
        Setting::set('limits.search_categories', $validated['search_categories']);
        Setting::set('limits.popular_searches', $validated['popular_searches']);
        Setting::set('limits.subcategories', $validated['subcategories']);
        Setting::set('limits.featured_collection_products', $validated['featured_collection_products']);
        Setting::set('limits.admin.low_stock', $validated['admin_low_stock']);
        Setting::set('limits.admin.top_products', $validated['admin_top_products']);
        Setting::set('limits.admin.top_customers', $validated['admin_top_customers']);
        Setting::set('limits.admin.latest_orders', $validated['admin_latest_orders']);
        Setting::set('limits.admin.latest_customers', $validated['admin_latest_customers']);
        Setting::set('limits.superadmin.low_stock', $validated['superadmin_low_stock']);
        Setting::set('limits.superadmin.recent_orders', $validated['superadmin_recent_orders']);
        Setting::set('limits.superadmin.recent_users', $validated['superadmin_recent_users']);
        Setting::set('limits.superadmin.top_products', $validated['superadmin_top_products']);

        return redirect()->route('admin.limit-settings.edit')
            ->with('success', 'Display limits saved successfully.');
    }
}
