<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductSectionController extends Controller
{
    protected array $sectionMap = [
        'featured-products' => ['slug' => 'featured-products', 'flag' => 'is_featured'],
        'new-arrivals' => ['slug' => 'new-arrivals', 'flag' => 'is_new_arrival'],
        'trending-products' => ['slug' => 'trending-products', 'flag' => 'is_trending'],
        'best-sellers' => ['slug' => 'best-sellers', 'flag' => 'is_best_seller'],
        'flash-sale' => ['slug' => 'flash-sale', 'flag' => 'is_flash_sale'],
        'recommended-products' => ['slug' => 'recommended-products', 'flag' => 'is_recommended'],
        'popular-products' => ['slug' => 'popular-products', 'flag' => 'is_popular'],
    ];

    protected array $sectionLabels = [
        'featured-products' => 'Featured Products',
        'new-arrivals' => 'New Arrivals',
        'trending-products' => 'Trending',
        'best-sellers' => 'Best Sellers',
        'flash-sale' => 'Flash Sale',
        'recommended-products' => 'Recommended',
        'popular-products' => 'Popular',
    ];

    public function index(string $section)
    {
        abort_unless(isset($this->sectionMap[$section]), 404);

        $map = $this->sectionMap[$section];
        $sectionConfig = HomepageSection::where('slug', $map['slug'])->first();
        $maxProducts = $sectionConfig?->max_products ?? 8;

        $assigned = Product::active()
            ->with(['category', 'brand', 'images'])
            ->where($map['flag'], true)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $search = request('search');
        $query = Product::active()->with(['category', 'brand']);
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "{$search}%")
                    ->orWhere('sku', 'like', "{$search}%");
            });
        }

        $existing = $assigned->pluck('id');
        $available = $query->where($map['flag'], false)
            ->whereNotIn('id', $existing)
            ->latest()
            ->limit(20)
            ->get();

        return view('admin.product-sections.index', [
            'sectionKey' => $section,
            'sectionLabel' => $this->sectionLabels[$section],
            'sectionConfig' => $sectionConfig,
            'maxProducts' => $maxProducts,
            'assigned' => $assigned,
            'available' => $available,
            'flag' => $map['flag'],
        ]);
    }

    public function bulkAssign(Request $request, string $section)
    {
        abort_unless(isset($this->sectionMap[$section]), 404);

        $map = $this->sectionMap[$section];

        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        Product::whereIn('id', $validated['product_ids'])
            ->update([$map['flag'] => true]);

        return redirect()->route('admin.product-sections.index', $section)
            ->with('success', count($validated['product_ids']).' products added.');
    }

    public function destroy(string $section, Product $product)
    {
        abort_unless(isset($this->sectionMap[$section]), 404);

        $map = $this->sectionMap[$section];
        $product->update([$map['flag'] => false]);

        return redirect()->route('admin.product-sections.index', $section)
            ->with('success', 'Product removed from section.');
    }

    public function bulkRemove(Request $request, string $section)
    {
        abort_unless(isset($this->sectionMap[$section]), 404);

        $map = $this->sectionMap[$section];

        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        Product::whereIn('id', $validated['product_ids'])
            ->update([$map['flag'] => false]);

        return redirect()->route('admin.product-sections.index', $section)
            ->with('success', count($validated['product_ids']).' products removed.');
    }
}
