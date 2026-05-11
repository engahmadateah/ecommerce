<x-app-layout>

    <div class="min-h-screen bg-gradient-to-br from-[#f8fafc] via-orange-50 to-rose-100 py-16 overflow-hidden">

        <!-- BACKGROUND -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden">

            <div class="absolute top-[-250px] left-[-120px] w-[600px] h-[600px] bg-orange-400/15 blur-3xl rounded-full"></div>

            <div class="absolute bottom-[-250px] right-[-120px] w-[600px] h-[600px] bg-pink-400/15 blur-3xl rounded-full"></div>

            <div class="absolute top-[35%] left-[45%] w-[320px] h-[320px] bg-amber-300/10 blur-3xl rounded-full"></div>

        </div>

        <div class="relative max-w-7xl mx-auto px-4 lg:px-8">

            @php
                $original = $package->products->sum('price');
                $save = $original - $package->price;
            @endphp

            <!-- HERO -->
            <div class="relative overflow-hidden rounded-[45px] bg-gradient-to-br from-[#0f172a] via-[#111827] to-[#020617] p-10 lg:p-16 shadow-[0_35px_120px_rgba(0,0,0,0.35)] border border-white/5 mb-14">

                <!-- GLOW -->
                <div class="absolute top-0 right-0 w-[420px] h-[420px] bg-orange-500/10 blur-3xl rounded-full"></div>

                <div class="absolute bottom-0 left-0 w-[420px] h-[420px] bg-rose-500/10 blur-3xl rounded-full"></div>

                <div class="relative z-10 grid lg:grid-cols-2 gap-12 items-center">

                    <!-- LEFT -->
                    <div>

                        <div class="inline-flex items-center gap-3 px-5 py-3 rounded-2xl bg-white/10 border border-white/10 backdrop-blur-xl mb-7">

                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-r from-orange-500 to-rose-500 flex items-center justify-center text-white shadow-2xl">

                                <i class="fa-solid fa-box-open"></i>

                            </div>

                            <span class="text-sm font-bold tracking-[0.18em] uppercase text-white">
                                Exclusive Bundle
                            </span>

                        </div>

                        <h1 class="text-5xl lg:text-7xl font-black leading-[0.95] text-white mb-7">

                            {{ $package->name }}

                        </h1>

                        <p class="text-lg lg:text-xl text-gray-300 leading-relaxed max-w-2xl">

                            {{ $package->description }}

                        </p>

                    </div>

                    <!-- RIGHT -->
                    <div class="relative">

                        <div class="rounded-[40px] bg-white/10 backdrop-blur-2xl border border-white/10 p-8 shadow-2xl">

                            <div class="flex items-center justify-between mb-8">

                                <div>

                                    <p class="text-xs uppercase tracking-[0.3em] text-gray-400 font-bold mb-3">
                                        Bundle Price
                                    </p>

                                    <h2 class="text-6xl font-black text-white">

                                        ${{ $package->price }}

                                    </h2>

                                </div>

                                @if($original > $package->price)

                                    <div class="text-right">

                                        <p class="text-lg text-gray-400 line-through mb-3">

                                            ${{ $original }}

                                        </p>

                                        <div class="inline-flex items-center gap-2 px-5 py-2 rounded-2xl bg-emerald-500/20 text-emerald-400 font-black text-sm border border-emerald-400/10">

                                            <i class="fa-solid fa-bolt"></i>

                                            Save ${{ $save }}

                                        </div>

                                    </div>

                                @endif

                            </div>

                            <form method="POST" action="{{ route('packages.add', $package) }}">
                                @csrf

                                <button class="w-full flex items-center justify-center gap-3 py-5 rounded-2xl bg-gradient-to-r from-orange-500 via-rose-500 to-pink-500 text-white font-black text-lg shadow-[0_20px_60px_rgba(249,115,22,0.35)] hover:scale-[1.02] transition duration-300">

                                    <i class="fa-solid fa-cart-plus"></i>

                                    Add Bundle

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

            <!-- PRODUCTS -->
            <div class="mb-8 flex items-center justify-between">

                <div>

                    <h2 class="text-4xl font-black text-gray-900 mb-2">
                        Included Products
                    </h2>

                    <p class="text-gray-500 text-lg">
                        Everything included in this premium bundle
                    </p>

                </div>

                <div class="hidden lg:flex items-center gap-3 px-5 py-3 rounded-2xl bg-white/70 backdrop-blur-xl border border-white/50 shadow-lg">

                    <i class="fa-solid fa-layer-group text-orange-500"></i>

                    <span class="font-bold text-gray-700">
                        {{ $package->products->count() }} Products
                    </span>

                </div>

            </div>

            <!-- GRID -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-7">

                @foreach($package->products as $product)

                    <div class="group relative overflow-hidden rounded-[35px] bg-white/75 backdrop-blur-2xl border border-white/50 shadow-[0_20px_70px_rgba(0,0,0,0.08)] hover:shadow-[0_30px_90px_rgba(249,115,22,0.18)] transition duration-500 hover:-translate-y-2">

                        <!-- HOVER -->
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-500 bg-gradient-to-br from-orange-500/5 via-transparent to-rose-500/10"></div>

                        <!-- IMAGE -->
                        <div class="relative p-5 pb-0">

                            <div class="relative overflow-hidden rounded-[28px]">

                                <div class="absolute inset-0 bg-orange-400/10 opacity-0 group-hover:opacity-100 transition duration-500 z-10"></div>

                                <img src="{{ asset('storage/'.$product->image) }}"
                                     class="w-full h-56 object-cover rounded-[28px] transition duration-700 group-hover:scale-110">

                            </div>

                        </div>

                        <!-- CONTENT -->
                        <div class="relative p-6">

                            <h3 class="text-2xl font-black text-gray-900 leading-tight mb-4">

                                {{ $product->name }}

                            </h3>

                            <div class="flex items-center justify-between">

                                <div>

                                    <p class="text-xs uppercase tracking-[0.25em] text-gray-400 font-bold mb-2">
                                        Product Price
                                    </p>

                                    <h4 class="text-3xl font-black bg-gradient-to-r from-orange-500 to-rose-500 bg-clip-text text-transparent">

                                        ${{ $product->price }}

                                    </h4>

                                </div>

                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-500 to-rose-500 text-white flex items-center justify-center shadow-xl">

                                    <i class="fa-solid fa-bag-shopping"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>

</x-app-layout>