<x-mail::message>
# Low stock

These products have **{{ $threshold }}** or fewer units left:

@foreach ($products as $product)
- {{ $product->name }}@if ($product->sku) ({{ $product->sku }})@endif — **{{ $product->stock }}** left
@endforeach

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
