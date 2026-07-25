<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Api\CartController as ApiCartController;
use App\Http\Controllers\Api\FavoriteController as ApiFavoriteController;
use App\Http\Controllers\Api\TranslationController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\FavoriteController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ShopController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Services\CurrencyService;

Route::get('/', HomeController::class)->name('frontend.home');

Route::get('/shop', ShopController::class)->name('frontend.shop');

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
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('customer.orders.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/notifications/mark-read', [NotificationController::class, 'markRead'])->name('notifications.markRead');
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
        Route::resource('categories', CategoryController::class);
        Route::resource('brands', BrandController::class);
        Route::resource('products', ProductController::class);
        Route::resource('orders', OrderController::class)->only(['index', 'show']);
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::resource('customers', CustomerController::class)->only(['index', 'show', 'destroy']);
        Route::get('/customers/{customer}/orders', [CustomerController::class, 'orders'])->name('customers.orders');
    });

require __DIR__.'/auth.php';
