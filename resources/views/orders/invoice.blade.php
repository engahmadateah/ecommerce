<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), config('shop.rtl'), true) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>{{ $order->invoiceNumber() }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Cairo', system-ui, -apple-system, 'Segoe UI', Arial, sans-serif; color: #111827; background: #f3f4f6; margin: 0; padding: 24px; }
        .sheet { max-width: 800px; margin: 0 auto; background: #fff; padding: 48px; border-radius: 12px; }
        .row { display: flex; justify-content: space-between; gap: 24px; }
        h1 { font-size: 28px; margin: 0 0 4px; }
        .muted { color: #6b7280; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 32px; }
        th { text-align: start; font-size: 12px; text-transform: uppercase; letter-spacing: .05em; color: #6b7280; border-bottom: 2px solid #e5e7eb; padding: 8px 0; }
        td { padding: 12px 0; border-bottom: 1px solid #f3f4f6; }
        .num { text-align: end; white-space: nowrap; }
        .totals { margin-top: 24px; margin-inline-start: auto; width: 320px; }
        .totals div { display: flex; justify-content: space-between; padding: 6px 0; font-size: 15px; }
        .totals .grand { border-top: 2px solid #111827; margin-top: 8px; padding-top: 12px; font-size: 20px; font-weight: 700; }
        .paid { display: inline-block; border: 2px solid #059669; color: #059669; font-weight: 700; padding: 4px 14px; border-radius: 8px; transform: rotate(-4deg); }
        .actions { max-width: 800px; margin: 0 auto 16px; text-align: end; }
        button { background: #111827; color: #fff; border: 0; border-radius: 10px; padding: 10px 20px; font: inherit; font-weight: 600; cursor: pointer; }
        @media print {
            body { background: #fff; padding: 0; }
            .sheet { padding: 0; border-radius: 0; }
            .actions { display: none; }
        }
    </style>
</head>
<body>
    <div class="actions"><button onclick="window.print()">{{ __('Print / Save as PDF') }}</button></div>

    <div class="sheet">
        <div class="row">
            <div>
                <h1>{{ __('Invoice') }}</h1>
                <div class="muted">{{ $order->invoiceNumber() }}</div>
                <div class="muted">{{ __('Date') }}: {{ $order->paid_at?->format('M d, Y') }}</div>
                <div class="muted">{{ __('Order') }}: #{{ $order->id }}</div>
            </div>
            <div style="text-align: end">
                <strong style="font-size: 20px">{{ $shop?->site_name ?: config('app.name') }}</strong>
                @if ($shop?->address)<div class="muted">{{ $shop->address }}</div>@endif
                @if ($shop?->email)<div class="muted">{{ $shop->email }}</div>@endif
                @if ($shop?->phone)<div class="muted">{{ $shop->phone }}</div>@endif
            </div>
        </div>

        <div class="row" style="margin-top: 32px">
            <div>
                <div class="muted">{{ __('Billed to') }}</div>
                <strong>{{ $order->customerName() }}</strong>
                <div class="muted">{{ $order->customerEmail() }}</div>
                @if ($order->shipping)
                    <div class="muted">{{ $order->shipping->address }}, {{ $order->shipping->city }}, {{ $order->shipping->country }}</div>
                @endif
            </div>
            <div style="text-align: end"><span class="paid">{{ __('PAID') }}</span></div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>{{ __('Item') }}</th>
                    <th class="num">{{ __('Qty') }}</th>
                    <th class="num">{{ __('Price') }}</th>
                    <th class="num">{{ __('Total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->name ?? __('Deleted Product') }}</td>
                        <td class="num">{{ $item->quantity }}</td>
                        <td class="num">${{ number_format($item->price, 2) }}</td>
                        <td class="num">${{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div><span>{{ __('Subtotal') }}</span><span>${{ number_format($order->subtotal, 2) }}</span></div>
            @if ((float) $order->discount_total > 0)
                <div><span>{{ __('Discount') }}@if($order->coupon) ({{ $order->coupon->code }})@endif</span><span>-${{ number_format($order->discount_total, 2) }}</span></div>
            @endif
            @if ((float) $order->shipping_total > 0)
                <div><span>{{ __('Shipping') }}</span><span>${{ number_format($order->shipping_total, 2) }}</span></div>
            @endif
            @if ((float) $order->tax_total > 0)
                <div><span>{{ $order->tax_included ? __('Tax included') : __('Tax') }}</span><span>${{ number_format($order->tax_total, 2) }}</span></div>
            @endif
            <div class="grand"><span>{{ __('Total') }} (USD)</span><span>${{ number_format($order->total_price, 2) }}</span></div>
        </div>

        <p class="muted" style="margin-top: 40px">{{ __('Thank you for your purchase!') }}</p>
    </div>
</body>
</html>
