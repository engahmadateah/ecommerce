<x-app-layout>
    <x-page-head :badge="__('Premium Brand')" icon="icon-[ph--sparkle-fill]"
                 :title="__('About').' '.__('Us')"
                 :text="__('We create a modern premium shopping experience focused on elegant design, trusted quality, and exceptional customer satisfaction.')" />

    <section class="container-x section">
        <div class="grid gap-4 md:grid-cols-2">
            <div data-reveal class="rounded-[2rem] bg-cobalt-50 p-8 sm:p-10">
                <span class="grid h-14 w-14 place-items-center rounded-2xl bg-white text-3xl text-cobalt-600"><i class="icon-[ph--diamond]"></i></span>
                <h2 class="h3 mt-10">{{ __('Premium Quality') }}</h2>
                <p class="mt-2 max-w-sm text-mute">{{ __('Carefully selected products designed for customers who value elegance and quality.') }}</p>
            </div>
            <div data-reveal data-reveal-delay="0.08" class="rounded-[2rem] bg-ink p-8 text-white sm:p-10">
                <span class="grid h-14 w-14 place-items-center rounded-2xl bg-white/10 text-3xl text-cobalt-300"><i class="icon-[ph--shield-check]"></i></span>
                <h2 class="h3 mt-10">{{ __('Trusted Experience') }}</h2>
                <p class="mt-2 max-w-sm text-white/65">{{ __('Secure shopping, fast delivery, and customer-first support every step of the way.') }}</p>
            </div>
        </div>

        <div class="mx-auto mt-20 max-w-3xl">
            <p class="text-sm font-bold text-cobalt-600">{{ __('Passion for premium digital commerce') }}</p>
            <h2 class="h1 mt-2">{{ __('Our Story') }}</h2>
            <div class="prose-legal mt-8">{!! nl2br(e($settings->about ?? __('No about content added yet.'))) !!}</div>
        </div>
    </section>
</x-app-layout>
