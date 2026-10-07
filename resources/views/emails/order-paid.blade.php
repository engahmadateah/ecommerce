<x-mail::message>
# Thank you, {{ $order->customerName() }}!

We received your payment for order **#{{ $order->id }}**. We'll start preparing it right away.

<x-mail::table>
| Item | Qty | Price |
|:-----|:---:|------:|
@foreach ($order->items as $item)
| {{ $item->name }} | {{ $item->quantity }} | ${{ number_format($item->price * $item->quantity, 2) }} |
@endforeach
</x-mail::table>

@if ($order->discount_total > 0)
Discount: **-${{ number_format($order->discount_total, 2) }}**
@endif

@if ((float) $order->shipping_total > 0)
Shipping: **${{ number_format($order->shipping_total, 2) }}**
@endif

@if ((float) $order->tax_total > 0)
{{ $order->tax_included ? 'Includes tax' : 'Tax' }}: **${{ number_format($order->tax_total, 2) }}**
@endif

**Total paid: ${{ number_format($order->total_price, 2) }}**

@if ($order->shipping)
**Shipping to:** {{ $order->shipping->full_name }}, {{ $order->shipping->address }}, {{ $order->shipping->city }}, {{ $order->shipping->country }}
@endif

<x-mail::button :url="$order->trackingUrl()">
Track your order
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
