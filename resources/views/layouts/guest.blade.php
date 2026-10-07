<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), config('shop.rtl'), true) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0e1230">
    @php
        $brand = \App\Models\Setting::current()?->site_name ?: __('MyStore');
        $locale = app()->getLocale();
    @endphp
    <title>{{ $brand }}</title>
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='9' fill='%233e4fff'/%3E%3Cpath d='M10 12h12l-1 11a1 1 0 0 1-1 .9h-8a1 1 0 0 1-1-.9L10 12Zm3 0v-1a3 3 0 0 1 6 0v1' fill='none' stroke='%23fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E">
    <script>
        document.documentElement.classList.add('js');
        if (matchMedia('(prefers-reduced-motion: reduce)').matches) document.documentElement.classList.add('reduce');
        window.__revealFallback = setTimeout(function () { document.documentElement.classList.add('reveal-fallback'); }, 4000);
    </script>
    <style>
        html.js:not(.reduce) [data-reveal], html.js:not(.reduce) [data-hero-item], html.js:not(.reduce) [data-hero-card] { opacity: 0; }
        html.reveal-fallback [data-reveal], html.reveal-fallback [data-hero-item], html.reveal-fallback [data-hero-card] { opacity: 1 !important; transform: none !important; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">
    <div class="grid min-h-screen lg:grid-cols-[1fr_1.05fr]">

        {{-- Brand panel --}}
        <aside data-hero class="grain relative isolate hidden overflow-hidden bg-ink p-12 text-white lg:flex lg:flex-col lg:justify-between xl:p-16">
            <div class="aurora" aria-hidden="true"><i></i><i></i><i></i></div>

            <a href="{{ route('home') }}" data-hero-item class="relative inline-flex items-center gap-3">
                <span class="grid h-12 w-12 place-items-center rounded-2xl bg-linear-to-br from-cobalt-500 to-[#7c5cff] text-2xl shadow-glow"><i class="icon-[ph--handbag-fill]"></i></span>
                <span class="font-display text-3xl font-bold">{{ $brand }}</span>
            </a>

            <div class="relative max-w-lg">
                <h2 data-hero-item class="display !text-[clamp(2.4rem,4vw,3.8rem)]">{{ __('Join The Future Of Shopping.') }}</h2>
                <ul class="mt-10 space-y-4 text-lg text-white/75">
                    <li data-hero-item class="flex items-center gap-3"><span class="grid h-10 w-10 place-items-center rounded-full bg-white/10 text-xl text-cobalt-300"><i class="icon-[ph--crown-simple]"></i></span>{{ __('Earn loyalty points instantly.') }}</li>
                    <li data-hero-item class="flex items-center gap-3"><span class="grid h-10 w-10 place-items-center rounded-full bg-white/10 text-xl text-cobalt-300"><i class="icon-[ph--ticket]"></i></span>{{ __('Unlock exclusive discounts.') }}</li>
                    <li data-hero-item class="flex items-center gap-3"><span class="grid h-10 w-10 place-items-center rounded-full bg-white/10 text-xl text-cobalt-300"><i class="icon-[ph--shield-check]"></i></span>{{ __('Secure authentication system.') }}</li>
                </ul>
            </div>

            <p data-hero-item class="relative text-sm text-white/45">© {{ date('Y') }} {{ $brand }}</p>
        </aside>

        {{-- Form side --}}
        <div class="flex min-h-screen flex-col px-5 py-6 sm:px-10">
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-mute transition hover:text-ink">
                    <i class="icon-[ph--arrow-left] text-lg rtl:-scale-x-100"></i>{{ __('Back to Shop') }}
                </a>
                <div class="flex gap-1.5">
                    @foreach (config('shop.locales') as $code => $label)
                        <a href="{{ route('locale', $code) }}" hreflang="{{ $code }}" class="chip h-9 px-3.5 text-[13px] {{ $locale === $code ? 'is-active' : '' }}">{{ $label }}</a>
                    @endforeach
                </div>
            </div>

            <main class="mx-auto flex w-full max-w-[26rem] flex-1 flex-col justify-center py-10">
                {{ $slot }}
            </main>
        </div>
    </div>
    <div id="toast-root" aria-live="polite"></div>
    <script type="application/json" id="flash-data">{}</script>
</body>
</html>
