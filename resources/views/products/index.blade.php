<x-app-layout>

    <div class="min-h-screen relative overflow-hidden bg-[#f6f8fc]">

        <!-- ULTRA PREMIUM BACKGROUND -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden">

            <!-- soft mesh -->
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(14,165,233,0.12),transparent_30%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_right,rgba(249,115,22,0.10),transparent_30%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(99,102,241,0.06),transparent_40%)]"></div>

            <!-- floating blur -->
            <div class="absolute -top-40 -left-32 w-[700px] h-[700px] bg-sky-300/20 blur-3xl rounded-full"></div>
            <div class="absolute bottom-[-250px] right-[-100px] w-[700px] h-[700px] bg-orange-300/20 blur-3xl rounded-full"></div>

            <!-- luxury grid -->
            <div class="absolute inset-0 opacity-[0.03]"
                 style="background-image: linear-gradient(to right, black 1px, transparent 1px),
                 linear-gradient(to bottom, black 1px, transparent 1px);
                 background-size: 70px 70px;">
            </div>

        </div>

        <div class="relative max-w-[1750px] mx-auto px-5 lg:px-10 py-12">

            <!-- HERO -->
            <div class="relative overflow-hidden rounded-[55px]
                        border border-white/70
                        bg-white/70
                        backdrop-blur-[30px]
                        shadow-[0_30px_120px_rgba(15,23,42,0.08)]
                        p-8 lg:p-16 mb-14">

                <!-- glows -->
                <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-sky-300/20 blur-3xl rounded-full"></div>
                <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-orange-300/20 blur-3xl rounded-full"></div>

                <!-- shine -->
                <div class="absolute inset-0 bg-[linear-gradient(120deg,transparent,rgba(255,255,255,0.45),transparent)] opacity-40"></div>

                <div class="relative z-10 flex flex-col xl:flex-row items-start xl:items-center justify-between gap-16">

                    <!-- LEFT -->
                    <div class="max-w-3xl">

                        <!-- badge -->
                        <div class="inline-flex items-center gap-4 px-6 py-4 rounded-[28px]
                                    bg-white/80 border border-white shadow-xl mb-8">

                            <div class="w-16 h-16 rounded-[22px]
                                        bg-gradient-to-br from-sky-500 via-indigo-500 to-blue-600
                                        flex items-center justify-center text-white
                                        shadow-[0_15px_45px_rgba(59,130,246,0.35)]">

                                <i class="fa-solid fa-crown text-2xl"></i>

                            </div>

                            <div>

                                <p class="text-xs uppercase tracking-[0.4em]
                                          text-sky-600 font-black">
                                    Luxury Marketplace
                                </p>

                                <p class="text-sm text-gray-500 mt-1">
                                    Elite Shopping Experience
                                </p>

                            </div>

                        </div>

                        <!-- title -->
                        <h1 class="text-6xl lg:text-[92px]
                                   leading-[0.88]
                                   font-black tracking-[-0.06em]
                                   text-gray-900">

                            Future Of
                            <span class="bg-gradient-to-r
                                         from-sky-500
                                         via-indigo-500
                                         to-orange-400
                                         bg-clip-text text-transparent">

                                Shopping

                            </span>

                        </h1>

                        <!-- desc -->
                        <p class="text-xl leading-relaxed text-gray-500 mt-8 max-w-2xl">

                            Experience a luxury marketplace crafted with
                            premium aesthetics, ultra-smooth interactions,
                            and next-generation shopping elegance.

                        </p>

                        <!-- mini cards -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-10">

                            <div class="rounded-[24px] border border-white bg-white/70 backdrop-blur-xl p-5 shadow-lg">

                                <div class="w-12 h-12 rounded-2xl bg-sky-100 flex items-center justify-center mb-4">

                                    <i class="fa-solid fa-shield-halved text-sky-600"></i>

                                </div>

                                <h4 class="font-black text-gray-900">
                                    Secure
                                </h4>

                                <p class="text-sm text-gray-500 mt-1">
                                    Protected payments
                                </p>

                            </div>

                            <div class="rounded-[24px] border border-white bg-white/70 backdrop-blur-xl p-5 shadow-lg">

                                <div class="w-12 h-12 rounded-2xl bg-orange-100 flex items-center justify-center mb-4">

                                    <i class="fa-solid fa-bolt text-orange-500"></i>

                                </div>

                                <h4 class="font-black text-gray-900">
                                    Fast
                                </h4>

                                <p class="text-sm text-gray-500 mt-1">
                                    Lightning delivery
                                </p>

                            </div>

                            <div class="rounded-[24px] border border-white bg-white/70 backdrop-blur-xl p-5 shadow-lg">

                                <div class="w-12 h-12 rounded-2xl bg-pink-100 flex items-center justify-center mb-4">

                                    <i class="fa-solid fa-gem text-pink-500"></i>

                                </div>

                                <h4 class="font-black text-gray-900">
                                    Premium
                                </h4>

                                <p class="text-sm text-gray-500 mt-1">
                                    Luxury quality
                                </p>

                            </div>

                        </div>

                    </div>

                    <!-- RIGHT -->
                    <div class="grid grid-cols-2 gap-6">

                        <!-- total -->
                        <div class="rounded-[38px]
                                    bg-white/80
                                    border border-white
                                    backdrop-blur-2xl
                                    p-8
                                    shadow-[0_20px_80px_rgba(15,23,42,0.08)]">

                            <div class="w-20 h-20 rounded-[28px]
                                        bg-sky-100
                                        flex items-center justify-center mb-8">

                                <i class="fa-solid fa-boxes-stacked text-sky-600 text-4xl"></i>

                            </div>

                            <h2 class="text-6xl font-black text-gray-900">

                                {{ $products->total() }}

                            </h2>

                            <p class="mt-3 text-xs uppercase tracking-[0.2em]
                                      text-gray-500 font-black">

                                Premium Products

                            </p>

                        </div>

                        <!-- deals -->
                        <div class="rounded-[38px]
                                    bg-white/80
                                    border border-white
                                    backdrop-blur-2xl
                                    p-8
                                    shadow-[0_20px_80px_rgba(15,23,42,0.08)]">

                            <div class="w-20 h-20 rounded-[28px]
                                        bg-orange-100
                                        flex items-center justify-center mb-8">

                                <i class="fa-solid fa-fire text-orange-500 text-4xl"></i>

                            </div>

                            <h2 class="text-6xl font-black text-gray-900">
                                HOT
                            </h2>

                            <p class="mt-3 text-xs uppercase tracking-[0.2em]
                                      text-gray-500 font-black">

                                Luxury Deals

                            </p>

                        </div>

                    </div>

                </div>

            </div>

            <!-- FILTER -->
            <div class="rounded-[40px]
                        border border-white/70
                        bg-white/70
                        backdrop-blur-[25px]
                        shadow-[0_25px_80px_rgba(15,23,42,0.06)]
                        p-7 mb-14">

                <form method="GET"
                      onsubmit="return false;"
                      class="flex flex-col xl:flex-row gap-5 items-center">

                    <!-- SEARCH -->
                    <div class="relative flex-1 w-full">

                        <div class="absolute left-6 top-1/2 -translate-y-1/2 text-sky-500 text-lg">

                            <i class="fa-solid fa-magnifying-glass"></i>

                        </div>

                        <input type="text"
                               id="searchInput"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search luxury products..."
                               class="w-full h-[76px]
                                      rounded-[30px]
                                      border border-gray-200
                                      bg-white/90
                                      pl-16 pr-6
                                      text-gray-800
                                      font-semibold
                                      shadow-lg
                                      outline-none
                                      focus:ring-4 focus:ring-sky-100
                                      focus:border-sky-400 transition">

                    </div>

                    <!-- CATEGORY -->
                    <div class="relative w-full xl:w-[320px]">

                        <div class="absolute left-6 top-1/2 -translate-y-1/2 text-orange-500">

                            <i class="fa-solid fa-layer-group"></i>

                        </div>

                        <select id="categorySelect"
                                name="category"
                                class="appearance-none
                                       w-full h-[76px]
                                       rounded-[30px]
                                       border border-gray-200
                                       bg-white/90
                                       pl-16 pr-6
                                       text-gray-700
                                       font-bold
                                       shadow-lg
                                       outline-none
                                       focus:ring-4 focus:ring-orange-100
                                       focus:border-orange-400 transition">

                            <option value="">All Categories</option>

                            @foreach($categories as $cat)

                                <option value="{{ $cat->id }}"
                                    {{ request('category') == $cat->id ? 'selected' : '' }}>

                                    {{ $cat->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <!-- BUTTON -->
                    <button class="h-[76px]
                                   px-14
                                   rounded-[30px]
                                   bg-gradient-to-r
                                   from-sky-500
                                   via-indigo-500
                                   to-blue-600
                                   text-white
                                   font-black
                                   text-lg
                                   shadow-[0_20px_60px_rgba(59,130,246,0.35)]
                                   hover:scale-[1.03]
                                   transition duration-300">

                        Explore

                    </button>

                </form>

            </div>

            <!-- PRODUCTS -->
            <div id="productsGrid"
                 class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-10">

                @forelse($products as $product)

                    <!-- PRODUCT CARD -->
                    <a href="{{ route('products.show', $product) }}"
                       class="group relative block overflow-hidden rounded-[42px]
                              border border-white/70
                              bg-white/75
                              backdrop-blur-3xl
                              shadow-[0_20px_80px_rgba(15,23,42,0.07)]
                              hover:-translate-y-4
                              hover:shadow-[0_35px_120px_rgba(59,130,246,0.16)]
                              transition duration-700">

                        <!-- glow -->
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-700
                                    bg-gradient-to-br from-sky-100/50 via-transparent to-orange-100/50">
                        </div>

                        <!-- offer -->
                        @if($product->discount_price)

                            <div class="absolute top-5 left-5 z-30">

                                <div class="px-5 py-2 rounded-full
                                            bg-gradient-to-r from-red-500 to-orange-500
                                            text-white text-xs font-black tracking-[0.18em]
                                            shadow-2xl">

                                    ✦ SPECIAL OFFER

                                </div>

                            </div>

                        @endif

                        <!-- wishlist -->
                        @auth

                        <button
                            onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist({{ $product->id }}, this)"
                            class="absolute top-5 right-5 z-30
                                   w-14 h-14 rounded-2xl
                                   bg-white/90 border border-white
                                   shadow-xl backdrop-blur-xl
                                   flex items-center justify-center
                                   hover:scale-110 transition">

                            @if(auth()->user()->wishlist->contains($product->id))

                                <span class="text-red-500">❤️</span>

                            @else

                                <span class="text-gray-300">🤍</span>

                            @endif

                        </button>

                        @endauth

                        <!-- image -->
                        <div class="relative overflow-hidden">

                            <img src="{{ asset('storage/' . $product->image) }}"
                                 class="w-full h-[380px] object-cover
                                        transition duration-1000
                                        group-hover:scale-110">

                            <div class="absolute inset-0 bg-gradient-to-t
                                        from-black/15 via-transparent to-transparent">
                            </div>

                        </div>

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

                                    <i class="fa-solid fa-cube"></i>

                                    {{ $product->category->name ?? 'No Category' }}

                                </span>

                            </div>

                            <!-- title -->
                            <h2 class="text-[30px]
                                       font-black
                                       text-gray-900
                                       leading-tight
                                       mb-4
                                       line-clamp-2
                                       min-h-[84px]">

                                {{ $product->name }}

                            </h2>

                            <!-- reviews -->
                            <div class="flex items-center justify-between mb-7">

                                <div class="flex items-center gap-1 text-yellow-400">

                                    {!! str_repeat('⭐', round($product->reviews_avg_rating ?? 0)) !!}

                                </div>

                                <span class="text-sm text-gray-400 font-semibold">

                                    {{ $product->reviews_count ?? 0 }} Reviews

                                </span>

                            </div>

                            <!-- price -->
                            <div class="flex items-end justify-between gap-5 mb-8">

                                <div>

                                    @if($product->discount_price)

                                        <p class="text-lg text-gray-400 line-through mb-1">

                                            ${{ $product->price }}

                                        </p>

                                        <h3 class="text-5xl font-black text-gray-900">

                                            ${{ $product->discount_price }}

                                        </h3>

                                    @else

                                        <h3 class="text-5xl font-black text-gray-900">

                                            ${{ $product->price }}

                                        </h3>

                                    @endif

                                </div>

                                @if($product->discount_price)

                                    <div class="px-4 py-3 rounded-2xl
                                                bg-emerald-50
                                                border border-emerald-100
                                                text-emerald-600
                                                text-sm font-black">

                                        Save
                                        ${{ number_format($product->price - $product->discount_price, 0) }}

                                    </div>

                                @endif

                            </div>

                            <!-- cart -->
                            <form method="POST"
                                  action="/cart/add/{{ $product->id }}"
                                  onclick="event.preventDefault(); event.stopPropagation(); this.submit();">

                                @csrf

                                <button type="submit"
                                        class="group/btn relative overflow-hidden
                                               w-full h-16 rounded-[24px]
                                               bg-gradient-to-r
                                               from-sky-500
                                               via-indigo-500
                                               to-blue-600
                                               text-white
                                               font-black text-lg
                                               shadow-[0_20px_60px_rgba(59,130,246,0.30)]
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

                    </a>

                @empty

                    <div class="col-span-full flex justify-center py-32">

                        <div class="max-w-3xl w-full rounded-[45px]
                                    border border-white/70
                                    bg-white/75
                                    backdrop-blur-3xl
                                    p-20 text-center
                                    shadow-[0_30px_120px_rgba(15,23,42,0.08)]">

                            <div class="w-44 h-44 rounded-full
                                        bg-gradient-to-br from-sky-500 to-indigo-600
                                        flex items-center justify-center
                                        mx-auto mb-12
                                        shadow-[0_25px_100px_rgba(59,130,246,0.25)]">

                                <i class="fa-solid fa-box-open text-white text-7xl"></i>

                            </div>

                            <h2 class="text-6xl font-black text-gray-900 mb-6">

                                No Products Found

                            </h2>

                            <p class="text-xl text-gray-500">

                                Try another category or search term.

                            </p>

                        </div>

                    </div>

                @endforelse

            </div>

            <!-- PAGINATION -->
            <div class="mt-20">

                {{ $products->links() }}

            </div>

        </div>

    </div>

    <!-- WISHLIST -->
    <script>

        function toggleWishlist(productId, el) {

            fetch(`/wishlist/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {

                el.innerHTML = data.status === 'added'
                    ? '<span class="text-red-500 animate-pulse">❤️</span>'
                    : '<span class="text-gray-300">🤍</span>';

            });

        }

    </script>

    <!-- LIVE SEARCH -->
    <script>

        let timeout = null;

        function fetchProducts() {

            const search = document.getElementById('searchInput').value;
            const category = document.getElementById('categorySelect').value;

            fetch(`/products/search?search=${encodeURIComponent(search)}&category=${category}`)

                .then(res => {

                    if (!res.ok) throw new Error();
                    return res.text();

                })

                .then(html => {

                    document.getElementById('productsGrid').innerHTML = html;

                })

                .catch(() => {

                    document.getElementById('productsGrid').innerHTML =
                        '<p class="text-red-500 text-center col-span-full text-xl">⚠️ Error loading products</p>';

                });

        }

        document.getElementById('searchInput').addEventListener('input', function () {

            clearTimeout(timeout);

            timeout = setTimeout(fetchProducts, 350);

        });

        document.getElementById('categorySelect').addEventListener('change', function () {

            fetchProducts();

        });

    </script>

</x-app-layout>