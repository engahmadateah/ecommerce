<x-app-layout>

    <div class="min-h-screen bg-gradient-to-br from-slate-100 via-orange-50 to-amber-100 py-14 overflow-hidden">

        <!-- BACKGROUND -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden">

            <div class="absolute top-[-250px] left-[-120px] w-[550px] h-[550px] bg-orange-400/20 blur-3xl rounded-full"></div>

            <div class="absolute bottom-[-250px] right-[-120px] w-[550px] h-[550px] bg-yellow-400/20 blur-3xl rounded-full"></div>

            <div class="absolute top-[30%] left-[45%] w-[300px] h-[300px] bg-rose-400/10 blur-3xl rounded-full"></div>

        </div>

        <div class="relative max-w-7xl mx-auto px-4 lg:px-8">

            @php

                $discountLabel = $coupon->type === 'percent'
                    ? $coupon->value . '% OFF'
                    : '$' . $coupon->value . ' OFF';

                $level = $coupon->required_level ?? 'bronze';

                $userLevel = auth()->user()->level ?? 'bronze';

                $levels = [
                    'bronze' => 1,
                    'silver' => 2,
                    'gold'   => 3,
                ];

                $canUse = $levels[$userLevel] >= $levels[$level];

                $levelGradient = match($level) {

                    'gold' => 'from-yellow-400 via-amber-400 to-yellow-600',

                    'silver' => 'from-slate-300 via-gray-300 to-slate-500',

                    default => 'from-orange-400 via-rose-400 to-pink-500',

                };

            @endphp

            <div class="grid xl:grid-cols-[1.1fr_0.9fr] gap-10 items-start">

                <!-- LEFT -->
                <div class="relative overflow-hidden rounded-[40px] bg-white/75 backdrop-blur-2xl border border-white/40 shadow-[0_30px_90px_rgba(0,0,0,0.10)]">

                    <!-- TOP IMAGE -->
                    <div class="relative h-[420px] overflow-hidden">

                        @if($coupon->image)

                            <img src="{{ asset('storage/' . $coupon->image) }}"
                                 class="w-full h-full object-cover scale-100 hover:scale-105 transition duration-700">

                        @else

                            <div class="w-full h-full bg-gradient-to-br from-orange-100 via-rose-100 to-yellow-100 flex items-center justify-center">

                                <div class="relative">

                                    <div class="absolute inset-0 bg-orange-400/30 blur-3xl rounded-full scale-150"></div>

                                    <div class="relative w-44 h-44 rounded-[40px] bg-gradient-to-br from-orange-500 via-rose-500 to-pink-600 flex items-center justify-center shadow-[0_25px_60px_rgba(249,115,22,0.45)]">

                                        <span class="text-8xl">
                                            🎁
                                        </span>

                                    </div>

                                </div>

                            </div>

                        @endif

                        <!-- OVERLAY -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>

                        <!-- BADGES -->
                        <div class="absolute top-6 left-6 flex items-center gap-3">

                            <div class="px-5 py-2 rounded-2xl text-white text-xs font-black uppercase tracking-[0.25em] bg-gradient-to-r {{ $levelGradient }} shadow-2xl">

                                {{ $level }}

                            </div>

                            <div class="px-5 py-2 rounded-2xl bg-white/90 backdrop-blur-xl shadow-xl">

                                <span class="text-sm font-black text-emerald-600">
                                    {{ $discountLabel }}
                                </span>

                            </div>

                        </div>

                        <!-- FLOAT CARD -->
                        <div class="absolute bottom-6 left-6 right-6">

                            <div class="rounded-[30px] bg-white/10 border border-white/10 backdrop-blur-2xl p-6">

                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                                    <div>

                                        <p class="text-sm uppercase tracking-[0.3em] text-orange-200 font-bold mb-2">
                                            Exclusive Premium Coupon
                                        </p>

                                        <h1 class="text-4xl lg:text-5xl font-black text-white leading-tight">

                                            {{ $coupon->title ?? $coupon->code }}

                                        </h1>

                                    </div>

                                    <div class="flex items-center gap-3">

                                        <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-xl flex items-center justify-center text-white shadow-2xl">

                                            <i class="fa-solid fa-ticket text-xl"></i>

                                        </div>

                                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-r from-orange-500 to-rose-500 flex items-center justify-center text-white shadow-2xl shadow-orange-500/40">

                                            <i class="fa-solid fa-crown text-xl"></i>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- CONTENT -->
                    <div class="p-8 lg:p-10">

                        <!-- DESCRIPTION -->
                        <div class="mb-10">

                            <div class="flex items-center gap-3 mb-5">

                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-r from-orange-500 to-rose-500 text-white flex items-center justify-center shadow-xl shadow-orange-500/30">

                                    <i class="fa-solid fa-sparkles"></i>

                                </div>

                                <div>

                                    <p class="text-xs uppercase tracking-[0.3em] text-gray-400 font-bold">
                                        About This Offer
                                    </p>

                                    <h2 class="text-2xl font-black text-gray-900">
                                        Premium Shopping Reward
                                    </h2>

                                </div>

                            </div>

                            <p class="text-gray-600 leading-relaxed text-lg">

                                {{ $coupon->description ?? 'Enjoy exclusive luxury shopping rewards and premium member discounts available for a limited time only.' }}

                            </p>

                        </div>

                        <!-- HOW TO USE -->
                        @if($coupon->how_to_use)

                            <div class="rounded-[30px] bg-gradient-to-br from-white to-orange-50 border border-orange-100 p-7 mb-8 shadow-xl shadow-orange-100/40">

                                <div class="flex items-center gap-4 mb-5">

                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-r from-orange-500 to-rose-500 text-white flex items-center justify-center shadow-2xl shadow-orange-500/30">

                                        <i class="fa-solid fa-wand-magic-sparkles text-lg"></i>

                                    </div>

                                    <div>

                                        <p class="text-xs uppercase tracking-[0.25em] text-gray-400 font-bold">
                                            Quick Guide
                                        </p>

                                        <h3 class="text-2xl font-black text-gray-900">
                                            How To Use
                                        </h3>

                                    </div>

                                </div>

                                <p class="text-gray-600 leading-relaxed whitespace-pre-line">

                                    {{ $coupon->how_to_use }}

                                </p>

                            </div>

                        @endif

                        <!-- INFO -->
                        <div class="grid sm:grid-cols-3 gap-5">

                            @if($coupon->expires_at)

                                <div class="rounded-[28px] bg-white border border-gray-100 p-6 shadow-lg shadow-black/[0.03]">

                                    <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-500 flex items-center justify-center mb-5">

                                        <i class="fa-solid fa-clock text-xl"></i>

                                    </div>

                                    <p class="text-xs uppercase tracking-[0.25em] text-gray-400 font-bold mb-2">
                                        Expires
                                    </p>

                                    <h4 class="text-lg font-black text-gray-900">

                                        {{ $coupon->expires_at->format('M d, Y') }}

                                    </h4>

                                </div>

                            @endif

                            @if($coupon->usage_limit)

                                <div class="rounded-[28px] bg-white border border-gray-100 p-6 shadow-lg shadow-black/[0.03]">

                                    <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-500 flex items-center justify-center mb-5">

                                        <i class="fa-solid fa-layer-group text-xl"></i>

                                    </div>

                                    <p class="text-xs uppercase tracking-[0.25em] text-gray-400 font-bold mb-2">
                                        Usage Limit
                                    </p>

                                    <h4 class="text-lg font-black text-gray-900">

                                        {{ $coupon->usage_limit }}

                                    </h4>

                                </div>

                            @endif

                            <div class="rounded-[28px] bg-white border border-gray-100 p-6 shadow-lg shadow-black/[0.03]">

                                <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-500 flex items-center justify-center mb-5">

                                    <i class="fa-solid fa-chart-line text-xl"></i>

                                </div>

                                <p class="text-xs uppercase tracking-[0.25em] text-gray-400 font-bold mb-2">
                                    Used Times
                                </p>

                                <h4 class="text-lg font-black text-gray-900">

                                    {{ $coupon->used }}

                                </h4>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- RIGHT -->
                <div class="sticky top-28 space-y-8">

                    <!-- CODE BOX -->
                    <div class="relative overflow-hidden rounded-[35px] bg-gradient-to-br from-[#0f172a] via-[#111827] to-[#1e293b] p-8 shadow-[0_25px_80px_rgba(0,0,0,0.30)]">

                        <div class="absolute top-0 right-0 w-[250px] h-[250px] bg-orange-500/10 blur-3xl rounded-full"></div>

                        <div class="absolute bottom-0 left-0 w-[250px] h-[250px] bg-rose-500/10 blur-3xl rounded-full"></div>

                        <div class="relative z-10">

                            <div class="flex items-center justify-between mb-8">

                                <div>

                                    <p class="text-xs uppercase tracking-[0.3em] text-orange-200 font-bold mb-2">
                                        Coupon Access
                                    </p>

                                    <h2 class="text-3xl font-black text-white">
                                        Redeem Offer
                                    </h2>

                                </div>

                                <div class="w-16 h-16 rounded-3xl bg-white/10 border border-white/10 backdrop-blur-xl flex items-center justify-center text-white shadow-2xl">

                                    <i class="fa-solid fa-gift text-2xl"></i>

                                </div>

                            </div>

                            <!-- NEW PREMIUM CODE BOX -->
                            <div class="rounded-[30px] bg-white/10 border border-white/10 backdrop-blur-2xl p-6 mb-6">

                                <div class="flex items-center justify-between mb-4">

                                    <p class="text-sm uppercase tracking-[0.25em] text-orange-200 font-bold">
                                        Coupon Code
                                    </p>

                                    <div class="flex items-center gap-2 text-emerald-300 text-sm font-semibold">

                                        <i class="fa-solid fa-bolt"></i>

                                        Instant Copy

                                    </div>

                                </div>

                                <div class="flex items-center gap-4">

                                    <!-- CODE -->
                                    <div class="flex-1 h-20 rounded-[24px]
                                                bg-black/30
                                                border border-white/10
                                                backdrop-blur-xl
                                                flex items-center px-6 overflow-hidden">

                                        <span class="text-2xl lg:text-3xl font-black tracking-[0.35em] text-white truncate">

                                            {{ $coupon->code }}

                                        </span>

                                    </div>

                                    <!-- COPY BUTTON -->
                                    <button
                                        onclick="
                                            navigator.clipboard.writeText('{{ $coupon->code }}');
                                            this.innerHTML = '<i class=\'fa-solid fa-check\'></i>';
                                            setTimeout(() => {
                                                this.innerHTML = '<i class=\'fa-solid fa-copy\'></i>';
                                            }, 2000);
                                        "
                                        class="group shrink-0 w-20 h-20 rounded-[24px]
                                               bg-gradient-to-r from-orange-500 via-rose-500 to-pink-600
                                               text-white text-2xl
                                               flex items-center justify-center
                                               shadow-[0_20px_50px_rgba(249,115,22,0.45)]
                                               hover:scale-105
                                               transition duration-300">

                                        <i class="fa-solid fa-copy group-hover:scale-110 transition duration-300"></i>

                                    </button>

                                </div>

                                <p class="text-gray-400 text-sm mt-4 leading-relaxed">

                                    Copy the code and paste it into your cart to automatically apply the discount.

                                </p>

                            </div>

                            <!-- STATUS -->
                            @auth

                                @if($canUse)

                                    <div class="rounded-2xl bg-emerald-500/15 border border-emerald-400/20 p-5 flex items-center gap-4">

                                        <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shadow-xl shadow-emerald-500/30">

                                            <i class="fa-solid fa-check"></i>

                                        </div>

                                        <div>

                                            <h3 class="font-black text-white">
                                                Coupon Available
                                            </h3>

                                            <p class="text-sm text-emerald-200">
                                                Your account can redeem this offer.
                                            </p>

                                        </div>

                                    </div>

                                @else

                                    <div class="rounded-2xl bg-red-500/15 border border-red-400/20 p-5 flex items-center gap-4">

                                        <div class="w-12 h-12 rounded-2xl bg-red-500 text-white flex items-center justify-center shadow-xl shadow-red-500/30">

                                            <i class="fa-solid fa-lock"></i>

                                        </div>

                                        <div>

                                            <h3 class="font-black text-white">
                                                Level Required
                                            </h3>

                                            <p class="text-sm text-red-200">
                                                Requires {{ strtoupper($level) }} membership.
                                            </p>

                                        </div>

                                    </div>

                                @endif

                            @else

                                <a href="/login"
                                   class="group flex items-center justify-center gap-3 w-full h-16 rounded-2xl bg-gradient-to-r from-orange-500 via-rose-500 to-pink-600 text-white text-lg font-black shadow-2xl shadow-orange-500/30 hover:scale-[1.02] transition duration-300">

                                    <i class="fa-solid fa-right-to-bracket group-hover:translate-x-1 transition duration-300"></i>

                                    Login To Redeem

                                </a>

                            @endauth

                        </div>

                    </div>

                    <!-- MEMBERSHIP -->
                    <div class="rounded-[35px] bg-white/80 backdrop-blur-2xl border border-white/40 p-8 shadow-[0_20px_70px_rgba(0,0,0,0.08)]">

                        <div class="flex items-center gap-4 mb-6">

                            <div class="w-16 h-16 rounded-3xl bg-gradient-to-r {{ $levelGradient }} text-white flex items-center justify-center shadow-2xl">

                                <i class="fa-solid fa-crown text-2xl"></i>

                            </div>

                            <div>

                                <p class="text-xs uppercase tracking-[0.25em] text-gray-400 font-bold">
                                    Membership Access
                                </p>

                                <h2 class="text-2xl font-black text-gray-900">
                                    {{ strtoupper($level) }} Tier
                                </h2>

                            </div>

                        </div>

                        <p class="text-gray-600 leading-relaxed mb-6">

                            This premium reward is designed for loyal members with elevated shopping status and exclusive account benefits.

                        </p>

                        <div class="space-y-4">

                            <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50 border border-gray-100">

                                <span class="font-semibold text-gray-700">
                                    Discount
                                </span>

                                <span class="font-black text-emerald-600">
                                    {{ $discountLabel }}
                                </span>

                            </div>

                            <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50 border border-gray-100">

                                <span class="font-semibold text-gray-700">
                                    Access Level
                                </span>

                                <span class="font-black uppercase text-gray-900">
                                    {{ $level }}
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>