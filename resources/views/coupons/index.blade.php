<x-app-layout>
    <x-page-head :badge="__('Exclusive Luxury Discounts')" icon="icon-[ph--ticket]"
                 :title="__('Discover Premium Coupons & Rewards')"
                 :text="__('Unlock elite shopping perks, luxury member rewards, exclusive seasonal campaigns, and premium discount codes crafted for the next generation shopping experience.')">
        <a href="#offers" class="btn btn-brand btn-lg">{{ __('Explore Offers') }}<i class="icon-[ph--arrow-down] text-xl"></i></a>
        <a href="/packages" class="btn glass btn-lg text-white hover:bg-white/20">{{ __('VIP Bundles') }}</a>
    </x-page-head>

    <section id="offers" class="container-x section">
        <div class="mb-10 flex flex-wrap items-end justify-between gap-4">
            <div>
                <h2 class="h2">{{ __('Featured Coupons') }}</h2>
                <p class="lead mt-2">{{ __('Premium Deals Updated Daily') }}</p>
            </div>
            <span class="badge badge-ink px-4 py-2 text-sm">{{ $coupons->count() }} {{ __('Active Premium Offers') }}</span>
        </div>

        @if ($coupons->count())
            <div class="grid gap-6 lg:grid-cols-2">
                @foreach ($coupons as $coupon)
                    @php
                        $discountLabel = $coupon->type === 'percent'
                            ? __(':value% off', ['value' => rtrim(rtrim((string) $coupon->value, '0'), '.')])
                            : __(':amount off', ['amount' => \App\Support\Currency::format($coupon->value)]);
                        $level = $coupon->required_level ?? 'bronze';
                        $tone = match ($level) {
                            'gold' => 'from-saffron-300 to-saffron-500 text-ink',
                            'silver' => 'from-[#e6e9f2] to-[#aab4cb] text-ink',
                            default => 'from-[#ff9a5a] to-coral-500 text-white',
                        };
                    @endphp
                    <a href="{{ route('coupons.show', $coupon) }}" data-reveal data-reveal-delay="{{ ($loop->index % 2) * 0.08 }}"
                       class="group flex overflow-hidden rounded-[2rem] border border-line bg-white shadow-soft transition duration-300 hover:-translate-y-1 hover:shadow-lift">
                        <div class="relative flex w-40 shrink-0 flex-col items-center justify-center gap-2 bg-linear-to-br p-5 text-center sm:w-48 {{ $tone }}">
                            @if ($coupon->image)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($coupon->image) }}" alt="" class="absolute inset-0 h-full w-full object-cover opacity-30 mix-blend-multiply">
                            @endif
                            <i class="icon-[ph--ticket] relative text-4xl opacity-80"></i>
                            <p class="relative font-display text-2xl font-bold leading-tight sm:text-3xl">{{ $discountLabel }}</p>
                            <span class="relative rounded-full bg-white/30 px-3 py-1 text-xs font-bold backdrop-blur">{{ __(ucfirst($level)) }}</span>
                        </div>

                        {{-- perforation --}}
                        <div class="relative w-0 border-s-2 border-dashed border-line">
                            <span class="absolute -start-3 -top-3 h-6 w-6 rounded-full bg-fog"></span>
                            <span class="absolute -bottom-3 -start-3 h-6 w-6 rounded-full bg-fog"></span>
                        </div>

                        <div class="flex min-w-0 flex-1 flex-col p-6">
                            <h3 class="h3 line-clamp-1">{{ $coupon->title ?? $coupon->code }}</h3>
                            <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-mute">{{ $coupon->description ?? __('Exclusive luxury shopping reward available for a limited time only.') }}</p>

                            <div class="mt-auto flex flex-wrap items-center justify-between gap-3 pt-5">
                                <span class="rounded-xl border border-dashed border-ink/30 bg-fog px-3 py-1.5 font-mono text-sm font-bold tracking-wider">{{ $coupon->code }}</span>
                                <span class="text-xs text-mute">
                                    {{ __('Available For') }}: <b class="text-ink">{{ __(ucfirst($level)) }} {{ __('& above') }}</b>
                                    @if ($coupon->expires_at)<br>{{ __('Expires') }}: <b class="text-ink">{{ $coupon->expires_at->format('M d, Y') }}</b>@endif
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="mx-auto flex max-w-md flex-col items-center py-16 text-center">
                <span class="grid h-24 w-24 place-items-center rounded-full bg-cobalt-50 text-5xl text-cobalt-500"><i class="icon-[ph--ticket]"></i></span>
                <h2 class="h2 mt-6">{{ __('No Coupons Yet') }}</h2>
                <p class="lead mt-3">{{ __('New premium rewards, luxury member discounts, and exclusive shopping offers will appear here soon.') }}</p>
            </div>
        @endif
    </section>
</x-app-layout>
