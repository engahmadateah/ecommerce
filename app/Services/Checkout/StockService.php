<?php

namespace App\Services\Checkout;

use App\Exceptions\CheckoutException;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;

class StockService
{
    /**
     * Reserves stock for the given lines. Must run inside a DB transaction so a
     * failure on one product rolls back the reservations already made.
     *
     * Each product is decremented with a single conditional UPDATE, so two
     * buyers racing for the last unit can never both succeed.
     *
     * @param iterable<QuoteLine> $lines
     * @throws CheckoutException
     */
    public function reserve(iterable $lines): void
    {
        $needed = [];
        $neededVariants = [];
        $variantProducts = [];

        foreach ($lines as $line) {
            if ($line->productId === null) {
                continue; // bundles don't track their own stock
            }

            if ($line->variantId !== null) {
                $neededVariants[$line->variantId] = ($neededVariants[$line->variantId] ?? 0) + $line->quantity;
                $variantProducts[$line->variantId] = $line->productId;

                continue;
            }

            $needed[$line->productId] = ($needed[$line->productId] ?? 0) + $line->quantity;
        }

        ksort($needed); // consistent lock order avoids deadlocks between checkouts
        ksort($neededVariants);

        foreach ($neededVariants as $variantId => $quantity) {
            $affected = ProductVariant::query()
                ->whereKey($variantId)
                ->where('is_active', true)
                ->where('stock', '>=', $quantity)
                ->decrement('stock', $quantity);

            if ($affected === 0) {
                $variant = ProductVariant::query()->with('product')->find($variantId);
                $name = $variant ? ($variant->product?->name . ' (' . $variant->name . ')') : 'Item';

                throw CheckoutException::outOfStock($name);
            }
        }

        $this->resyncProducts(array_unique(array_values($variantProducts)));

        foreach ($needed as $productId => $quantity) {
            $affected = Product::query()
                ->whereKey($productId)
                ->where('stock', '>=', $quantity)
                ->decrement('stock', $quantity);

            if ($affected === 0) {
                $name = Product::query()->whereKey($productId)->value('name') ?? 'Item';

                throw CheckoutException::outOfStock($name);
            }
        }
    }

    /** Gives back the stock held by an order (cancelled / expired payment). */
    public function release(Order $order): void
    {
        $order->loadMissing('items');

        $returned = [];
        $returnedVariants = [];
        $touched = [];

        foreach ($order->items as $item) {
            if ($item->variant_id) {
                $returnedVariants[$item->variant_id] = ($returnedVariants[$item->variant_id] ?? 0) + $item->quantity;
                $touched[] = $item->product_id;
            } elseif ($item->product_id) {
                $returned[$item->product_id] = ($returned[$item->product_id] ?? 0) + $item->quantity;
            }
        }

        ksort($returnedVariants);

        foreach ($returnedVariants as $variantId => $quantity) {
            ProductVariant::query()->whereKey($variantId)->increment('stock', $quantity);
        }

        $this->resyncProducts(array_unique(array_filter($touched)));

        ksort($returned);

        foreach ($returned as $productId => $quantity) {
            Product::query()->whereKey($productId)->increment('stock', $quantity);
        }
    }

    /**
     * Puts returned goods back on the shelf.
     *
     * @param iterable<\App\Models\OrderItem> $items
     * @param array<int,int> $quantities order_item_id => units coming back
     */
    public function restock(iterable $items, array $quantities): void
    {
        $touched = [];

        foreach ($items as $item) {
            $qty = $quantities[$item->id] ?? 0;
            if ($qty <= 0) {
                continue;
            }

            if ($item->variant_id) {
                ProductVariant::query()->whereKey($item->variant_id)->increment('stock', $qty);
                $touched[] = $item->product_id;
            } elseif ($item->product_id) {
                Product::query()->whereKey($item->product_id)->increment('stock', $qty);
            }
        }

        $this->resyncProducts(array_unique(array_filter($touched)));
    }

    /** Products with variants keep `stock` = sum of their active variants. */
    private function resyncProducts(array $productIds): void
    {
        foreach (Product::query()->whereIn('id', $productIds)->get() as $product) {
            $product->syncStockFromVariants();
        }
    }
}
