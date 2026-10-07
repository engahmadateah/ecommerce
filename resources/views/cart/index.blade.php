<x-app-layout>
@section('seo_robots', 'noindex,nofollow')

<div class="container-x pb-4 pt-8 sm:pt-12">
    <div class="mb-10 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="h1">{{ __('Your Shopping Cart') }}</h1>
            <p class="lead mt-2 max-w-xl">{{ __('Review your items and complete your premium checkout experience.') }}</p>
        </div>
        @if (count($cart) > 0)
            <p class="badge badge-ink px-4 py-2 text-sm">{{ count($cart) }} {{ __('Items') }}</p>
        @endif
    </div>

    @if (count($cart) > 0)
        <div class="grid items-start gap-8 lg:grid-cols-[1fr_25rem] xl:grid-cols-[1fr_27rem]">

            {{-- ───────── Items + delivery ───────── --}}
            <div class="space-y-6">
                <div class="space-y-4">
                    @foreach ($cart as $id => $item)
                        <article class="flex gap-4 rounded-[1.75rem] border border-line bg-white p-4 sm:gap-6 sm:p-5">
                            <div class="tile h-28 w-24 shrink-0 rounded-2xl sm:h-36 sm:w-32">
                                @if (!empty($item['image']))
                                    <img loading="lazy" decoding="async" src="{{ asset('storage/'.$item['image']) }}" alt="{{ $item['display_name'] ?? $item['name'] }}" class="h-full w-full object-cover">
                                @endif
                            </div>

                            <div class="flex min-w-0 flex-1 flex-col">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <h2 class="line-clamp-2 font-display text-lg font-semibold leading-snug">{{ $item['display_name'] ?? $item['name'] }}</h2>
                                        <p class="mt-1 text-sm text-mute">@money($item['price'])</p>
                                        @if (isset($item['stock']) && $item['stock'] <= 5)
                                            <span class="badge badge-deal mt-2">{{ __('Only :count left', ['count' => $item['stock']]) }}</span>
                                        @endif
                                    </div>
                                    <p class="shrink-0 font-display text-xl font-bold">@money($item['price'] * $item['quantity'])</p>
                                </div>

                                <div class="mt-auto flex flex-wrap items-center justify-between gap-3 pt-4">
                                    <div class="inline-flex items-center rounded-full border border-line bg-fog p-1">
                                        <form method="POST" action="/cart/update/{{ $id }}">
                                            @csrf
                                            <input type="hidden" name="action" value="decrease">
                                            <button type="submit" class="grid h-9 w-9 place-items-center rounded-full text-lg transition hover:bg-white" aria-label="-"><i class="icon-[ph--minus]"></i></button>
                                        </form>
                                        <span class="min-w-9 text-center text-[15px] font-bold tabular-nums">{{ $item['quantity'] }}</span>
                                        <form method="POST" action="/cart/update/{{ $id }}">
                                            @csrf
                                            <input type="hidden" name="action" value="increase">
                                            <button type="submit" class="grid h-9 w-9 place-items-center rounded-full text-lg transition hover:bg-white" aria-label="+"><i class="icon-[ph--plus]"></i></button>
                                        </form>
                                    </div>

                                    <div class="flex items-center gap-1">
                                        @auth
                                            <form method="POST" action="/save-for-later/{{ $id }}">
                                                @csrf
                                                <button type="submit" class="btn btn-ghost btn-sm text-mute"><i class="icon-[ph--heart] text-lg"></i><span class="hidden sm:inline">{{ __('Save for later') }}</span></button>
                                            </form>
                                        @endauth
                                        <form method="POST" action="/cart/{{ $id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-ghost btn-sm text-coral-600 hover:bg-coral-50"><i class="icon-[ph--trash] text-lg"></i><span class="hidden sm:inline">{{ __('Remove') }}</span></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Delivery details: submitted by the button in the summary (form="checkout-form") --}}
                <form id="checkout-form" method="POST" action="/checkout" class="panel">
                    @csrf
                    <h2 class="h3 mb-6 flex items-center gap-3"><span class="grid h-10 w-10 place-items-center rounded-2xl bg-cobalt-50 text-xl text-cobalt-600"><i class="icon-[ph--map-pin]"></i></span>{{ __('Delivery details') }}</h2>

                    <div class="grid gap-4 sm:grid-cols-2">
                        @guest
                            <div class="sm:col-span-2">
                                <label for="email" class="label">{{ __('Email (for your order confirmation)') }}</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" class="field @error('email') is-invalid @enderror" autocomplete="email" required>
                                @error('email')<p class="field-error">{{ $message }}</p>@enderror
                                <p class="mt-2 text-sm text-mute">
                                    {{ __('Checking out as a guest.') }}
                                    <a href="{{ route('login') }}" class="font-bold text-ink underline underline-offset-4">{{ __('Log in') }}</a> {{ __('to earn loyalty points.') }}
                                </p>
                            </div>
                        @endguest
                        <div>
                            <label for="full_name" class="label">{{ __('Full Name') }}</label>
                            <input id="full_name" type="text" name="full_name" value="{{ old('full_name') }}" class="field" autocomplete="name" required>
                        </div>
                        <div>
                            <label for="phone" class="label">{{ __('Phone Number') }}</label>
                            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="field" autocomplete="tel" required>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="address_line" class="label">{{ __('Address') }}</label>
                            <input id="address_line" type="text" name="address_line" value="{{ old('address_line') }}" class="field" autocomplete="street-address" required>
                        </div>
                        <div>
                            <label for="city" class="label">{{ __('City') }}</label>
                            <input id="city" type="text" name="city" value="{{ old('city') }}" class="field" autocomplete="address-level2" required>
                        </div>
                        <div>
                            <label for="postal_code" class="label">{{ __('Postal Code') }}</label>
                            <input id="postal_code" type="text" name="postal_code" value="{{ old('postal_code') }}" class="field" autocomplete="postal-code">
                        </div>
                    </div>
                </form>
            </div>

            {{-- ───────── Summary ───────── --}}
            <aside class="space-y-4 lg:sticky lg:top-28">
                <div class="panel !p-6">
                    <h2 class="h3">{{ __('Order Summary') }}</h2>
                    <p class="mt-1 flex items-center gap-1.5 text-sm text-mute"><i class="icon-[ph--lock-simple] text-base"></i>{{ __('Secure premium checkout') }}</p>

                    @if ($autoCoupon)
                        <div class="mt-5 flex items-center justify-between gap-3 rounded-2xl bg-saffron-300/30 p-4">
                            <div class="min-w-0">
                                <p class="flex items-center gap-1.5 text-sm font-bold"><i class="icon-[ph--ticket] text-lg text-saffron-600"></i>{{ __('Coupon Available') }}</p>
                                <p class="mt-0.5 text-sm text-ink/70">{{ __('Use code:') }} <strong class="font-mono">{{ $autoCoupon->code }}</strong></p>
                            </div>
                            <form method="POST" action="/apply-coupon">
                                @csrf
                                <input type="hidden" name="code" value="{{ $autoCoupon->code }}">
                                <button type="submit" class="btn btn-ink btn-sm">{{ __('Apply Now') }}</button>
                            </form>
                        </div>
                    @endif

                    <form method="POST" action="/apply-coupon" class="mt-5 flex gap-2">
                        @csrf
                        <input type="text" name="code" class="field h-11 flex-1 rounded-full" placeholder="{{ __('Coupon code') }}" autocomplete="off">
                        <button type="submit" class="btn btn-line h-11 px-5">{{ __('Apply') }}</button>
                    </form>

                    <dl class="mt-6 space-y-3 text-[15px]">
                        <div class="flex justify-between"><dt class="text-mute">{{ __('Subtotal') }}</dt><dd class="font-semibold">@money($total)</dd></div>
                        @if ($coupon)
                            <div class="flex justify-between text-mint-600"><dt>{{ __('Discount') }} ({{ $coupon->code }})</dt><dd class="font-semibold">-@money($discount)</dd></div>
                        @endif
                        <div class="flex justify-between">
                            <dt class="text-mute">{{ __('Shipping') }}</dt>
                            <dd class="font-semibold">@if ((float) $shipping > 0) @money($shipping) @else <span class="text-mint-600">{{ __('Free') }}</span> @endif</dd>
                        </div>
                        @if ((float) $tax > 0)
                            <div class="flex justify-between"><dt class="text-mute">{{ $taxLabel }}</dt><dd class="font-semibold">@money($tax)</dd></div>
                        @endif
                    </dl>

                    @if (! is_null($freeShippingRemaining))
                        @php $shipPct = min(100, max(4, round(((float) $total / max(0.01, (float) $total + (float) $freeShippingRemaining)) * 100))); @endphp
                        <div class="mt-5 rounded-2xl bg-cobalt-50 p-4">
                            <p class="flex items-center gap-2 text-sm font-semibold text-cobalt-700"><i class="icon-[ph--truck] text-lg"></i>{{ __('Add :amount more for free shipping', ['amount' => \App\Support\Currency::format($freeShippingRemaining)]) }}</p>
                            <div class="mt-3 h-2 overflow-hidden rounded-full bg-white"><div class="h-full rounded-full bg-cobalt-500 transition-all duration-700" style="width: {{ $shipPct }}%"></div></div>
                        </div>
                    @endif

                    <div class="mt-6 flex items-end justify-between border-t border-line pt-5">
                        <span class="font-semibold">{{ __('Final Total') }}</span>
                        <span class="font-display text-4xl font-bold leading-none">@money($final)</span>
                    </div>
                    @if ((float) $taxIncluded > 0)
                        <p class="mt-2 text-end text-xs text-mute">{{ __('Includes :label of :amount', ['label' => $taxLabel, 'amount' => \App\Support\Currency::format($taxIncluded)]) }}</p>
                    @endif
                    @if (\App\Support\Currency::isConverted())
                        <p class="mt-3 rounded-xl bg-fog p-3 text-xs leading-relaxed text-mute">{{ __('Prices in :currency are estimates. You will be charged in USD.', ['currency' => \App\Support\Currency::code()]) }}</p>
                    @endif

                    <button type="submit" form="checkout-form" class="btn btn-brand btn-lg btn-block mt-6">
                        <i class="icon-[ph--lock-simple-fill] text-xl"></i>{{ __('Complete Checkout') }}
                    </button>
                </div>
            </aside>
        </div>

        @if (isset($suggestions) && $suggestions->count())
            <section class="mt-20 border-t border-line pt-14">
                <h2 class="h2">{{ __('You May Also Like') }}</h2>
                <p class="lead mt-2">{{ __('Curated premium recommendations for you.') }}</p>
                <div class="mt-8 grid grid-cols-2 gap-x-4 gap-y-10 sm:gap-x-6 lg:grid-cols-4">
                    @foreach ($suggestions->take(4) as $p)
                        <x-product-card :product="$p" :index="$loop->index" />
                    @endforeach
                </div>
            </section>
        @endif
    @else
        <div class="mx-auto flex max-w-lg flex-col items-center py-20 text-center">
            <span class="grid h-28 w-28 place-items-center rounded-full bg-cobalt-50 text-6xl text-cobalt-500"><i class="icon-[ph--handbag]"></i></span>
            <h2 class="h2 mt-8">{{ __('Your Cart Is Empty') }}</h2>
            <p class="lead mt-3">{{ __('Looks like you haven’t added anything yet. Start exploring our premium products now.') }}</p>
            <a href="{{ route('home') }}" class="btn btn-brand btn-lg mt-8">{{ __('Continue Shopping') }}<i class="icon-[ph--arrow-right] text-xl rtl:-scale-x-100"></i></a>
        </div>
    @endif
</div>
</x-app-layout>
