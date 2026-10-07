<?php

namespace App\Services\Returns;

use App\Enums\OrderStatus;
use App\Exceptions\ReturnException;
use App\Models\Order;
use App\Models\OrderReturn;
use App\Services\Checkout\StockService;
use App\Services\Notifications\ReturnNotifier;
use App\Services\Payment\PaymentGateway;
use App\Support\Money;
use Illuminate\Support\Facades\DB;

/**
 * Customer return requests and the admin side: approve / reject / refund.
 *
 * Refunds go back to the original card through the payment gateway. The
 * refund is capped at what was actually paid, and the gateway call uses an
 * idempotency key, so clicking twice (or retrying after an error) can never
 * refund twice.
 */
class ReturnService
{
    public function __construct(
        private readonly PaymentGateway $gateway,
        private readonly StockService $stock,
        private readonly ReturnNotifier $notifier,
    ) {
    }

    /** Why this order can't take a return request right now, or null if it can. */
    public function blockedReason(Order $order): ?string
    {
        if (! in_array($order->status, [OrderStatus::Paid->value, OrderStatus::Shipped->value, OrderStatus::Completed->value], true)
            || $order->paid_at === null) {
            return __('This order is not eligible for a return.');
        }

        $days = (int) config('shop.returns.days', 14);

        if ($order->paid_at->copy()->addDays($days)->isPast()) {
            return __('The return period of :days days has ended.', ['days' => $days]);
        }

        if ($order->returns()->whereIn('status', [OrderReturn::REQUESTED, OrderReturn::APPROVED])->exists()) {
            return __('A return request for this order is already being processed.');
        }

        if (Money::toCents($order->refunded_total) >= Money::toCents($order->total_price)) {
            return __('This order has already been fully refunded.');
        }

        return null;
    }

    /** How many units of each order item can still be returned. @return array<int,int> */
    public function returnable(Order $order): array
    {
        $order->loadMissing('items');

        $taken = [];
        foreach ($order->returns()->whereIn('status', [OrderReturn::REQUESTED, OrderReturn::APPROVED, OrderReturn::REFUNDED])->get() as $return) {
            foreach ($return->quantities() as $itemId => $qty) {
                $taken[$itemId] = ($taken[$itemId] ?? 0) + $qty;
            }
        }

        $out = [];
        foreach ($order->items as $item) {
            $left = $item->quantity - ($taken[$item->id] ?? 0);
            if ($left > 0) {
                $out[$item->id] = $left;
            }
        }

        return $out;
    }

    /**
     * @param array<int,int|string> $quantities order_item_id => quantity wanted back
     * @throws ReturnException
     */
    public function request(Order $order, array $quantities, string $reason, ?string $details): OrderReturn
    {
        return tap(DB::transaction(function () use ($order, $quantities, $reason, $details) {
            $locked = Order::query()->whereKey($order->id)->lockForUpdate()->firstOrFail();

            if ($blocked = $this->blockedReason($locked)) {
                throw new ReturnException($blocked);
            }

            $available = $this->returnable($locked);
            $items = [];

            foreach ($quantities as $itemId => $qty) {
                $qty = (int) $qty;
                if ($qty <= 0) {
                    continue;
                }

                if (! isset($available[(int) $itemId]) || $qty > $available[(int) $itemId]) {
                    throw new ReturnException(__('Invalid quantity selected for a returned item.'));
                }

                $items[] = ['order_item_id' => (int) $itemId, 'quantity' => $qty];
            }

            if ($items === []) {
                throw new ReturnException(__('Select at least one item to return.'));
            }

            return $locked->returns()->create([
                'status' => OrderReturn::REQUESTED,
                'reason' => in_array($reason, OrderReturn::REASONS, true) ? $reason : 'other',
                'details' => $details,
                'items' => $items,
            ]);
        }), fn (OrderReturn $return) => $this->notifier->requested($return));
    }

    /** What to give back for the items of a return (after the order's discount), in cents. */
    public function suggestedRefundCents(OrderReturn $return): int
    {
        $order = $return->order()->with('items')->first();
        $subtotal = Money::toCents($order->subtotal);
        $paidForGoods = $subtotal - Money::toCents($order->discount_total);
        $ratio = $subtotal > 0 ? max(0, $paidForGoods) / $subtotal : 1;

        $sum = 0;
        foreach ($return->quantities() as $itemId => $qty) {
            $item = $order->items->firstWhere('id', $itemId);
            if ($item) {
                $sum += Money::toCents($item->price) * $qty;
            }
        }

        return min($this->refundableCents($order), (int) round($sum * $ratio));
    }

    public function refundableCents(Order $order): int
    {
        return max(0, Money::toCents($order->total_price) - Money::toCents($order->refunded_total));
    }

    public function approve(OrderReturn $return, ?string $note = null): OrderReturn
    {
        return $this->transition($return, OrderReturn::APPROVED, $note);
    }

    public function reject(OrderReturn $return, ?string $note = null): OrderReturn
    {
        return $this->transition($return, OrderReturn::REJECTED, $note);
    }

    /**
     * Refunds `$cents` to the customer's card and closes the return.
     *
     * @throws ReturnException
     */
    public function refund(OrderReturn $return, int $cents, bool $restock, ?string $note = null): OrderReturn
    {
        $refunded = DB::transaction(function () use ($return, $cents, $restock, $note) {
            $lockedReturn = OrderReturn::query()->whereKey($return->id)->lockForUpdate()->firstOrFail();
            $order = Order::query()->whereKey($lockedReturn->order_id)->lockForUpdate()->firstOrFail();

            if (! $lockedReturn->isOpen()) {
                throw new ReturnException(__('This return was already processed.'));
            }

            if (! $order->payment_intent_id) {
                throw new ReturnException(__('This order has no payment to refund.'));
            }

            if ($cents < 1 || $cents > $this->refundableCents($order)) {
                throw new ReturnException(__('The refund amount is not valid for this order.'));
            }

            // If this throws, nothing above has been written and the admin can simply retry.
            $refundId = $this->gateway->refund($order->payment_intent_id, $cents, 'return-'.$lockedReturn->id);

            $totalRefunded = Money::toCents($order->refunded_total) + $cents;

            $order->forceFill(['refunded_total' => Money::fromCents($totalRefunded)]);
            if ($totalRefunded >= Money::toCents($order->total_price)) {
                $order->status = OrderStatus::Refunded->value;
            }
            $order->save();

            if ($restock) {
                $order->loadMissing('items');
                $this->stock->restock($order->items, $lockedReturn->quantities());
            }

            $lockedReturn->forceFill([
                'status' => OrderReturn::REFUNDED,
                'refund_amount' => Money::fromCents($cents),
                'refund_id' => $refundId,
                'refunded_at' => now(),
                'restocked' => $restock,
                'admin_note' => $note ?? $lockedReturn->admin_note,
            ])->save();

            return $lockedReturn;
        });

        $this->notifier->updated($refunded);

        return $refunded;
    }

    private function transition(OrderReturn $return, string $to, ?string $note): OrderReturn
    {
        $updated = DB::transaction(function () use ($return, $to, $note) {
            $locked = OrderReturn::query()->whereKey($return->id)->lockForUpdate()->firstOrFail();

            if (! $locked->isOpen()) {
                throw new ReturnException(__('This return was already processed.'));
            }

            $locked->forceFill(['status' => $to, 'admin_note' => $note ?? $locked->admin_note])->save();

            return $locked;
        });

        $this->notifier->updated($updated);

        return $updated;
    }
}
