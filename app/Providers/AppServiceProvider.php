<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Category;
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

            $view->with(compact('categories', 'brands'));
        });
    }
}
