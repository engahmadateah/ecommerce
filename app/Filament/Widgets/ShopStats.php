<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ShopStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $paid = fn () => Order::query()->whereNotNull('paid_at');
        $net = 'COALESCE(SUM(total_price - refunded_total), 0)';

        $today = (float) $paid()->where('paid_at', '>=', now()->startOfDay())->selectRaw("$net as t")->value('t');
        $month = (float) $paid()->where('paid_at', '>=', now()->subDays(30))->selectRaw("$net as t")->value('t');
        $orders = $paid()->where('paid_at', '>=', now()->subDays(30))->count();
        $awaiting = Order::query()->where('status', 'paid')->count();
        $returns = OrderReturn::query()->where('status', OrderReturn::REQUESTED)->count();
        $low = Product::query()->where('stock', '<=', (int) config('shop.low_stock_threshold', 5))->count();

        return [
            Stat::make('Sales today', '$'.number_format($today, 2))->description('after refunds'),
            Stat::make('Sales, last 30 days', '$'.number_format($month, 2))->description($orders.' paid orders'),
            Stat::make('To ship', (string) $awaiting)->description('paid, not shipped yet')->color($awaiting ? 'warning' : 'success'),
            Stat::make('Return requests', (string) $returns)->description('waiting for you')->color($returns ? 'danger' : 'success'),
            Stat::make('Low stock', (string) $low)->description('products running out')->color($low ? 'danger' : 'success'),
        ];
    }
}
