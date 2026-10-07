{{--
    One product tile, used by the home page, the live search results, deals and "you may also like".
    Data used: name, image, price, discount_price, stock, category, reviews_avg_rating, reviews_count (all optional).
--}}
@props(['product', 'index' => 0, 'reveal' => true])
@php
    $price = (float) $product->price;
    $deal = $product->discount_price !== null && (float) $product->discount_price > 0 && (float) $product->discount_price < $price;
    $now = $deal ? (float) $product->discount_price : $price;
    $off = $deal && $price > 0 ? (int) round((1 - $now / $price) * 100) : 0;
    $stock = $product->stock;
    $soldOut = $stock !== null && (int) $stock <= 0;
    $low = ! $soldOut && $stock !== null && (int) $stock <= 5;
    $user = auth()->user();
    $wished = $user ? $user->wishlist->contains($product->id) : false;
    $name = $product->localized_name;
    $url = route('products.show', $product);
    $reviews = (int) ($product->reviews_count ?? 0);
@endphp

<article data-product-card
         @if ($reveal) data-reveal data-reveal-delay="{{ ($index % 4) * 0.07 }}" @endif
         class="group relative">

    <div class="tile aspect-[4/5] rounded-[1.75rem]">
        <a href="{{ $url }}" class="absolute inset-0 z-10" aria-label="{{ $name }}"></a>

        @if ($product->image)
            <img data-product-img src="{{ asset('storage/'.$product->image) }}" alt="{{ $name }}" loading="lazy" decoding="async"
                 class="h-full w-full object-cover transition duration-700 ease-out group-hover:scale-[1.05] {{ $soldOut ? 'opacity-60 grayscale' : '' }}">
        @else
            <div class="grid h-full w-full place-items-center text-6xl text-mute/30"><i class="icon-[ph--image]"></i></div>
        @endif

        <div class="pointer-events-none absolute start-3 top-3 z-20 flex flex-col items-start gap-1.5">
            @if ($deal)<span class="badge badge-sale" dir="ltr">-{{ $off }}%</span>@endif
            @if ($soldOut)
                <span class="badge badge-ink">{{ __('Out of stock') }}</span>
            @elseif ($low)
                <span class="badge badge-deal">{{ __('Only :count left', ['count' => $stock]) }}</span>
            @endif
        </div>

        @auth
            <button type="button" data-wishlist="{{ $product->id }}" aria-pressed="{{ $wished ? 'true' : 'false' }}"
                    data-added-text="{{ __('Saved to your wishlist') }}" data-removed-text="{{ __('Removed from your wishlist') }}" data-error-text="{{ __('Something went wrong. Please try again.') }}"
                    aria-label="{{ __('Wishlist') }}"
                    class="absolute end-3 top-3 z-20 grid h-10 w-10 place-items-center rounded-full bg-white/90 text-xl text-ink shadow-soft backdrop-blur transition hover:scale-110">
                <i class="w-off icon-[ph--heart]"></i><i class="w-on icon-[ph--heart-fill] text-coral-500"></i>
            </button>
        @else
            <a href="/login" aria-label="{{ __('Wishlist') }}" class="absolute end-3 top-3 z-20 grid h-10 w-10 place-items-center rounded-full bg-white/90 text-xl text-ink shadow-soft backdrop-blur transition hover:scale-110">
                <i class="icon-[ph--heart]"></i>
            </a>
        @endauth

        @unless ($soldOut)
            <form method="POST" action="/cart/add/{{ $product->id }}" data-cart-form data-success-text="{{ __('Added to cart') }}"
                  class="absolute bottom-3 end-3 z-20 transition duration-300 md:start-3
                         md:pointer-fine:translate-y-3 md:pointer-fine:opacity-0 md:pointer-fine:group-hover:translate-y-0 md:pointer-fine:group-hover:opacity-100 md:pointer-fine:group-focus-within:translate-y-0 md:pointer-fine:group-focus-within:opacity-100">
                @csrf
                <button type="submit" class="btn btn-ink h-11 w-11 p-0 shadow-lift md:w-full md:px-4" aria-label="{{ __('Add To Cart') }}">
                    <i class="icon-[ph--handbag] text-xl"></i>
                    <span class="hidden md:inline">{{ __('Add To Cart') }}</span>
                </button>
            </form>
        @endunless
    </div>

    <div class="mt-3.5 px-1">
        @if ($product->category)
            <p class="truncate text-[13px] font-medium text-mute">{{ $product->category->localized_name }}</p>
        @endif
        <h3 class="mt-0.5 line-clamp-2 font-display text-[17px] font-semibold leading-snug">
            <a href="{{ $url }}" class="transition hover:text-cobalt-600">{{ $name }}</a>
        </h3>
        <div class="mt-2 flex items-center justify-between gap-3">
            <p class="flex items-baseline gap-2">
                <span class="font-display text-xl font-bold">@money($now)</span>
                @if ($deal)<s class="text-sm text-mute">@money($price)</s>@endif
            </p>
            @if ($reviews > 0)
                <span class="inline-flex items-center gap-1 text-[13px] font-semibold">
                    <i class="icon-[ph--star-fill] text-saffron-500"></i>{{ number_format((float) $product->reviews_avg_rating, 1) }}
                    <span class="font-normal text-mute">({{ $reviews }})</span>
                </span>
            @endif
        </div>
    </div>
</article>
