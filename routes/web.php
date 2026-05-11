<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ContactController;
use App\Models\Setting;

// 🛍️ Home
Route::get('/', [ProductController::class, 'index'])->name('home');

// 📊 Dashboard
Route::get('/dashboard', [ProductController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// 👤 Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 🛒 Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/{id}/decrease', [CartController::class, 'decrease']);
Route::post('/cart/update/{id}', [CartController::class, 'updateQuantity']);

// 🎁 Coupon
Route::post('/apply-coupon', [CartController::class, 'applyCoupon']);

// 💳 Checkout (🔥 نظيف بدون تكرار)
Route::middleware('auth')->group(function () {

    // صفحة إدخال بيانات الشحن
    Route::get('/checkout', [CheckoutController::class, 'form'])->name('checkout.form');

    // تنفيذ الدفع
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');

});

// ✅ Success
Route::get('/success', [CheckoutController::class, 'success']);

// 📦 Orders
Route::get('/orders', [OrderController::class, 'index'])
    ->middleware('auth');

// ❤️ Wishlist
Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])
    ->middleware('auth');

Route::get('/wishlist', function () {
    $products = auth()->user()->wishlist;
    return view('wishlist.index', compact('products'));
})->middleware('auth');

// ⭐ Reviews
Route::post('/products/{id}/review', [ReviewController::class, 'store'])
    ->middleware('auth');

// 💾 Save for later
Route::post('/save-for-later/{id}', [CartController::class, 'saveForLater'])
    ->middleware('auth');

// 📦 Packages
Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
Route::get('/packages/{package}', [PackageController::class, 'show'])->name('packages.show');
Route::post('/packages/add/{package}', [PackageController::class, 'addToCart'])->name('packages.add');

// 🔍 Search
Route::get('/products/search', [ProductController::class, 'search']);
Route::get('/products/autocomplete', [ProductController::class, 'autocomplete']);

// 📦 Product Details (آخر شي)
Route::get('/products/{product}', [ProductController::class, 'show'])
    ->name('products.show');

// 🎟️ Coupons
Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');
Route::get('/coupons/{coupon}', [CouponController::class, 'show'])->name('coupons.show');

// 🔥 Deals
Route::get('/deals', [ProductController::class, 'deals'])->name('products.deals');

// 📞 Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::get('/privacy-policy', function () {

    $settings = Setting::first();

    return view('pages.privacy-policy', compact('settings'));

});

Route::get('/terms', function () {

    $settings = Setting::first();

    return view('pages.terms', compact('settings'));

});

Route::get('/about', function () {

    $settings = Setting::first();

    return view('pages.about', compact('settings'));

});
require __DIR__.'/auth.php';