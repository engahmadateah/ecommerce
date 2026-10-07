<x-mail::message>
# Your order is on its way

Hi {{ $order->customerName() }}, order **#{{ $order->id }}** has been shipped.

@if ($order->shipping && ($order->shipping->carrier || $order->shipping->tracking_number))
@if ($order->shipping->carrier)
**Carrier:** {{ $order->shipping->carrier }}<br>
@endif
@if ($order->shipping->tracking_number)
**Tracking number:** {{ $order->shipping->tracking_number }}
@endif
@endif

<x-mail::button :url="$order->trackingUrl()">
Track your order
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
