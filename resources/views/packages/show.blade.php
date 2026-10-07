<x-app-layout>
@php
    $original = $package->products->sum('price');
    $save = $original - $package->price;
@endphp

<div class="container-x pt-6 sm:pt-10">
    <nav class="mb-6 flex items-center gap-2 text-sm text-mute" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="transition hover:text-ink">{{ __('Home') }}</a>
        <i class="icon-[ph--caret-right] text-xs rtl:-scale-x-100"></i>
        <a href="{{ route('packages.index') }}" class="transition hover:text-ink">{{ __('Bundles') }}</a>
        <i class="icon-[ph--caret-right] text-xs rtl:-scale-x-100"></i>
        <span class="truncate font-semibold text-ink">{{ $package->name }}</span>
    </nav>

    <div data-product-card class="relative isolate overflow-hidden rounded-[2rem] bg-ink p-8 text-white sm:rounded-[2.5rem] sm:p-12">
        <div class="aurora opacity-70" aria-hidden="true"><i></i><i></i><i></i></div>
        <div class="relative grid items-center gap-10 lg:grid-cols-[1.2fr_1fr]">
            <div>
                <span class="badge badge-deal text-[13px]"><i class="icon-[ph--stack-simple]"></i>{{ __('Exclusive Bundle') }}</span>
                <h1 class="display mt-5 !text-[clamp(2.2rem,4.6vw,3.8rem)]">{{ $package->name }}</h1>
                @if ($package->description)
                    <p class="mt-5 max-w-xl text-lg leading-relaxed text-white/70">{{ $package->description }}</p>
                @endif
            </div>

            <div class="glass rounded-[2rem] p-7">
                <p class="text-sm text-white/65">{{ __('Bundle Price') }}</p>
                <div class="mt-1 flex flex-wrap items-baseline gap-3">
                    <span class="font-display text-5xl font-bold leading-none">@money($package->price)</span>
                    @if ($original > $package->price)<s class="text-white/50">@money($original)</s>@endif
                </div>
                @if ($original > $package->price)
                    <span class="badge badge-deal mt-3">{{ __('Save') }} <bdi>@money($save)</bdi></span>
                @endif
                <form method="POST" action="{{ route('packages.add', $package) }}" data-cart-form data-success-text="{{ __('Added to cart') }}" class="mt-6">
                    @csrf
                    <button type="submit" class="btn btn-brand btn-lg btn-block"><i class="icon-[ph--handbag] text-xl"></i>{{ __('Add Bundle') }}</button>
                </form>
            </div>
        </div>
    </div>

    <section class="section pb-0">
        <div class="mb-8 flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 class="h2">{{ __('Included Products') }}</h2>
                <p class="lead mt-2">{{ __('Everything included in this premium bundle') }}</p>
            </div>
            <span class="badge badge-ink px-4 py-2 text-sm">{{ $package->products->count() }} {{ __('Items') }}</span>
        </div>

        <div class="grid grid-cols-2 gap-x-4 gap-y-10 sm:gap-x-6 lg:grid-cols-4">
            @foreach ($package->products as $product)
                <article data-reveal data-reveal-delay="{{ ($loop->index % 4) * 0.07 }}" class="group">
                    <a href="{{ route('products.show', $product) }}" class="tile block aspect-[4/5] rounded-[1.75rem]">
                        @if ($product->image)<img data-product-img loading="lazy" decoding="async" src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->localized_name }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.05]">@endif
                    </a>
                    <div class="mt-3.5 px-1">
                        <h3 class="line-clamp-2 font-display text-[17px] font-semibold leading-snug">{{ $product->localized_name }}</h3>
                        <p class="mt-1 text-sm text-mute">{{ __('Product Price') }} · <span class="font-bold text-ink">@money($product->price)</span></p>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
</div>
</x-app-layout>
