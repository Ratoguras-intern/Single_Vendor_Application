<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeaturedCategoryController;
use App\Http\Controllers\Admin\HomepageSectionController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductSectionController;
use App\Http\Controllers\Admin\SaleBannerController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Api\CartController as ApiCartController;
use App\Http\Controllers\Api\FavoriteController as ApiFavoriteController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\TranslationController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\FavoriteController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ShopController;
use App\Http\Controllers\Frontend\CategoryPageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Services\CurrencyService;

Route::get('/', HomeController::class)->name('frontend.home');

Route::get('/shop', ShopController::class)->name('frontend.shop');

Route::get('/category/{slug}', [CategoryPageController::class, 'show'])->name('frontend.category');

Route::get('/api/search', SearchController::class)->name('api.search');

Route::get('/api/currency/rates', function () {
    $service = new CurrencyService();
    return response()->json($service->getRates());
})->name('api.currency.rates');

Route::post('/language', function (\Illuminate\Http\Request $request) {
    $locale = $request->input('locale', 'en');
    $supported = ['en', 'ja', 'ne'];
    if (in_array($locale, $supported)) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('language.switch');

Route::post('/api/translate', [TranslationController::class, 'translate'])
    ->name('api.translate')
    ->middleware('throttle:30,1');

Route::get('/product/{id}', [App\Http\Controllers\Frontend\ProductController::class, 'show'])->name('frontend.product.show');
Route::get('/cart', CartController::class)->name('frontend.cart');
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('frontend.checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('frontend.checkout.store');
    Route::get('/checkout/confirmation/{orderNumber}', [CheckoutController::class, 'confirmation'])->name('frontend.checkout.confirmation');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('frontend.favorites');
});

Route::prefix('api')->name('api.')->middleware('auth')->group(function () {
    Route::get('/cart', [ApiCartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [ApiCartController::class, 'add'])->name('cart.add');
    Route::put('/cart', [ApiCartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{productId}', [ApiCartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [ApiCartController::class, 'clear'])->name('cart.clear');
    Route::get('/favorites', [ApiFavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{productId}', [ApiFavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::delete('/favorites/{productId}', [ApiFavoriteController::class, 'destroy'])->name('favorites.destroy');
});
Route::get('/contact', ContactController::class)->name('frontend.contact');
Route::get('/about', AboutController::class)->name('frontend.about');

Route::get('/dashboard', function () {
    $role = auth()->user()->role;

    if ($role === 'super_admin') {
        return redirect()->route('superadmin.dashboard');
    }

    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('customer.orders.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:super_admin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\SuperAdmin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('admins', App\Http\Controllers\SuperAdmin\AdminUserController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
            ->parameters(['admins' => 'admin']);
        Route::patch('/admins/{admin}/toggle-status', [App\Http\Controllers\SuperAdmin\AdminUserController::class, 'toggleStatus'])->name('admins.toggleStatus');

        Route::resource('users', App\Http\Controllers\SuperAdmin\UserController::class)
            ->only(['index', 'edit', 'update', 'destroy']);
        Route::patch('/users/{user}/toggle-status', [App\Http\Controllers\SuperAdmin\UserController::class, 'toggleStatus'])->name('users.toggleStatus');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/notifications/mark-read', [NotificationController::class, 'markRead'])->name('notifications.markRead');
    Route::get('/notifications/{notification}/redirect', [NotificationController::class, 'redirect'])->name('notifications.redirect');
});

Route::middleware(['auth', 'customer'])
    ->prefix('account')
    ->name('customer.')
    ->group(function () {
        Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
    });

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/currency', function (\Illuminate\Http\Request $request) {
            $currency = strtoupper((string) $request->input('currency'));
            $supported = array_keys(config('currency.supported', []));
            if (in_array($currency, $supported, true)) {
                session(['admin_currency' => $currency]);
            }
            return response()->json(['currency' => session('admin_currency')]);
        })->name('currency');
        Route::delete('/categories/bulk-destroy', [CategoryController::class, 'bulkDestroy'])->name('categories.bulkDestroy');
        Route::resource('categories', CategoryController::class);
        Route::patch('/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggleStatus');
        Route::patch('/categories/{category}/toggle-featured', [CategoryController::class, 'toggleFeatured'])->name('categories.toggleFeatured');
        Route::patch('/categories/order', [CategoryController::class, 'updateOrder'])->name('categories.updateOrder');
        Route::patch('/categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
        Route::delete('/brands/bulk-destroy', [BrandController::class, 'bulkDestroy'])->name('brands.bulkDestroy');
        Route::patch('/brands/{brand}/toggle-status', [BrandController::class, 'toggleStatus'])->name('brands.toggleStatus');
        Route::resource('brands', BrandController::class);
        Route::patch('/products/{product}/toggle-flag/{flag}', [ProductController::class, 'toggleFlag'])->name('products.toggleFlag');
        Route::patch('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');
        Route::delete('/products/bulk-destroy', [ProductController::class, 'bulkDestroy'])->name('products.bulkDestroy');
        Route::delete('/products/destroy-all', [ProductController::class, 'destroyAll'])->name('products.destroyAll');
        Route::resource('products', ProductController::class);
        Route::resource('orders', OrderController::class)->only(['index', 'show']);
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::patch('/orders/{order}/tracking', [OrderController::class, 'updateTracking'])->name('orders.updateTracking');
        Route::delete('/customers/bulk-destroy', [CustomerController::class, 'bulkDestroy'])->name('customers.bulkDestroy');
        Route::resource('customers', CustomerController::class)->only(['index', 'show', 'destroy']);
        Route::get('/customers/{customer}/orders', [CustomerController::class, 'orders'])->name('customers.orders');

        // Content Management
        Route::get('/homepage-sections', [HomepageSectionController::class, 'index'])->name('homepage-sections.index');
        Route::get('/homepage-sections/{homepageSection}', [HomepageSectionController::class, 'show'])->name('homepage-sections.show');
        Route::put('/homepage-sections/{homepageSection}', [HomepageSectionController::class, 'update'])->name('homepage-sections.update');
        Route::patch('/homepage-sections/{homepageSection}/toggle', [HomepageSectionController::class, 'toggleEnabled'])->name('homepage-sections.toggle');
        Route::patch('/homepage-sections/order', [HomepageSectionController::class, 'updateOrder'])->name('homepage-sections.updateOrder');

        Route::resource('banners', BannerController::class);
        Route::patch('/banners/{banner}/toggle', [BannerController::class, 'toggleEnabled'])->name('banners.toggle');
        Route::post('/banners/{banner}/duplicate', [BannerController::class, 'duplicate'])->name('banners.duplicate');
        Route::post('/banners/reorder', [BannerController::class, 'updateSortOrder'])->name('banners.reorder');

        Route::resource('sale-banners', SaleBannerController::class)->except(['show']);
        Route::patch('/sale-banners/{saleBanner}/toggle', [SaleBannerController::class, 'toggleEnabled'])->name('sale-banners.toggle');
        Route::post('/sale-banners/reorder', [SaleBannerController::class, 'updateSortOrder'])->name('sale-banners.reorder');

        Route::get('/featured-categories', [FeaturedCategoryController::class, 'index'])->name('featured-categories.index');
        Route::post('/featured-categories', [FeaturedCategoryController::class, 'store'])->name('featured-categories.store');
        Route::delete('/featured-categories/{featuredCategory}', [FeaturedCategoryController::class, 'destroy'])->name('featured-categories.destroy');
        Route::patch('/featured-categories/order', [FeaturedCategoryController::class, 'updateOrder'])->name('featured-categories.updateOrder');
        Route::patch('/featured-categories/{featuredCategory}/toggle', [FeaturedCategoryController::class, 'toggleEnabled'])->name('featured-categories.toggle');
        Route::patch('/featured-categories/{featuredCategory}/style', [FeaturedCategoryController::class, 'updateStyle'])->name('featured-categories.updateStyle');

        Route::get('/product-sections/{section}', [ProductSectionController::class, 'index'])->name('product-sections.index');
        Route::post('/product-sections/{section}/assign', [ProductSectionController::class, 'bulkAssign'])->name('product-sections.bulkAssign');
        Route::delete('/product-sections/{section}/{product}', [ProductSectionController::class, 'destroy'])->name('product-sections.remove');
        Route::post('/product-sections/{section}/bulk-remove', [ProductSectionController::class, 'bulkRemove'])->name('product-sections.bulkRemove');
    });

require __DIR__.'/auth.php';
