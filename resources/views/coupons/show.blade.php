<x-app-layout>
@php
    $discountLabel = $coupon->type === 'percent'
        ? __(':value% off', ['value' => rtrim(rtrim((string) $coupon->value, '0'), '.')])
        : __(':amount off', ['amount' => \App\Support\Currency::format($coupon->value)]);
    $level = $coupon->required_level ?? 'bronze';
    $userLevel = auth()->user()->level ?? 'bronze';
    $levels = ['bronze' => 1, 'silver' => 2, 'gold' => 3];
    $canUse = ($levels[$userLevel] ?? 1) >= ($levels[$level] ?? 1);
    $tone = match ($level) {
        'gold' => 'from-saffron-300 to-saffron-500 text-ink',
        'silver' => 'from-[#e6e9f2] to-[#aab4cb] text-ink',
        default => 'from-[#ff9a5a] to-coral-500 text-white',
    };
@endphp

<div class="container-x pt-6 sm:pt-10">
    <nav class="mb-6 flex items-center gap-2 text-sm text-mute" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="transition hover:text-ink">{{ __('Home') }}</a>
        <i class="icon-[ph--caret-right] text-xs rtl:-scale-x-100"></i>
        <a href="{{ route('coupons.index') }}" class="transition hover:text-ink">{{ __('Coupons') }}</a>
    </nav>

    <div class="grid gap-6 lg:grid-cols-[1.1fr_.9fr]">
        {{-- Offer --}}
        <div class="overflow-hidden rounded-[2rem] border border-line bg-white shadow-soft">
            <div class="relative flex min-h-64 flex-col justify-end bg-linear-to-br p-8 sm:p-10 {{ $tone }}">
                @if ($coupon->image)
                    <img loading="lazy" decoding="async" src="{{ asset('storage/'.$coupon->image) }}" alt="" class="absolute inset-0 h-full w-full object-cover opacity-30 mix-blend-multiply">
                @else
                    <i class="icon-[ph--ticket] absolute -end-6 -top-6 text-[12rem] opacity-15"></i>
                @endif
                <div class="relative">
                    <span class="rounded-full bg-white/35 px-3 py-1 text-xs font-bold backdrop-blur">{{ __(ucfirst($level)) }}</span>
                    <p class="display mt-4 !text-[clamp(2.8rem,6vw,4.6rem)]">{{ $discountLabel }}</p>
                </div>
            </div>

            <div class="p-8 sm:p-10">
                <p class="text-sm font-bold text-cobalt-600">{{ __('Exclusive Premium Coupon') }}</p>
                <h1 class="h1 mt-2">{{ $coupon->title ?? $coupon->code }}</h1>
                <h2 class="h3 mt-8">{{ __('Premium Shopping Reward') }}</h2>
                <p class="mt-2 leading-relaxed text-mute">{{ $coupon->description ?? __('Enjoy exclusive luxury shopping rewards and premium member discounts available for a limited time only.') }}</p>

                @if ($coupon->how_to_use)
                    <h3 class="h3 mt-8">{{ __('How To Use') }}</h3>
                    <p class="mt-2 leading-relaxed text-mute">{{ $coupon->how_to_use }}</p>
                @endif

                <dl class="mt-8 grid gap-4 border-t border-line pt-8 sm:grid-cols-3">
                    @if ($coupon->expires_at)
                        <div><dt class="text-sm text-mute">{{ __('Expires') }}</dt><dd class="mt-1 font-display text-lg font-bold">{{ $coupon->expires_at->format('M d, Y') }}</dd></div>
                    @endif
                    @if ($coupon->usage_limit)
                        <div><dt class="text-sm text-mute">{{ __('Usage Limit') }}</dt><dd class="mt-1 font-display text-lg font-bold">{{ $coupon->usage_limit }}</dd></div>
                    @endif
                    <div><dt class="text-sm text-mute">{{ __('Used Times') }}</dt><dd class="mt-1 font-display text-lg font-bold">{{ $coupon->used }}</dd></div>
                </dl>
            </div>
        </div>

        {{-- Redeem --}}
        <aside class="space-y-4 lg:sticky lg:top-28 lg:self-start">
            <div class="panel">
                <p class="text-sm font-bold text-cobalt-600">{{ __('Coupon Access') }}</p>
                <h2 class="h2 mt-1">{{ __('Redeem Offer') }}</h2>

                <p class="mt-6 text-sm text-mute">{{ __('Coupon Code') }}</p>
                <div class="mt-2 flex items-center gap-2 rounded-2xl border-2 border-dashed border-ink/25 bg-fog p-2 ps-5">
                    <span class="flex-1 font-mono text-xl font-bold tracking-widest">{{ $coupon->code }}</span>
                    <button type="button" onclick="copyText(@js($coupon->code), this)" data-copied-text="{{ __('Copied') }}" class="btn btn-ink btn-sm"><i class="icon-[ph--copy] text-lg"></i>{{ __('Instant Copy') }}</button>
                </div>
                <p class="mt-3 text-sm leading-relaxed text-mute">{{ __('Copy the code and paste it into your cart to automatically apply the discount.') }}</p>

                @auth
                    @if ($canUse)
                        <div class="alert alert-ok mt-6"><i class="icon-[ph--check-circle-fill] mt-0.5 shrink-0 text-lg"></i><div><b class="block">{{ __('Coupon Available') }}</b>{{ __('Your account can redeem this offer.') }}</div></div>
                        <a href="/cart" class="btn btn-brand btn-lg btn-block mt-4">{{ __('Cart') }}<i class="icon-[ph--arrow-right] text-xl rtl:-scale-x-100"></i></a>
                    @else
                        <div class="alert alert-err mt-6"><i class="icon-[ph--lock-simple] mt-0.5 shrink-0 text-lg"></i><div><b class="block">{{ __('Level Required') }}</b>{{ __('Requires :level membership.', ['level' => __(ucfirst($level))]) }}</div></div>
                    @endif
                @else
                    <a href="/login" class="btn btn-brand btn-lg btn-block mt-6">{{ __('Login To Redeem') }}</a>
                @endauth
            </div>

            <div class="rounded-[2rem] bg-ink p-7 text-white">
                <p class="text-sm font-bold text-cobalt-300">{{ __('Membership Access') }}</p>
                <h2 class="h2 mt-1">{{ __(ucfirst($level)) }} {{ __('Tier') }}</h2>
                <p class="mt-3 text-sm leading-relaxed text-white/65">{{ __('This premium reward is designed for loyal members with elevated shopping status and exclusive account benefits.') }}</p>
                <div class="mt-5 flex flex-wrap gap-2">
                    <span class="badge bg-white/10 text-white">{{ __('Discount') }}: {{ $discountLabel }}</span>
                    <span class="badge bg-white/10 text-white">{{ __('Access Level') }}: {{ __(ucfirst($level)) }}</span>
                </div>
            </div>
        </aside>
    </div>
</div>
</x-app-layout>
