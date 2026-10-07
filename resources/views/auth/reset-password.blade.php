<x-guest-layout>
    <h1 class="h1">{{ __('Reset Password') }}</h1>

    <form method="POST" action="{{ route('password.store') }}" class="mt-8 space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="label">{{ __('Email') }}</label>
            <div class="relative">
                <i class="icon-[ph--envelope-simple] field-icon"></i>
                <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" class="field has-icon @error('email') is-invalid @enderror" placeholder="{{ __('Enter your email') }}" autocomplete="username" required autofocus>
            </div>
            <x-input-error :messages="$errors->get('email')" />
        </div>
        <div x-data="{ show: false }">
            <label for="password" class="label">{{ __('Password') }}</label>
            <div class="relative">
                <i class="icon-[ph--lock-simple] field-icon"></i>
                <input id="password" name="password" :type="show ? 'text' : 'password'" class="field has-icon pe-12 @error('password') is-invalid @enderror" placeholder="{{ __('Create password') }}" autocomplete="new-password" required >
                <button type="button" @click="show = !show" class="absolute end-3 top-1/2 grid h-8 w-8 -translate-y-1/2 place-items-center rounded-full text-lg text-mute transition hover:bg-tile hover:text-ink" :aria-label="show ? 'Hide' : 'Show'">
                    <i x-show="!show" class="icon-[ph--eye]"></i><i x-show="show" x-cloak class="icon-[ph--eye-slash]"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>
        <div x-data="{ show: false }">
            <label for="password_confirmation" class="label">{{ __('Confirm Password') }}</label>
            <div class="relative">
                <i class="icon-[ph--lock-simple] field-icon"></i>
                <input id="password_confirmation" name="password_confirmation" :type="show ? 'text' : 'password'" class="field has-icon pe-12 @error('password_confirmation') is-invalid @enderror" placeholder="{{ __('Confirm password') }}" autocomplete="new-password" required >
                <button type="button" @click="show = !show" class="absolute end-3 top-1/2 grid h-8 w-8 -translate-y-1/2 place-items-center rounded-full text-lg text-mute transition hover:bg-tile hover:text-ink" :aria-label="show ? 'Hide' : 'Show'">
                    <i x-show="!show" class="icon-[ph--eye]"></i><i x-show="show" x-cloak class="icon-[ph--eye-slash]"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <button type="submit" class="btn btn-brand btn-lg btn-block">{{ __('Reset Password') }}</button>
    </form>
</x-guest-layout>
