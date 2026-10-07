{{-- Live-search results: the same tiles as the home page, fetched by resources/js/app.js (catalog). --}}
@forelse($products as $product)
    <x-product-card :product="$product" :index="$loop->index" />
@empty
    <div class="col-span-full mx-auto flex max-w-md flex-col items-center py-20 text-center">
        <span class="grid h-20 w-20 place-items-center rounded-full bg-cobalt-50 text-4xl text-cobalt-500"><i class="icon-[ph--magnifying-glass]"></i></span>
        <h3 class="h3 mt-6">{{ __('No Products Found') }}</h3>
        <p class="mt-2 text-mute">{{ __('Try another category or search term.') }}</p>
    </div>
@endforelse
