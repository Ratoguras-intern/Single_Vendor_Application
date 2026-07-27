<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\Concerns\FilterProducts;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    use FilterProducts;

    public function __invoke(Request $request)
    {
        $query = Product::with(['images', 'category', 'brand'])
            ->active();

        if ($categorySlug = $request->input('category')) {
            $category = Category::where('slug', $categorySlug)->active()->first();
            if ($category) {
                $childIds = $category->children()->active()->pluck('id');
                $query->whereIn('category_id', $childIds->push($category->id));
            }
        }

        $query = $this->applyProductFilters($query, $request);
        $query = $this->applyProductSort($query, $request);

        $products = $query->paginate(24)->withQueryString();

        $mappedProducts = $products->getCollection()
            ->map(fn ($p) => $this->mapProduct($p))
            ->toArray();
        $products->setCollection(collect($mappedProducts));

        $brands = Brand::where('status', true)
            ->withCount(['products' => fn ($q) => $q->active()])
            ->orderBy('name')
            ->get();

        $categories = Category::active()->ordered()
            ->withCount(['products' => fn ($q) => $q->active()])
            ->get();

        $priceRange = Product::active()
            ->selectRaw('MIN(COALESCE(discount_price, price)) as min_price, MAX(price) as max_price')
            ->first();

        $recommendations = $this->getRecommendations();

        if ($request->ajax()) {
            return view('frontend.partials.shop-products-content', [
                'products' => $products,
                'viewMode' => $request->input('view', 'grid'),
                'ajax' => true,
            ]);
        }

        return view('frontend.shop', compact(
            'products', 'brands', 'categories', 'priceRange', 'recommendations'
        ));
    }

    protected function getRecommendations(): array
    {
        $limit = 8;

        $trending = Product::with(['images', 'brand'])
            ->active()
            ->withSum('orderItems', 'quantity')
            ->orderByDesc('order_items_sum_quantity')
            ->limit($limit)
            ->get()
            ->map(fn ($p) => $this->mapProduct($p))
            ->filter(fn ($p) => $p['id'])
            ->values()
            ->toArray();

        $newArrivals = Product::with(['images', 'brand'])
            ->active()
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
}
