<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeaturedCategoryController;
use App\Http\Controllers\Admin\FooterController;
use App\Http\Controllers\Admin\HomepageSectionController;
use App\Http\Controllers\Admin\NavigationController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductSectionController;
use App\Http\Controllers\Admin\ReturnController as AdminReturnController;
use App\Http\Controllers\Admin\SaleBannerController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\ReturnController as CustomerReturnController;
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
use App\Http\Controllers\Frontend\PageController as FrontendPageController;
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
Route::post('/contact', [ContactController::class, 'store'])->name('frontend.contact.store');
Route::get('/about', AboutController::class)->name('frontend.about');

require __DIR__.'/auth.php';

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
        Route::post('/orders/{order}/cancel', [CustomerOrderController::class, 'cancel'])->name('orders.cancel');
        Route::match(['get', 'post'], '/orders/{order}/confirm-delivery', [CustomerOrderController::class, 'confirmDelivery'])->name('orders.confirmDelivery');
        Route::get('/orders/{order}/receipt', [CustomerOrderController::class, 'receipt'])->name('orders.receipt');

        Route::get('/returns', [CustomerReturnController::class, 'index'])->name('returns.index');
        Route::get('/returns/{return}', [CustomerReturnController::class, 'show'])->name('returns.show');
        Route::get('/orders/{order}/returns', fn (Order $order) => redirect()->route('customer.returns.create', $order));
        Route::get('/orders/{order}/returns/create', [CustomerReturnController::class, 'create'])->name('returns.create');
        Route::post('/orders/{order}/returns', [CustomerReturnController::class, 'store'])->name('returns.store');
        Route::post('/returns/{return}/add-info', [CustomerReturnController::class, 'addInfo'])->name('returns.addInfo');
        Route::post('/returns/{return}/ship', [CustomerReturnController::class, 'shipReturn'])->name('returns.ship');
        Route::post('/returns/{return}/cancel', [CustomerReturnController::class, 'cancel'])->name('returns.cancel');
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
        Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');

        Route::get('/returns', [AdminReturnController::class, 'index'])->name('returns.index');
        Route::get('/returns/{return}', [AdminReturnController::class, 'show'])->name('returns.show');
        Route::post('/returns/{return}/approve', [AdminReturnController::class, 'approve'])->name('returns.approve');
        Route::post('/returns/{return}/reject', [AdminReturnController::class, 'reject'])->name('returns.reject');
        Route::post('/returns/{return}/more-info', [AdminReturnController::class, 'requestMoreInfo'])->name('returns.moreInfo');
        Route::post('/returns/{return}/receive', [AdminReturnController::class, 'markReceived'])->name('returns.receive');
        Route::post('/returns/{return}/refund', [AdminReturnController::class, 'processRefund'])->name('returns.refund');
        Route::post('/returns/{return}/complete', [AdminReturnController::class, 'complete'])->name('returns.complete');

        Route::delete('/customers/bulk-destroy', [CustomerController::class, 'bulkDestroy'])->name('customers.bulkDestroy');
        Route::resource('customers', CustomerController::class)->only(['index', 'show', 'destroy']);
        Route::get('/customers/{customer}/orders', [CustomerController::class, 'orders'])->name('customers.orders');

        // Pages
        Route::delete('/pages/bulk-destroy', [PageController::class, 'bulkDestroy'])->name('pages.bulkDestroy');
        Route::patch('/pages/{page}/toggle-status', [PageController::class, 'toggleStatus'])->name('pages.toggleStatus');
        Route::resource('pages', PageController::class);

        // Footer
        Route::get('/footer', [FooterController::class, 'edit'])->name('footer.edit');
        Route::put('/footer/{homepageSection}', [FooterController::class, 'update'])->name('footer.update');

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

        // Navigation Management (super_admin only)
        Route::middleware('role:super_admin')->group(function () {
            Route::get('/navigations', [NavigationController::class, 'index'])->name('navigations.index');
            Route::get('/navigations/{navigation}', [NavigationController::class, 'show'])->name('navigations.show');
            Route::post('/navigations/{navigation}/items', [NavigationController::class, 'storeItem'])->name('navigations.storeItem');
            Route::put('/navigations/{navigation}/items/{item}', [NavigationController::class, 'updateItem'])->name('navigations.updateItem');
            Route::delete('/navigations/{navigation}/items/{item}', [NavigationController::class, 'destroyItem'])->name('navigations.destroyItem');
            Route::patch('/navigations/{navigation}/items/{item}/toggle', [NavigationController::class, 'toggleItem'])->name('navigations.toggleItem');
            Route::patch('/navigations/{navigation}/items/order', [NavigationController::class, 'updateOrder'])->name('navigations.updateOrder');
            Route::put('/navigations/{navigation}/config', [NavigationController::class, 'updateConfig'])->name('navigations.updateConfig');
            Route::patch('/navigations/{navigation}/toggle', [NavigationController::class, 'toggleMenu'])->name('navigations.toggleMenu');
    });
});

Route::get('/{slug}', [FrontendPageController::class, 'show'])->name('frontend.page')->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*');
