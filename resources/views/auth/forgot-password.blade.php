<x-guest-layout>
    <span class="grid h-14 w-14 place-items-center rounded-2xl bg-cobalt-50 text-3xl text-cobalt-600"><i class="icon-[ph--key]"></i></span>
    <h1 class="h1 mt-6">{{ __('Forgot Your Password?') }}</h1>
    <p class="lead mt-3 text-base">{{ __('No worries. Enter your email address and we’ll send you a secure password reset link instantly.') }}</p>

    <x-auth-session-status class="mt-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-5">
        @csrf

        <div>
            <label for="email" class="label">{{ __('Email Address') }}</label>
            <div class="relative">
                <i class="icon-[ph--envelope-simple] field-icon"></i>
                <input id="email" name="email" type="email" value="{{ old('email') }}" class="field has-icon @error('email') is-invalid @enderror" placeholder="{{ __('Enter your email') }}" autocomplete="username" required autofocus>
            </div>
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <button type="submit" class="btn btn-brand btn-lg btn-block">{{ __('Send Reset Link') }}<i class="icon-[ph--paper-plane-tilt] text-xl rtl:-scale-x-100"></i></button>
    </form>

    <a href="{{ route('login') }}" class="mt-8 inline-flex items-center justify-center gap-2 text-[15px] font-semibold text-mute transition hover:text-ink">
        <i class="icon-[ph--arrow-left] text-lg rtl:-scale-x-100"></i>{{ __('Back to Login') }}
    </a>
</x-guest-layout>
