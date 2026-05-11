<?php

namespace App\Services;

use App\Models\Product;

class CartService
{
    // 🛒 Get cart
    public function getCart()
    {
        return session()->get('cart', []);
    }

    // 💾 Save cart
    private function save($cart)
    {
        session()->put('cart', $cart);
    }

    // ➕ Add to cart
    public function add(Product $product, $quantity = 1)
    {
        $cart = $this->getCart();

        // 🔥 أهم سطر (السعر مع الخصم)
        $price = ($product->discount_price && $product->discount_price < $product->price)
            ? $product->discount_price
            : $product->price;

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $price, // ✅ السعر بعد الخصم
                'original_price' => $product->price, // 👈 للعرض فقط
                'quantity' => $quantity,
                'image' => $product->image,
            ];
        }

        $this->save($cart);
    }

    // ➖ Decrease quantity
    public function decrease($productId)
    {
        $cart = $this->getCart();

        if (!isset($cart[$productId])) return;

        $cart[$productId]['quantity']--;

        if ($cart[$productId]['quantity'] <= 0) {
            unset($cart[$productId]);
        }

        $this->save($cart);
    }

    // 🔄 Update quantity
    public function update($productId, $quantity)
    {
        $cart = $this->getCart();

        if (!isset($cart[$productId])) return;

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId]['quantity'] = $quantity;
        }

        $this->save($cart);
    }

    // ❌ Remove item
    public function remove($productId)
    {
        $cart = $this->getCart();

        unset($cart[$productId]);

        $this->save($cart);
    }

    // 🧹 Clear cart
    public function clear()
    {
        session()->forget('cart');
    }

    // 💰 Total
    public function total()
    {
        return collect($this->getCart())
            ->sum(fn ($item) => $item['price'] * $item['quantity']);
    }

    // 💵 Subtotal per item
    public function subtotal($productId)
    {
        $cart = $this->getCart();

        if (!isset($cart[$productId])) return 0;

        return $cart[$productId]['price'] * $cart[$productId]['quantity'];
    }

    // ❤️ Save for later
    public function saveForLater($productId)
    {
        $cart = $this->getCart();
        $saved = session()->get('saved', []);

        if (!isset($cart[$productId])) return;

        $saved[$productId] = $cart[$productId];

        unset($cart[$productId]);

        session()->put('saved', $saved);
        $this->save($cart);
    }

    // 🔁 Move back to cart
    public function moveToCart($productId)
    {
        $cart = $this->getCart();
        $saved = session()->get('saved', []);

        if (!isset($saved[$productId])) return;

        $cart[$productId] = $saved[$productId];

        unset($saved[$productId]);

        session()->put('saved', $saved);
        $this->save($cart);
    }
}