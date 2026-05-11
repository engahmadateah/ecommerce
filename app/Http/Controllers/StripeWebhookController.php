<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Shipping;
use Illuminate\Support\Facades\DB;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $endpoint_secret = config('services.stripe.webhook_secret');

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        } catch (SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        // 🎯 أهم حدث
        if ($event->type === 'checkout.session.completed') {

            $session = $event->data->object;

            $userId = $session->metadata->user_id ?? null;
            $couponId = $session->metadata->coupon_id ?? null;

            $cart = session('cart', []);
            $shippingData = session('shipping_data');

            if (!$userId || empty($cart)) {
                return response('Missing data', 400);
            }

            DB::beginTransaction();

            try {

                // 🧮 total
                $total = collect($cart)->sum(fn ($item) =>
                    $item['price'] * $item['quantity']
                );

                // 🎁 Coupon
                $discount = 0;

                if ($couponId) {
                    $coupon = Coupon::find($couponId);

                    if ($coupon) {
                        $discount = $coupon->type === 'fixed'
                            ? $coupon->value
                            : ($total * $coupon->value) / 100;

                        $coupon->increment('used');
                    }
                }

                $final = max($total - $discount, 0);

                // 🧾 Order
                $order = Order::create([
                    'user_id' => $userId,
                    'total_price' => $final,
                    'status' => 'paid',
                ]);

                // 📦 Items + Stock
                foreach ($cart as $id => $item) {

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

                // 🚚 Shipping
                Shipping::create([
                    'order_id' => $order->id,
                    'full_name' => $shippingData['full_name'] ?? 'N/A',
                    'phone' => $shippingData['phone'] ?? 'N/A',
                    'address' => $shippingData['address_line'] ?? 'N/A',
                    'city' => $shippingData['city'] ?? 'N/A',
                    'country' => 'Unknown',
                    'status' => 'pending',
                ]);

                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                return response('Webhook error', 500);
            }
        }

        return response('Webhook handled', 200);
    }
}