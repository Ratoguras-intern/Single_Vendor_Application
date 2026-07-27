<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\FeaturedHomepageCategory;
use Illuminate\Http\Request;

class FeaturedCategoryController extends Controller
{
    public function index()
    {
        $categories = FeaturedHomepageCategory::with('category')->orderBy('sort_order')->orderBy('id')->get();
        $availableCategories = Category::where('status', true)
            ->whereNotIn('id', $categories->pluck('category_id')->toArray())
            ->get();

        return view('admin.featured-categories.index', compact('categories', 'availableCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id|unique:featured_homepage_categories,category_id',
        ]);

        $maxOrder = FeaturedHomepageCategory::max('sort_order') ?? 0;

        FeaturedHomepageCategory::create([
            'category_id' => $validated['category_id'],
            'sort_order' => $maxOrder + 1,
            'is_enabled' => true,
            'display_style' => 'grid',
        ]);

        FeaturedHomepageCategory::clearCache();

        return redirect()->route('admin.featured-categories.index')
            ->with('success', 'Category added to homepage.');
    }

    public function destroy(FeaturedHomepageCategory $featuredCategory)
    {
        $featuredCategory->delete();
        FeaturedHomepageCategory::clearCache();

        return redirect()->route('admin.featured-categories.index')
            ->with('success', 'Category removed from homepage.');
    }

    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:featured_homepage_categories,id',
        ]);

        foreach ($validated['order'] as $index => $id) {
            FeaturedHomepageCategory::where('id', $id)->update(['sort_order' => $index]);
        }

        FeaturedHomepageCategory::clearCache();

        return response()->json(['message' => 'Order updated.']);
    }

    public function toggleEnabled(FeaturedHomepageCategory $featuredCategory)
    {
        $featuredCategory->update(['is_enabled' => !$featuredCategory->is_enabled]);
        FeaturedHomepageCategory::clearCache();

        return response()->json([
            'is_enabled' => $featuredCategory->is_enabled,
            'message' => 'Category ' . ($featuredCategory->is_enabled ? 'enabled' : 'disabled') . '.',
        ]);
    }

    public function updateStyle(Request $request, FeaturedHomepageCategory $featuredCategory)
    {
        $validated = $request->validate([
            'display_style' => 'required|in:grid,list,carousel',
        ]);

        $featuredCategory->update($validated);
        FeaturedHomepageCategory::clearCache();

        return response()->json([
            'display_style' => $featuredCategory->display_style,
            'message' => 'Display style updated.',
        ]);
    }
}
