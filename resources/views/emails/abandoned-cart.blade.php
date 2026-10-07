<x-mail::message>
# Still thinking it over?

Hi {{ $cart->user->name }}, you left these items in your cart:

@foreach ($cart->items as $item)
- {{ $item['name'] }} × {{ $item['quantity'] }}
@endforeach

<x-mail::button :url="$cart->restoreUrl()">
Return to your cart
</x-mail::button>

Items and prices are checked again when you open your cart, and stock is limited.

Thanks,<br>
{{ config('app.name') }}

<small>[Don't remind me about my cart again]({{ $cart->stopUrl() }})</small>
</x-mail::message>
