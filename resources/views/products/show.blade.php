<x-app-layout>

<div class="min-h-screen relative overflow-hidden bg-[#f3f6fb]">

    <!-- ULTRA PREMIUM BACKGROUND -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">

        <!-- mesh -->
        <div class="absolute inset-0 opacity-[0.03]"
             style="background-image:
             linear-gradient(to right, black 1px, transparent 1px),
             linear-gradient(to bottom, black 1px, transparent 1px);
             background-size: 90px 90px;">
        </div>

        <!-- glows -->
        <div class="absolute top-[-250px] left-[-150px] w-[750px] h-[750px] bg-sky-300/20 blur-3xl rounded-full"></div>

        <div class="absolute bottom-[-250px] right-[-150px] w-[750px] h-[750px] bg-orange-300/20 blur-3xl rounded-full"></div>

        <div class="absolute top-[40%] left-[40%] w-[500px] h-[500px] bg-pink-200/10 blur-3xl rounded-full"></div>

        <!-- vignette -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.95),transparent_50%)]"></div>

    </div>

    <div class="relative max-w-[1650px] mx-auto px-5 lg:px-10 py-14">

        <!-- PRODUCT -->
        <div class="grid xl:grid-cols-[1.1fr_0.9fr] gap-10 items-start">

            <!-- IMAGE SIDE -->
            <div class="space-y-6">

                <!-- IMAGE CARD -->
                <div class="relative rounded-[50px]
                            overflow-hidden
                            border border-white/70
                            bg-white/70
                            backdrop-blur-3xl
                            shadow-[0_40px_120px_rgba(15,23,42,0.08)]">

                    <!-- overlay -->
                    <div class="absolute inset-0 bg-gradient-to-br from-sky-100/30 via-transparent to-orange-100/20 z-10"></div>

                    <!-- image -->
                    <div class="relative overflow-hidden">

                        <img src="{{ asset('storage/'.$product->image) }}"
                             class="w-full h-[820px] object-cover hover:scale-105 transition duration-[2000ms]">

                        <div class="absolute inset-0 bg-gradient-to-t from-black/25 via-transparent to-transparent"></div>

                    </div>

                    <!-- floating controls -->
                    <div class="absolute top-6 left-6 z-30 flex flex-col gap-4">

                        @if($product->stock <= 5)

                            <div class="px-6 py-3 rounded-full
                                        bg-red-500 text-white
                                        font-black text-sm
                                        shadow-[0_15px_40px_rgba(239,68,68,0.45)]">

                                ⚠️ Only {{ $product->stock }} Left

                            </div>

                        @endif

                        @if($product->discount_price)

                            <div class="px-6 py-3 rounded-full
                                        bg-gradient-to-r from-orange-500 to-red-500
                                        text-white font-black text-sm
                                        shadow-[0_15px_40px_rgba(249,115,22,0.45)]">

                                🔥 Limited Offer

                            </div>

                        @endif

                    </div>

                    <!-- floating action -->
                    <div class="absolute top-6 right-6 z-30">

                        <button
                            onclick="toggleWishlist({{ $product->id }}, this)"
                            class="w-16 h-16 rounded-[24px]
                                   bg-white/90 backdrop-blur-2xl
                                   border border-white
                                   shadow-[0_15px_50px_rgba(15,23,42,0.12)]
                                   flex items-center justify-center
                                   text-2xl hover:scale-110 transition duration-300">

                            ❤️

                        </button>

                    </div>

                </div>

            </div>

            <!-- INFO SIDE -->
            <div class="relative rounded-[50px]
                        border border-white/70
                        bg-white/75
                        backdrop-blur-3xl
                        shadow-[0_40px_120px_rgba(15,23,42,0.06)]
                        p-8 lg:p-12 overflow-hidden">

                <!-- glow -->
                <div class="absolute top-0 right-0 w-[350px] h-[350px] bg-sky-200/20 blur-3xl rounded-full"></div>

                <div class="relative z-10">

                    <!-- CATEGORY -->
                    <div class="mb-6">

                        <span class="inline-flex items-center gap-3
                                     px-5 py-3 rounded-2xl
                                     bg-sky-50 border border-sky-100
                                     text-sky-700
                                     uppercase tracking-[0.2em]
                                     text-xs font-black">

                            <i class="fa-solid fa-gem"></i>

                            {{ $product->category->name ?? 'Luxury Product' }}

                        </span>

                    </div>

                    <!-- TITLE -->
                    <h1 class="text-6xl xl:text-7xl
                               leading-[0.9]
                               tracking-[-0.06em]
                               font-black text-gray-900 mb-7">

                        {{ $product->name }}

                    </h1>

                    <!-- SUB -->
                    <p class="text-xl text-gray-500 leading-relaxed mb-8">

                        Crafted with premium quality and designed for people
                        who appreciate luxury, elegance, and modern lifestyle.

                    </p>

                    <!-- STATS -->
                    @php
                        $avg = round($product->reviews->avg('rating'), 1);
                        $count = $product->reviews->count();
                    @endphp

                    <div class="flex flex-wrap items-center gap-5 mb-10">

                        <!-- rating -->
                        <div class="flex items-center gap-3
                                    px-5 py-4 rounded-3xl
                                    bg-[#fafafa]
                                    border border-gray-100">

                            <div class="flex text-yellow-400 text-xl">

                                {!! str_repeat('⭐', round($avg)) !!}

                            </div>

                            <span class="font-bold text-gray-700">

                                {{ $avg }} Rating

                            </span>

                        </div>

                        <!-- reviews -->
                        <div class="px-5 py-4 rounded-3xl
                                    bg-[#fafafa]
                                    border border-gray-100
                                    font-bold text-gray-700">

                            {{ $count }} Reviews

                        </div>

                        <!-- stock -->
                        <div class="px-5 py-4 rounded-3xl
                                    bg-[#fafafa]
                                    border border-gray-100
                                    font-bold text-gray-700">

                            {{ $product->stock }} In Stock

                        </div>

                    </div>

                    <!-- PRICE -->
                    <div class="flex items-end gap-5 mb-12">

                        @if($product->discount_price)

                            <div>

                                <p class="text-2xl text-gray-400 line-through mb-2">

                                    ${{ $product->price }}

                                </p>

                                <h2 class="text-7xl font-black text-gray-900 leading-none">

                                    ${{ $product->discount_price }}

                                </h2>

                            </div>

                            <div class="mb-3 px-5 py-3 rounded-2xl
                                        bg-emerald-50 border border-emerald-100
                                        text-emerald-600 font-black">

                                Save
                                ${{ number_format($product->price - $product->discount_price, 0) }}

                            </div>

                        @else

                            <h2 class="text-7xl font-black text-gray-900 leading-none">

                                ${{ $product->price }}

                            </h2>

                        @endif

                    </div>

                    <!-- FEATURES -->
                    <div class="grid grid-cols-2 gap-5 mb-12">

                        <div class="rounded-[32px]
                                    bg-[#fafafa]
                                    border border-gray-100
                                    p-6">

                            <div class="w-14 h-14 rounded-2xl
                                        bg-sky-100
                                        flex items-center justify-center
                                        mb-5">

                                <i class="fa-solid fa-shield-halved text-sky-600 text-xl"></i>

                            </div>

                            <h4 class="font-black text-gray-900 mb-2">

                                Secure Checkout

                            </h4>

                            <p class="text-sm text-gray-500 leading-relaxed">

                                100% encrypted premium payment system.

                            </p>

                        </div>

                        <div class="rounded-[32px]
                                    bg-[#fafafa]
                                    border border-gray-100
                                    p-6">

                            <div class="w-14 h-14 rounded-2xl
                                        bg-orange-100
                                        flex items-center justify-center
                                        mb-5">

                                <i class="fa-solid fa-truck-fast text-orange-500 text-xl"></i>

                            </div>

                            <h4 class="font-black text-gray-900 mb-2">

                                Fast Shipping

                            </h4>

                            <p class="text-sm text-gray-500 leading-relaxed">

                                Express delivery with premium packaging.

                            </p>

                        </div>

                    </div>

                    <!-- DESCRIPTION -->
                    <div class="rounded-[36px]
                                bg-[#fafafa]
                                border border-gray-100
                                p-7 mb-12">

                        <h3 class="text-2xl font-black text-gray-900 mb-4">

                            Product Details

                        </h3>

                        <p class="text-gray-600 text-lg leading-relaxed">

                            {{ $product->description }}

                        </p>

                    </div>

                    <!-- ACTIONS -->
                    <div class="flex flex-col sm:flex-row gap-5">

                        <!-- add -->
                        <form method="POST"
                              action="/cart/add/{{ $product->id }}"
                              class="flex-1">

                            @csrf

                            <button class="group relative overflow-hidden
                                           w-full h-20 rounded-[30px]
                                           bg-gradient-to-r
                                           from-sky-500
                                           via-indigo-500
                                           to-blue-600
                                           text-white font-black text-xl
                                           shadow-[0_25px_80px_rgba(59,130,246,0.35)]
                                           hover:scale-[1.02]
                                           transition duration-300">

                                <span class="relative z-10 flex items-center justify-center gap-4">

                                    <i class="fa-solid fa-cart-plus
                                              group-hover:rotate-12
                                              transition duration-300"></i>

                                    Add To Cart

                                </span>

                                <div class="absolute inset-0
                                            bg-white/20
                                            translate-x-[-100%]
                                            group-hover:translate-x-[100%]
                                            transition duration-700">
                                </div>

                            </button>

                        </form>

                        <!-- buy now -->
                        <button class="h-20 px-10 rounded-[30px]
                                       bg-white border border-gray-200
                                       text-gray-900 font-black text-lg
                                       shadow-xl hover:scale-[1.02]
                                       transition">

                            Buy Now

                        </button>

                    </div>

                </div>

            </div>

        </div>

        <!-- REVIEWS -->
        <div class="grid xl:grid-cols-[0.8fr_1.2fr] gap-10 mt-16">

            <!-- ADD REVIEW -->
            @auth

            <div>

                <div class="rounded-[45px]
                            border border-white/70
                            bg-white/75
                            backdrop-blur-3xl
                            shadow-[0_30px_100px_rgba(15,23,42,0.06)]
                            p-8"
                     x-data="ratingComponent({{ $product->id }})">

                    <h2 class="text-4xl font-black text-gray-900 mb-7">

                        Write Review

                    </h2>

                    <!-- stars -->
                    <div class="flex gap-3 text-5xl mb-8 cursor-pointer">

                        <template x-for="star in 5">

                            <span
                                @mouseenter="hover = star"
                                @mouseleave="hover = 0"
                                @click="rate(star)"
                                :class="(hover >= star || rating >= star)
                                    ? 'text-yellow-400 scale-110'
                                    : 'text-gray-300'"
                                class="transition duration-200">

                                ⭐

                            </span>

                        </template>

                    </div>

                    <textarea x-model="comment"
                              class="w-full h-48 rounded-[30px]
                                     border border-gray-200
                                     bg-[#fafafa]
                                     p-6 outline-none
                                     text-lg
                                     focus:ring-4 focus:ring-sky-100"
                              placeholder="Share your premium experience..."></textarea>

                    <button @click="submit()"
                            class="mt-6 w-full h-16 rounded-[24px]
                                   bg-gradient-to-r
                                   from-emerald-500
                                   to-green-600
                                   text-white font-black text-lg
                                   shadow-[0_20px_60px_rgba(16,185,129,0.35)]
                                   hover:scale-[1.02]
                                   transition">

                        Submit Review

                    </button>

                </div>

            </div>

            @endauth

            <!-- REVIEW LIST -->
            <div>

                <div class="rounded-[45px]
                            border border-white/70
                            bg-white/75
                            backdrop-blur-3xl
                            shadow-[0_30px_100px_rgba(15,23,42,0.06)]
                            p-8 lg:p-10">

                    <div class="flex items-center justify-between mb-10">

                        <div>

                            <h2 class="text-5xl font-black text-gray-900 mb-2">

                                Customer Reviews

                            </h2>

                            <p class="text-gray-500 text-lg">

                                Trusted opinions from buyers

                            </p>

                        </div>

                    </div>

                    <div class="space-y-6">

                        @forelse($product->reviews as $review)

                            <div class="rounded-[32px]
                                        bg-[#fafafa]
                                        border border-gray-100
                                        p-7">

                                <div class="flex items-start justify-between mb-5">

                                    <div class="flex items-center gap-4">

                                        <div class="w-14 h-14 rounded-2xl
                                                    bg-gradient-to-br
                                                    from-sky-500
                                                    to-indigo-600
                                                    text-white font-black
                                                    flex items-center justify-center">

                                            {{ strtoupper(substr($review->user->name, 0, 1)) }}

                                        </div>

                                        <div>

                                            <h4 class="font-black text-lg text-gray-900">

                                                {{ $review->user->name }}

                                            </h4>

                                            <p class="text-sm text-gray-400">

                                                Verified Purchase

                                            </p>

                                        </div>

                                    </div>

                                    <div class="text-yellow-400 text-lg">

                                        {!! str_repeat('⭐', $review->rating) !!}

                                    </div>

                                </div>

                                <p class="text-gray-600 leading-relaxed text-lg">

                                    {{ $review->comment }}

                                </p>

                            </div>

                        @empty

                            <div class="text-center py-24">

                                <div class="text-8xl mb-6">
                                    💬
                                </div>

                                <h3 class="text-4xl font-black text-gray-900 mb-4">

                                    No Reviews Yet

                                </h3>

                                <p class="text-gray-500 text-lg">

                                    Be the first to review this luxury product.

                                </p>

                            </div>

                        @endforelse

                    </div>

                </div>

            </div>

        </div>

        <!-- ALSO BOUGHT -->
        @if($alsoBought->count())

        <div class="mt-24">

            <div class="mb-10">

                <h2 class="text-6xl font-black tracking-[-0.05em] text-gray-900 mb-3">

                    You May Also Like

                </h2>

                <p class="text-xl text-gray-500">

                    Curated premium recommendations for you

                </p>

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-8">

                @foreach($alsoBought as $item)

                    <a href="{{ route('products.show', $item->id) }}"
                       class="group rounded-[40px]
                              overflow-hidden
                              border border-white/70
                              bg-white/75
                              backdrop-blur-3xl
                              shadow-[0_25px_90px_rgba(15,23,42,0.06)]
                              hover:-translate-y-4
                              hover:shadow-[0_40px_120px_rgba(59,130,246,0.14)]
                              transition duration-700">

                        <!-- image -->
                        <div class="relative overflow-hidden">

                            <img src="{{ asset('storage/'.$item->image) }}"
                                 class="w-full h-80 object-cover
                                        group-hover:scale-110
                                        transition duration-[1800ms]">

                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent"></div>

                        </div>

                        <!-- content -->
                        <div class="p-7">

                            <h3 class="text-2xl font-black text-gray-900 mb-3 line-clamp-1">

                                {{ $item->name }}

                            </h3>

                            <p class="text-4xl font-black text-gray-900 mb-5">

                                ${{ $item->price }}

                            </p>

                            @if(isset($item->frequency))

                                <div class="inline-flex items-center gap-2
                                            px-4 py-3 rounded-2xl
                                            bg-emerald-50 border border-emerald-100
                                            text-emerald-600 font-bold text-sm">

                                    🔥 Bought {{ $item->frequency }} times together

                                </div>

                            @endif

                        </div>

                    </a>

                @endforeach

            </div>

        </div>

        @endif

    </div>

</div>

<!-- REVIEW SCRIPT -->
<script>

function ratingComponent(productId) {

    return {

        rating: 0,
        hover: 0,
        comment: '',

        rate(value) {

            this.rating = value;

        },

        submit() {

            if (!this.rating) {

                alert('Please select rating');
                return;

            }

            fetch(`/products/${productId}/review`, {

                method: 'POST',

                headers: {

                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'

                },

                body: JSON.stringify({

                    rating: this.rating,
                    comment: this.comment

                })

            }).then(() => {

                location.reload();

            });

        }

    }

}

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
            ? '❤️'
            : '🤍';

    });

}

</script>

</x-app-layout>