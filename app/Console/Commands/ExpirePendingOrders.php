<?php

namespace App\Console\Commands;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Services\Checkout\OrderService;
use Illuminate\Console\Command;

class ExpirePendingOrders extends Command
{
    protected $signature = 'orders:expire-pending {--grace=5 : Minutes to wait after the payment window closes}';

    protected $description = 'Safety net: settle pending orders whose payment window has closed and release their stock';

    public function handle(OrderService $orders): int
    {
        $cutoff = now()->subMinutes((int) $this->option('grace'));
        $count = 0;

        Order::query()
            ->where('status', OrderStatus::Pending->value)
            ->where('expires_at', '<', $cutoff)
            ->orderBy('id')
            ->each(function (Order $order) use ($orders, &$count): void {
                $orders->reconcile($order);
                $count++;
            });

        $this->info("Reconciled {$count} pending order(s).");

        return self::SUCCESS;
    }
}
