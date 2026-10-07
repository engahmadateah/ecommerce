<x-app-layout>
@section('seo_robots', 'noindex,nofollow')
@php
    $me = auth()->user();
    $level = $me->level ?? 'bronze';
    $points = (int) ($me->points ?? 0);
    [$next, $target, $from] = match (true) {
        $points >= 5000 => [null, 5000, 5000],
        $points >= 2000 => ['gold', 5000, 2000],
        default => ['silver', 2000, 0],
    };
    $pct = $next ? min(100, round(($points - $from) / ($target - $from) * 100)) : 100;
    $initials = collect(explode(' ', trim($me->name)))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('');
@endphp

<div class="container-x py-10 sm:py-16">
    <h1 class="h1">{{ __('My').' '.__('Profile') }}</h1>
    <p class="lead mt-2 max-w-xl">{{ __('Customize your personal information, update your password, and manage your account securely with a premium experience.') }}</p>

    <div class="mt-10 grid items-start gap-8 lg:grid-cols-[22rem_1fr]">
        <aside class="space-y-4 lg:sticky lg:top-28">
            <div class="relative isolate overflow-hidden rounded-[2rem] bg-ink p-7 text-white">
                <div class="aurora opacity-60" aria-hidden="true"><i></i><i></i><i></i></div>
                <div class="relative">
                    <span class="grid h-16 w-16 place-items-center rounded-full bg-linear-to-br from-cobalt-500 to-[#7c5cff] font-display text-2xl font-bold shadow-glow">{{ mb_strtoupper($initials) }}</span>
                    <h2 class="mt-5 font-display text-2xl font-bold">{{ $me->name }}</h2>
                    <p class="mt-0.5 break-all text-sm text-white/60">{{ $me->email }}</p>

                    <div class="mt-6 flex items-center justify-between text-sm">
                        <span class="badge {{ $level === 'gold' ? 'badge-deal' : 'bg-white/15 text-white' }}"><i class="icon-[ph--crown-simple-fill]"></i>{{ __(ucfirst($level)) }}</span>
                        <span class="font-semibold">{{ number_format($points) }} {{ __('pts') }}</span>
                    </div>
                    <div class="mt-3 h-2 overflow-hidden rounded-full bg-white/15"><div class="h-full rounded-full bg-saffron-500 transition-all duration-1000" style="width: {{ $pct }}%"></div></div>
                    @if ($next)
                        <p class="mt-2 text-xs text-white/55">{{ __(':points pts to reach :level', ['points' => number_format($target - $points), 'level' => __(ucfirst($next))]) }}</p>
                    @endif
                </div>
            </div>

            <ul class="panel-flat !p-5 space-y-4 text-sm">
                <li class="flex items-center justify-between"><span class="flex items-center gap-3 font-semibold"><i class="icon-[ph--shield-check] text-xl text-mute"></i>{{ __('Security') }}</span><span class="badge badge-ok">{{ __('Active') }}</span></li>
                <li class="flex items-center justify-between"><span class="flex items-center gap-3 font-semibold"><i class="icon-[ph--envelope-simple] text-xl text-mute"></i>{{ __('Email') }}</span><span class="badge badge-ok">{{ __('Verified') }}</span></li>
                <li class="flex items-center justify-between"><span class="flex items-center gap-3 font-semibold"><i class="icon-[ph--fingerprint] text-xl text-mute"></i>{{ __('Privacy') }}</span><span class="badge badge-ok">{{ __('Safe') }}</span></li>
            </ul>
        </aside>

        <div class="space-y-6">
            <div class="panel">@include('profile.partials.update-profile-information-form')</div>
            <div class="panel">@include('profile.partials.update-password-form')</div>
            <div class="panel border-coral-500/25">@include('profile.partials.delete-user-form')</div>
        </div>
    </div>
</div>
</x-app-layout>
