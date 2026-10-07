<x-app-layout>
@section('seo_robots', 'noindex,nofollow')

<div class="container-x py-10 sm:py-16">
    <div class="mx-auto max-w-2xl">
        <h1 class="h1">{{ __('Shipping Information') }}</h1>

        <form method="POST" action="/checkout" class="panel mt-8 space-y-5">
            @csrf

            @guest
                <div>
                    <label for="email" class="label">{{ __('Email (for your order confirmation)') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="field @error('email') is-invalid @enderror" required>
                    @error('email')<p class="field-error">{{ $message }}</p>@enderror
                    <p class="mt-2 text-sm text-mute">{{ __('Checking out as a guest.') }} <a href="{{ route('login') }}" class="font-bold text-ink underline underline-offset-4">{{ __('Log in') }}</a> {{ __('to earn loyalty points.') }}</p>
                </div>
            @endguest

            <div class="grid gap-5 sm:grid-cols-2">
                <div><label class="label">{{ __('Full Name') }}</label><input name="full_name" class="field" required></div>
                <div><label class="label">{{ __('Phone') }}</label><input name="phone" class="field" required></div>
                <div class="sm:col-span-2"><label class="label">{{ __('Address') }}</label><input name="address_line" class="field" required></div>
                <div><label class="label">{{ __('City') }}</label><input name="city" class="field" required></div>
                <div><label class="label">{{ __('Country') }}</label><input name="country" class="field" required></div>
            </div>

            <button type="submit" class="btn btn-brand btn-lg btn-block">{{ __('Continue to Payment') }}<i class="icon-[ph--arrow-right] text-xl rtl:-scale-x-100"></i></button>
        </form>
    </div>
</div>
</x-app-layout>
