<x-mail::message>
# New paid order #{{ $order->id }}

**Customer:** {{ $order->customerName() }} ({{ $order->customerEmail() }}){{ $order->isGuest() ? ' — guest' : '' }}<br>
**Total:** ${{ number_format($order->total_price, 2) }}<br>
@if ((float) $order->shipping_total > 0)
**Shipping:** ${{ number_format($order->shipping_total, 2) }}<br>
@endif
@if ((float) $order->tax_total > 0)
**Tax:** ${{ number_format($order->tax_total, 2) }}
@endif

<x-mail::table>
| Item | Qty | Price |
|:-----|:---:|------:|
@foreach ($order->items as $item)
| {{ $item->name }} | {{ $item->quantity }} | ${{ number_format($item->price * $item->quantity, 2) }} |
@endforeach
</x-mail::table>

@if ($order->shipping)
**Ship to:** {{ $order->shipping->full_name }} — {{ $order->shipping->phone }}<br>
{{ $order->shipping->address }}, {{ $order->shipping->city }}, {{ $order->shipping->country }}
@endif

<x-mail::button :url="url('/admin/orders/'.$order->id.'/edit')">
Open in admin
</x-mail::button>
</x-mail::message>
