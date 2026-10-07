{{-- Information banner. The shop only uses essential cookies (session, cart, language, currency). --}}
<div x-data="{
         show: false,
         init() { try { this.show = localStorage.getItem('cookie_notice') !== '1' } catch (e) { this.show = true } },
         dismiss() { try { localStorage.setItem('cookie_notice', '1') } catch (e) {} this.show = false },
     }"
     x-show="show" x-cloak
     x-transition:enter="transition duration-500 ease-out delay-1000" x-transition:enter-start="translate-y-6 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
     class="fixed inset-x-4 bottom-4 z-[60] rounded-3xl border border-line bg-white p-5 shadow-lift sm:inset-x-auto sm:end-6 sm:bottom-6 sm:max-w-sm">
    <div class="flex items-start gap-3">
        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-2xl bg-saffron-300/40 text-xl text-saffron-600"><i class="icon-[ph--cookie]"></i></span>
        <div>
            <p class="font-display text-base font-bold">{{ __('Cookies') }}</p>
            <p class="mt-1 text-sm leading-relaxed text-mute">
                {{ __('We only use essential cookies (login, cart, language and currency). We do not track you for advertising.') }}
                <a href="{{ url('/privacy-policy') }}" class="font-semibold text-ink underline underline-offset-4">{{ __('Privacy Policy') }}</a>
            </p>
        </div>
    </div>
    <button type="button" @click="dismiss()" class="btn btn-ink btn-sm btn-block mt-4">{{ __('Got it') }}</button>
</div>
