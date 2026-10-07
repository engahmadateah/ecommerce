<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), config('shop.rtl'), true) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0e1230">
    @php
        $siteName = \App\Models\Setting::current()?->site_name ?: config('app.name');
        // Pages set these with @section('seo_title', ...) etc. (values are already escaped by Blade).
        $seoTitle = trim($__env->yieldContent('seo_title')) ?: e($siteName);
        $seoDescription = trim($__env->yieldContent('seo_description')) ?: e($siteName.' — shop online with secure checkout and fast delivery.');
        $seoImage = trim($__env->yieldContent('seo_image')) ?: asset('images/logo.png');
        $seoType = trim($__env->yieldContent('seo_type')) ?: 'website';
        $seoRobots = trim($__env->yieldContent('seo_robots')) ?: 'index,follow';

        // Flash messages become toasts (see resources/js/app.js). Server texts are English keys: translate here.
        $statusLabels = [
            'profile-updated' => __('Profile updated'),
            'password-updated' => __('Password updated'),
            'verification-link-sent' => __('A new verification link has been sent.'),
        ];
        $rawStatus = session('status');
        $flash = array_filter([
            'success' => is_string(session('success')) ? __(session('success')) : null,
            'error' => is_string(session('error')) ? __(session('error')) : null,
            'status' => is_string($rawStatus) ? ($statusLabels[$rawStatus] ?? __($rawStatus)) : null,
        ]);
    @endphp
    <title>{!! $seoTitle !!}</title>
    <meta name="description" content="{!! $seoDescription !!}">
    <meta name="robots" content="{{ $seoRobots }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='9' fill='%233e4fff'/%3E%3Cpath d='M10 12h12l-1 11a1 1 0 0 1-1 .9h-8a1 1 0 0 1-1-.9L10 12Zm3 0v-1a3 3 0 0 1 6 0v1' fill='none' stroke='%23fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E">

    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:type" content="{{ $seoType }}">
    <meta property="og:title" content="{!! $seoTitle !!}">
    <meta property="og:description" content="{!! $seoDescription !!}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $seoImage }}">
    <meta name="twitter:card" content="summary_large_image">
    @stack('seo')

    {{-- Arms the scroll-reveal styles only when JS runs; a timer un-hides everything if the bundle fails to load. --}}
    <script>
        document.documentElement.classList.add('js');
        if (matchMedia('(prefers-reduced-motion: reduce)').matches) document.documentElement.classList.add('reduce');
        window.__revealFallback = setTimeout(function () { document.documentElement.classList.add('reveal-fallback'); }, 4000);
    </script>
    <style>
        html.js:not(.reduce) [data-reveal], html.js:not(.reduce) [data-hero-item], html.js:not(.reduce) [data-hero-card] { opacity: 0; }
        html.reveal-fallback [data-reveal], html.reveal-fallback [data-hero-item], html.reveal-fallback [data-hero-card] { opacity: 1 !important; transform: none !important; }
    </style>

    {{-- Fonts (Fontsource), icons (Phosphor via Iconify), motion (Motion, Lenis, Swiper) are all bundled by Vite. --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex min-h-screen flex-col">

    <a href="#main" class="sr-only z-[300] rounded-full bg-ink px-5 py-3 text-sm font-semibold text-white focus:not-sr-only focus:fixed focus:start-4 focus:top-4">{{ __('Skip to content') }}</a>

    @include('partials.navbar')

    <main id="main" class="flex-1">
        {{ $slot }}
    </main>

    @include('partials.footer')
    @include('partials.cookie-notice')

    <div id="toast-root" aria-live="polite"></div>
    <script type="application/json" id="flash-data">@json($flash, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)</script>

</body>
</html>
