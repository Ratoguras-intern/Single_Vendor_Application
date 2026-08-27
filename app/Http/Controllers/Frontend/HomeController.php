<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\FeaturedHomepageCategory;
use App\Models\HomepageSection;
use App\Models\Product;
use App\Models\SaleBanner;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function __invoke()
    {
        $sections = HomepageSection::getCached()->filter(fn ($s) => $s->is_enabled);

        $featuredProducts = $this->getFlaggedProducts('featured', $sections->get('featured-products')?->max_products ?? 8);
        $featuredCollections = $this->getFeaturedCollections();
        $newArrivals = $this->getFlaggedProducts('newArrival', $sections->get('new-arrivals')?->max_products ?? 10);
        $trendingProducts = $this->getFlaggedProducts('trending', $sections->get('trending-products')?->max_products ?? 8);
        $flashSaleProducts = $this->getFlaggedProducts('flashSale', $sections->get('flash-sale')?->max_products ?? 8);
        $bestSellers = $this->getFlaggedProducts('bestSeller', $sections->get('best-sellers')?->max_products ?? 8);
        $recommendedProducts = $this->getFlaggedProducts('recommended', $sections->get('recommended-products')?->max_products ?? 8);
        $popularProducts = $this->getFlaggedProducts('popular', $sections->get('popular-products')?->max_products ?? 8);
        $subcategories = $this->getSubcategories();
        $brands = $this->getBrands();
        $heroBanners = Banner::forPosition('hero')->active()->ordered()->get();
        $saleBanners = SaleBanner::running()->ordered()
            ->with('featuredProduct.images')
            ->get();
        $promotionalBanners = Banner::forPosition('promotional')->active()->ordered()->get();
        $middleBanners = Banner::forPosition('middle')->active()->ordered()->get();
        $featuredBanners = Banner::forPosition('featured-section')->active()->ordered()->get();
        $bottomBanners = Banner::forPosition('bottom')->active()->ordered()->get();
        $sidebarBanners = Banner::forPosition('sidebar')->active()->ordered()->get();

        return view('frontend.home', compact(
            'sections',
            'featuredProducts',
            'featuredCollections',
            'newArrivals',
            'trendingProducts',
            'flashSaleProducts',
            'bestSellers',
            'recommendedProducts',
            'popularProducts',
            'subcategories',
            'brands',
            'heroBanners',
            'saleBanners',
            'promotionalBanners',
            'middleBanners',
            'featuredBanners',
            'bottomBanners',
            'sidebarBanners',
        ));
    }

    protected function getFlaggedProducts(string $scope, int $limit): array
    {
        $products = Product::active()
            ->with(['images', 'brand'])
            ->onSale()
            ->{$scope}()
            ->inRandomOrder()
            ->limit($limit)
            ->get();

        return $this->mapProducts($products);
    }

    protected function getFeaturedCollections(): array
    {
        return FeaturedHomepageCategory::getCached()
            ->map(function (FeaturedHomepageCategory $fc) {
                $category = $fc->category;

                if (! $category) {
                    return null;
                }

                $categoryIds = $category->children()
                    ->active()
                    ->pluck('id')
                    ->push($category->id)
                    ->all();

                $products = Product::active()
                    ->whereIn('category_id', $categoryIds)
                    ->onSale()
                    ->with(['images', 'brand'])
                    ->inRandomOrder()
                    ->limit((int) Setting::get('limits.featured_collection_products', 4))
                    ->get();

                return [
                    'id' => $fc->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'image' => $category->banner_url ?: $category->display_image,
                    'products_count' => $category->total_products_count,
                    'display_style' => $fc->display_style,
                    'url' => route('frontend.category', $category->slug),
                    'products' => $this->mapProducts($products),
                ];
            })
            ->filter()
            ->values()
            ->toArray();
    }

    protected function getSubcategories(): array
    {
        return Category::active()
            ->whereNotNull('parent_id')
            ->with('parent')
            ->withCount(['products' => fn ($q) => $q->where('status', true)])
            ->ordered()
            ->limit((int) Setting::get('limits.subcategories', 10))
            ->get()
            ->map(fn ($cat) => [
                'id' => $cat->id,
                'name' => $cat->name,
                'slug' => $cat->slug,
                'image' => $cat->display_image,
                'products_count' => $cat->total_products_count,
                'parent_name' => $cat->parent?->name,
                'parent_slug' => $cat->parent?->slug,
            ])
            ->toArray();
    }

    protected function getBrands(): array
    {
        return Brand::where('status', true)
            ->withCount(['products' => fn ($q) => $q->where('status', true)])
            ->get()
            ->map(fn ($brand) => [
                'id' => $brand->id,
                'name' => $brand->name,
                'slug' => $brand->slug,
                'logo' => $brand->logo
                    ? Storage::disk('public')->url($brand->logo)
                    : null,
                'products_count' => $brand->products_count,
            ])
            ->toArray();
    }

    protected function mapProducts($products): array
    {
        return $products->map(function ($product) {
            $image = $product->primaryImage();

            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->discount_price ?? $product->price,
                'original_price' => $product->discount_price ? $product->price : null,
                'image' => product_image_url($image?->image),
                'description' => $product->description,
                'stock' => $product->stock,
                'brand' => $product->brand?->name,
                'is_featured' => $product->is_featured,
                'is_new_arrival' => $product->is_new_arrival,
                'is_trending' => $product->is_trending,
                'is_best_seller' => $product->is_best_seller,
                'is_flash_sale' => $product->is_flash_sale,
                'is_recommended' => $product->is_recommended,
                'is_popular' => $product->is_popular,
                'is_limited_edition' => $product->is_limited_edition,
                'average_rating' => (float) $product->average_rating,
                'reviews_count' => $product->reviews_count,
            ];
        })->toArray();
    }
}
