@php
    $cartCount = count(session('cart', []));
    $user = auth()->user();
    $wishlistCount = $user ? $user->wishlist()->count() : 0;
    $brand = \App\Models\Setting::current()?->site_name ?: __('MyStore');
    $returnDays = (int) config('shop.returns.days', 14);
    $locale = app()->getLocale();
    $currency = \App\Support\Currency::code();

    $navLinks = [
        ['label' => __('All products'), 'href' => route('home'), 'active' => request()->routeIs('home', 'dashboard') || request()->is('products*'), 'icon' => 'icon-[ph--squares-four]'],
        ['label' => __("Today's deals"), 'href' => route('products.deals'), 'active' => request()->is('deals*'), 'icon' => 'icon-[ph--fire-fill]', 'deal' => true],
        ['label' => __('Bundles'), 'href' => route('packages.index'), 'active' => request()->is('packages*'), 'icon' => 'icon-[ph--stack-simple]'],
        ['label' => __('Coupons'), 'href' => route('coupons.index'), 'active' => request()->is('coupons*'), 'icon' => 'icon-[ph--ticket]'],
        ['label' => __('Contact'), 'href' => route('contact'), 'active' => request()->is('contact*'), 'icon' => 'icon-[ph--chat-circle-dots]'],
    ];
@endphp

<header x-data="{ open: false, lang: false, account: false, scrolled: false }"
        x-init="$watch('open', v => { v ? window.lenis?.stop() : window.lenis?.start(); })"
        @scroll.window.passive="scrolled = window.scrollY > 6"
        @keydown.escape.window="open = false; lang = false; account = false"
        data-cart-count="{{ $cartCount }}"
        class="sticky -top-9 z-50">

    {{-- Announcement strip (scrolls away; the main bar below stays) --}}
    <div class="h-9 bg-ink text-xs text-white/80 sm:text-[13px]">
        <div class="container-x flex h-full items-center justify-center gap-8">
            <span class="inline-flex items-center gap-2"><i class="icon-[ph--lock-simple] text-base text-cobalt-300"></i>{{ __('Encrypted checkout on every order') }}</span>
            <span class="hidden items-center gap-2 sm:inline-flex"><i class="icon-[ph--truck] text-base text-cobalt-300"></i>{{ __('Real-time order tracking') }}</span>
            <span class="hidden items-center gap-2 lg:inline-flex"><i class="icon-[ph--arrow-counter-clockwise] text-base text-cobalt-300"></i>{{ __('Easy :days-day returns', ['days' => $returnDays]) }}</span>
        </div>
    </div>

    {{-- Main bar --}}
    <div class="border-b border-line/80 backdrop-blur-xl transition-[background-color,box-shadow] duration-300"
         :class="scrolled ? 'bg-white/85 shadow-[0_10px_30px_-20px_rgb(14_18_48/.45)]' : 'bg-fog/80'">
        <div class="container-x flex h-[68px] items-center gap-2 sm:gap-3 xl:gap-5">

            <button type="button" class="btn btn-ghost btn-icon -ms-2 text-[22px] xl:hidden" @click="open = !open" :aria-expanded="open" aria-label="{{ __('Menu') }}">
                <i x-show="!open" class="icon-[ph--list]"></i>
                <i x-show="open" x-cloak class="icon-[ph--x]"></i>
            </button>

            <a href="{{ route('home') }}" class="group flex shrink-0 items-center gap-2.5" aria-label="{{ $brand }}">
                <span class="grid h-10 w-10 place-items-center rounded-2xl bg-linear-to-br from-cobalt-500 to-[#7c5cff] text-xl text-white shadow-glow transition duration-300 group-hover:-rotate-6">
                    <i class="icon-[ph--handbag-fill]"></i>
                </span>
                <span class="font-display text-[22px] font-bold leading-none tracking-tight">{{ $brand }}</span>
            </a>

            {{-- Primary links (wide screens) --}}
            <nav class="hidden items-center gap-0.5 xl:flex" aria-label="Primary">
                @foreach ($navLinks as $link)
                    <a href="{{ $link['href'] }}"
                       @if ($link['active']) aria-current="page" @endif
                       class="inline-flex h-10 items-center gap-1.5 whitespace-nowrap rounded-full px-3 text-[13.5px] font-semibold transition
                              {{ $link['active'] ? 'bg-ink text-white' : (!empty($link['deal']) ? 'text-coral-600 hover:bg-coral-50' : 'text-mute hover:bg-white hover:text-ink') }}">
                        @if (!empty($link['deal']))<i class="{{ $link['icon'] }} text-base"></i>@endif
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            {{-- Search --}}
            <form action="{{ route('home') }}" method="GET" role="search" class="relative mx-auto hidden w-full max-w-xl flex-1 md:block xl:max-w-sm 2xl:max-w-md">
                <i class="icon-[ph--magnifying-glass] pointer-events-none absolute start-4 top-1/2 -translate-y-1/2 text-xl text-mute"></i>
                <input type="text" name="search" value="{{ request('search') }}" data-search-input autocomplete="off"
                       placeholder="{{ __('Search for products…') }}" aria-label="{{ __('Search for products…') }}"
                       class="field h-11 rounded-full bg-white/90 ps-12 pe-10">
                <kbd class="pointer-events-none absolute end-4 top-1/2 hidden -translate-y-1/2 rounded-md border border-line bg-fog px-1.5 py-0.5 text-[11px] font-bold text-mute lg:block">/</kbd>
            </form>

            <div class="ms-auto flex items-center gap-0.5 sm:gap-1 md:ms-0">

                {{-- Language + currency --}}
                <div class="relative hidden sm:block" @click.outside="lang = false">
                    <button type="button" class="btn btn-ghost btn-sm gap-1.5 px-3" @click="lang = !lang; account = false" :aria-expanded="lang" aria-label="{{ __('Language') }} / {{ __('Currency') }}">
                        <i class="icon-[ph--globe-simple] text-lg"></i>
                        <span class="hidden text-[13px] font-bold 2xl:inline">{{ strtoupper($locale) }}<span class="font-semibold text-mute"> · {{ $currency }}</span></span>
                    </button>
                    <div x-show="lang" x-cloak x-transition.opacity.scale.95.origin.top.duration.150ms
                         class="absolute end-0 top-full mt-2 w-72 rounded-3xl border border-line bg-white p-4 shadow-lift">
                        <p class="mb-2 text-xs font-bold text-mute">{{ __('Language') }}</p>
                        <div class="mb-4 flex flex-wrap gap-2">
                            @foreach (config('shop.locales') as $code => $label)
                                <a href="{{ route('locale', $code) }}" hreflang="{{ $code }}" class="chip h-9 {{ $locale === $code ? 'is-active' : '' }}">{{ $label }}</a>
                            @endforeach
                        </div>
                        <p class="mb-2 text-xs font-bold text-mute">{{ __('Currency') }}</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach (array_keys(config('shop.currencies')) as $code)
                                <a href="{{ route('currency', $code) }}" class="chip h-9 {{ $currency === $code ? 'is-active' : '' }}">{{ $code }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>

                @auth
                    <a href="/wishlist" class="btn btn-ghost btn-icon relative hidden text-[22px] sm:inline-flex" aria-label="{{ __('Wishlist') }}">
                        <i class="icon-[ph--heart]"></i>
                        <span data-wishlist-badge @if ($wishlistCount <= 0) hidden @endif class="absolute -end-0.5 -top-0.5 grid h-[18px] min-w-[18px] place-items-center rounded-full bg-coral-500 px-1 text-[11px] font-bold leading-none text-white ring-2 ring-fog">{{ $wishlistCount }}</span>
                    </a>
                    <a href="/orders" class="btn btn-ghost btn-icon hidden text-[22px] md:inline-flex" aria-label="{{ __('Orders') }}"><i class="icon-[ph--package]"></i></a>
                @endauth

                <a href="/cart" data-cart-target class="btn btn-ghost btn-icon relative text-[22px]" aria-label="{{ __('Cart') }}">
                    <i class="icon-[ph--handbag]"></i>
                    <span data-cart-badge @if ($cartCount <= 0) hidden @endif class="absolute -end-0.5 -top-0.5 grid h-[18px] min-w-[18px] place-items-center rounded-full bg-cobalt-500 px-1 text-[11px] font-bold leading-none text-white ring-2 ring-fog">{{ $cartCount }}</span>
                </a>

                @auth
                    @php
                        $level = $user->level ?? 'bronze';
                        $initials = collect(explode(' ', trim($user->name)))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('');
                        $levelDot = ['gold' => 'bg-[#f0c34c]', 'silver' => 'bg-[#b9c4cd]', 'bronze' => 'bg-[#d98b53]'][$level] ?? 'bg-[#d98b53]';
                    @endphp
                    <div class="relative ms-1" @click.outside="account = false">
                        <button type="button" class="relative grid h-11 w-11 place-items-center rounded-full bg-ink text-sm font-bold text-white transition hover:bg-ink-2"
                                @click="account = !account; lang = false" :aria-expanded="account" aria-label="{{ __('Profile') }}">
                            {{ mb_strtoupper($initials) }}
                            <span class="absolute -bottom-0.5 -end-0.5 h-3.5 w-3.5 rounded-full ring-2 ring-fog {{ $levelDot }}"></span>
                        </button>
                        <div x-show="account" x-cloak x-transition.opacity.scale.95.origin.top.duration.150ms
                             class="absolute end-0 top-full mt-2 w-72 rounded-3xl border border-line bg-white p-2 shadow-lift">
                            <div class="mb-1 rounded-2xl bg-fog p-4">
                                <p class="truncate font-display text-base font-bold">{{ $user->name }}</p>
                                <p class="mt-0.5 truncate text-[13px] text-mute">{{ $user->email }}</p>
                                <p class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-white px-2.5 py-1 text-xs font-bold">
                                    <span class="h-2 w-2 rounded-full {{ $levelDot }}"></span>{{ __(ucfirst($level)) }} · {{ number_format($user->points ?? 0) }} {{ __('pts') }}
                                </p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition hover:bg-fog"><i class="icon-[ph--user] text-lg text-mute"></i>{{ __('Profile') }}</a>
                            <a href="/orders" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition hover:bg-fog"><i class="icon-[ph--package] text-lg text-mute"></i>{{ __('My orders') }}</a>
                            <a href="/wishlist" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition hover:bg-fog"><i class="icon-[ph--heart] text-lg text-mute"></i>{{ __('Wishlist') }}</a>
                            <form method="POST" action="/logout" class="mt-1 border-t border-line pt-1">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-3 rounded-2xl px-4 py-3 text-start text-sm font-semibold text-coral-600 transition hover:bg-coral-50"><i class="icon-[ph--sign-out] text-lg rtl:-scale-x-100"></i>{{ __('Log out') }}</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="/login" class="btn btn-ink btn-sm hidden sm:inline-flex 2xl:btn-ghost">{{ __('Log in') }}</a>
                    <a href="/register" class="btn btn-ink btn-sm hidden 2xl:inline-flex">{{ __('Create account') }}</a>
                    <a href="/login" class="btn btn-ghost btn-icon text-[22px] sm:hidden" aria-label="{{ __('Log in') }}"><i class="icon-[ph--user]"></i></a>
                @endauth
            </div>
        </div>
    </div>

    {{-- Mobile / tablet panel --}}
    <div x-show="open" x-cloak
         x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition duration-150 ease-in" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         data-lenis-prevent
         class="absolute inset-x-0 top-full max-h-[calc(100dvh-6.5rem)] overflow-y-auto border-b border-line bg-white shadow-lift xl:hidden">
        <div class="container-x py-5">
            <form action="{{ route('home') }}" method="GET" role="search" class="relative mb-4 md:hidden">
                <i class="icon-[ph--magnifying-glass] pointer-events-none absolute start-4 top-1/2 -translate-y-1/2 text-xl text-mute"></i>
                <input type="text" name="search" value="{{ request('search') }}" data-search-input autocomplete="off" placeholder="{{ __('Search for products…') }}" class="field ps-12">
            </form>

            <nav class="grid gap-1 sm:grid-cols-2" aria-label="Mobile">
                @foreach ($navLinks as $link)
                    <a href="{{ $link['href'] }}" class="flex items-center gap-3 rounded-2xl px-4 py-3.5 text-[15px] font-semibold transition {{ $link['active'] ? 'bg-ink text-white' : 'hover:bg-fog' }}">
                        <i class="{{ $link['icon'] }} text-xl {{ $link['active'] ? '' : (!empty($link['deal']) ? 'text-coral-500' : 'text-mute') }}"></i>{{ $link['label'] }}
                    </a>
                @endforeach
                @auth
                    <a href="/wishlist" class="flex items-center gap-3 rounded-2xl px-4 py-3.5 text-[15px] font-semibold transition hover:bg-fog"><i class="icon-[ph--heart] text-xl text-mute"></i>{{ __('Wishlist') }}</a>
                    <a href="/orders" class="flex items-center gap-3 rounded-2xl px-4 py-3.5 text-[15px] font-semibold transition hover:bg-fog"><i class="icon-[ph--package] text-xl text-mute"></i>{{ __('My orders') }}</a>
                @endauth
                <a href="{{ route('orders.track.form') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3.5 text-[15px] font-semibold transition hover:bg-fog"><i class="icon-[ph--map-pin-line] text-xl text-mute"></i>{{ __('Track an order') }}</a>
            </nav>

            <div class="mt-5 flex flex-wrap items-center gap-2 border-t border-line pt-5">
                @foreach (config('shop.locales') as $code => $label)
                    <a href="{{ route('locale', $code) }}" hreflang="{{ $code }}" class="chip h-9 {{ $locale === $code ? 'is-active' : '' }}">{{ $label }}</a>
                @endforeach
                <span class="mx-1 h-5 w-px bg-line"></span>
                @foreach (array_keys(config('shop.currencies')) as $code)
                    <a href="{{ route('currency', $code) }}" class="chip h-9 {{ $currency === $code ? 'is-active' : '' }}">{{ $code }}</a>
                @endforeach
            </div>

            @guest
                <div class="mt-5 grid grid-cols-2 gap-3">
                    <a href="/login" class="btn btn-line">{{ __('Log in') }}</a>
                    <a href="/register" class="btn btn-ink">{{ __('Create account') }}</a>
                </div>
            @endguest
        </div>
    </div>
</header>
