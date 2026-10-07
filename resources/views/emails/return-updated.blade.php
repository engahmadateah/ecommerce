@php $order = $return->order; @endphp
<x-mail::message>
# Your return for order #{{ $order->id }}

Hi {{ $order->customerName() }},

@if ($return->status === 'refunded')
we refunded **${{ number_format((float) $return->refund_amount, 2) }}** to your original payment method. It can take a few days to appear on your statement.
@elseif ($return->status === 'approved')
your return request was **approved**. We will contact you with the next steps.
@else
we are sorry, your return request was **not approved**.
@endif

@if ($return->admin_note)
> {{ $return->admin_note }}
@endif

<x-mail::button :url="$order->trackingUrl()">
View your order
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
