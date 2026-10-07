<x-app-layout>
    <x-page-head :badge="__('Premium Bundle Collection')" icon="icon-[ph--stack-simple]"
                 :title="__('Exclusive').' '.__('Bundles')"
                 :text="__('Curated premium product combinations crafted for smarter shopping, better value, and a luxury experience in every bundle.')">
        <div class="glass rounded-3xl px-6 py-4"><p class="font-display text-3xl font-bold leading-none">{{ $packages->count() }}</p><p class="mt-1 text-sm text-white/70">{{ __('Active Bundles') }}</p></div>
        <div class="glass rounded-3xl px-6 py-4"><p class="font-display text-3xl font-bold leading-none">{{ __('Save') }}</p><p class="mt-1 text-sm text-white/70">{{ __('More With Bundles') }}</p></div>
    </x-page-head>

    <section class="container-x section">
        @if ($packages->count())
            <div class="grid gap-6 lg:grid-cols-2">
                @foreach ($packages as $package)
                    @php
                        $original = $package->products->sum('price');
                        $save = $original - $package->price;
                        $shown = $package->products->take(5);
                        $more = $package->products->count() - $shown->count();
                    @endphp
                    <article data-product-card data-reveal data-reveal-delay="{{ ($loop->index % 2) * 0.08 }}" class="flex flex-col rounded-[2rem] border border-line bg-white p-6 shadow-soft sm:p-8">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="badge badge-info">{{ __('Bundle Offer') }}</span>
                            <span class="badge badge-muted">{{ $package->products->count() }} {{ __('Items') }}</span>
                            @if ($original > $package->price)<span class="badge badge-sale">{{ __('Save') }} <bdi>@money($save)</bdi></span>@endif
                        </div>

                        <h2 class="h2 mt-5">{{ $package->name }}</h2>
                        @if ($package->description)
                            <p class="mt-2 line-clamp-2 text-mute">{{ $package->description }}</p>
                        @endif

                        <div class="mt-7 flex items-center">
                            @foreach ($shown as $product)
                                <div class="tile h-20 w-20 shrink-0 rounded-[1.25rem] ring-4 ring-white sm:h-24 sm:w-24 {{ $loop->first ? '' : '-ms-5' }}" style="z-index: {{ 10 - $loop->index }}">
                                    @if ($product->image)<img @if ($loop->first) data-product-img @endif loading="lazy" decoding="async" src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->localized_name }}" class="h-full w-full object-cover">@endif
                                </div>
                            @endforeach
                            @if ($more > 0)
                                <span class="-ms-5 grid h-20 w-20 shrink-0 place-items-center rounded-[1.25rem] bg-ink font-display text-lg font-bold text-white ring-4 ring-white sm:h-24 sm:w-24">+{{ $more }}</span>
                            @endif
                        </div>

                        <div class="mt-auto flex flex-wrap items-end justify-between gap-5 pt-8">
                            <div>
                                <p class="text-sm text-mute">{{ __('Bundle Price') }}</p>
                                <p class="flex items-baseline gap-3">
                                    <span class="font-display text-4xl font-bold leading-none">@money($package->price)</span>
                                    @if ($original > $package->price)<s class="text-mute">@money($original)</s>@endif
                                </p>
                            </div>
                            <div class="flex gap-2">
                                <a href="/packages/{{ $package->id }}" class="btn btn-line">{{ __('View Bundle') }}</a>
                                <form method="POST" action="{{ route('packages.add', $package) }}" data-cart-form data-success-text="{{ __('Added to cart') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-brand"><i class="icon-[ph--handbag] text-xl"></i>{{ __('Add Bundle') }}</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="mx-auto flex max-w-md flex-col items-center py-16 text-center">
                <span class="grid h-24 w-24 place-items-center rounded-full bg-cobalt-50 text-5xl text-cobalt-500"><i class="icon-[ph--stack-simple]"></i></span>
                <h2 class="h2 mt-6">{{ __('No Bundles Yet') }}</h2>
                <p class="lead mt-3">{{ __('Premium bundle offers will appear here soon.') }}</p>
            </div>
        @endif
    </section>
</x-app-layout>
