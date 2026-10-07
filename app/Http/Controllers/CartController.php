<?php

namespace App\Http\Controllers;

use App\Exceptions\CheckoutException;
use App\Models\Product;
use App\Models\SavedItem;
use App\Services\CartService;
use App\Services\Checkout\CouponService;
use App\Services\Checkout\PricingService;
use App\Support\Money;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cart,
        private readonly CouponService $coupons,
        private readonly PricingService $pricing,
    ) {
    }

    public function index(): View
    {
        $cart = $this->cart->getCart();
        $user = auth()->user();

        try {
            $coupon = $this->coupons->resolve(session('coupon_id'), $user);
        } catch (CheckoutException $e) {
            session()->forget('coupon_id');
            session()->flash('error', $e->getMessage());
            $coupon = null;
        }

        $quote = $this->pricing->quote($cart, $coupon);

        return view('cart.index', [
            'cart'        => $cart,
            'total'       => Money::fromCents($quote->subtotalCents),
            'discount'    => Money::fromCents($quote->discountCents),
            'final'       => Money::fromCents($quote->totalCents),
            'shipping'    => Money::fromCents($quote->shippingCents),
            'tax'         => Money::fromCents($quote->taxCents),
            'taxIncluded' => Money::fromCents($quote->taxIncludedCents),
            'taxLabel'    => $quote->taxLabel,
            'freeShippingRemaining' => $quote->freeShippingRemainingCents !== null
                ? Money::fromCents($quote->freeShippingRemainingCents)
                : null,
            'coupon'      => $coupon,
            'suggestions' => Product::query()
                ->boughtTogetherWith(collect($cart)->pluck('product_id'))
                ->take(4)
                ->get(),
            'autoCoupon'  => $this->coupons->suggest($user),
        ]);
    }

    public function add(Request $request, Product $product): RedirectResponse
    {
        $variantId = $request->integer('variant_id') ?: null;


        if (! $variantId && $product->activeVariants()->exists()) {
            return redirect()->route('products.show', $product)
                ->with('error', 'Please choose an option (size, colour, ...) first.');
        }

        return $this->attempt(fn () => $this->cart->add($product, 1, $variantId), 'Added to cart');
    }

    public function remove(string $id): RedirectResponse
    {
        $this->cart->remove($id);

        return back()->with('success', 'Item removed');
    }

    public function decrease(string $id): RedirectResponse
    {
        $this->cart->decrease($id);

        return back();
    }

    public function updateQuantity(string $id, Request $request): RedirectResponse
    {
        $data = $request->validate(['action' => ['required', 'in:increase,decrease']]);

        return $data['action'] === 'increase'
            ? $this->attempt(fn () => $this->cart->increase($id))
            : $this->attempt(fn () => $this->cart->decrease($id));
    }

    public function applyCoupon(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string']]);

        $coupon = $this->coupons->findByCode($request->string('code')->toString());

        if (! $coupon) {
            return back()->with('error', 'Invalid coupon');
        }

        try {
            $this->coupons->validate($coupon, $request->user());
        } catch (CheckoutException $e) {
            return back()->with('error', $e->getMessage());
        }

        session(['coupon_id' => $coupon->id]);

        return back()->with('success', 'Coupon applied successfully');
    }

    public function saveForLater(string $id): RedirectResponse
    {
        if (! ctype_digit($id)) {
            return back()->with('error', 'This item cannot be saved for later.');
        }

        SavedItem::firstOrCreate([
            'user_id'    => auth()->id(),
            'product_id' => (int) $id,
        ]);

        $this->cart->remove($id);

        return back()->with('success', 'Saved for later');
    }

    private function attempt(callable $action, ?string $successMessage = null): RedirectResponse
    {
        try {
            $action();
        } catch (CheckoutException $e) {
            return back()->with('error', $e->getMessage());
        }

        return $successMessage ? back()->with('success', $successMessage) : back();
    }
}
