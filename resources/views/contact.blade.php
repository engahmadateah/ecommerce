<x-app-layout>
@php $settings = \App\Models\Setting::current(); @endphp
    <x-page-head :badge="__('Contact Support')" icon="icon-[ph--headset]"
                 :title="__('Get In').' '.__('Touch')"
                 :text="__('We\'d love to hear from you. Send us your questions, feedback, or business inquiries and our team will respond quickly.')" />

    <section class="container-x section">
        <div class="grid gap-8 lg:grid-cols-[1fr_1.15fr] lg:gap-14">
            <div>
                <h2 class="h2">{{ __('Premium Support') }}</h2>
                <p class="lead mt-3">{{ __('Our support team is available to help you with orders, products, and account assistance anytime.') }}</p>

                <ul class="mt-10 space-y-5">
                    <li class="flex gap-4"><span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-cobalt-50 text-2xl text-cobalt-600"><i class="icon-[ph--lightning]"></i></span><div><h3 class="font-bold">{{ __('Fast Response') }}</h3><p class="text-mute">{{ __('Quick replies from our dedicated team.') }}</p></div></li>
                    <li class="flex gap-4"><span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-cobalt-50 text-2xl text-cobalt-600"><i class="icon-[ph--lock-simple]"></i></span><div><h3 class="font-bold">{{ __('Secure Communication') }}</h3><p class="text-mute">{{ __('Your information is fully protected.') }}</p></div></li>
                    <li class="flex gap-4"><span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-cobalt-50 text-2xl text-cobalt-600"><i class="icon-[ph--sparkle]"></i></span><div><h3 class="font-bold">{{ __('Premium Experience') }}</h3><p class="text-mute">{{ __('Elegant customer care experience.') }}</p></div></li>
                </ul>

                @if ($settings?->email || $settings?->phone || $settings?->address)
                    <div class="mt-10 space-y-3 rounded-3xl bg-ink p-7 text-white">
                        @if ($settings->email)<p class="flex items-center gap-3"><i class="icon-[ph--envelope-simple] text-xl text-cobalt-300"></i><a href="mailto:{{ $settings->email }}" class="break-all hover:underline">{{ $settings->email }}</a></p>@endif
                        @if ($settings->phone)<p class="flex items-center gap-3"><i class="icon-[ph--phone] text-xl text-cobalt-300"></i><span dir="ltr">{{ $settings->phone }}</span></p>@endif
                        @if ($settings->address)<p class="flex items-start gap-3"><i class="icon-[ph--map-pin] mt-0.5 text-xl text-cobalt-300"></i>{{ $settings->address }}</p>@endif
                    </div>
                @endif
            </div>

            <form method="POST" action="{{ route('contact.send') }}" class="panel space-y-5">
                @csrf
                <div>
                    <h2 class="h3">{{ __('Send Message') }}</h2>
                    <p class="mt-1 text-mute">{{ __('Fill out the form below and we’ll get back to you soon.') }}</p>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="name" class="label">{{ __('Full Name') }}</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="{{ __('Enter your name') }}" class="field @error('name') is-invalid @enderror" autocomplete="name">
                        @error('name')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="label">{{ __('Email Address') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="your@email.com" class="field @error('email') is-invalid @enderror" autocomplete="email">
                        @error('email')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label for="message" class="label">{{ __('Your Message') }}</label>
                    <textarea id="message" name="message" rows="7" placeholder="{{ __('Write your message...') }}" class="field @error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                    @error('message')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn btn-brand btn-lg btn-block"><i class="icon-[ph--paper-plane-tilt] text-xl rtl:-scale-x-100"></i>{{ __('Send Message') }}</button>
            </form>
        </div>
    </section>
</x-app-layout>
