@php
    $settings = \App\Models\Setting::current();
    $brand = $settings?->site_name ?: __('MyStore');
    $socials = array_filter([
        ['url' => $settings?->facebook, 'icon' => 'icon-[ph--facebook-logo]', 'name' => 'Facebook'],
        ['url' => $settings?->instagram, 'icon' => 'icon-[ph--instagram-logo]', 'name' => 'Instagram'],
        ['url' => $settings?->twitter, 'icon' => 'icon-[ph--x-logo]', 'name' => 'X'],
        ['url' => $settings?->tiktok, 'icon' => 'icon-[ph--tiktok-logo]', 'name' => 'TikTok'],
    ], fn ($s) => filled($s['url']));
@endphp

<footer class="relative mt-24 overflow-hidden rounded-t-[2rem] bg-ink text-white sm:mt-32 sm:rounded-t-[3rem]">
    <div class="aurora opacity-60" aria-hidden="true"><i></i><i></i><i></i></div>

    <div class="container-x relative py-16 sm:py-20">
        <div class="grid gap-12 lg:grid-cols-12">

            <div class="lg:col-span-5">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                    <span class="grid h-12 w-12 place-items-center rounded-2xl bg-linear-to-br from-cobalt-500 to-[#7c5cff] text-2xl shadow-glow"><i class="icon-[ph--handbag-fill]"></i></span>
                    <span class="font-display text-3xl font-bold">{{ $brand }}</span>
                </a>
                <p class="mt-6 max-w-md leading-relaxed text-white/60">
                    {{ $settings?->about ?: __('Carefully selected products designed for customers who value elegance and quality.') }}
                </p>

                @if ($socials)
                    <div class="mt-8 flex items-center gap-2.5">
                        @foreach ($socials as $social)
                            <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $social['name'] }}"
                               class="grid h-11 w-11 place-items-center rounded-full border border-white/15 text-xl text-white/80 transition hover:-translate-y-0.5 hover:border-white hover:bg-white hover:text-ink">
                                <i class="{{ $social['icon'] }}"></i>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-10 lg:col-span-7 lg:grid-cols-3">
                <div>
                    <h3 class="mb-5 text-base font-bold">{{ __('Quick Links') }}</h3>
                    <ul class="space-y-3 text-[15px] text-white/60">
                        <li><a href="{{ route('home') }}" class="transition hover:text-white">{{ __('Home') }}</a></li>
                        <li><a href="{{ route('products.deals') }}" class="transition hover:text-white">{{ __("Today's deals") }}</a></li>
                        <li><a href="{{ route('packages.index') }}" class="transition hover:text-white">{{ __('Bundles') }}</a></li>
                        <li><a href="{{ route('coupons.index') }}" class="transition hover:text-white">{{ __('Coupons') }}</a></li>
                        <li><a href="/cart" class="transition hover:text-white">{{ __('Cart') }}</a></li>
                        <li><a href="/orders" class="transition hover:text-white">{{ __('Orders') }}</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="mb-5 text-base font-bold">{{ __('Information') }}</h3>
                    <ul class="space-y-3 text-[15px] text-white/60">
                        <li><a href="/about" class="transition hover:text-white">{{ __('About Us') }}</a></li>
                        <li><a href="/track" class="transition hover:text-white">{{ __('Track an order') }}</a></li>
                        <li><a href="/shipping-policy" class="transition hover:text-white">{{ __('Shipping Policy') }}</a></li>
                        <li><a href="/refund-policy" class="transition hover:text-white">{{ __('Refund & Returns') }}</a></li>
                        <li><a href="/privacy-policy" class="transition hover:text-white">{{ __('Privacy Policy') }}</a></li>
                        <li><a href="/terms" class="transition hover:text-white">{{ __('Terms & Conditions') }}</a></li>
                    </ul>
                </div>

                <div class="col-span-2 lg:col-span-1">
                    <h3 class="mb-5 text-base font-bold">{{ __('Contact') }}</h3>
                    <ul class="space-y-4 text-[15px] text-white/60">
                        @if ($settings?->email)
                            <li class="flex items-start gap-3"><i class="icon-[ph--envelope-simple] mt-0.5 shrink-0 text-xl text-cobalt-300"></i><a href="mailto:{{ $settings->email }}" class="break-all transition hover:text-white">{{ $settings->email }}</a></li>
                        @endif
                        @if ($settings?->phone)
                            <li class="flex items-start gap-3"><i class="icon-[ph--phone] mt-0.5 shrink-0 text-xl text-cobalt-300"></i><a href="tel:{{ preg_replace('/[^\d+]/', '', $settings->phone) }}" dir="ltr" class="transition hover:text-white">{{ $settings->phone }}</a></li>
                        @endif
                        @if ($settings?->address)
                            <li class="flex items-start gap-3"><i class="icon-[ph--map-pin] mt-0.5 shrink-0 text-xl text-cobalt-300"></i><span class="leading-relaxed">{{ $settings->address }}</span></li>
                        @endif
                        @if (! $settings?->email && ! $settings?->phone && ! $settings?->address)
                            <li><a href="{{ route('contact') }}" class="inline-flex items-center gap-2 transition hover:text-white"><i class="icon-[ph--chat-circle-dots] text-xl text-cobalt-300"></i>{{ __('Contact Support') }}</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="relative border-t border-white/10">
        <div class="container-x flex flex-col items-center justify-between gap-3 py-6 text-sm text-white/50 sm:flex-row">
            <p>{{ $settings?->footer_text ?: '© '.date('Y').' '.$brand }}</p>
            <p class="inline-flex items-center gap-2"><i class="icon-[ph--lock-simple] text-base text-mint-500"></i>{{ __('Payments processed securely by Stripe') }}</p>
        </div>
    </div>
</footer>
