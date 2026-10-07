<x-app-layout>
@section('seo_title', __('Order').' #'.$order->id)
@section('seo_robots', 'noindex,nofollow')
@php
    $steps = [
        ['key' => 'paid', 'icon' => 'icon-[ph--credit-card]', 'label' => __('Paid')],
        ['key' => 'shipped', 'icon' => 'icon-[ph--truck]', 'label' => __('Shipped')],
        ['key' => 'completed', 'icon' => 'icon-[ph--package]', 'label' => __('Delivered')],
    ];
    $rank = ['pending' => -1, 'cancelled' => -1, 'paid' => 0, 'shipped' => 1, 'completed' => 2, 'refunded' => 2];
    $current = $rank[$order->status] ?? -1;
    $shipping = $order->shipping;
@endphp

<div class="container-x py-10 sm:py-16">
    <div class="mx-auto max-w-3xl space-y-5">

        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-sm font-bold text-cobalt-600">{{ __('Order') }}</p>
                <h1 class="h1">#{{ $order->id }}</h1>
                <p class="mt-1 text-mute">{{ $order->created_at?->format('M d, Y') }}</p>
            </div>
            @if ($order->paid_at)
                <a href="{{ route('orders.track.invoice', $order->public_token) }}" target="_blank" class="btn btn-line"><i class="icon-[ph--receipt] text-xl"></i>{{ __('Invoice') }}</a>
            @endif
        </div>

        {{-- Progress --}}
        @if ($order->status === 'refunded')
            <div class="alert alert-info p-5"><i class="icon-[ph--arrow-counter-clockwise] mt-0.5 shrink-0 text-xl"></i>{{ __('This order was refunded.') }}</div>
        @elseif ($order->status === 'cancelled')
            <div class="alert alert-err p-5"><i class="icon-[ph--x-circle-fill] mt-0.5 shrink-0 text-xl"></i>{{ __('This order was cancelled.') }}</div>
        @elseif ($order->status === 'pending')
            <div class="alert p-5 bg-saffron-300/30 text-ink"><i class="icon-[ph--hourglass-medium] mt-0.5 shrink-0 text-xl text-saffron-600"></i>{{ __('Waiting for the payment to be confirmed.') }}</div>
        @else
            <div class="panel flex items-start">
                @foreach ($steps as $i => $step)
                    <div class="relative flex flex-1 flex-col items-center text-center">
                        @if (! $loop->first)
                            <span class="absolute end-1/2 top-6 h-1 w-full rounded-full {{ $i <= $current ? 'bg-cobalt-500' : 'bg-line' }}"></span>
                        @endif
                        <span class="relative z-10 grid h-12 w-12 place-items-center rounded-full text-2xl ring-4 ring-white {{ $i <= $current ? 'bg-cobalt-500 text-white shadow-glow' : 'bg-tile text-mute' }}">
                            <i class="{{ $step['icon'] }}"></i>
                        </span>
                        <p class="mt-3 text-sm font-bold {{ $i <= $current ? 'text-ink' : 'text-mute' }}">{{ $step['label'] }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        @if ($shipping && ($shipping->carrier || $shipping->tracking_number))
            <div class="panel-flat">
                <h2 class="h3 flex items-center gap-2"><i class="icon-[ph--truck] text-2xl text-cobalt-500"></i>{{ __('Shipment') }}</h2>
                <dl class="mt-4 space-y-2 text-[15px]">
                    @if ($shipping->carrier)<div class="flex justify-between gap-4"><dt class="text-mute">{{ __('Carrier') }}</dt><dd class="font-semibold">{{ $shipping->carrier }}</dd></div>@endif
                    @if ($shipping->tracking_number)<div class="flex justify-between gap-4"><dt class="text-mute">{{ __('Tracking number') }}</dt><dd class="font-mono font-semibold" dir="ltr">{{ $shipping->tracking_number }}</dd></div>@endif
                </dl>
            </div>
        @endif

        {{-- Items --}}
        <div class="panel-flat">
            <h2 class="h3">{{ __('Items') }}</h2>
            <div class="mt-4 divide-y divide-line">
                @foreach ($order->items as $item)
                    <div class="flex items-center justify-between gap-4 py-3 text-[15px]">
                        <span>{{ $item->name ?? __('Deleted Product') }} <span class="text-mute">× {{ $item->quantity }}</span></span>
                        <span class="font-semibold">@money($item->price * $item->quantity)</span>
                    </div>
                @endforeach
            </div>
            <dl class="mt-4 space-y-2 border-t border-line pt-4 text-[15px]">
                @if ((float) $order->discount_total > 0)
                    <div class="flex justify-between text-mint-600"><dt>{{ __('Discount') }}</dt><dd class="font-semibold">-@money($order->discount_total)</dd></div>
                @endif
                @if ((float) $order->shipping_total > 0)
                    <div class="flex justify-between"><dt class="text-mute">{{ __('Shipping') }}</dt><dd class="font-semibold">@money($order->shipping_total)</dd></div>
                @endif
                @if ((float) $order->tax_total > 0)
                    <div class="flex justify-between"><dt class="text-mute">{{ $order->tax_included ? __('Tax included') : __('Tax') }}</dt><dd class="font-semibold">@money($order->tax_total)</dd></div>
                @endif
                <div class="flex items-end justify-between pt-2"><dt class="font-semibold">{{ __('Total') }}</dt><dd class="font-display text-3xl font-bold leading-none">@money($order->total_price)</dd></div>
            </dl>
        </div>

        {{-- Returns --}}
        @if ($order->returns->isNotEmpty())
            <div class="panel-flat">
                <h2 class="h3 flex items-center gap-2"><i class="icon-[ph--arrow-counter-clockwise] text-2xl text-cobalt-500"></i>{{ __('Returns') }}</h2>
                <div class="mt-4 divide-y divide-line">
                    @foreach ($order->returns as $return)
                        <div class="flex flex-wrap items-center justify-between gap-2 py-3 text-[15px]">
                            <span>{{ $return->created_at->format('M d, Y') }} · {{ $return->reasonLabel() }}</span>
                            <span class="badge badge-info">
                                {{ $return->statusLabel() }}
                                @if ($return->status === 'refunded') · @money($return->refund_amount) @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($returnBlocked === null && $returnable)
            <div class="panel-flat" x-data="{ open: {{ $errors->has('return') || $errors->has('reason') || $errors->has('quantities') ? 'true' : 'false' }} }">
                <button type="button" @click="open = !open" class="flex w-full items-center justify-between gap-3 text-start">
                    <span class="h3 flex items-center gap-2"><i class="icon-[ph--arrow-counter-clockwise] text-2xl text-cobalt-500"></i>{{ __('Request a return') }}</span>
                    <i class="icon-[ph--caret-down] text-xl transition" :class="open && 'rotate-180'"></i>
                </button>

                <form x-show="open" x-cloak method="POST" action="{{ route('orders.track.return', $order->public_token) }}" class="mt-6 space-y-5">
                    @csrf
                    @if ($errors->has('return'))<p class="field-error">{{ $errors->first('return') }}</p>@endif

                    <div class="space-y-3">
                        @foreach ($order->items->whereIn('id', array_keys($returnable)) as $item)
                            <label class="flex items-center justify-between gap-4 rounded-2xl bg-fog p-3 ps-4">
                                <span class="text-[15px] font-semibold">{{ $item->name ?? __('Deleted Product') }}</span>
                                <select name="quantities[{{ $item->id }}]" class="field h-10 w-24 rounded-xl">
                                    @for ($q = 0; $q <= $returnable[$item->id]; $q++)
                                        <option value="{{ $q }}" @selected((int) old('quantities.'.$item->id, 0) === $q)>{{ $q }}</option>
                                    @endfor
                                </select>
                            </label>
                        @endforeach
                    </div>

                    <div>
                        <label class="label">{{ __('Reason') }}</label>
                        <select name="reason" class="field">
                            @foreach (\App\Models\OrderReturn::reasonLabels() as $code => $label)
                                <option value="{{ $code }}" @selected(old('reason') === $code)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">{{ __('Details (optional)') }}</label>
                        <textarea name="details" rows="3" maxlength="1000" class="field">{{ old('details') }}</textarea>
                    </div>
                    <p class="text-sm text-mute">{{ __('You can request a return within :days days of payment. The refund goes back to your original payment method.', ['days' => config('shop.returns.days')]) }}</p>
                    <button type="submit" class="btn btn-ink">{{ __('Send return request') }}</button>
                </form>
            </div>
        @endif

        @if ($shipping)
            <div class="panel-flat">
                <h2 class="h3">{{ __('Shipping Information') }}</h2>
                <p class="mt-3 leading-relaxed text-mute">{{ $shipping->full_name }}<br>{{ $shipping->address }}, {{ $shipping->city }}, {{ $shipping->country }}</p>
            </div>
        @endif
    </div>
</div>
</x-app-layout>
