<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Shipping;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Cart is empty');
        }

        $request->validate([
            'full_name'    => 'required|string|max:255',
            'phone'        => 'required|string|max:50',
            'city'         => 'required|string|max:100',
            'address_line' => 'required|string|max:255',
            'postal_code'  => 'nullable|string|max:20',
        ]);

        // ✅ STOCK CHECK
        foreach ($cart as $id => $item) {

            if (!empty($item['is_package'])) {
                continue;
            }

            $product = Product::find($id);

            if (!$product || $product->stock < $item['quantity']) {
                return back()->with('error', "Product {$item['name']} is out of stock");
            }
        }

        // ✅ TOTAL FROM CART
        $total = collect($cart)->sum(function ($item) {
            return (float) $item['price'] * (int) $item['quantity'];
        });

        // ✅ COUPON DISCOUNT ONLY
        $discount = 0;
        $couponId = session('coupon_id');

        if ($couponId) {
            $coupon = Coupon::find($couponId);

            if ($coupon) {

                if ($coupon->expires_at && $coupon->expires_at->isPast()) {
                    session()->forget('coupon_id');
                    $coupon = null;
                } elseif ($coupon->usage_limit && $coupon->used >= $coupon->usage_limit) {
                    session()->forget('coupon_id');
                    $coupon = null;
                } else {
                    $levels = [
                        'bronze' => 1,
                        'silver' => 2,
                        'gold'   => 3,
                    ];

                    $userLevel = Auth::user()->level ?? 'bronze';
                    $couponLevel = $coupon->required_level ?? 'bronze';

                    if (($levels[$userLevel] ?? 1) < ($levels[$couponLevel] ?? 1)) {
                        return back()->with('error', 'This coupon is not available for your level');
                    }

                    $discount = $coupon->type === 'fixed'
                        ? (float) $coupon->value
                        : ($total * (float) $coupon->value) / 100;
                }
            }
        }

        // ✅ FINAL AMOUNT TO PAY
        $final = max($total - $discount, 1);

        // ✅ SAVE CHECKOUT DATA
        session()->put('checkout_summary', [
            'total'     => $total,
            'discount'  => $discount,
            'final'     => $final,
            'coupon_id' => $couponId,
        ]);

        session()->put('shipping_data', $request->only([
            'full_name',
            'phone',
            'city',
            'address_line',
            'postal_code',
        ]));

        // ✅ STRIPE LINE ITEM = FINAL PRICE ONLY
        $lineItems = [
            [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'MyStore Order',
                    ],
                    'unit_amount' => (int) round($final * 100),
                ],
                'quantity' => 1,
            ]
        ];

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'metadata' => [
                    'user_id'   => Auth::id(),
                    'coupon_id' => $couponId,
                    'final'     => $final,
                    'total'     => $total,
                    'discount'  => $discount,
                ],
                'success_url' => url('/success?session_id={CHECKOUT_SESSION_ID}'),
                'cancel_url'  => url('/cart'),
            ]);

            return redirect($session->url);

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect('/');
        }

        Stripe::setApiKey(config('services.stripe.secret'));
        $session = Session::retrieve($sessionId);

        if ($session->payment_status !== 'paid') {
            return redirect('/cart')->with('error', 'Payment not completed');
        }

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect('/');
        }

        DB::beginTransaction();

        try {
            $summary = session('checkout_summary', []);

            $total = $summary['total'] ?? collect($cart)->sum(function ($item) {
                return (float) $item['price'] * (int) $item['quantity'];
            });

            $discount = $summary['discount'] ?? 0;
            $final = $summary['final'] ?? max($total - $discount, 0);

            // ✅ Coupon use count
            $couponId = $session->metadata->coupon_id ?? null;

            if ($couponId) {
                $coupon = Coupon::find($couponId);
                if ($coupon) {
                    $coupon->increment('used');
                }
            }

            // ✅ Create Order
            $order = Order::create([
                'user_id'     => auth()->id(),
                'total_price' => $final,
                'status'      => 'processing',
            ]);

            // ✅ Save items + stock deduction
            foreach ($cart as $id => $item) {

                if (!empty($item['is_package'])) {
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => null,
                        'quantity'   => $item['quantity'],
                        'price'      => $item['price'],
                    ]);

                    continue;
                }

                $product = Product::find($id);

                if (!$product || $product->stock < $item['quantity']) {
                    throw new \Exception("Stock error");
                }

                $product->decrement('stock', $item['quantity']);

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $id,
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                ]);
            }

            // ✅ Shipping
            $shippingData = session('shipping_data', []);

            Shipping::create([
                'order_id'  => $order->id,
                'full_name' => $shippingData['full_name'] ?? 'N/A',
                'phone'     => $shippingData['phone'] ?? 'N/A',
                'address'   => $shippingData['address_line'] ?? 'N/A',
                'city'      => $shippingData['city'] ?? 'N/A',
                'country'   => 'Unknown',
                'status'    => 'pending',
            ]);

            // ✅ Points = final paid amount
            // 1 dollar = 1 point
            $earnedPoints = (int) floor($final);

            /** @var User $user */
            $user = User::whereKey(Auth::id())->lockForUpdate()->first();

            $user->points = (int) ($user->points ?? 0) + $earnedPoints;

            // ✅ Auto level update
            if ($user->points >= 5000) {
                $user->level = 'gold';
            } elseif ($user->points >= 2000) {
                $user->level = 'silver';
            } else {
                $user->level = 'bronze';
            }

            $user->save();

            DB::commit();

            session()->forget('cart');
            session()->forget('coupon_id');
            session()->forget('shipping_data');
            session()->forget('checkout_summary');

            return redirect('/orders')->with('success', 'Order placed successfully 🎉');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect('/cart')->with('error', $e->getMessage());
        }
    }

    public function form()
    {
        return view('checkout.form');
    }
}