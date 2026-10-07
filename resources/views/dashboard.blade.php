<x-app-layout>
    <div class="container-x py-16">
        <div class="panel mx-auto max-w-xl text-center">
            <span class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-mint-50 text-3xl text-mint-500"><i class="icon-[ph--check-circle-fill]"></i></span>
            <h1 class="h2 mt-5">{{ __('Dashboard') }}</h1>
            <p class="lead mt-2 text-base">{{ __("You're logged in!") }}</p>
            <a href="{{ route('home') }}" class="btn btn-brand mt-6">{{ __('Back to Shop') }}</a>
        </div>
    </div>
</x-app-layout>
