<?php

namespace App\Services\Checkout;

use App\Models\Coupon;
use App\Models\Setting;
use App\Support\Money;

class PricingService
{
    /** Stripe rejects card charges below this amount (USD). */
    private const MIN_CHARGE_CENTS = 50;

    public function __construct(private readonly CouponService $coupons)
    {
    }

    /**
     * Builds a quote from an already-synced cart (see CartService::getCart(),
     * which always takes prices from the database) and an optional coupon.
     *
     * @param array<string|int, array<string, mixed>> $cart
     */
    public function quote(array $cart, ?Coupon $coupon = null): CheckoutQuote
    {
        $lines = [];
        $subtotal = 0;

        foreach ($cart as $key => $item) {
            $line = new QuoteLine(
                cartKey: (string) $key,
                name: (string) $item['name'],
                unitPriceCents: Money::toCents($item['price']),
                quantity: (int) $item['quantity'],
                productId: isset($item['product_id']) ? (int) $item['product_id'] : null,
                packageId: isset($item['package_id']) ? (int) $item['package_id'] : null,
                variantId: isset($item['variant_id']) ? (int) $item['variant_id'] : null,
            );

            $lines[] = $line;
            $subtotal += $line->totalCents();
        }

        $discount = $coupon ? $this->coupons->discountCents($coupon, $subtotal) : 0;
        $total = $subtotal - $discount;

        // A coupon must not leave a payable remainder the card network refuses.
        if ($discount > 0 && $total > 0 && $total < self::MIN_CHARGE_CENTS) {
            $discount = max(0, $subtotal - self::MIN_CHARGE_CENTS);
            $total = $subtotal - $discount;
        }

        // $total is now the goods value after discounts. Shipping and tax come on top.
        $setting = Setting::query()->first();
        $hasGoods = $lines !== [];

        $fee = $hasGoods ? Money::toCents($setting?->shipping_fee ?? config('shop.shipping.fee')) : 0;
        $freeOver = $setting?->free_shipping_threshold ?? config('shop.shipping.free_over');
        $freeOverCents = $freeOver !== null && (float) $freeOver > 0 ? Money::toCents($freeOver) : null;

        $shipping = $fee;
        $remaining = null;

        if ($fee > 0 && $freeOverCents !== null) {
            if ($total >= $freeOverCents) {
                $shipping = 0;
            } else {
                $remaining = $freeOverCents - $total;
            }
        }

        $rate = (float) ($setting?->tax_rate ?? config('shop.tax.rate'));
        $included = (bool) ($setting?->tax_included ?? config('shop.tax.included'));
        $label = (string) ($setting?->tax_label ?: config('shop.tax.label'));
        $taxBase = $total + $shipping;
        $tax = 0;
        $taxIncluded = 0;

        if ($hasGoods && $rate > 0) {
            if ($included) {
                $taxIncluded = (int) round($taxBase * $rate / (100 + $rate));
            } else {
                $tax = (int) round($taxBase * $rate / 100);
            }
        }

        return new CheckoutQuote(
            $lines,
            $subtotal,
            $discount,
            $total + $shipping + $tax,
            $coupon,
            $shipping,
            $tax,
            $taxIncluded,
            $label,
            $remaining,
        );
    }
}
