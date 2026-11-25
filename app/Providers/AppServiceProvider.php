<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\NavigationItem;
use App\Models\NavigationMenu;
use App\Models\Product;
use App\Support\WindowsFilesystem;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('files', fn () => new WindowsFilesystem);
    }

    public function boot(): void
    {
        App::setLocale(Session::get('locale', config('app.locale', 'en')));

        View::composer(['layouts.frontend', 'frontend.partials.header', 'frontend.partials.mobile-drawer'], function ($view) {
            $categories = Cache::remember('frontend_categories', 300, function () {
                return Category::active()
                    ->with([
                        'children' => fn ($q) => $q->active()->ordered()
                            ->withCount(['products' => fn ($q) => $q->where('status', true)]),
                    ])
                    ->withCount(['products' => fn ($q) => $q->where('status', true)])
                    ->ordered()
                    ->get();
            });

            $brands = Cache::remember('frontend_brands', 300, function () {
                return Brand::where('status', true)
                    ->withCount(['products' => fn ($q) => $q->where('status', true)])
                    ->orderBy('name')
                    ->get();
            });

            $headerNavItems = Cache::remember('frontend_header_nav', 300, function () {
                $menu = NavigationMenu::where('slug', 'header-nav')->enabled()->first();
                if (!$menu) {
                    return collect([
                        ['name' => 'Home', 'url' => route('frontend.home')],
                        ['name' => 'Shop', 'url' => route('frontend.shop')],
                        ['name' => 'Categories', 'url' => null, 'config' => ['type' => 'mega-menu']],
                        ['name' => 'New Arrivals', 'url' => route('frontend.shop') . '?sort=newest'],
                        ['name' => 'Sale', 'url' => route('frontend.shop') . '?sort=sale', 'css_class' => 'text-red-500 hover:text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:text-red-300 dark:hover:bg-red-500/10'],
                    ]);
                }
                return NavigationItem::where('menu_id', $menu->id)
                    ->enabled()->ordered()->get()
                    ->map(fn ($item) => [
                        'name' => $item->name,
                        'url' => $item->url ? (str_starts_with($item->url, '/') ? $item->url : route($item->url)) : null,
                        'target' => $item->target,
                        'css_class' => $item->css_class,
                        'config' => $item->config,
                        'badge' => $item->badge,
                    ]);
            });

            $mobileNavItems = Cache::remember('frontend_mobile_nav', 300, function () {
                $menu = NavigationMenu::where('slug', 'mobile-nav')->enabled()->first();
                if (!$menu) {
                    return collect([
                        ['name' => 'Home', 'url' => route('frontend.home'), 'icon_key' => 'home'],
                        ['name' => 'Shop', 'url' => route('frontend.shop'), 'icon_key' => 'shop'],
                        ['name' => 'New Arrivals', 'url' => route('frontend.shop') . '?sort=newest', 'icon_key' => 'clock'],
                        ['name' => 'Sale', 'url' => route('frontend.shop') . '?sort=sale', 'icon_key' => 'fire', 'css_class' => 'text-red-500 dark:text-red-400'],
                        ['name' => 'Wishlist', 'url' => route('frontend.favorites'), 'icon_key' => 'heart'],
                        ['name' => 'Cart', 'url' => route('frontend.cart'), 'icon_key' => 'cart'],
                        ['name' => 'Contact', 'url' => route('frontend.contact'), 'icon_key' => 'mail'],
                        ['name' => 'About', 'url' => route('frontend.about'), 'icon_key' => 'info'],
                    ]);
                }
                return NavigationItem::where('menu_id', $menu->id)
                    ->enabled()->ordered()->get()
                    ->map(fn ($item) => [
                        'name' => $item->name,
                        'url' => $item->url ? (str_starts_with($item->url, '/') ? $item->url : route($item->url)) : '#',
                        'icon_key' => $item->icon_key,
                        'css_class' => $item->css_class,
                        'config' => $item->config,
                        'badge' => $item->badge,
                    ]);
            });

            $megaMenuPromoSlides = Cache::remember('frontend_mega_promo_slides', 300, function () {
                $menu = NavigationMenu::where('slug', 'mega-menu-promo')->enabled()->first();
                $config = $menu?->config ?? [];

                $scopeUrl = fn (string $scope) => match ($scope) {
                    'featured'   => route('frontend.shop') . '?featured=1',
                    'onSale'     => route('frontend.shop') . '?on_sale=1',
                    'trending'   => route('frontend.shop'),
                    'bestSeller' => route('frontend.shop'),
                    default      => route('frontend.shop'),
                };

                // Defaults used the first time, before the panel configures its own slides.
                $defaults = [
                    [
                        'enabled' => true, 'data_scope' => 'featured', 'badge' => 'Curated',
                        'heading' => 'Curated Favorites', 'description' => 'Handpicked products selected for quality.',
                        'cta_text' => 'Shop Now', 'cta_url' => '', 'product_id' => null, 'image' => null,
                    ],
                    [
                        'enabled' => true, 'data_scope' => 'onSale', 'badge' => 'Exclusive',
                        'heading' => 'Exclusive Deals', 'description' => 'Limited-time offers and special promotions.',
                        'cta_text' => 'View Deals', 'cta_url' => '', 'product_id' => null, 'image' => null,
                    ],
                    [
                        'enabled' => true, 'data_scope' => 'trending', 'badge' => 'Trending',
                        'heading' => 'Trending Now', 'description' => 'Products currently getting attention from customers.',
                        'cta_text' => 'Explore Trending', 'cta_url' => '', 'product_id' => null, 'image' => null,
                    ],
                    [
                        'enabled' => true, 'data_scope' => 'bestSeller', 'badge' => 'Best Selling',
                        'heading' => 'Best Sellers', 'description' => 'Popular products customers are buying most.',
                        'cta_text' => 'Shop Best Sellers', 'cta_url' => '', 'product_id' => null, 'image' => null,
                    ],
                ];

                $configured = collect($config['slides'] ?? [])
                    ->filter(fn ($s) => ($s['enabled'] ?? true) && !blank($s['heading'] ?? null))
                    ->values();

                $source = $configured->isNotEmpty() ? $configured : collect($defaults);

                return $source->map(function ($slide) use ($scopeUrl) {
                    $scope = $slide['data_scope'] ?? null;
                    $product = null;

                    if (!empty($slide['product_id'])) {
                        $product = Product::with(['images'])->find($slide['product_id']);
                    } elseif ($scope) {
                        $product = Product::active()->with(['images'])->{$scope}()->latest()->first();
                    }

                    $image = $slide['image'] ?? null;
                    $productName = null;
                    $productUrl = null;
                    if ($product) {
                        $image = $image ?: product_image_url($product->primaryImage()?->image);
                        $productName = $product->name;
                        $productUrl = route('frontend.product.show', $product->id);
                    }

                    $ctaUrl = $slide['cta_url'] ?? null;

                    return [
                        'badge' => $slide['badge'] ?? 'Featured',
                        'heading' => $slide['heading'] ?? 'Explore Our Collection',
                        'description' => $slide['description'] ?? '',
                        'cta_text' => $slide['cta_text'] ?? 'Shop Now',
                        'url' => $ctaUrl
                            ? (str_starts_with($ctaUrl, '/') || str_starts_with($ctaUrl, 'http') ? $ctaUrl : route($ctaUrl))
                            : ($scope ? $scopeUrl($scope) : route('frontend.shop')),
                        'image' => $image ?: null,
                        'product_name' => $productName,
                        'product_url' => $productUrl,
                    ];
                })->values()->all();
            });

            $view->with(compact('categories', 'brands', 'headerNavItems', 'mobileNavItems', 'megaMenuPromoSlides'));
        });
    }
}
