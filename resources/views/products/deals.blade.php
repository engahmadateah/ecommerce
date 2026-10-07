<x-app-layout>
    <x-page-head tone="brand" :badge="__('Limited Offers')" icon="icon-[ph--lightning-fill]"
                 :title="__('Today\'s').' '.__('Deals')"
                 :text="__('Discover exclusive discounts on premium products with elegant shopping experience and limited-time offers.')">
        <div class="glass rounded-3xl px-6 py-4"><p class="font-display text-3xl font-bold leading-none">{{ $products->total() }}</p><p class="mt-1 text-sm text-white/70">{{ __('Active Deals') }}</p></div>
        <div class="glass rounded-3xl px-6 py-4"><p class="font-display text-3xl font-bold leading-none">{{ __('HOT') }}</p><p class="mt-1 text-sm text-white/70">{{ __('Daily Discounts') }}</p></div>
    </x-page-head>

    <section class="container-x section">
        @if ($products->count())
            <div class="grid grid-cols-2 gap-x-4 gap-y-10 sm:gap-x-6 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($products as $product)
                    <x-product-card :product="$product" :index="$loop->index" />
                @endforeach
            </div>
            <div class="mt-16">{{ $products->links('vendor.pagination.shop') }}</div>
        @else
            <div class="mx-auto flex max-w-md flex-col items-center py-16 text-center">
                <span class="grid h-24 w-24 place-items-center rounded-full bg-cobalt-50 text-5xl text-cobalt-500"><i class="icon-[ph--tag]"></i></span>
                <h2 class="h2 mt-6">{{ __('No Deals Available') }}</h2>
                <p class="lead mt-3">{{ __('There are no active deals right now. Check back later for new premium discounts.') }}</p>
                <a href="{{ route('home') }}" class="btn btn-brand btn-lg mt-8">{{ __('Explore Products') }}</a>
            </div>
        @endif
    </section>
</x-app-layout>
