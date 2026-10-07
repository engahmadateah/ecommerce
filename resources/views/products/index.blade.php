<x-app-layout>
@php
    $filtering = request()->filled('search') || request()->filled('category') || request()->integer('page', 1) > 1;
    $heroProducts = $products->filter(fn ($p) => $p->image)->take(3)->values();
    $returnDays = (int) config('shop.returns.days', 14);
    $trust = [
        ['icon' => 'icon-[ph--lock-simple]', 'text' => __('Encrypted checkout on every order')],
        ['icon' => 'icon-[ph--truck]', 'text' => __('Real-time order tracking')],
        ['icon' => 'icon-[ph--arrow-counter-clockwise]', 'text' => __('Easy :days-day returns', ['days' => $returnDays])],
        ['icon' => 'icon-[ph--crown-simple]', 'text' => __('Earn points on every order.')],
        ['icon' => 'icon-[ph--ticket]', 'text' => __('Daily Discounts')],
    ];
    $parallax = 'transition: transform .35s ease-out; transform: translate3d(calc(var(--mx, 0) * %spx), calc(var(--my, 0) * %spx), 0);';
@endphp

@unless ($filtering)
    {{-- ───────────── Hero ───────────── --}}
    <section class="px-3 pt-3 sm:px-5 sm:pt-4">
        <div data-hero class="grain relative isolate overflow-hidden rounded-[2rem] bg-ink text-white sm:rounded-[2.75rem]">
            <div class="aurora" aria-hidden="true"><i></i><i></i><i></i></div>

            <div class="container-x relative grid items-center gap-14 py-16 sm:py-20 lg:grid-cols-[1.05fr_.95fr] lg:py-28">
                <div>
                    <h1 data-hero-item class="display max-w-[15ch] rtl:max-w-[20ch]">{{ __('Curated products. Tracked to your door.') }}</h1>

                    <p data-hero-item class="mt-6 max-w-[48ch] text-lg leading-relaxed text-white/70">
                        {{ __('Browse') }}
                        <b class="font-semibold text-white">{{ number_format($products->total()) }} {{ __('products') }}</b>
                        {{ __('from sellers we vet ourselves. Every payment is encrypted end to end, and every order is tracked from checkout to your door.') }}
                    </p>

                    <div data-hero-item class="mt-9 flex flex-wrap gap-3">
                        <a href="#catalog" class="btn btn-brand btn-lg">
                            {{ __('Browse the catalog') }}
                            <i class="icon-[ph--arrow-down] text-xl"></i>
                        </a>
                        <a href="{{ route('orders.track.form') }}" class="btn glass btn-lg text-white hover:bg-white/20">
                            <i class="icon-[ph--map-pin-line] text-xl"></i>
                            {{ __('Track an order') }}
                        </a>
                    </div>
                </div>

                {{-- Floating real products (parallax follows the pointer) --}}
                <div class="relative mx-auto h-[420px] w-full max-w-[540px] sm:h-[500px]">
                    @if ($heroProducts->count() > 0)
                        @php
                            $slots = [
                                ['pos' => 'end-[2%] top-0 w-[54%] z-10', 'tilt' => 'rotate-[4deg] rtl:-rotate-[4deg]', 'depth' => -26],
                                ['pos' => 'start-0 top-[24%] w-[44%] z-20', 'tilt' => '-rotate-[6deg] rtl:rotate-[6deg]', 'depth' => 34],
                                ['pos' => 'end-[12%] bottom-0 w-[38%] z-30', 'tilt' => 'rotate-[2deg] rtl:-rotate-[2deg]', 'depth' => -44],
                            ];
                        @endphp
                        @foreach ($heroProducts as $i => $hp)
                            @php $slot = $slots[$i]; $hpDeal = $hp->discount_price && (float) $hp->discount_price < (float) $hp->price; @endphp
                            <div class="absolute {{ $slot['pos'] }}" style="{{ sprintf($parallax, $slot['depth'], $slot['depth']) }}">
                                <div data-hero-card class="{{ $slot['tilt'] }}">
                                    <a href="{{ route('products.show', $hp) }}" class="block rounded-[1.75rem] border border-white/20 bg-white p-2 shadow-[0_40px_80px_-24px_rgba(0,0,0,.7)]">
                                        <div class="aspect-[4/5] overflow-hidden rounded-[1.3rem] bg-tile">
                                            <img src="{{ asset('storage/'.$hp->image) }}" alt="{{ $hp->localized_name }}" class="h-full w-full object-cover" @if ($i === 0) fetchpriority="high" @endif>
                                        </div>
                                        <div class="flex items-center justify-between gap-2 px-1.5 pb-1 pt-2.5 text-ink">
                                            <span class="truncate text-[13px] font-semibold">{{ $hp->localized_name }}</span>
                                            <span class="shrink-0 font-display text-[13px] font-bold">@money($hpDeal ? $hp->discount_price : $hp->price)</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="absolute inset-x-[4%] inset-y-[4%]" style="{{ sprintf($parallax, -24, -24) }}">
                            <div data-hero-card class="h-full rotate-[3deg] rtl:-rotate-[3deg]">
                                <div class="h-full overflow-hidden rounded-[2rem] border border-white/20 shadow-[0_40px_80px_-24px_rgba(0,0,0,.7)]">
                                    <img src="{{ asset('images/hero-cart.jpg') }}" alt="" class="h-full w-full object-cover">
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="absolute -bottom-2 start-2 z-40 sm:bottom-4" style="{{ sprintf($parallax, 52, 52) }}">
                        <div data-hero-card>
                            <div class="glass inline-flex items-center gap-2.5 rounded-full py-2.5 pe-5 ps-3 text-sm font-semibold">
                                <span class="grid h-8 w-8 place-items-center rounded-full bg-mint-500 text-lg text-white"><i class="icon-[ph--shield-check-fill]"></i></span>
                                {{ __('Checkout secured, end to end') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ───────────── Trust marquee ───────────── --}}
    <div class="marquee py-6" dir="ltr" aria-label="{{ __('Why shop with us') }}">
        <div class="marquee-track">
            @foreach ([1, 2] as $copy)
                <ul class="flex shrink-0 items-center" @if ($copy === 2) aria-hidden="true" @endif dir="{{ in_array(app()->getLocale(), config('shop.rtl'), true) ? 'rtl' : 'ltr' }}">
                    @foreach ($trust as $item)
                        <li class="flex items-center gap-3 px-8 text-[15px] font-semibold text-ink">
                            <i class="{{ $item['icon'] }} text-xl text-cobalt-500"></i>{{ $item['text'] }}
                            <span class="ms-8 h-1.5 w-1.5 rounded-full bg-line"></span>
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </div>
    </div>

    {{-- ───────────── Today's deals ───────────── --}}
    @if ($deals->count() >= 2)
        <section class="container-x pt-10" data-rail-scope>
            <div class="grid gap-6 lg:grid-cols-[22rem_minmax(0,1fr)] lg:gap-10">
                <div class="relative isolate flex flex-col justify-between overflow-hidden rounded-[2rem] bg-linear-to-br from-cobalt-500 via-cobalt-600 to-[#6d4cff] p-8 text-white">
                    <i class="icon-[ph--fire-fill] pointer-events-none absolute -bottom-6 -end-6 -z-10 text-[11rem] text-white/10"></i>
                    <div>
                        <span class="badge badge-deal"><i class="icon-[ph--lightning-fill]"></i>{{ __('Limited Offers') }}</span>
                        <h2 class="h1 mt-5">{{ __("Today's deals") }}</h2>
                        <p class="mt-3 text-white/75">{{ __('Premium discounts available today') }}</p>
                    </div>
                    <div class="mt-8 flex items-center justify-between gap-4">
                        <a href="{{ route('products.deals') }}" class="btn btn-white">{{ __('Explore Offers') }}<i class="icon-[ph--arrow-right] text-lg rtl:-scale-x-100"></i></a>
                        <div class="flex gap-2">
                            <button type="button" data-prev class="nav-btn !h-10 !w-10 !border-white/25 !bg-white/10 !text-white hover:!bg-white hover:!text-ink" aria-label="{{ __('Previous') }}"><i class="icon-[ph--arrow-left] rtl:-scale-x-100"></i></button>
                            <button type="button" data-next class="nav-btn !h-10 !w-10 !border-white/25 !bg-white/10 !text-white hover:!bg-white hover:!text-ink" aria-label="{{ __('Next') }}"><i class="icon-[ph--arrow-right] rtl:-scale-x-100"></i></button>
                        </div>
                    </div>
                </div>

                <div class="min-w-0">
                    <div class="swiper !overflow-hidden" data-rail>
                        <div class="swiper-wrapper">
                            @foreach ($deals as $deal)
                                <div class="swiper-slide h-auto!">
                                    <x-product-card :product="$deal" :reveal="false" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endunless

{{-- ───────────── Catalog ───────────── --}}
<section id="catalog" class="container-x {{ $filtering ? 'pt-12 sm:pt-16' : 'section' }}"
         x-data="catalog({ search: @js((string) request('search', '')), category: @js((string) request('category', '')), errorText: @js(__('Something went wrong. Please try again.')) })">

    <div class="mb-8 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <{{ $filtering ? 'h1' : 'h2' }} class="h1">{{ __('Explore Products') }}</{{ $filtering ? 'h1' : 'h2' }}>
            <p class="lead mt-2 max-w-xl">{{ __('Carefully selected products designed for customers who value elegance and quality.') }}</p>
        </div>

        <div class="relative w-full lg:max-w-sm">
            <i class="icon-[ph--magnifying-glass] pointer-events-none absolute start-4 top-1/2 -translate-y-1/2 text-xl text-mute"></i>
            <input type="text" x-model="search" @input.debounce.350ms="load()" @keydown.enter.prevent="load()"
                   data-search-input autocomplete="off" placeholder="{{ __('Search for products…') }}" aria-label="{{ __('Search for products…') }}"
                   class="field rounded-full ps-12 pe-12">
            <button type="button" x-show="search" x-cloak @click="search = ''; load()" class="absolute end-3 top-1/2 grid h-7 w-7 -translate-y-1/2 place-items-center rounded-full text-lg text-mute transition hover:bg-tile hover:text-ink" aria-label="{{ __('Clear') }}"><i class="icon-[ph--x-circle-fill]"></i></button>
        </div>
    </div>

    <div class="no-scrollbar -mx-5 mb-10 flex gap-2 overflow-x-auto px-5 pb-1 sm:mx-0 sm:flex-wrap sm:px-0" data-lenis-prevent-wheel>
        <a href="{{ route('home') }}" @click.prevent="pick('')" class="chip {{ request('category') ? '' : 'is-active' }}" :class="{ 'is-active': category === '' }">{{ __('All Categories') }}</a>
        @foreach ($categories as $cat)
            <a href="{{ route('home', ['category' => $cat->id]) }}" @click.prevent="pick({{ $cat->id }})"
               class="chip {{ (string) request('category') === (string) $cat->id ? 'is-active' : '' }}"
               :class="{ 'is-active': category === '{{ $cat->id }}' }">{{ $cat->localized_name }}</a>
        @endforeach
    </div>

    <div class="relative" :aria-busy="loading">
        <div x-ref="grid" id="productsGrid"
             class="grid grid-cols-2 gap-x-4 gap-y-10 transition-opacity duration-200 sm:gap-x-6 lg:grid-cols-3 xl:grid-cols-4"
             :class="loading && 'pointer-events-none opacity-40'">
            @include('products.partials.grid', ['products' => $products])
        </div>
        <div x-show="loading" x-cloak class="pointer-events-none absolute inset-x-0 top-16 flex justify-center">
            <span class="grid h-12 w-12 place-items-center rounded-full bg-white text-2xl text-cobalt-500 shadow-lift"><i class="icon-[ph--circle-notch] animate-spin"></i></span>
        </div>
    </div>

    <div class="mt-16" x-show="!live">
        {{ $products->withQueryString()->links('vendor.pagination.shop') }}
    </div>
</section>

@unless ($filtering)
    {{-- ───────────── Why shop here ───────────── --}}
    <section class="container-x pb-4">
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <div data-reveal class="relative overflow-hidden rounded-[2rem] bg-ink p-8 text-white lg:col-span-2 lg:row-span-1">
                <span class="grid h-14 w-14 place-items-center rounded-2xl bg-white/10 text-3xl text-cobalt-300"><i class="icon-[ph--shield-check]"></i></span>
                <h3 class="h2 mt-14">{{ __('Secure Checkout') }}</h3>
                <p class="mt-3 max-w-sm text-white/65">{{ __('100% encrypted premium payment system.') }}</p>
                <i class="icon-[ph--lock-simple] pointer-events-none absolute -bottom-8 -end-8 text-[12rem] text-white/5"></i>
            </div>
            <div data-reveal data-reveal-delay="0.08" class="rounded-[2rem] bg-cobalt-50 p-8">
                <span class="grid h-14 w-14 place-items-center rounded-2xl bg-white text-3xl text-cobalt-600"><i class="icon-[ph--truck]"></i></span>
                <h3 class="h3 mt-14">{{ __('Fast Shipping') }}</h3>
                <p class="mt-2 text-mute">{{ __('Express delivery with premium packaging.') }}</p>
            </div>
            <div data-reveal data-reveal-delay="0.16" class="rounded-[2rem] bg-saffron-300/35 p-8">
                <span class="grid h-14 w-14 place-items-center rounded-2xl bg-white text-3xl text-saffron-600"><i class="icon-[ph--crown-simple]"></i></span>
                <h3 class="h3 mt-14">{{ __('Rewards') }}</h3>
                <p class="mt-2 text-ink/65">{{ __('Earn points on every order.') }}</p>
            </div>
        </div>
    </section>
@endunless

</x-app-layout>
