<x-app-layout>
@section('seo_robots', 'noindex,nofollow')

    <x-page-head :badge="__('Wishlist Collection')" icon="icon-[ph--heart-fill]"
                 :title="__('My').' '.__('Wishlist')"
                 :text="__('Save and organize your favorite premium products with a modern luxury shopping experience.')">
        <div class="glass rounded-3xl px-6 py-4"><p class="font-display text-3xl font-bold leading-none">{{ $products->count() }}</p><p class="mt-1 text-sm text-white/70">{{ __('Saved Items') }}</p></div>
    </x-page-head>

    <section class="container-x section">
        @if ($products->count())
            <div data-wishlist-page class="grid grid-cols-2 gap-x-4 gap-y-10 sm:gap-x-6 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($products as $product)
                    <x-product-card :product="$product" :index="$loop->index" />
                @endforeach
            </div>
        @else
            <div class="mx-auto flex max-w-md flex-col items-center py-16 text-center">
                <span class="grid h-24 w-24 place-items-center rounded-full bg-coral-50 text-5xl text-coral-500"><i class="icon-[ph--heart-break]"></i></span>
                <h2 class="h2 mt-6">{{ __('Wishlist Empty') }}</h2>
                <p class="lead mt-3">{{ __('Start building your premium collection by saving the products you love.') }}</p>
                <a href="{{ route('home') }}" class="btn btn-brand btn-lg mt-8">{{ __('Explore Products') }}</a>
            </div>
        @endif
    </section>
</x-app-layout>
