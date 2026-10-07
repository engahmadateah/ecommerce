<x-app-layout>
@section('seo_robots', 'noindex,nofollow')
@php $confirming = $confirming ?? false; @endphp

<div class="container-x py-16 sm:py-24" @unless ($confirming) data-confetti @endunless>
    <div class="mx-auto flex max-w-xl flex-col items-center text-center">
        <span class="grid h-28 w-28 place-items-center rounded-full text-6xl {{ $confirming ? 'bg-saffron-300/40 text-saffron-600' : 'bg-mint-50 text-mint-500' }}">
            <i class="{{ $confirming ? 'icon-[ph--hourglass-medium]' : 'icon-[ph--check-circle-fill]' }}"></i>
        </span>
        <h1 class="h1 mt-8">{{ $confirming ? __('Payment received') : __('Payment Successful') }}</h1>
        <p class="lead mt-4">
            {{ __('Thank you for your purchase!') }}
            @isset($order)
                {{ __('Order #:id', ['id' => $order->id]) }}@if ($order->customerEmail()) — {{ __('we sent the details to :email', ['email' => $order->customerEmail()]) }}@endif.
            @endisset
        </p>

        <div class="mt-10 flex flex-wrap justify-center gap-3">
            @isset($order)
                <a href="{{ $order->trackingUrl() }}" class="btn btn-ink btn-lg"><i class="icon-[ph--map-pin-line] text-xl"></i>{{ __('Track order') }}</a>
            @endisset
            <a href="{{ route('home') }}" class="btn btn-line btn-lg">{{ __('Back to Shop') }}</a>
        </div>
    </div>
</div>
</x-app-layout>
