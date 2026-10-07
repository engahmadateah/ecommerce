<?php

namespace App\Services;

use App\Exceptions\CheckoutException;
use App\Models\Package;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\Cart\AbandonedCartService;
use App\Support\Money;

/**
 * The cart lives in the session, but the session only remembers *what* and
 * *how many*. Names, prices, images and stock are always re-read from the
 * database in getCart(), so the cart can never show or charge a stale price.
 */
class CartService
{
    private const SESSION_KEY = 'cart';
    private const PACKAGE_PREFIX = 'package_';
    private const VARIANT_PREFIX = 'variant_';

    /** The cart exactly as stored in the session (prices may be stale). */
    public function raw(): array
    {
        return session()->get(self::SESSION_KEY, []);
    }

    /** The cart synced with the database. Unavailable items are dropped. */
    public function getCart(): array
    {
        $raw = $this->raw();

        if ($raw === []) {
            return [];
        }

        $synced = $this->sync($raw);
        $this->save($synced, false); // just a refresh: not a customer action

        return $synced;
    }

    /** True when prices, quantities or availability differ from what the session remembers. */
    public function isStale(): bool
    {
        $raw = $this->raw();

        return $this->signature($this->sync($raw)) !== $this->signature($raw);
    }

    /** @throws CheckoutException when the requested quantity exceeds stock */
    public function add(Product $product, int $quantity = 1, ?int $variantId = null): void
    {
        if (! $product->is_published) {
            throw CheckoutException::outOfStock($product->name);
        }

        $variants = $product->activeVariants()->get();

        if ($variants->isNotEmpty()) {
            $variant = $variants->firstWhere('id', $variantId);

            if (! $variant) {
                throw CheckoutException::optionRequired();
            }

            $variant->setRelation('product', $product);

            $cart = $this->getCart();
            $key = self::VARIANT_PREFIX . $variant->id;
            $desired = ($cart[$key]['quantity'] ?? 0) + $quantity;

            if ($variant->stock < $desired) {
                throw CheckoutException::outOfStock($product->name . ' (' . $variant->name . ')');
            }

            $cart[$key] = $this->variantLine($product, $variant, $desired);
            $this->save($cart);

            return;
        }

        $cart = $this->getCart();
        $desired = ($cart[$product->id]['quantity'] ?? 0) + $quantity;

        if ($product->stock < $desired) {
            throw CheckoutException::outOfStock($product->name);
        }

        $cart[$product->id] = $this->productLine($product, $desired);
        $this->save($cart);
    }

    public function addPackage(Package $package): void
    {
        $package->loadMissing('products');

        $cart = $this->getCart();
        $key = self::PACKAGE_PREFIX . $package->id;
        $quantity = ($cart[$key]['quantity'] ?? 0) + 1;

        $cart[$key] = $this->packageLine($package, $quantity);
        $this->save($cart);
    }

    /** @throws CheckoutException when the new quantity exceeds stock */
    public function increase(string|int $key): void
    {
        $cart = $this->getCart();

        if (! isset($cart[$key])) {
            return;
        }

        $item = $cart[$key];
        $quantity = $item['quantity'] + 1;

        if (empty($item['is_package']) && $quantity > ($item['stock'] ?? 0)) {
            throw CheckoutException::outOfStock($item['name']);
        }

        $cart[$key]['quantity'] = $quantity;
        $this->save($cart);
    }

    public function decrease(string|int $key): void
    {
        $cart = $this->getCart();

        if (! isset($cart[$key])) {
            return;
        }

        $cart[$key]['quantity']--;

        if ($cart[$key]['quantity'] <= 0) {
            unset($cart[$key]);
        }

        $this->save($cart);
    }

    public function remove(string|int $key): void
    {
        $cart = $this->raw();
        unset($cart[$key]);

        $this->save($cart);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);

        if ($user = auth()->user()) {
            app(AbandonedCartService::class)->forget($user);
        }
    }

    /**
     * Puts saved items (cart key => quantity) back, e.g. from a reminder e-mail.
     * Prices and stock are re-read from the database like always.
     *
     * @param array<string,int> $quantities
     */
    public function restore(array $quantities): void
    {
        $merged = $this->raw();

        foreach ($quantities as $key => $quantity) {
            $quantity = max(1, (int) $quantity);
            $merged[$key] = ['quantity' => max($quantity, (int) ($merged[$key]['quantity'] ?? 0))];
        }

        $this->save($this->sync($merged));
    }

    // ------------------------------------------------------------------

    private function sync(array $raw): array
    {
        $productIds = [];
        $packageIds = [];
        $variantIds = [];

        foreach ($raw as $key => $item) {
            if ($this->isPackageKey($key)) {
                $packageIds[] = $this->packageId($key);
            } elseif ($this->isVariantKey($key)) {
                $variantIds[] = $this->variantId($key);
            } else {
                $productIds[] = (int) $key;
            }
        }

        $products = Product::query()->published()
            ->withCount(['variants as active_variants_count' => fn ($q) => $q->where('is_active', true)])
            ->whereIn('id', $productIds)->get()->keyBy('id');
        $variants = ProductVariant::query()->active()->with('product')->whereIn('id', $variantIds)->get()->keyBy('id');
        $packages = Package::query()->with('products')->whereIn('id', $packageIds)->get()->keyBy('id');

        $synced = [];

        foreach ($raw as $key => $item) {
            $quantity = max(1, (int) ($item['quantity'] ?? 1));

            if ($this->isPackageKey($key)) {
                $package = $packages->get($this->packageId($key));

                if ($package) {
                    $synced[$key] = $this->packageLine($package, $quantity);
                }

                continue;
            }

            if ($this->isVariantKey($key)) {
                $variant = $variants->get($this->variantId($key));

                if ($variant && $variant->product && $variant->product->is_published && $variant->stock > 0) {
                    $synced[$key] = $this->variantLine($variant->product, $variant, min($quantity, $variant->stock));
                }

                continue;
            }

            $product = $products->get((int) $key);

            // A product that got variants meanwhile can't be bought without choosing one.
            if ($product && $product->active_variants_count === 0 && $product->stock > 0) {
                $synced[$key] = $this->productLine($product, min($quantity, $product->stock));
            }
        }

        return $synced;
    }

    private function productLine(Product $product, int $quantity): array
    {
        return [
            'id' => $product->id,
            'product_id' => $product->id,
            'name' => $product->name,
            'display_name' => $product->localized_name,
            'price' => $product->currentPrice(),
            'original_price' => (float) $product->price,
            'quantity' => $quantity,
            'stock' => (int) $product->stock,
            'image' => $product->image,
            'is_package' => false,
        ];
    }

    private function variantLine(Product $product, ProductVariant $variant, int $quantity): array
    {
        return [
            'id' => self::VARIANT_PREFIX . $variant->id,
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'name' => $product->name . ' — ' . $variant->name,
            'display_name' => $product->localized_name . ' — ' . $variant->name,
            'price' => $variant->currentPrice(),
            'original_price' => $variant->price !== null ? (float) $variant->price : (float) $product->price,
            'quantity' => $quantity,
            'stock' => (int) $variant->stock,
            'image' => $product->image,
            'is_package' => false,
        ];
    }

    private function packageLine(Package $package, int $quantity): array
    {
        return [
            'id' => self::PACKAGE_PREFIX . $package->id,
            'package_id' => $package->id,
            'name' => $package->name,
            'price' => (float) $package->price,
            'original_price' => (float) $package->price,
            'quantity' => $quantity,
            'image' => $package->products->first()?->image,
            'is_package' => true,
        ];
    }

    /** What the customer would notice changing: price and quantity per line. */
    private function signature(array $cart): array
    {
        return collect($cart)
            ->map(fn ($item) => [Money::toCents($item['price'] ?? 0), (int) ($item['quantity'] ?? 0)])
            ->all();
    }

    private function save(array $cart, bool $remember = true): void
    {
        session()->put(self::SESSION_KEY, $cart);

        if ($remember && ($user = auth()->user())) {
            app(AbandonedCartService::class)->remember($user, $cart);
        }
    }

    private function isPackageKey(string|int $key): bool
    {
        return str_starts_with((string) $key, self::PACKAGE_PREFIX);
    }

    private function isVariantKey(string|int $key): bool
    {
        return str_starts_with((string) $key, self::VARIANT_PREFIX);
    }

    private function variantId(string|int $key): int
    {
        return (int) substr((string) $key, strlen(self::VARIANT_PREFIX));
    }

    private function packageId(string|int $key): int
    {
        return (int) substr((string) $key, strlen(self::PACKAGE_PREFIX));
    }
}
