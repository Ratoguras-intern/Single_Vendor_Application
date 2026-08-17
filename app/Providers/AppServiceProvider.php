<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\NavigationItem;
use App\Models\NavigationMenu;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        App::setLocale(Session::get('locale', config('app.locale', 'en')));

        View::composer(['layouts.frontend', 'frontend.partials.header', 'frontend.partials.mega-menu', 'frontend.partials.mobile-drawer'], function ($view) {
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

            $megaMenuPromo = Cache::remember('frontend_mega_promo', 300, function () {
                $menu = NavigationMenu::where('slug', 'mega-menu-promo')->enabled()->first();
                if (!$menu) {
                    return [
                        'badge' => 'Featured',
                        'heading' => 'Explore Our Collection',
                        'description' => 'Discover quality products across all categories.',
                        'cta_text' => 'Shop Now',
                        'url' => route('frontend.shop'),
                    ];
                }
                $config = $menu->config ?? [];
                return [
                    'badge' => $config['badge'] ?? 'Featured',
                    'heading' => $config['heading'] ?? 'Explore Our Collection',
                    'description' => $config['description'] ?? 'Discover quality products across all categories.',
                    'cta_text' => $config['cta_text'] ?? 'Shop Now',
                    'url' => isset($config['url']) ? (str_starts_with($config['url'], '/') ? $config['url'] : route($config['url'])) : route('frontend.shop'),
                ];
            });

            $view->with(compact('categories', 'brands', 'headerNavItems', 'mobileNavItems', 'megaMenuPromo'));
        });
    }
}
