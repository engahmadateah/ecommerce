<x-app-layout>

    <div class="min-h-screen bg-[#f6f7fb] overflow-hidden relative">

        <!-- PREMIUM BACKGROUND -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden">

            <div class="absolute top-[-250px] left-[-180px] w-[650px] h-[650px] bg-pink-500/10 blur-3xl rounded-full"></div>

            <div class="absolute bottom-[-250px] right-[-180px] w-[650px] h-[650px] bg-orange-500/10 blur-3xl rounded-full"></div>

            <div class="absolute top-[30%] left-[40%] w-[400px] h-[400px] bg-purple-500/10 blur-3xl rounded-full"></div>

        </div>

        <div class="relative max-w-7xl mx-auto px-4 lg:px-8 py-10">

            <!-- HERO -->
            <div class="relative overflow-hidden rounded-[45px] bg-gradient-to-br from-[#0b1120] via-[#111827] to-[#1e293b] shadow-[0_35px_120px_rgba(0,0,0,0.35)] mb-14">

                <!-- GLOWS -->
                <div class="absolute -top-32 -right-32 w-[450px] h-[450px] bg-pink-500/20 blur-3xl rounded-full"></div>

                <div class="absolute -bottom-32 -left-32 w-[450px] h-[450px] bg-orange-500/20 blur-3xl rounded-full"></div>

                <!-- GRID -->
                <div class="relative z-10 grid lg:grid-cols-2 gap-10 p-8 md:p-12 xl:p-16 items-center">

                    <!-- LEFT -->
                    <div>

                        <!-- BADGE -->
                        <div class="inline-flex items-center gap-3 px-5 py-3 rounded-2xl border border-white/10 bg-white/10 backdrop-blur-xl mb-8 shadow-xl">

                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-r from-pink-500 to-orange-500 flex items-center justify-center text-white shadow-lg shadow-pink-500/30">

                                <i class="fa-solid fa-fire text-sm"></i>

                            </div>

                            <span class="text-sm tracking-[0.25em] uppercase text-white font-black">
                                Exclusive Luxury Discounts
                            </span>

                        </div>

                        <!-- TITLE -->
                        <h1 class="text-5xl sm:text-6xl xl:text-7xl font-black text-white leading-[1.02]">

                            Discover
                            Premium
                            Coupons
                            &
                            Rewards

                        </h1>

                        <!-- TEXT -->
                        <p class="mt-8 text-lg text-gray-300 leading-relaxed max-w-2xl">

                            Unlock elite shopping perks, luxury member rewards,
                            exclusive seasonal campaigns, and premium discount codes
                            crafted for the next generation shopping experience.

                        </p>

                        <!-- BUTTONS -->
                        <div class="flex flex-wrap gap-4 mt-10">

                            <a href="#offers"
                               class="group relative overflow-hidden px-8 py-4 rounded-2xl bg-gradient-to-r from-pink-500 via-rose-500 to-orange-500 text-white font-black shadow-2xl shadow-pink-500/30 hover:scale-105 transition duration-300">

                                <span class="relative z-10 flex items-center gap-3">

                                    <i class="fa-solid fa-ticket"></i>

                                    Explore Offers

                                </span>

                                <div class="absolute inset-0 bg-white/20 translate-x-[-100%] group-hover:translate-x-[100%] transition duration-700"></div>

                            </a>

                            <a href="/packages"
                               class="px-8 py-4 rounded-2xl border border-white/10 bg-white/10 backdrop-blur-xl text-white font-bold hover:bg-white/20 transition duration-300">

                                VIP Bundles

                            </a>

                        </div>

                    </div>

                    <!-- RIGHT -->
                    <div class="grid grid-cols-2 gap-5">

                        <!-- CARD -->
                        <div class="rounded-[30px] bg-white/10 border border-white/10 backdrop-blur-xl p-7 shadow-2xl">

                            <div class="w-16 h-16 rounded-3xl bg-gradient-to-r from-pink-500 to-rose-500 flex items-center justify-center text-white shadow-xl shadow-pink-500/30 mb-6">

                                <i class="fa-solid fa-bolt text-2xl"></i>

                            </div>

                            <h3 class="text-5xl font-black text-white">

                                {{ $coupons->count() }}

                            </h3>

                            <p class="text-gray-300 mt-3 text-sm tracking-wide">
                                Active Premium Offers
                            </p>

                        </div>

                        <!-- CARD -->
                        <div class="rounded-[30px] bg-white/10 border border-white/10 backdrop-blur-xl p-7 shadow-2xl mt-8">

                            <div class="w-16 h-16 rounded-3xl bg-gradient-to-r from-yellow-400 to-orange-500 flex items-center justify-center text-white shadow-xl shadow-yellow-500/30 mb-6">

                                <i class="fa-solid fa-crown text-2xl"></i>

                            </div>

                            <h3 class="text-5xl font-black text-white">
                                VIP
                            </h3>

                            <p class="text-gray-300 mt-3 text-sm tracking-wide">
                                Elite Loyalty Rewards
                            </p>

                        </div>

                        <!-- LARGE CARD -->
                        <div class="col-span-2 rounded-[35px] bg-gradient-to-r from-pink-500/20 to-orange-500/20 border border-white/10 backdrop-blur-xl p-8 shadow-2xl">

                            <div class="flex items-center justify-between gap-5">

                                <div>

                                    <p class="text-pink-200 uppercase tracking-[0.3em] text-xs font-black mb-3">
                                        MEMBER BENEFITS
                                    </p>

                                    <h2 class="text-3xl font-black text-white leading-tight">

                                        Premium customers
                                        save more every week.

                                    </h2>

                                </div>

                                <div class="w-20 h-20 rounded-[28px] bg-gradient-to-br from-pink-500 to-orange-500 flex items-center justify-center shadow-2xl shadow-pink-500/30 text-white">

                                    <i class="fa-solid fa-gem text-3xl"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- SECTION TITLE -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5 mb-10">

                <div>

                    <p class="text-pink-500 font-black tracking-[0.3em] uppercase text-xs mb-3">
                        Luxury Offers
                    </p>

                    <h2 class="text-4xl lg:text-5xl font-black text-gray-900">
                        Featured Coupons
                    </h2>

                </div>

                <div class="hidden lg:flex items-center gap-3 px-5 py-3 rounded-2xl bg-white border border-gray-100 shadow-xl">

                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-r from-pink-500 to-orange-500 flex items-center justify-center text-white">

                        <i class="fa-solid fa-star"></i>

                    </div>

                    <div>

                        <p class="text-xs text-gray-500 font-semibold">
                            Premium Deals Updated Daily
                        </p>

                        <p class="font-black text-gray-900">
                            Save More Today
                        </p>

                    </div>

                </div>

            </div>

            <!-- COUPONS -->
            <div id="offers"
                 class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">

                @foreach($coupons as $coupon)

                    @php

                        $discountLabel = $coupon->type === 'percent'
                            ? $coupon->value . '% OFF'
                            : '$' . $coupon->value . ' OFF';

                        $level = $coupon->required_level ?? 'bronze';

                        $levelColor = match($level) {

                            'gold' => 'from-yellow-400 to-yellow-600',

                            'silver' => 'from-gray-300 to-gray-500',

                            default => 'from-orange-400 to-rose-500',

                        };

                    @endphp

                    <a href="{{ route('coupons.show', $coupon) }}"
                       class="group relative overflow-hidden rounded-[38px] bg-white/80 backdrop-blur-2xl border border-white/40 shadow-[0_20px_80px_rgba(0,0,0,0.08)] hover:-translate-y-4 hover:shadow-[0_35px_100px_rgba(236,72,153,0.20)] transition duration-500 block">

                        <!-- HOVER GLOW -->
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-500 bg-gradient-to-br from-pink-500/5 via-transparent to-orange-500/10"></div>

                        <!-- IMAGE -->
                        <div class="relative h-72 overflow-hidden">

                            @if($coupon->image)

                                <img src="{{ \Illuminate\Support\Facades\Storage::url($coupon->image) }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-700">

                            @else

                                <div class="w-full h-full bg-gradient-to-br from-pink-100 via-orange-100 to-rose-100 flex items-center justify-center">

                                    <div class="relative">

                                        <div class="absolute inset-0 bg-pink-500/30 blur-3xl rounded-full scale-150"></div>

                                        <div class="relative w-32 h-32 rounded-[35px] bg-gradient-to-br from-pink-500 to-orange-500 flex items-center justify-center shadow-2xl shadow-pink-500/30">

                                            <span class="text-6xl">
                                                🎁
                                            </span>

                                        </div>

                                    </div>

                                </div>

                            @endif

                            <!-- TOP BADGES -->
                            <div class="absolute top-5 left-5 flex items-center gap-3">

                                <div class="px-4 py-2 rounded-2xl bg-gradient-to-r {{ $levelColor }} text-white text-xs font-black uppercase tracking-wider shadow-xl">

                                    {{ $level }}

                                </div>

                            </div>

                            <div class="absolute top-5 right-5">

                                <div class="px-5 py-3 rounded-2xl bg-white/90 backdrop-blur-xl shadow-2xl">

                                    <span class="text-sm font-black text-emerald-600">

                                        {{ $discountLabel }}

                                    </span>

                                </div>

                            </div>

                            <!-- FLOAT -->
                            <div class="absolute bottom-5 right-5 w-16 h-16 rounded-3xl bg-white/90 backdrop-blur-xl flex items-center justify-center shadow-2xl group-hover:rotate-12 group-hover:scale-110 transition duration-500">

                                <i class="fa-solid fa-arrow-right text-gray-800 text-lg"></i>

                            </div>

                        </div>

                        <!-- CONTENT -->
                        <div class="relative p-8">

                            <div class="flex items-start justify-between gap-5 mb-5">

                                <div>

                                    <h2 class="text-3xl font-black text-gray-900 leading-tight group-hover:text-pink-600 transition duration-300">

                                        {{ $coupon->title ?? $coupon->code }}

                                    </h2>

                                </div>

                            </div>

                            <p class="text-gray-600 leading-relaxed line-clamp-3">

                                {{ $coupon->description ?? 'Exclusive luxury shopping reward available for a limited time only.' }}

                            </p>

                            <!-- INFO -->
                            <div class="mt-7 space-y-4">

                                <!-- LEVEL -->
                                <div class="flex items-center justify-between rounded-3xl bg-gray-50 border border-gray-100 px-5 py-4">

                                    <div class="flex items-center gap-4">

                                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-r {{ $levelColor }} text-white flex items-center justify-center shadow-lg">

                                            <i class="fa-solid fa-crown"></i>

                                        </div>

                                        <div>

                                            <p class="text-xs text-gray-500 font-semibold">
                                                Available For
                                            </p>

                                            <p class="text-sm font-black uppercase text-gray-900">

                                                {{ $level }} & Above

                                            </p>

                                        </div>

                                    </div>

                                </div>

                                <!-- CODE -->
                                <div class="rounded-3xl border border-dashed border-gray-200 bg-gradient-to-r from-gray-50 to-white px-5 py-5">

                                    <div class="flex items-center justify-between gap-4">

                                        <div>

                                            <p class="text-xs text-gray-500 font-semibold mb-2">
                                                Coupon Code
                                            </p>

                                            <p class="text-xl font-black tracking-[0.2em] text-gray-900">

                                                {{ $coupon->code }}

                                            </p>

                                        </div>

                                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-r from-emerald-500 to-green-600 flex items-center justify-center text-white shadow-xl shadow-green-500/30">

                                            <i class="fa-solid fa-ticket"></i>

                                        </div>

                                    </div>

                                </div>

                                <!-- FOOTER -->
                                <div class="flex items-center justify-between pt-2">

                                    @if($coupon->expires_at)

                                        <div>

                                            <p class="text-xs text-gray-400 font-semibold">
                                                Expires
                                            </p>

                                            <p class="font-black text-rose-500">

                                                {{ $coupon->expires_at->format('M d, Y') }}

                                            </p>

                                        </div>

                                    @endif

                                    <div class="flex items-center gap-2 text-pink-500 font-black text-sm">

                                        View Offer

                                        <i class="fa-solid fa-arrow-right-long group-hover:translate-x-1 transition duration-300"></i>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </a>

                @endforeach

            </div>

            <!-- EMPTY -->
            @if($coupons->count() === 0)

                <div class="flex justify-center py-28">

                    <div class="max-w-2xl w-full rounded-[45px] bg-white/80 backdrop-blur-2xl border border-white/40 shadow-[0_30px_100px_rgba(0,0,0,0.10)] p-14 text-center">

                        <div class="relative w-40 h-40 mx-auto mb-10">

                            <div class="absolute inset-0 bg-pink-500/30 blur-3xl rounded-full"></div>

                            <div class="relative w-full h-full rounded-full bg-gradient-to-br from-pink-500 to-orange-500 flex items-center justify-center shadow-2xl shadow-pink-500/30">

                                <i class="fa-solid fa-ticket text-white text-6xl"></i>

                            </div>

                        </div>

                        <h2 class="text-5xl font-black text-gray-900 mb-5">
                            No Coupons Yet
                        </h2>

                        <p class="text-lg text-gray-500 leading-relaxed max-w-xl mx-auto">

                            New premium rewards, luxury member discounts,
                            and exclusive shopping offers will appear here soon.

                        </p>

                    </div>

                </div>

            @endif

        </div>

    </div>

</x-app-layout>