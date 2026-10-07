<x-guest-layout>
    <span class="grid h-14 w-14 place-items-center rounded-2xl bg-cobalt-50 text-3xl text-cobalt-600"><i class="icon-[ph--shield-check]"></i></span>
    <h1 class="h1 mt-6">{{ __('Confirm Password') }}</h1>
    <p class="lead mt-3 text-base">{{ __('This is a secure area of the application. Please confirm your password before continuing.') }}</p>

    <form method="POST" action="{{ route('password.confirm') }}" class="mt-8 space-y-5">
        @csrf

        <div x-data="{ show: false }">
            <label for="password" class="label">{{ __('Password') }}</label>
            <div class="relative">
                <i class="icon-[ph--lock-simple] field-icon"></i>
                <input id="password" name="password" :type="show ? 'text' : 'password'" class="field has-icon pe-12 @error('password') is-invalid @enderror" placeholder="{{ __('Enter your password') }}" autocomplete="current-password" required >
                <button type="button" @click="show = !show" class="absolute end-3 top-1/2 grid h-8 w-8 -translate-y-1/2 place-items-center rounded-full text-lg text-mute transition hover:bg-tile hover:text-ink" :aria-label="show ? 'Hide' : 'Show'">
                    <i x-show="!show" class="icon-[ph--eye]"></i><i x-show="show" x-cloak class="icon-[ph--eye-slash]"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <button type="submit" class="btn btn-brand btn-lg btn-block">{{ __('Confirm Password') }}</button>
    </form>
</x-guest-layout>
