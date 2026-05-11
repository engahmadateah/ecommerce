<x-app-layout>

<div class="min-h-screen relative overflow-hidden bg-gradient-to-br from-[#f8fafc] via-[#fdfdfd] to-[#eef4ff] py-16">

    <!-- SOFT PREMIUM BACKGROUND -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">

        <!-- glows -->
        <div class="absolute top-[-250px] left-[-150px] w-[700px] h-[700px] bg-sky-300/20 blur-3xl rounded-full"></div>

        <div class="absolute bottom-[-250px] right-[-150px] w-[700px] h-[700px] bg-indigo-300/20 blur-3xl rounded-full"></div>

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
                                bg-gradient-to-br from-pink-500 to-rose-500
                                flex items-center justify-center
                                text-white shadow-xl">

                        <i class="fa-solid fa-heart text-xl"></i>

                    </div>

                    <div>

                        <p class="text-xs uppercase tracking-[0.35em] text-pink-600 font-black">
                            Wishlist Collection
                        </p>

                        <p class="text-sm text-gray-500 mt-1">
                            Your saved premium products
                        </p>

                    </div>

                </div>

                <!-- title -->
                <h1 class="text-6xl lg:text-7xl font-black tracking-tight leading-[0.95] text-gray-900">

                    My
                    <span class="bg-gradient-to-r from-pink-500 via-rose-500 to-orange-400 bg-clip-text text-transparent">
                        Wishlist
                    </span>

                </h1>

                <!-- desc -->
                <p class="text-xl text-gray-500 leading-relaxed mt-7 max-w-2xl">

                    Save and organize your favorite premium products
                    with a modern luxury shopping experience.

                </p>

            </div>

            <!-- STATS -->
            <div class="grid grid-cols-2 gap-5">

                <!-- items -->
                <div class="rounded-[34px]
                            border border-white/70
                            bg-white/75
                            backdrop-blur-3xl
                            p-8 min-w-[220px]
                            shadow-[0_20px_80px_rgba(15,23,42,0.06)]">

                    <div class="w-18 h-18 rounded-[26px]
                                bg-pink-100
                                flex items-center justify-center mb-6">

                        <i class="fa-solid fa-heart text-pink-500 text-3xl"></i>

                    </div>

                    <h2 class="text-5xl font-black text-gray-900">

                        {{ $products->count() }}

                    </h2>

                    <p class="text-gray-500 mt-3 uppercase tracking-[0.2em] text-xs font-black">

                        Saved Items

                    </p>

                </div>

                <!-- premium -->
                <div class="rounded-[34px]
                            border border-white/70
                            bg-white/75
                            backdrop-blur-3xl
                            p-8 min-w-[220px]
                            shadow-[0_20px_80px_rgba(15,23,42,0.06)]">

                    <div class="w-18 h-18 rounded-[26px]
                                bg-sky-100
                                flex items-center justify-center mb-6">

                        <i class="fa-solid fa-gem text-sky-500 text-3xl"></i>

                    </div>

                    <h2 class="text-5xl font-black text-gray-900">

                        VIP

                    </h2>

                    <p class="text-gray-500 mt-3 uppercase tracking-[0.2em] text-xs font-black">

                        Premium Access

                    </p>

                </div>

            </div>

        </div>

        <!-- PRODUCTS -->
        @if($products->count())

        <div class="grid sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-8">

            @foreach($products as $product)

                <div class="group relative rounded-[38px]
                            overflow-hidden
                            border border-white/70
                            bg-white/75
                            backdrop-blur-3xl
                            shadow-[0_25px_90px_rgba(15,23,42,0.07)]
                            hover:-translate-y-3
                            hover:shadow-[0_35px_110px_rgba(59,130,246,0.12)]
                            transition duration-700">

                    <!-- hover glow -->
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-700
                                bg-gradient-to-br from-pink-100/40 via-transparent to-sky-100/40">
                    </div>

                    <!-- top -->
                    <div class="absolute top-5 left-5 right-5 z-30 flex items-center justify-between">

                        <!-- badge -->
                        <div class="px-4 py-2 rounded-full
                                    bg-white/80
                                    border border-white
                                    backdrop-blur-xl
                                    text-gray-700 text-xs
                                    tracking-[0.18em]
                                    font-black shadow-lg">

                            SAVED

                        </div>

                        <!-- remove -->
                        <form method="POST"
                              action="/wishlist/{{ $product->id }}">

                            @csrf

                            <button class="w-14 h-14 rounded-2xl
                                           bg-white/80
                                           border border-white
                                           shadow-xl
                                           text-pink-500 text-lg
                                           hover:bg-pink-500
                                           hover:text-white
                                           hover:scale-110
                                           transition duration-300">

                                <i class="fa-solid fa-heart-crack"></i>

                            </button>

                        </form>

                    </div>

                    <!-- image -->
                    <a href="{{ route('products.show', $product) }}"
                       class="block relative overflow-hidden">

                        <!-- overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent z-10"></div>

                        <!-- image -->
                        <img src="{{ asset('storage/'.$product->image) }}"
                             class="w-full h-[380px] object-cover group-hover:scale-110 transition duration-1000">

                    </a>

                    <!-- content -->
                    <div class="relative z-20 p-7">

                        <!-- category -->
                        <div class="mb-5">

                            <span class="inline-flex items-center gap-2
                                         px-4 py-2 rounded-2xl
                                         bg-sky-50 border border-sky-100
                                         text-sky-700 text-xs
                                         uppercase tracking-[0.18em]
                                         font-black">

                                <i class="fa-solid fa-layer-group"></i>

                                {{ $product->category->name ?? 'Premium Product' }}

                            </span>

                        </div>

                        <!-- title -->
                        <h2 class="text-[30px] font-black text-gray-900 leading-tight mb-5 line-clamp-2 min-h-[84px]">

                            {{ $product->name }}

                        </h2>

                        <!-- bottom -->
                        <div class="flex items-end justify-between gap-4 mb-7">

                            <div>

                                <p class="text-4xl font-black text-gray-900">

                                    ${{ $product->price }}

                                </p>

                            </div>

                        </div>

                        <!-- actions -->
                        <div class="flex items-center gap-4">

                            <!-- view -->
                            <a href="{{ route('products.show', $product) }}"
                               class="group/btn relative overflow-hidden flex-1 h-15 rounded-[22px]
                                      bg-gradient-to-r from-sky-500 via-indigo-500 to-blue-600
                                      text-white font-black
                                      shadow-[0_15px_50px_rgba(59,130,246,0.25)]
                                      hover:scale-[1.02]
                                      transition duration-300">

                                <span class="relative z-10 flex items-center justify-center gap-3 h-full">

                                    <i class="fa-solid fa-arrow-right group-hover/btn:translate-x-1 transition duration-300"></i>

                                    View Product

                                </span>

                                <div class="absolute inset-0 bg-white/20
                                            translate-x-[-100%]
                                            group-hover/btn:translate-x-[100%]
                                            transition duration-700">
                                </div>

                            </a>

                            <!-- cart -->
                            <form method="POST"
                                  action="/cart/add/{{ $product->id }}">

                                @csrf

                                <button class="w-15 h-15 rounded-[22px]
                                               bg-[#f9fafb]
                                               border border-gray-200
                                               text-gray-700 text-xl
                                               hover:bg-sky-500
                                               hover:border-sky-500
                                               hover:text-white
                                               hover:scale-105
                                               transition duration-300">

                                    <i class="fa-solid fa-cart-plus"></i>

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

        @else

        <!-- EMPTY -->
        <div class="flex justify-center py-28">

            <div class="max-w-4xl w-full rounded-[45px]
                        border border-white/70
                        bg-white/75
                        backdrop-blur-3xl
                        p-20 text-center
                        shadow-[0_30px_120px_rgba(15,23,42,0.08)]">

                <!-- icon -->
                <div class="w-44 h-44 rounded-full
                            bg-gradient-to-br from-pink-500 to-rose-500
                            flex items-center justify-center
                            mx-auto mb-12
                            shadow-[0_25px_100px_rgba(244,63,94,0.25)]">

                    <i class="fa-solid fa-heart-crack text-white text-7xl"></i>

                </div>

                <!-- title -->
                <h2 class="text-6xl font-black text-gray-900 mb-6">

                    Wishlist Empty

                </h2>

                <!-- text -->
                <p class="text-2xl text-gray-500 leading-relaxed max-w-2xl mx-auto mb-12">

                    Start building your premium collection by saving
                    the products you love.

                </p>

                <!-- button -->
                <a href="/products"
                   class="inline-flex items-center gap-4
                          px-10 h-20 rounded-[28px]
                          bg-gradient-to-r from-pink-500 via-rose-500 to-orange-400
                          text-white text-xl font-black
                          shadow-[0_20px_80px_rgba(244,63,94,0.25)]
                          hover:scale-105 transition duration-300">

                    <i class="fa-solid fa-bag-shopping"></i>

                    Explore Products

                </a>

            </div>

        </div>

        @endif

    </div>

</div>

</x-app-layout>