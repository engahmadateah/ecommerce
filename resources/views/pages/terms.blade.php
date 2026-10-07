<x-app-layout>
    <x-page-head :badge="__('Legal Agreement')" icon="icon-[ph--scales]"
                 :title="__('Terms &').' '.__('Conditions')"
                 :text="__('Please review these terms carefully before using our platform, services, and digital shopping experience.')" />

    <section class="container-x section">
        <div class="grid gap-12 lg:grid-cols-[18rem_1fr]">
            <aside class="space-y-3 lg:sticky lg:top-28 lg:self-start">
                <div class="rounded-3xl bg-cobalt-50 p-6"><i class="icon-[ph--user-check] text-3xl text-cobalt-600"></i><h3 class="mt-4 font-bold">{{ __('User Responsibility') }}</h3><p class="mt-1 text-sm text-mute">{{ __('Users are expected to use the platform responsibly and comply with all applicable terms and policies.') }}</p></div>
            </aside>
            <div class="prose-legal">{!! nl2br(e($settings->terms ?? __('No terms added yet.'))) !!}</div>
        </div>
    </section>
</x-app-layout>
