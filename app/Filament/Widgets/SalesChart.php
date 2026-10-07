<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class SalesChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Sales, last 30 days';

    protected int|string|array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $from = now()->subDays(29)->startOfDay();

        $perDay = Order::query()
            ->whereNotNull('paid_at')
            ->where('paid_at', '>=', $from)
            ->get(['paid_at', 'total_price', 'refunded_total'])
            ->groupBy(fn (Order $o) => $o->paid_at->format('Y-m-d'))
            ->map(fn ($orders) => round($orders->sum(fn ($o) => (float) $o->total_price - (float) $o->refunded_total), 2));

        $labels = [];
        $values = [];
        for ($i = 0; $i < 30; $i++) {
            $day = $from->copy()->addDays($i);
            $labels[] = $day->format('M d');
            $values[] = $perDay[$day->format('Y-m-d')] ?? 0;
        }

        return [
            'datasets' => [['label' => 'Sales (USD)', 'data' => $values, 'tension' => 0.3]],
            'labels' => $labels,
        ];
    }
}
