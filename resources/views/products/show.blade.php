<x-app-layout>
@php
    $seoPrice = number_format($product->currentPrice(), 2, '.', '');
    $seoReviews = $product->reviews;
    $seoJson = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->localized_name,
        'description' => \Illuminate\Support\Str::limit(strip_tags((string) $product->localized_description), 300),
        'image' => array_map(fn ($i) => asset('storage/'.$i), $product->all_images),
        'sku' => $product->sku ?: (string) $product->id,
        'offers' => [
            '@type' => 'Offer',
            'url' => route('products.show', $product),
            'priceCurrency' => 'USD',
            'price' => $seoPrice,
            'availability' => $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
        ],
    ];
    if ($seoReviews->count() > 0) {
        $seoJson['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => round($seoReviews->avg('rating'), 1),
            'reviewCount' => $seoReviews->count(),
        ];
    }
@endphp
@section('seo_title', $product->localized_name.' | '.(\App\Models\Setting::current()?->site_name ?: config('app.name')))
@section('seo_description', \Illuminate\Support\Str::limit(strip_tags((string) $product->localized_description), 155))
@if($product->image)
    @section('seo_image', asset('storage/'.$product->image))
@endif
@section('seo_type', 'product')
@push('seo')
    <script type="application/ld+json">{!! json_encode($seoJson, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endpush

@php
    $images = $product->all_images;
    $name = $product->localized_name;
    $price = (float) $product->price;
    $hasDeal = $product->discount_price !== null && (float) $product->discount_price < $price;
    $now = $product->currentPrice();
    $off = $hasDeal && $price > 0 ? (int) round((1 - $now / $price) * 100) : 0;
    $reviews = $product->reviews;
    $reviewCount = $reviews->count();
    $avg = $reviewCount ? round($reviews->avg('rating'), 1) : 0;
    $dist = $reviews->groupBy('rating')->map->count();
    $soldOut = $product->stock <= 0;
    $low = ! $soldOut && $product->stock <= 5;
    $wished = auth()->check() && auth()->user()->wishlist->contains($product->id);
    $variantOptions = $product->activeVariants()->orderBy('sort_order')->orderBy('id')->get();
    $returnDays = (int) config('shop.returns.days', 14);
@endphp

<div class="container-x pt-6 sm:pt-10">

    {{-- Breadcrumb --}}
    <nav class="mb-6 flex items-center gap-2 text-sm text-mute" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="transition hover:text-ink">{{ __('Home') }}</a>
        <i class="icon-[ph--caret-right] text-xs rtl:-scale-x-100"></i>
        @if ($product->category)
            <a href="{{ route('home', ['category' => $product->category_id]) }}" class="transition hover:text-ink">{{ $product->category->localized_name }}</a>
            <i class="icon-[ph--caret-right] text-xs rtl:-scale-x-100"></i>
        @endif
        <span class="truncate font-semibold text-ink">{{ $name }}</span>
    </nav>

    <div class="grid gap-10 lg:grid-cols-[1.1fr_.9fr] lg:gap-16">

        {{-- ───────── Gallery ───────── --}}
        <div data-gallery class="min-w-0 lg:sticky lg:top-28 lg:self-start">
            <div class="relative">
                <div class="swiper tile rounded-[2rem]" data-gallery-main>
                    <div class="swiper-wrapper">
                        @forelse ($images as $img)
                            <div class="swiper-slide">
                                <div class="swiper-zoom-container aspect-[4/5]">
                                    <img src="{{ asset('storage/'.$img) }}" alt="{{ $name }}" class="h-full w-full object-cover" @if ($loop->first) data-product-main-img fetchpriority="high" @endif>
                                </div>
                            </div>
                        @empty
                            <div class="swiper-slide"><div class="grid aspect-[4/5] place-items-center text-8xl text-mute/30"><i class="icon-[ph--image]"></i></div></div>
                        @endforelse
                    </div>
                    <div class="swiper-pagination !bottom-4 sm:!hidden"></div>
                </div>

                <div class="pointer-events-none absolute start-4 top-4 z-10 flex flex-col items-start gap-1.5">
                    @if ($hasDeal)<span class="badge badge-sale text-[13px]" dir="ltr">-{{ $off }}%</span>@endif
                    @if ($soldOut)
                        <span class="badge badge-ink text-[13px]">{{ __('Out of stock') }}</span>
                    @elseif ($low)
                        <span class="badge badge-deal text-[13px]">{{ __('Only :count left', ['count' => $product->stock]) }}</span>
                    @endif
                </div>

                @if (count($images) > 1)
                    <div class="absolute inset-x-4 top-1/2 z-10 hidden -translate-y-1/2 justify-between sm:flex">
                        <button type="button" data-prev class="nav-btn" aria-label="{{ __('Previous') }}"><i class="icon-[ph--arrow-left] rtl:-scale-x-100"></i></button>
                        <button type="button" data-next class="nav-btn" aria-label="{{ __('Next') }}"><i class="icon-[ph--arrow-right] rtl:-scale-x-100"></i></button>
                    </div>
                @endif
            </div>

            @if (count($images) > 1)
                <div class="swiper mt-3" data-gallery-thumbs>
                    <div class="swiper-wrapper">
                        @foreach ($images as $img)
                            <div class="swiper-slide !w-20 cursor-pointer overflow-hidden rounded-2xl bg-tile">
                                <img src="{{ asset('storage/'.$img) }}" alt="" class="aspect-square h-full w-full object-cover" loading="lazy">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- ───────── Details ───────── --}}
        <div class="min-w-0">
            @if ($product->category)
                <a href="{{ route('home', ['category' => $product->category_id]) }}" class="badge badge-info text-[13px]">{{ $product->category->localized_name }}</a>
            @endif

            <h1 class="h1 mt-4">{{ $name }}</h1>

            <div class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm">
                @if ($reviewCount)
                    <a href="#reviews" class="inline-flex items-center gap-2 font-semibold">
                        <x-stars :rating="round($avg)" class="text-lg" />
                        <span>{{ $avg }}</span>
                        <span class="font-normal text-mute underline underline-offset-4">{{ $reviewCount }} {{ __('Reviews') }}</span>
                    </a>
                @endif
                @if ($soldOut)
                    <span class="badge badge-muted">{{ __('Out of stock') }}</span>
                @else
                    <span class="badge badge-ok"><i class="icon-[ph--check-circle-fill]"></i>{{ $low ? __('Only :count left', ['count' => $product->stock]) : __('In stock') }}</span>
                @endif
            </div>

            <div class="mt-7 flex flex-wrap items-end gap-x-4 gap-y-2">
                <p class="font-display text-5xl font-bold leading-none">@money($now)</p>
                @if ($hasDeal)
                    <s class="pb-1 text-xl text-mute">@money($price)</s>
                    <span class="badge badge-sale mb-1.5 text-[13px]">{{ __('Save') }} <bdi>@money($price - $now)</bdi></span>
                @endif
            </div>

            @if ($product->localized_description)
                <p class="mt-7 max-w-prose whitespace-pre-line leading-relaxed text-mute">{{ $product->localized_description }}</p>
            @endif

            {{-- Buy box --}}
            <form method="POST" action="/cart/add/{{ $product->id }}" data-cart-form data-success-text="{{ __('Added to cart') }}" class="mt-8">
                @csrf

                @if ($variantOptions->isNotEmpty())
                    <fieldset x-data="{ selected: null }" class="mb-6">
                        <legend class="mb-3 text-sm font-bold">{{ __('Choose an option') }}</legend>
                        <div class="flex flex-wrap gap-2.5">
                            @foreach ($variantOptions as $variant)
                                @php $variant->setRelation('product', $product); @endphp
                                <label class="relative cursor-pointer {{ $variant->stock <= 0 ? 'cursor-not-allowed opacity-45' : '' }}">
                                    <input type="radio" name="variant_id" value="{{ $variant->id }}" x-model.number="selected" class="peer sr-only" @disabled($variant->stock <= 0) required>
                                    <span class="flex min-w-24 flex-col rounded-2xl border border-line bg-white px-4 py-3 transition peer-checked:border-cobalt-500 peer-checked:bg-cobalt-50 peer-checked:ring-4 peer-checked:ring-cobalt-500/15 peer-focus-visible:outline-2 peer-focus-visible:outline-cobalt-500 hover:border-ink">
                                        <span class="text-[15px] font-bold">{{ $variant->name }}</span>
                                        <span class="mt-0.5 text-[13px] text-mute">
                                            @money($variant->currentPrice())
                                            @if ($variant->stock <= 0) · {{ __('Out of stock') }} @endif
                                        </span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>
                @endif

                <div class="flex flex-col gap-3 sm:flex-row">
                    <button type="submit" class="btn btn-brand btn-lg sm:flex-1" @disabled($soldOut)>
                        <i class="icon-[ph--handbag] text-xl"></i>{{ $soldOut ? __('Out of stock') : __('Add To Cart') }}
                    </button>
                    @unless ($soldOut)
                        <button type="submit" data-buy-now class="btn btn-ink btn-lg">{{ __('Buy Now') }}</button>
                    @endunless
                    @auth
                        <button type="button" data-wishlist="{{ $product->id }}" aria-pressed="{{ $wished ? 'true' : 'false' }}"
                                data-added-text="{{ __('Saved to your wishlist') }}" data-removed-text="{{ __('Removed from your wishlist') }}" data-error-text="{{ __('Something went wrong. Please try again.') }}"
                                class="btn btn-line btn-lg w-full text-2xl sm:w-14 sm:px-0" aria-label="{{ __('Wishlist') }}">
                            <i class="w-off icon-[ph--heart]"></i><i class="w-on icon-[ph--heart-fill] text-coral-500"></i>
                        </button>
                    @endauth
                </div>
            </form>

            <ul class="mt-8 grid gap-3 border-t border-line pt-8 sm:grid-cols-3">
                <li class="flex items-start gap-3"><span class="grid h-10 w-10 shrink-0 place-items-center rounded-2xl bg-cobalt-50 text-xl text-cobalt-600"><i class="icon-[ph--shield-check]"></i></span><span class="text-[13px] leading-snug"><b class="block text-sm">{{ __('Secure Checkout') }}</b><span class="text-mute">{{ __('100% encrypted premium payment system.') }}</span></span></li>
                <li class="flex items-start gap-3"><span class="grid h-10 w-10 shrink-0 place-items-center rounded-2xl bg-cobalt-50 text-xl text-cobalt-600"><i class="icon-[ph--truck]"></i></span><span class="text-[13px] leading-snug"><b class="block text-sm">{{ __('Fast Shipping') }}</b><span class="text-mute">{{ __('Express delivery with premium packaging.') }}</span></span></li>
                <li class="flex items-start gap-3"><span class="grid h-10 w-10 shrink-0 place-items-center rounded-2xl bg-cobalt-50 text-xl text-cobalt-600"><i class="icon-[ph--arrow-counter-clockwise]"></i></span><span class="text-[13px] leading-snug"><b class="block text-sm">{{ __('Returns') }}</b><span class="text-mute">{{ __('Easy :days-day returns', ['days' => $returnDays]) }}</span></span></li>
            </ul>
        </div>
    </div>

    {{-- ───────── Reviews ───────── --}}
    <section id="reviews" class="mt-20 grid gap-10 border-t border-line pt-16 lg:grid-cols-[22rem_1fr] lg:gap-16">
        <div class="space-y-6">
            <div>
                <h2 class="h2">{{ __('Customer Reviews') }}</h2>
                @if ($reviewCount)
                    <div class="mt-5 flex items-end gap-4">
                        <span class="font-display text-6xl font-bold leading-none">{{ $avg }}</span>
                        <div class="pb-1"><x-stars :rating="round($avg)" class="text-xl" /><p class="mt-1 text-sm text-mute">{{ $reviewCount }} {{ __('Reviews') }}</p></div>
                    </div>
                    <div class="mt-5 space-y-2">
                        @foreach ([5, 4, 3, 2, 1] as $star)
                            @php $n = (int) ($dist[$star] ?? 0); $pct = $reviewCount ? round($n / $reviewCount * 100) : 0; @endphp
                            <div class="flex items-center gap-3 text-sm">
                                <span class="inline-flex w-8 items-center gap-1 font-semibold">{{ $star }}<i class="icon-[ph--star-fill] text-saffron-500"></i></span>
                                <span class="h-2 flex-1 overflow-hidden rounded-full bg-tile"><span class="block h-full rounded-full bg-saffron-500" style="width: {{ $pct }}%"></span></span>
                                <span class="w-8 text-end text-mute">{{ $n }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="mt-2 text-mute">{{ __('Trusted opinions from buyers') }}</p>
                @endif
            </div>

            @auth
                <div class="panel-flat"
                     x-data="{
                        rating: 0, hover: 0, comment: '', busy: false,
                        async submit() {
                            if (!this.rating) { window.toast(@js(__('Please select a rating')), 'error'); return; }
                            this.busy = true;
                            try {
                                const res = await fetch('/products/{{ $product->id }}/review', {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                                    body: JSON.stringify({ rating: this.rating, comment: this.comment }),
                                });
                                if (!res.ok) throw new Error('review');
                                location.reload();
                            } catch (e) {
                                window.toast(@js(__('Something went wrong. Please try again.')), 'error');
                                this.busy = false;
                            }
                        }
                     }">
                    <h3 class="h3">{{ __('Write Review') }}</h3>
                    <div class="mt-3 flex gap-1 text-3xl" @mouseleave="hover = 0">
                        <template x-for="star in 5" :key="star">
                            <button type="button" @mouseenter="hover = star" @click="rating = star" :aria-label="star" class="transition hover:scale-110"
                                    :class="(hover || rating) >= star ? 'text-saffron-500' : 'text-line'"><i class="icon-[ph--star-fill]"></i></button>
                        </template>
                    </div>
                    <textarea x-model="comment" rows="3" class="field mt-4" placeholder="{{ __('Share your premium experience...') }}"></textarea>
                    <button type="button" @click="submit()" :disabled="busy" class="btn btn-ink btn-block mt-4">{{ __('Submit Review') }}</button>
                </div>
            @endauth
        </div>

        <div class="space-y-4">
            @forelse ($reviews as $review)
                <article class="rounded-3xl border border-line bg-white p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="grid h-11 w-11 place-items-center rounded-full bg-cobalt-50 font-display text-base font-bold text-cobalt-700">{{ mb_strtoupper(mb_substr($review->user->name, 0, 1)) }}</span>
                            <div>
                                <h3 class="text-[15px] font-bold">{{ $review->user->name }}</h3>
                                <p class="inline-flex items-center gap-1 text-xs font-semibold text-mint-600"><i class="icon-[ph--seal-check-fill]"></i>{{ __('Verified Purchase') }}</p>
                            </div>
                        </div>
                        <x-stars :rating="$review->rating" />
                    </div>
                    @if ($review->comment)
                        <p class="mt-4 leading-relaxed text-mute">{{ $review->comment }}</p>
                    @endif
                </article>
            @empty
                <div class="grid place-items-center rounded-3xl border border-dashed border-line px-6 py-16 text-center">
                    <span class="grid h-16 w-16 place-items-center rounded-full bg-cobalt-50 text-3xl text-cobalt-500"><i class="icon-[ph--chat-circle-dots]"></i></span>
                    <h3 class="h3 mt-5">{{ __('No Reviews Yet') }}</h3>
                    <p class="mt-1 text-mute">{{ __('Be the first to review this luxury product.') }}</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- ───────── You may also like ───────── --}}
    @if ($alsoBought->count())
        <section class="mt-20 border-t border-line pt-16">
            <h2 class="h2">{{ __('You May Also Like') }}</h2>
            <p class="lead mt-2">{{ __('Curated premium recommendations for you') }}</p>
            <div class="mt-8 grid grid-cols-2 gap-x-4 gap-y-10 sm:gap-x-6 lg:grid-cols-4">
                @foreach ($alsoBought as $item)
                    <x-product-card :product="$item" :index="$loop->index" />
                @endforeach
            </div>
        </section>
    @endif
</div>
</x-app-layout>
