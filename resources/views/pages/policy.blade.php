<x-app-layout>
    <x-page-head :title="__($title)" icon="icon-[ph--file-text]" :badge="__('Information')" />

    <section class="container-x section">
        <div class="mx-auto max-w-3xl space-y-10">
            @foreach ($sections as $heading => $text)
                <section data-reveal class="rounded-[2rem] border border-line bg-white p-8">
                    <h2 class="h3">{{ __($heading) }}</h2>
                    <p class="mt-3 leading-relaxed text-mute">{{ __($text) }}</p>
                </section>
            @endforeach
        </div>
    </section>
</x-app-layout>
