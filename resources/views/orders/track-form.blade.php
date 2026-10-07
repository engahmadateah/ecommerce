<x-app-layout>
@section('seo_title', __('Track an order'))
@section('seo_robots', 'noindex,nofollow')

<div class="container-x py-12 sm:py-20">
    <div class="mx-auto max-w-lg">
        <span class="grid h-16 w-16 place-items-center rounded-3xl bg-cobalt-50 text-4xl text-cobalt-600"><i class="icon-[ph--map-pin-line]"></i></span>
        <h1 class="h1 mt-6">{{ __('Track an order') }}</h1>
        <p class="lead mt-3 text-base">{{ __('Enter your order number and the e-mail you used at checkout.') }}</p>

        <form method="POST" action="{{ route('orders.track.lookup') }}" class="panel mt-8 space-y-5">
            @csrf
            <div>
                <label for="order" class="label">{{ __('Order number') }}</label>
                <div class="relative">
                    <i class="icon-[ph--hash] field-icon"></i>
                    <input id="order" type="number" name="order" value="{{ old('order') }}" required min="1" class="field has-icon @error('order') is-invalid @enderror">
                </div>
                @error('order')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="email" class="label">{{ __('Email') }}</label>
                <div class="relative">
                    <i class="icon-[ph--envelope-simple] field-icon"></i>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required class="field has-icon @error('email') is-invalid @enderror">
                </div>
                @error('email')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="btn btn-brand btn-lg btn-block"><i class="icon-[ph--magnifying-glass] text-xl"></i>{{ __('Track order') }}</button>
        </form>
    </div>
</div>
</x-app-layout>
