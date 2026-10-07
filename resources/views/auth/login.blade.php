<x-guest-layout>
    <h1 class="h1">{{ __('Welcome Back.') }}</h1>
    <p class="lead mt-3 text-base">{{ __('Login to access your orders, premium rewards, wishlist, coupons and exclusive shopping experience.') }}</p>

    <x-auth-session-status class="mt-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
        @csrf

        <div>
            <label for="email" class="label">{{ __('Email Address') }}</label>
            <div class="relative">
                <i class="icon-[ph--envelope-simple] field-icon"></i>
                <input id="email" name="email" type="email" value="{{ old('email') }}" class="field has-icon @error('email') is-invalid @enderror" placeholder="{{ __('Enter your email') }}" autocomplete="username" required autofocus>
            </div>
            <x-input-error :messages="$errors->get('email')" />
        </div>
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

        <div class="flex items-center justify-between gap-4">
            <label class="inline-flex cursor-pointer items-center gap-2.5 text-sm font-medium">
                <input type="checkbox" name="remember" class="h-4.5 w-4.5 rounded-md border-line"> {{ __('Remember me') }}
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-cobalt-600 hover:underline">{{ __('Forgot password?') }}</a>
            @endif
        </div>

        <button type="submit" class="btn btn-brand btn-lg btn-block">{{ __('Log in') }}<i class="icon-[ph--arrow-right] text-xl rtl:-scale-x-100"></i></button>
    </form>

    <p class="mt-8 text-center text-[15px] text-mute">
        {{ __("Don't have an account?") }}
        <a href="{{ route('register') }}" class="font-bold text-ink underline underline-offset-4 hover:text-cobalt-600">{{ __('Create account') }}</a>
    </p>
</x-guest-layout>
