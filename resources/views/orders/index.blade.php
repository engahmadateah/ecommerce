<x-app-layout>
@section('seo_robots', 'noindex,nofollow')

<div class="container-x pt-8 sm:pt-12">
    <div class="mb-10 flex flex-wrap items-end justify-between gap-5">
        <div>
            <h1 class="h1">{{ __('My Orders') }}</h1>
            <p class="lead mt-2 max-w-xl">{{ __('Track your purchases, monitor delivery progress, and review your premium shopping history in one elegant place.') }}</p>
        </div>
        <div class="flex gap-3">
            <div class="rounded-3xl border border-line bg-white px-6 py-4"><p class="font-display text-3xl font-bold leading-none">{{ $orders->count() }}</p><p class="mt-1 text-sm text-mute">{{ __('Total Orders') }}</p></div>
            <div class="rounded-3xl border border-line bg-white px-6 py-4"><p class="font-display text-3xl font-bold leading-none">{{ $orders->whereIn('status', ['paid', 'completed'])->count() }}</p><p class="mt-1 text-sm text-mute">{{ __('Completed') }}</p></div>
        </div>
    </div>

    <div class="space-y-5">
        @forelse ($orders as $order)
            @php
                $status = strtolower($order->status);
                $badge = match ($status) {
                    'paid', 'completed' => 'badge-ok',
                    'pending' => 'badge-deal',
                    'cancelled' => 'badge-sale',
                    'shipped' => 'badge-info',
                    default => 'badge-muted',
                };
            @endphp
            <article data-reveal class="overflow-hidden rounded-[2rem] border border-line bg-white shadow-soft">
                <header class="flex flex-wrap items-center justify-between gap-3 border-b border-line bg-fog/60 px-6 py-4 sm:px-8">
                    <div class="flex items-center gap-4">
                        <span class="grid h-11 w-11 place-items-center rounded-2xl bg-white text-xl text-cobalt-600 shadow-soft"><i class="icon-[ph--package]"></i></span>
                        <div>
                            <h2 class="font-display text-lg font-bold">{{ __('Order') }} #{{ $order->id }}</h2>
                            <p class="text-sm text-mute">{{ $order->created_at?->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <span class="badge {{ $badge }} px-3 py-1.5 text-[13px]">{{ __(ucfirst($order->status)) }}</span>
                </header>

                <div class="divide-y divide-line px-6 sm:px-8">
                    @foreach ($order->items as $item)
                        <div class="flex items-center gap-4 py-4">
                            <div class="tile h-16 w-14 shrink-0 rounded-xl">
                                @if ($item->product && $item->product->image)
                                    <img loading="lazy" decoding="async" src="{{ asset('storage/'.$item->product->image) }}" alt="" class="h-full w-full object-cover">
                                @else
                                    <span class="grid h-full place-items-center text-2xl text-mute/40"><i class="icon-[ph--image]"></i></span>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="truncate text-[15px] font-semibold">{{ $item->name ?? $item->product->name ?? __('Deleted Product') }}</h3>
                                <p class="text-sm text-mute">{{ __('Quantity') }}: {{ $item->quantity }} · @money($item->price)</p>
                            </div>
                            <p class="shrink-0 font-display text-base font-bold">@money($item->price * $item->quantity)</p>
                        </div>
                    @endforeach
                </div>

                <footer class="flex flex-wrap items-center justify-between gap-4 border-t border-line px-6 py-5 sm:px-8">
                    <div>
                        <p class="text-sm text-mute">{{ __('Final Payment') }}</p>
                        <p class="font-display text-2xl font-bold leading-none">@money($order->total_price)</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ $order->trackingUrl() }}" class="btn btn-ink btn-sm"><i class="icon-[ph--map-pin-line] text-lg"></i>{{ __('Track order') }}</a>
                        @if ($order->paid_at)
                            <a href="{{ route('orders.invoice', $order) }}" target="_blank" class="btn btn-line btn-sm"><i class="icon-[ph--receipt] text-lg"></i>{{ __('Invoice') }}</a>
                        @endif
                    </div>
                </footer>
            </article>
        @empty
            <div class="mx-auto flex max-w-md flex-col items-center py-16 text-center">
                <span class="grid h-24 w-24 place-items-center rounded-full bg-cobalt-50 text-5xl text-cobalt-500"><i class="icon-[ph--package]"></i></span>
                <h2 class="h2 mt-6">{{ __('No Orders Yet') }}</h2>
                <p class="lead mt-3">{{ __('Your premium shopping journey starts here. Discover luxury products and place your first order today.') }}</p>
                <a href="{{ route('home') }}" class="btn btn-brand btn-lg mt-8">{{ __('Start Shopping') }}</a>
            </div>
        @endforelse
    </div>
</div>
</x-app-layout>
