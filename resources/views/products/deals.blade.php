<x-app-layout>

<div class="min-h-screen relative overflow-hidden bg-gradient-to-br from-[#f8fafc] via-[#ffffff] to-[#fff7f7] py-16">

    <!-- PREMIUM BACKGROUND -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">

        <!-- glow -->
        <div class="absolute top-[-250px] left-[-150px] w-[700px] h-[700px] bg-red-300/20 blur-3xl rounded-full"></div>

        <div class="absolute bottom-[-250px] right-[-150px] w-[700px] h-[700px] bg-orange-300/20 blur-3xl rounded-full"></div>

        <div class="absolute top-[35%] left-[40%] w-[350px] h-[350px] bg-pink-200/20 blur-3xl rounded-full"></div>

        <!-- grid -->
        <div class="absolute inset-0 opacity-[0.03]"
             style="background-image:
             linear-gradient(to right, black 1px, transparent 1px),
             linear-gradient(to bottom, black 1px, transparent 1px);
             background-size: 70px 70px;">
        </div>

    </div>

    <div class="relative max-w-[1700px] mx-auto px-5 lg:px-10">

        <!-- HERO -->
        <div class="flex flex-col xl:flex-row xl:items-end xl:justify-between gap-10 mb-14">

            <!-- LEFT -->
            <div>

                <!-- badge -->
                <div class="inline-flex items-center gap-4 px-6 py-4 rounded-[30px]
                            border border-white/70
                            bg-white/70
                            backdrop-blur-3xl
                            shadow-[0_15px_60px_rgba(15,23,42,0.06)]
                            mb-8">

                    <div class="w-14 h-14 rounded-2xl
                                bg-gradient-to-br from-red-500 to-orange-500
                                flex items-center justify-center
                                text-white shadow-xl">

                        <i class="fa-solid fa-fire text-xl"></i>

                    </div>

                    <div>

                        <p class="text-xs uppercase tracking-[0.35em] text-red-500 font-black">
                            Limited Offers
                        </p>

                        <p class="text-sm text-gray-500 mt-1">
                            Premium discounts available today
                        </p>

                    </div>

                </div>

                <!-- title -->
                <h1 class="text-6xl lg:text-7xl font-black tracking-tight leading-[0.95] text-gray-900">

                    Today's
                    <span class="bg-gradient-to-r from-red-500 via-orange-500 to-pink-500 bg-clip-text text-transparent">
                        Deals
                    </span>

                </h1>

                <!-- desc -->
                <p class="text-xl text-gray-500 leading-relaxed mt-8 max-w-3xl">

                    Discover exclusive discounts on premium products
                    with elegant shopping experience and limited-time offers.

                </p>

            </div>

            <!-- STATS -->
            <div class="grid grid-cols-2 gap-5">

                <!-- products -->
                <div class="rounded-[34px]
                            border border-white/70
                            bg-white/75
                            backdrop-blur-3xl
                            p-8 min-w-[220px]
                            shadow-[0_20px_80px_rgba(15,23,42,0.06)]">

                    <div class="w-18 h-18 rounded-[26px]
                                bg-red-100
                                flex items-center justify-center mb-6">

                        <i class="fa-solid fa-tag text-red-500 text-3xl"></i>

                    </div>

                    <h2 class="text-5xl font-black text-gray-900">

                        {{ $products->count() }}

                    </h2>

                    <p class="text-gray-500 mt-3 uppercase tracking-[0.2em] text-xs font-black">

                        Active Deals

                    </p>

                </div>

                <!-- hot -->
                <div class="rounded-[34px]
                            border border-white/70
                            bg-white/75
                            backdrop-blur-3xl
                            p-8 min-w-[220px]
                            shadow-[0_20px_80px_rgba(15,23,42,0.06)]">

                    <div class="w-18 h-18 rounded-[26px]
                                bg-orange-100
                                flex items-center justify-center mb-6">

                        <i class="fa-solid fa-bolt text-orange-500 text-3xl"></i>

                    </div>

                    <h2 class="text-5xl font-black text-gray-900">

                        HOT

                    </h2>

                    <p class="text-gray-500 mt-3 uppercase tracking-[0.2em] text-xs font-black">

                        Daily Discounts

                    </p>

                </div>

            </div>

        </div>

        <!-- PRODUCTS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-8">

            @forelse($products as $product)

                <div class="group relative rounded-[38px]
                            overflow-hidden
                            border border-white/70
                            bg-white/75
                            backdrop-blur-3xl
                            shadow-[0_25px_90px_rgba(15,23,42,0.07)]
                            hover:-translate-y-3
                            hover:shadow-[0_35px_110px_rgba(239,68,68,0.12)]
                            transition duration-700">

                    <!-- glow -->
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-700
                                bg-gradient-to-br from-red-100/40 via-transparent to-orange-100/40">
                    </div>

                    <!-- badge -->
                    <div class="absolute top-5 left-5 z-30">

                        <div class="px-5 py-2 rounded-full
                                    bg-gradient-to-r from-red-500 to-orange-500
                                    text-white text-xs font-black
                                    tracking-[0.18em]
                                    shadow-xl">

                            -{{ round(100 - ($product->discount_price / $product->price * 100)) }}%

                        </div>

                    </div>

                    <!-- image -->
                    <a href="{{ route('products.show', $product) }}"
                       class="block relative overflow-hidden">

                        <!-- overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent z-10"></div>

                        <!-- image -->
                        <img src="{{ asset('storage/' . $product->image) }}"
                             class="w-full h-[380px] object-cover group-hover:scale-110 transition duration-1000">

                    </a>

                    <!-- content -->
                    <div class="relative z-20 p-7">

                        <!-- category -->
                        <div class="mb-5">

                            <span class="inline-flex items-center gap-2
                                         px-4 py-2 rounded-2xl
                                         bg-red-50 border border-red-100
                                         text-red-600 text-xs
                                         uppercase tracking-[0.18em]
                                         font-black">

                                <i class="fa-solid fa-layer-group"></i>

                                {{ $product->category->name ?? 'Premium Product' }}

                            </span>

                        </div>

                        <!-- title -->
                        <h2 class="text-[28px] font-black text-gray-900 leading-tight mb-4 line-clamp-2 min-h-[78px]">

                            {{ $product->name }}

                        </h2>

                        <!-- rating -->
                        <div class="flex items-center justify-between mb-6">

                            <div class="flex items-center gap-1 text-yellow-400">

                                {!! str_repeat('⭐', round($product->reviews_avg_rating ?? 0)) !!}

                            </div>

                            <span class="text-sm text-gray-400 font-semibold">

                                {{ $product->reviews_count ?? 0 }} Reviews

                            </span>

                        </div>

                        <!-- price -->
                        <div class="flex items-end justify-between gap-4 mb-8">

                            <div>

                                <p class="text-gray-400 line-through text-lg mb-1">

                                    ${{ $product->price }}

                                </p>

                                <h3 class="text-5xl font-black text-gray-900 leading-none">

                                    ${{ $product->discount_price }}

                                </h3>

                            </div>

                            <!-- save -->
                            <div class="px-4 py-3 rounded-2xl
                                        bg-emerald-50
                                        border border-emerald-100
                                        text-emerald-600 text-sm
                                        font-black shadow-lg">

                                Save
                                ${{ number_format($product->price - $product->discount_price, 0) }}

                            </div>

                        </div>

                        <!-- button -->
                        <form method="POST"
                              action="/cart/add/{{ $product->id }}"
                              class="relative z-20"
                              onclick="event.stopPropagation();">

                            @csrf

                            <button class="group/btn relative overflow-hidden
                                           w-full h-16 rounded-[24px]
                                           bg-gradient-to-r
                                           from-red-500
                                           via-orange-500
                                           to-pink-500
                                           text-white font-black text-lg
                                           shadow-[0_20px_60px_rgba(239,68,68,0.25)]
                                           hover:scale-[1.02]
                                           transition duration-300">

                                <span class="relative z-10 flex items-center justify-center gap-3">

                                    <i class="fa-solid fa-cart-plus
                                              group-hover/btn:rotate-12
                                              transition duration-300"></i>

                                    Add To Cart

                                </span>

                                <div class="absolute inset-0
                                            bg-white/20
                                            translate-x-[-100%]
                                            group-hover/btn:translate-x-[100%]
                                            transition duration-700">
                                </div>

                            </button>

                        </form>

                    </div>

                </div>

            @empty

                <!-- EMPTY -->
                <div class="col-span-full flex justify-center py-28">

                    <div class="max-w-4xl w-full rounded-[45px]
                                border border-white/70
                                bg-white/75
                                backdrop-blur-3xl
                                p-20 text-center
                                shadow-[0_30px_120px_rgba(15,23,42,0.08)]">

                        <!-- icon -->
                        <div class="w-44 h-44 rounded-full
                                    bg-gradient-to-br from-red-500 to-orange-500
                                    flex items-center justify-center
                                    mx-auto mb-12
                                    shadow-[0_25px_100px_rgba(239,68,68,0.25)]">

                            <i class="fa-solid fa-fire text-white text-7xl"></i>

                        </div>

                        <!-- title -->
                        <h2 class="text-6xl font-black text-gray-900 mb-6">

                            No Deals Available

                        </h2>

                        <!-- text -->
                        <p class="text-2xl text-gray-500 leading-relaxed max-w-2xl mx-auto mb-12">

                            There are no active deals right now.
                            Check back later for new premium discounts.

                        </p>

                        <!-- button -->
                        <a href="/products"
                           class="inline-flex items-center gap-4
                                  px-10 h-20 rounded-[28px]
                                  bg-gradient-to-r from-red-500 via-orange-500 to-pink-500
                                  text-white text-xl font-black
                                  shadow-[0_20px_80px_rgba(239,68,68,0.25)]
                                  hover:scale-105 transition duration-300">

                            <i class="fa-solid fa-bag-shopping"></i>

                            Explore Products

                        </a>

                    </div>

                </div>

            @endforelse

        </div>

        <!-- PAGINATION -->
        <div class="mt-16">

            {{ $products->links() }}

        </div>

    </div>

</div>

</x-app-layout>