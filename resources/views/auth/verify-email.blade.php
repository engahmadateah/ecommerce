<x-guest-layout>
    <span class="grid h-14 w-14 place-items-center rounded-2xl bg-cobalt-50 text-3xl text-cobalt-600"><i class="icon-[ph--envelope-simple-open]"></i></span>
    <h1 class="h1 mt-6">{{ __('Email Verification') }}</h1>
    <p class="lead mt-3 text-base">{{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}</p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-ok mt-6"><i class="icon-[ph--check-circle-fill] mt-0.5 shrink-0 text-lg"></i>{{ __('A new verification link has been sent to the email address you provided during registration.') }}</div>
    @endif

    <div class="mt-8 flex flex-col gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-brand btn-lg btn-block">{{ __('Resend Verification Email') }}</button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-ghost btn-block">{{ __('Log Out') }}</button>
        </form>
    </div>
</x-guest-layout>
