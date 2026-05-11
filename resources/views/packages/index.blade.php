<x-app-layout>

    <div class="min-h-screen bg-gradient-to-br from-[#f8fafc] via-orange-50 to-rose-100 py-16 overflow-hidden">

        <!-- BACKGROUND -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden">

            <div class="absolute top-[-250px] left-[-120px] w-[600px] h-[600px] bg-orange-400/15 blur-3xl rounded-full"></div>

            <div class="absolute bottom-[-250px] right-[-120px] w-[600px] h-[600px] bg-pink-400/15 blur-3xl rounded-full"></div>

            <div class="absolute top-[35%] left-[45%] w-[320px] h-[320px] bg-amber-300/10 blur-3xl rounded-full"></div>

        </div>

        <div class="relative max-w-7xl mx-auto px-4 lg:px-8">

            <!-- HERO -->
            <div class="relative overflow-hidden rounded-[45px] bg-gradient-to-br from-[#0f172a] via-[#111827] to-[#020617] p-10 lg:p-16 shadow-[0_35px_120px_rgba(0,0,0,0.35)] mb-14 border border-white/5">

                <!-- GLOW -->
                <div class="absolute top-0 right-0 w-[420px] h-[420px] bg-orange-500/10 blur-3xl rounded-full"></div>

                <div class="absolute bottom-0 left-0 w-[420px] h-[420px] bg-rose-500/10 blur-3xl rounded-full"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-12">

                    <!-- LEFT -->
                    <div class="max-w-2xl">

                        <div class="inline-flex items-center gap-3 px-5 py-3 rounded-2xl bg-white/10 border border-white/10 backdrop-blur-xl mb-7">

                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-r from-orange-500 to-rose-500 flex items-center justify-center text-white shadow-2xl">

                                <i class="fa-solid fa-boxes-stacked"></i>

                            </div>

                            <span class="text-sm font-bold tracking-[0.18em] uppercase text-white">
                                Premium Bundle Collection
                            </span>

                        </div>

                        <h1 class="text-5xl lg:text-7xl font-black leading-[0.95] text-white">

                            Exclusive
                            <span class="bg-gradient-to-r from-orange-400 via-rose-400 to-pink-400 bg-clip-text text-transparent">
                                Bundles
                            </span>

                        </h1>

                        <p class="text-lg lg:text-xl text-gray-300 leading-relaxed mt-7 max-w-2xl">

                            Curated premium product combinations crafted for smarter shopping,
                            better value, and a luxury experience in every bundle.

                        </p>

                    </div>

                    <!-- STATS -->
                    <div class="grid grid-cols-2 gap-5 w-full lg:w-auto">

                        <div class="rounded-[30px] bg-white/10 border border-white/10 backdrop-blur-2xl p-7 min-w-[190px] shadow-2xl">

                            <div class="w-16 h-16 rounded-3xl bg-orange-500/20 flex items-center justify-center mb-6">

                                <i class="fa-solid fa-fire text-orange-400 text-3xl"></i>

                            </div>

                            <h3 class="text-5xl font-black text-white">
                                {{ $packages->count() }}
                            </h3>

                            <p class="text-sm text-gray-300 mt-3 tracking-wide">
                                Active Bundles
                            </p>

                        </div>

                        <div class="rounded-[30px] bg-white/10 border border-white/10 backdrop-blur-2xl p-7 min-w-[190px] shadow-2xl">

                            <div class="w-16 h-16 rounded-3xl bg-emerald-500/20 flex items-center justify-center mb-6">

                                <i class="fa-solid fa-tags text-emerald-400 text-3xl"></i>

                            </div>

                            <h3 class="text-5xl font-black text-white">
                                Save
                            </h3>

                            <p class="text-sm text-gray-300 mt-3 tracking-wide">
                                More With Bundles
                            </p>

                        </div>

                    </div>

                </div>

            </div>

            <!-- PACKAGES -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-10">

                @foreach($packages as $package)

                    @php
                        $original = $package->products->sum('price');
                        $save = $original - $package->price;
                    @endphp

                    <div class="group relative rounded-[40px] bg-white/75 backdrop-blur-2xl border border-white/60 shadow-[0_20px_80px_rgba(0,0,0,0.08)] hover:shadow-[0_35px_110px_rgba(249,115,22,0.18)] transition duration-500 hover:-translate-y-2 overflow-hidden">

                        <!-- HOVER -->
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-500 bg-gradient-to-br from-orange-500/5 via-transparent to-rose-500/10"></div>

                        <!-- TOP BORDER -->
                        <div class="absolute top-0 inset-x-0 h-[2px] bg-gradient-to-r from-transparent via-orange-300 to-transparent"></div>

                        <!-- HEADER -->
                        <div class="relative p-8 pb-6 border-b border-gray-100/80">

                            <div class="flex items-start justify-between gap-5">

                                <div class="flex-1 min-w-0">

                                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-2xl bg-orange-100 text-orange-700 text-xs font-black uppercase tracking-[0.18em] mb-5">

                                        <i class="fa-solid fa-gift"></i>

                                        Bundle Offer

                                    </span>

                                    <h2 class="text-[30px] leading-tight font-black text-gray-900 break-words">

                                        {{ $package->name }}

                                    </h2>

                                </div>

                                <div class="shrink-0 w-20 h-20 rounded-[28px] bg-gradient-to-br from-orange-500 to-rose-500 text-white flex items-center justify-center shadow-[0_20px_60px_rgba(249,115,22,0.35)]">

                                    <i class="fa-solid fa-box-open text-3xl"></i>

                                </div>

                            </div>

                            <p class="text-gray-500 leading-relaxed mt-6 text-[15px] line-clamp-3 min-h-[72px]">

                                {{ $package->description }}

                            </p>

                        </div>

                        <!-- CONTENT -->
                        <div class="relative p-8">

                            <!-- PRODUCTS -->
                            <div class="mb-8">

                                <div class="flex items-center justify-between mb-5">

                                    <h3 class="text-sm font-black uppercase tracking-[0.18em] text-gray-400">
                                        Included Products
                                    </h3>

                                    <span class="text-sm font-bold text-orange-600">
                                        {{ $package->products->count() }} Items
                                    </span>

                                </div>

                                <div class="flex flex-wrap gap-4">

                                    @foreach($package->products as $product)

                                        <div class="relative group/product">

                                            <div class="absolute inset-0 bg-orange-400/20 blur-xl rounded-3xl"></div>

                                            <img src="{{ asset('storage/'.$product->image) }}"
                                                 class="relative w-20 h-20 rounded-3xl object-cover border-[5px] border-white shadow-2xl transition duration-500 group-hover/product:scale-105">

                                        </div>

                                    @endforeach

                                </div>

                            </div>

                            <!-- PRICE BOX -->
                            <div class="relative overflow-hidden rounded-[36px] bg-gradient-to-br from-[#020617] via-[#111827] to-black p-8 shadow-[0_25px_90px_rgba(0,0,0,0.4)]">

                                <!-- GLOW -->
                                <div class="absolute top-0 right-0 w-44 h-44 bg-orange-500/10 blur-3xl rounded-full"></div>

                                <div class="absolute bottom-0 left-0 w-44 h-44 bg-rose-500/10 blur-3xl rounded-full"></div>

                                <div class="relative z-10">

                                    <!-- TOP -->
                                    <div class="flex flex-col gap-6">

                                        <!-- PRICE -->
                                        <div class="flex flex-col gap-5">

                                            <div>

                                                <p class="text-xs uppercase tracking-[0.35em] text-gray-500 font-bold mb-3">
                                                    Bundle Price
                                                </p>

                                                <h3 class="text-5xl lg:text-6xl font-black text-white leading-none break-words">

                                                    ${{ $package->price }}

                                                </h3>

                                            </div>

                                            @if($original > $package->price)

                                                <div class="flex items-center justify-between gap-4 flex-wrap">

                                                    <div class="flex items-center gap-3 text-gray-400">

                                                        <span class="text-sm uppercase tracking-[0.2em] font-bold">
                                                            Original
                                                        </span>

                                                        <span class="text-lg line-through font-semibold">
                                                            ${{ $original }}
                                                        </span>

                                                    </div>

                                                    <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-emerald-500/20 border border-emerald-400/10 text-emerald-400 font-black text-sm shadow-lg whitespace-nowrap">

                                                        <i class="fa-solid fa-bolt"></i>

                                                        Save ${{ $save }}

                                                    </div>

                                                </div>

                                            @endif

                                        </div>

                                        <!-- BOTTOM -->
                                        <div class="flex items-center justify-between border-t border-white/5 pt-5 flex-wrap gap-4">

                                            <div class="flex items-center gap-2 text-gray-400 text-sm font-semibold">

                                                <i class="fa-solid fa-shield-heart text-orange-400"></i>

                                                Premium Bundle

                                            </div>

                                            <div class="flex items-center gap-2 text-emerald-400 text-sm font-bold">

                                                <i class="fa-solid fa-circle-check"></i>

                                                Instant Savings

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- ACTIONS -->
                            <div class="mt-7 space-y-4">

                                <!-- VIEW -->
                                <a href="/packages/{{ $package->id }}"
                                   class="group/btn flex items-center justify-center gap-3 w-full py-4 rounded-2xl bg-gradient-to-r from-slate-900 to-gray-800 text-white font-black shadow-2xl hover:scale-[1.02] transition duration-300">

                                    <i class="fa-solid fa-eye group-hover/btn:scale-110 transition duration-300"></i>

                                    View Bundle

                                </a>

                                <!-- ADD -->
                                <form method="POST" action="{{ route('packages.add', $package) }}">
                                    @csrf

                                    <button class="group/btn2 w-full flex items-center justify-center gap-3 py-4 rounded-2xl bg-gradient-to-r from-orange-500 via-rose-500 to-pink-500 text-white font-black shadow-[0_20px_50px_rgba(249,115,22,0.35)] hover:scale-[1.02] transition duration-300">

                                        <i class="fa-solid fa-cart-plus group-hover/btn2:scale-110 transition duration-300"></i>

                                        Add Bundle

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

            <!-- EMPTY -->
            @if($packages->count() === 0)

                <div class="flex justify-center py-28">

                    <div class="max-w-2xl w-full rounded-[45px] bg-white/80 backdrop-blur-2xl border border-white/40 shadow-[0_25px_100px_rgba(0,0,0,0.08)] p-16 text-center">

                        <div class="relative w-44 h-44 mx-auto mb-12">

                            <div class="absolute inset-0 bg-orange-500/20 blur-3xl rounded-full"></div>

                            <div class="relative w-full h-full rounded-full bg-gradient-to-br from-orange-500 to-rose-600 flex items-center justify-center shadow-[0_25px_80px_rgba(249,115,22,0.45)]">

                                <i class="fa-solid fa-box-open text-white text-7xl"></i>

                            </div>

                        </div>

                        <h2 class="text-5xl font-black text-gray-900 mb-6">
                            No Bundles Yet
                        </h2>

                        <p class="text-gray-500 text-lg leading-relaxed">

                            Premium bundle offers will appear here soon.

                        </p>

                    </div>

                </div>

            @endif

        </div>

    </div>

</x-app-layout>