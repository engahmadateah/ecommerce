<?php

namespace App\Support;

use App\Models\Order;
use Carbon\CarbonInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Orders as a CSV file. Excel opens it directly (UTF-8 with BOM, so Arabic names are fine).
 */
class OrderCsv
{
    public const HEADERS = [
        'Order', 'Date', 'Status', 'Customer', 'E-mail', 'Items', 'Subtotal', 'Discount',
        'Shipping', 'Tax', 'Total', 'Refunded', 'Paid at', 'Country', 'City',
    ];

    public static function stream(?CarbonInterface $from = null, ?CarbonInterface $to = null): StreamedResponse
    {
        $name = 'orders-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($from, $to) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, self::HEADERS);

            Order::query()
                ->with(['user', 'items', 'shipping'])
                ->when($from, fn ($q) => $q->where('created_at', '>=', $from->copy()->startOfDay()))
                ->when($to, fn ($q) => $q->where('created_at', '<=', $to->copy()->endOfDay()))
                ->orderBy('id')
                ->chunk(500, function ($orders) use ($out) {
                    foreach ($orders as $order) {
                        fputcsv($out, self::row($order));
                    }
                });

            fclose($out);
        }, $name, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public static function row(Order $o): array
    {
        return array_map([self::class, 'safe'], [
            $o->id,
            $o->created_at?->format('Y-m-d H:i'),
            $o->status,
            $o->customerName(),
            $o->customerEmail(),
            $o->items->map(fn ($i) => ($i->name ?? 'Item').' x'.$i->quantity)->implode('; '),
            $o->subtotal,
            $o->discount_total,
            $o->shipping_total,
            $o->tax_total,
            $o->total_price,
            $o->refunded_total,
            $o->paid_at?->format('Y-m-d H:i'),
            $o->shipping?->country,
            $o->shipping?->city,
        ]);
    }

    /** Stops spreadsheet formula injection: text starting with = + - @ would run as a formula in Excel. */
    private static function safe(mixed $value): mixed
    {
        if (is_string($value) && $value !== '' && in_array($value[0], ['=', '+', '-', '@', "\t", "\r"], true)) {
            return "'".$value;
        }

        return $value;
    }
}
