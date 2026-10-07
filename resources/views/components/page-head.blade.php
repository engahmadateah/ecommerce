{{-- Rounded "stage" header for collection pages (deals, wishlist, bundles, coupons...). --}}
@props(['title', 'text' => null, 'badge' => null, 'icon' => null, 'tone' => 'ink'])
@php
    $bg = $tone === 'brand' ? 'bg-linear-to-br from-cobalt-600 via-cobalt-500 to-[#6d4cff]' : 'bg-ink';
@endphp
<section class="px-3 pt-3 sm:px-5 sm:pt-4">
    <div data-hero class="grain relative isolate overflow-hidden rounded-[2rem] text-white sm:rounded-[2.75rem] {{ $bg }}">
        <div class="aurora" aria-hidden="true"><i></i><i></i><i></i></div>
        <div class="container-x relative flex flex-col gap-8 py-14 sm:py-20 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-2xl">
                @if ($badge)
                    <span data-hero-item class="badge badge-deal mb-5 text-[13px]">@if ($icon)<i class="{{ $icon }}"></i>@endif{{ $badge }}</span>
                @endif
                <h1 data-hero-item class="display !text-[clamp(2.4rem,5vw,4.2rem)]">{{ $title }}</h1>
                @if ($text)
                    <p data-hero-item class="mt-5 max-w-xl text-lg leading-relaxed text-white/70">{{ $text }}</p>
                @endif
            </div>
            @if (isset($slot) && trim((string) $slot) !== '')
                <div data-hero-item class="flex flex-wrap gap-3">{{ $slot }}</div>
            @endif
        </div>
    </div>
</section>
