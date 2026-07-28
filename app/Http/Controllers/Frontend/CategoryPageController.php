<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\Concerns\FilterProducts;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryPageController extends Controller
{
    use FilterProducts;

    public function show(string $slug, Request $request)
    {
        $category = Category::where('slug', $slug)
            ->active()
            ->firstOrFail();

        $children = $category->children()
            ->active()
            ->ordered()
            ->withCount(['products' => fn ($q) => $q->where('status', true)])
            ->get();

        $categoryIds = $children->pluck('id')->push($category->id);

        $query = Product::with(['images', 'brand'])
            ->where('status', true)
            ->whereIn('category_id', $categoryIds);

        $query = $this->applyProductFilters($query, $request);
        $query = $this->applyProductSort($query, $request);

        $products = $query->paginate(24)->withQueryString();

        $mappedProducts = $products->getCollection()->map(fn ($p) => $this->mapProduct($p))->toArray();
        $products->setCollection(collect($mappedProducts));

        $brands = Brand::whereHas('products', function ($q) use ($categoryIds) {
            $q->where('status', true)->whereIn('category_id', $categoryIds);
        })
            ->withCount(['products' => fn ($q) => $q->where('status', true)->whereIn('category_id', $categoryIds)])
            ->orderBy('name')
            ->get();

        $priceRange = Product::where('status', true)
            ->whereIn('category_id', $categoryIds)
            ->selectRaw('MIN(COALESCE(discount_price, price)) as min_price, MAX(price) as max_price')
            ->first();

        $recommendations = $this->getRecommendations($categoryIds);

        $breadcrumbs = $this->buildBreadcrumbs($category);

        $sidebarBanners = Banner::onPage('category')->active()->ordered()->get();

        return view('frontend.category', compact(
            'category',
            'children',
            'products',
            'brands',
            'priceRange',
            'recommendations',
            'breadcrumbs',
            'sidebarBanners'
        ));
    }

    protected function getRecommendations($categoryIds): array
    {
        $limit = 8;

        $trending = Product::with(['images', 'brand'])
            ->where('status', true)
            ->whereIn('category_id', $categoryIds)
            ->withSum('orderItems', 'quantity')
            ->orderByDesc('order_items_sum_quantity')
            ->limit($limit)
            ->get()
            ->map(fn ($p) => $this->mapProduct($p))
            ->filter(fn ($p) => $p['id'])
            ->values()
            ->toArray();

        $newArrivals = Product::with(['images', 'brand'])
            ->where('status', true)
            ->whereIn('category_id', $categoryIds)
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn ($p) => $this->mapProduct($p))
            ->values()
            ->toArray();

        return [
            'trending' => $trending,
            'new_arrivals' => $newArrivals,
        ];
    }

    protected function buildBreadcrumbs(Category $category): array
    {
        $crumbs = [['name' => 'Home', 'url' => route('frontend.home')]];

        if ($category->parent) {
            $crumbs[] = [
                'name' => $category->parent->name,
                'url' => route('frontend.category', $category->parent->slug),
            ];
        }

        $crumbs[] = ['name' => $category->name, 'url' => null];

        return $crumbs;
    }
}
