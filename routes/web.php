<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\OrderReturnController;
use App\Models\Setting;

// 🌍 Language / currency switchers
Route::get('/locale/{locale}', [LocaleController::class, 'locale'])->name('locale');
Route::get('/currency/{code}', [LocaleController::class, 'currency'])->name('currency');

// 🔎 SEO
Route::get('/sitemap.xml', [SeoController::class, 'sitemap']);
Route::get('/robots.txt', [SeoController::class, 'robots']);

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
    Route::get('/profile/export', [ProfileController::class, 'export'])->middleware('throttle:5,1')->name('profile.export');
});

// 🛒 Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/restore/{token}', [\App\Http\Controllers\AbandonedCartController::class, 'restore'])->middleware('throttle:20,1')->name('cart.restore');
Route::get('/cart/reminders/stop/{token}', [\App\Http\Controllers\AbandonedCartController::class, 'stop'])->middleware('throttle:20,1')->name('cart.reminders.stop');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->middleware('throttle:60,1')->name('cart.add');
Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/{id}/decrease', [CartController::class, 'decrease']);
Route::post('/cart/update/{id}', [CartController::class, 'updateQuantity']);

// 🎁 Coupon
Route::post('/apply-coupon', [CartController::class, 'applyCoupon'])->middleware('throttle:10,1');

// 💳 Checkout
Route::group([], function () {

    // صفحة إدخال بيانات الشحن (الضيف يدخل إيميله فقط، بدون حساب)
    Route::get('/checkout', [CheckoutController::class, 'form'])->name('checkout.form');

    // ينشئ طلب pending ويحوّل لصفحة الدفع
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->middleware('throttle:10,1')->name('checkout');

    // الرجوع من Stripe (نجاح / إلغاء)
    Route::get('/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel/{order}', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
});

// 🔔 Stripe webhook (بدون auth/CSRF: موثّق بالتوقيع)
Route::post('/stripe/webhook', StripeWebhookController::class)->name('stripe.webhook');

// Order tracking (no account needed) and invoices
Route::get('/track', [OrderTrackingController::class, 'form'])->name('orders.track.form');
Route::post('/track', [OrderTrackingController::class, 'lookup'])->middleware('throttle:10,1')->name('orders.track.lookup');
Route::get('/track/{token}', [OrderTrackingController::class, 'show'])->middleware('throttle:60,1')->name('orders.track');
Route::get('/track/{token}/invoice', [OrderTrackingController::class, 'invoice'])->middleware('throttle:60,1')->name('orders.track.invoice');
Route::post('/track/{token}/return', [OrderReturnController::class, 'store'])->middleware('throttle:5,1')->name('orders.track.return');
Route::get('/orders/{order}/invoice', [OrderTrackingController::class, 'invoiceForUser'])->middleware('auth')->name('orders.invoice');

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
    ->middleware(['auth', 'throttle:10,1']);

// 💾 Save for later
Route::post('/save-for-later/{id}', [CartController::class, 'saveForLater'])
    ->middleware('auth');

// 📦 Packages
Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
Route::get('/packages/{package}', [PackageController::class, 'show'])->name('packages.show');
Route::post('/packages/add/{package}', [PackageController::class, 'addToCart'])->name('packages.add');

// 🔍 Search
Route::get('/products/search', [ProductController::class, 'search']);

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
Route::post('/contact', [ContactController::class, 'send'])->middleware('throttle:5,1')->name('contact.send');

Route::get('/privacy-policy', function () {

    $settings = Setting::current();

    return view('pages.privacy-policy', compact('settings'));

});

Route::get('/terms', function () {

    $settings = Setting::current();

    return view('pages.terms', compact('settings'));

});

Route::view('/refund-policy', 'pages.policy', [
    'title' => 'Refund & Return Policy',
    'sections' => config('policies.refund'),
]);

Route::view('/shipping-policy', 'pages.policy', [
    'title' => 'Shipping Policy',
    'sections' => config('policies.shipping'),
]);

Route::get('/about', function () {

    $settings = Setting::current();

    return view('pages.about', compact('settings'));

});
require __DIR__.'/auth.php';