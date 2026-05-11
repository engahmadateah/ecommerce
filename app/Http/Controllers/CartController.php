<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\SavedItem;
use App\Services\CartService;

class CartController extends Controller
{
    protected $cart;

    public function __construct(CartService $cart)
    {
        $this->cart = $cart;
    }

    // ➕ Add to cart
    public function add(Product $product)
    {
        $this->cart->add($product);

        return back()->with('success', 'Added to cart');
    }

    // ❌ Remove item
    public function remove($id)
    {
        $this->cart->remove($id);

        return back()->with('success', 'Item removed');
    }

    // ➖ Decrease quantity
    public function decrease($id)
    {
        $this->cart->decrease($id);

        return back();
    }

    // 🔄 Update quantity
    public function update(Request $request, $id)
    {
        $this->cart->update($id, $request->quantity);

        return back();
    }

    // 🛒 Show cart
    public function index()
    {
        $cart = $this->cart->getCart();
        $cartIds = collect($cart)->keys();

        // 🔥 Suggestions
        $suggestions = Product::whereIn('id', function ($query) use ($cartIds) {
            $query->select('oi2.product_id')
                ->from('order_items as oi1')
                ->join('order_items as oi2', 'oi1.order_id', '=', 'oi2.order_id')
                ->whereIn('oi1.product_id', $cartIds)
                ->whereNotIn('oi2.product_id', $cartIds);
        })
        ->take(4)
        ->get();

        // 💰 Total
        $total = $this->cart->total();

        // 🎁 Auto coupon suggestion (لسه بدون فلترة level)
        $coupon = Coupon::where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->whereColumn('used', '<', 'usage_limit')
            ->first();

        return view('cart.index', [
            'cart' => $cart,
            'total' => $total,
            'suggestions' => $suggestions,
            'autoCoupon' => $coupon,
        ]);
    }

    // 🎁 Apply coupon (🔥 WITH LEVEL SYSTEM)
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return back()->with('error', 'Invalid coupon');
        }

        // ⛔ Expired
        if ($coupon->expires_at && $coupon->expires_at < now()) {
            return back()->with('error', 'Coupon expired');
        }

        // ⛔ Limit reached
        if ($coupon->usage_limit && $coupon->used >= $coupon->usage_limit) {
            return back()->with('error', 'Coupon limit reached');
        }

        // ⭐ LEVEL SYSTEM
        $levels = [
            'bronze' => 1,
            'silver' => 2,
            'gold'   => 3,
        ];

        $userLevel = auth()->user()->level ?? 'bronze';
        $couponLevel = $coupon->required_level ?? 'bronze';

        if (($levels[$userLevel] ?? 1) < ($levels[$couponLevel] ?? 1)) {
            return back()->with('error', 'This coupon is not available for your level');
        }

        // ✅ Apply coupon
        session(['coupon_id' => $coupon->id]);

        return back()->with('success', 'Coupon applied successfully');
    }

    // ❤️ Save for later
    public function saveForLater($id)
    {
        if (!auth()->check()) {
            return back()->with('error', 'Login required');
        }

        SavedItem::firstOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $id
        ]);

        $this->cart->remove($id);

        return back()->with('success', 'Saved for later');
    }

    // 🔄 Update quantity buttons
    public function updateQuantity($id, Request $request, CartService $cart)
    {
        $action = $request->action;

        $cartItems = $cart->getCart();

        if (!isset($cartItems[$id])) {
            return back();
        }

        if ($action === 'increase') {
            $cartItems[$id]['quantity']++;
        }

        if ($action === 'decrease') {
            $cartItems[$id]['quantity']--;
            if ($cartItems[$id]['quantity'] <= 0) {
                unset($cartItems[$id]);
            }
        }

        session()->put('cart', $cartItems);

        return back();
    }
}