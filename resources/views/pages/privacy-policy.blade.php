<x-app-layout>
    <x-page-head :badge="__('Enterprise Security')" icon="icon-[ph--shield-check-fill]"
                 :title="__('Privacy').' '.__('Policy')"
                 :text="__('We are committed to protecting your personal data, maintaining transparency, and delivering a secure digital experience aligned with modern global privacy practices.')" />

    <section class="container-x section">
        <div class="grid gap-12 lg:grid-cols-[18rem_1fr]">
            <aside class="space-y-3 lg:sticky lg:top-28 lg:self-start">
                <div class="rounded-3xl bg-cobalt-50 p-6"><i class="icon-[ph--database] text-3xl text-cobalt-600"></i><h3 class="mt-4 font-bold">{{ __('Data Collection') }}</h3><p class="mt-1 text-sm text-mute">{{ __('Information is collected only when necessary to improve platform functionality, customer support, and account security.') }}</p></div>
                <div class="rounded-3xl bg-cobalt-50 p-6"><i class="icon-[ph--fingerprint] text-3xl text-cobalt-600"></i><h3 class="mt-4 font-bold">{{ __('Confidentiality') }}</h3><p class="mt-1 text-sm text-mute">{{ __('Personal data remains confidential and is never sold or shared without lawful and transparent consent.') }}</p></div>
            </aside>
            <div class="prose-legal">{!! nl2br(e($settings->privacy_policy ?? __('No privacy policy added yet.'))) !!}</div>
        </div>
    </section>
</x-app-layout>
