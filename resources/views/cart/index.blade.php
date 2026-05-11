<x-app-layout>

    <div class="min-h-screen bg-gradient-to-br from-slate-100 via-blue-50 to-indigo-100 py-12">

        <!-- BG EFFECT -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">

            <div class="absolute top-[-200px] left-[-120px] w-[500px] h-[500px] bg-blue-400/20 blur-3xl rounded-full"></div>

            <div class="absolute bottom-[-250px] right-[-120px] w-[500px] h-[500px] bg-purple-400/20 blur-3xl rounded-full"></div>

        </div>

        <div class="relative max-w-7xl mx-auto px-4 lg:px-8">

            <!-- HEADER -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-10">

                <div>

                    <h1 class="text-5xl font-black text-gray-900 leading-tight">
                        Your Shopping Cart
                    </h1>

                    <p class="text-gray-500 text-lg mt-3">
                        Review your items and complete your premium checkout experience.
                    </p>

                </div>

                @if(count($cart) > 0)

                    <div class="flex items-center gap-4">

                        <div class="px-6 py-4 rounded-3xl bg-white/80 backdrop-blur-xl border border-white/50 shadow-xl">

                            <p class="text-sm text-gray-500 font-semibold">
                                Items
                            </p>

                            <h3 class="text-3xl font-black text-gray-900">
                                {{ count($cart) }}
                            </h3>

                        </div>

                        <div class="px-6 py-4 rounded-3xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-2xl shadow-blue-500/30">

                            <p class="text-sm font-semibold opacity-80">
                                Estimated Total
                            </p>

                            <h3 class="text-3xl font-black">
                                ${{ collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']) }}
                            </h3>

                        </div>

                    </div>

                @endif

            </div>

            @if(count($cart) > 0)

                @php

                    $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

                    $discount = 0;

                    $coupon = session('coupon_id')
                        ? \App\Models\Coupon::find(session('coupon_id'))
                        : null;

                    if($coupon) {

                        if($coupon->type == 'fixed') {
                            $discount = $coupon->value;
                        } else {
                            $discount = ($total * $coupon->value) / 100;
                        }

                    }

                    $final = max($total - $discount, 0);

                @endphp

                <div class="grid lg:grid-cols-[1.4fr_0.6fr] gap-8">

                    <!-- CART ITEMS -->
                    <div class="space-y-6">

                        @foreach($cart as $id => $item)

                            <div class="group relative overflow-hidden rounded-[32px] bg-white/80 backdrop-blur-2xl border border-white/40 shadow-[0_20px_60px_rgba(0,0,0,0.08)] hover:shadow-[0_25px_70px_rgba(59,130,246,0.15)] transition duration-500">

                                <!-- GLOW -->
                                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-500 bg-gradient-to-r from-blue-500/5 to-purple-500/5"></div>

                                <div class="relative p-6">

                                    <div class="flex flex-col xl:flex-row xl:items-center gap-6">

                                        <!-- IMAGE -->
                                        <div class="relative">

                                            <div class="absolute inset-0 bg-blue-500/20 blur-2xl rounded-3xl"></div>

                                            <img src="{{ asset('storage/' . $item['image']) }}"
                                                 class="relative w-full xl:w-36 h-36 object-cover rounded-3xl shadow-2xl">

                                        </div>

                                        <!-- INFO -->
                                        <div class="flex-1">

                                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5">

                                                <div>

                                                    <h2 class="text-2xl font-black text-gray-900 mb-2">
                                                        {{ $item['name'] }}
                                                    </h2>

                                                    <div class="flex items-center gap-3 flex-wrap">

                                                        <span class="px-4 py-2 rounded-2xl bg-blue-50 text-blue-700 text-sm font-bold">
                                                            Premium Product
                                                        </span>

                                                        <span class="px-4 py-2 rounded-2xl bg-gray-100 text-gray-700 text-sm font-bold">
                                                            ${{ $item['price'] }}
                                                        </span>

                                                        @if(isset($item['stock']) && $item['stock'] <= 5)

                                                            <span class="px-4 py-2 rounded-2xl bg-red-100 text-red-600 text-sm font-black animate-pulse">
                                                                Only {{ $item['stock'] }} Left
                                                            </span>

                                                        @endif

                                                    </div>

                                                    @auth

                                                        <form method="POST"
                                                              action="/save-for-later/{{ $id }}"
                                                              class="mt-5">

                                                            @csrf

                                                            <button class="group inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">

                                                                <i class="fa-solid fa-heart group-hover:scale-125 transition"></i>

                                                                Save for later

                                                            </button>

                                                        </form>

                                                    @endauth

                                                </div>

                                                <!-- PRICE -->
                                                <div class="text-left lg:text-right">

                                                    <p class="text-sm text-gray-500 font-semibold mb-1">
                                                        Total
                                                    </p>

                                                    <h3 class="text-3xl font-black text-gray-900">
                                                        ${{ $item['price'] * $item['quantity'] }}
                                                    </h3>

                                                </div>

                                            </div>

                                            <!-- ACTIONS -->
                                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5 mt-8">

                                                <!-- QUANTITY -->
                                                <div class="flex items-center gap-4">

                                                    <div class="flex items-center bg-gray-100 rounded-2xl p-2 shadow-inner">

                                                        <form method="POST"
                                                              action="/cart/update/{{ $id }}">
                                                            @csrf

                                                            <input type="hidden"
                                                                   name="action"
                                                                   value="decrease">

                                                            <button class="w-11 h-11 rounded-xl bg-white hover:bg-gray-200 transition font-black text-lg shadow">
                                                                -
                                                            </button>

                                                        </form>

                                                        <span class="w-14 text-center text-xl font-black text-gray-900">
                                                            {{ $item['quantity'] }}
                                                        </span>

                                                        <form method="POST"
                                                              action="/cart/update/{{ $id }}">
                                                            @csrf

                                                            <input type="hidden"
                                                                   name="action"
                                                                   value="increase">

                                                            <button class="w-11 h-11 rounded-xl bg-white hover:bg-gray-200 transition font-black text-lg shadow">
                                                                +
                                                            </button>

                                                        </form>

                                                    </div>

                                                </div>

                                                <!-- REMOVE -->
                                                <form method="POST"
                                                      action="/cart/{{ $id }}">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button class="group flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-red-50 hover:bg-red-100 text-red-500 font-bold transition duration-300">

                                                        <i class="fa-solid fa-trash-can group-hover:rotate-12 transition"></i>

                                                        Remove

                                                    </button>

                                                </form>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                    <!-- SUMMARY -->
                    <div class="space-y-6">

                        <!-- SUMMARY CARD -->
                        <div class="sticky top-32 rounded-[32px] bg-white/80 backdrop-blur-2xl border border-white/50 shadow-[0_20px_60px_rgba(0,0,0,0.08)] overflow-hidden">

                            <!-- TOP -->
                            <div class="relative p-8 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 text-white overflow-hidden">

                                <div class="absolute top-0 right-0 w-52 h-52 bg-white/10 rounded-full blur-3xl"></div>

                                <div class="relative">

                                    <div class="flex items-center gap-3 mb-4">

                                        <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center backdrop-blur-xl">

                                            <i class="fa-solid fa-credit-card text-2xl"></i>

                                        </div>

                                        <div>

                                            <h2 class="text-3xl font-black">
                                                Order Summary
                                            </h2>

                                            <p class="text-blue-100 text-sm">
                                                Secure premium checkout
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- CONTENT -->
                            <div class="p-8">

                                @if($autoCoupon)

                                    <div class="mb-6 rounded-3xl bg-green-50 border border-green-200 p-5">

                                        <div class="flex items-start gap-4">

                                            <div class="w-12 h-12 rounded-2xl bg-green-500 text-white flex items-center justify-center shadow-lg">

                                                <i class="fa-solid fa-ticket"></i>

                                            </div>

                                            <div class="flex-1">

                                                <h3 class="font-black text-green-700 text-lg">
                                                    Coupon Available
                                                </h3>

                                                <p class="text-green-600 mt-1">
                                                    Use code:
                                                    <strong>{{ $autoCoupon->code }}</strong>
                                                </p>

                                                <form method="POST"
                                                      action="/apply-coupon"
                                                      class="mt-3">

                                                    @csrf

                                                    <input type="hidden"
                                                           name="code"
                                                           value="{{ $autoCoupon->code }}">

                                                    <button class="text-sm font-bold text-green-700 underline">
                                                        Apply Now
                                                    </button>

                                                </form>

                                            </div>

                                        </div>

                                    </div>

                                @endif

                                <!-- COUPON -->
                                <form method="POST"
                                      action="/apply-coupon"
                                      class="flex gap-3 mb-8">

                                    @csrf

                                    <input type="text"
                                           name="code"
                                           placeholder="Coupon code"
                                           class="flex-1 h-14 rounded-2xl border border-gray-200 bg-gray-50 px-5 focus:border-green-500 focus:ring-4 focus:ring-green-100 outline-none">

                                    <button class="px-6 rounded-2xl bg-gradient-to-r from-green-500 to-emerald-600 text-white font-black shadow-xl shadow-green-500/30 hover:scale-105 transition">

                                        Apply

                                    </button>

                                </form>

                                <!-- PRICE DETAILS -->
                                <div class="space-y-5">

                                    <div class="flex items-center justify-between text-gray-600">

                                        <span class="font-semibold">
                                            Subtotal
                                        </span>

                                        <span class="font-bold">
                                            ${{ $total }}
                                        </span>

                                    </div>

                                    @if($coupon)

                                        <div class="flex items-center justify-between text-green-600">

                                            <span class="font-semibold">
                                                Discount ({{ $coupon->code }})
                                            </span>

                                            <span class="font-black">
                                                -${{ $discount }}
                                            </span>

                                        </div>

                                    @endif

                                    <div class="border-t pt-5 flex items-center justify-between">

                                        <span class="text-xl font-black text-gray-900">
                                            Final Total
                                        </span>

                                        <span class="text-3xl font-black bg-gradient-to-r from-blue-700 to-indigo-700 bg-clip-text text-transparent">
                                            ${{ $final }}
                                        </span>

                                    </div>

                                </div>

                                <!-- FORM -->
                                <form method="POST"
                                      action="/checkout"
                                      class="space-y-4 mt-8">

                                    @csrf

                                    <input type="text"
                                           name="full_name"
                                           placeholder="Full Name"
                                           class="w-full h-14 rounded-2xl border border-gray-200 bg-gray-50 px-5 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none"
                                           required>

                                    <input type="text"
                                           name="phone"
                                           placeholder="Phone Number"
                                           class="w-full h-14 rounded-2xl border border-gray-200 bg-gray-50 px-5 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none"
                                           required>

                                    <input type="text"
                                           name="address_line"
                                           placeholder="Address"
                                           class="w-full h-14 rounded-2xl border border-gray-200 bg-gray-50 px-5 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none"
                                           required>

                                    <div class="grid grid-cols-2 gap-4">

                                        <input type="text"
                                               name="city"
                                               placeholder="City"
                                               class="w-full h-14 rounded-2xl border border-gray-200 bg-gray-50 px-5 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none"
                                               required>

                                        <input type="text"
                                               name="postal_code"
                                               placeholder="Postal Code"
                                               class="w-full h-14 rounded-2xl border border-gray-200 bg-gray-50 px-5 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none">

                                    </div>

                                    <button class="group relative overflow-hidden w-full h-16 rounded-3xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white text-lg font-black shadow-2xl shadow-blue-500/30 hover:scale-[1.02] transition duration-300">

                                        <span class="relative z-10 flex items-center justify-center gap-3">

                                            <i class="fa-solid fa-lock"></i>

                                            Complete Checkout

                                        </span>

                                        <div class="absolute inset-0 bg-white/20 translate-x-[-100%] group-hover:translate-x-[100%] transition duration-700"></div>

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- SUGGESTIONS -->
                @if(isset($suggestions) && $suggestions->count())

                    <div class="mt-20">

                        <div class="flex items-center justify-between mb-8">

                            <div>

                                <h2 class="text-4xl font-black text-gray-900">
                                    You May Also Like
                                </h2>

                                <p class="text-gray-500 mt-2">
                                    Curated premium recommendations for you.
                                </p>

                            </div>

                        </div>

                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">

                            @foreach($suggestions as $p)

                                <a href="/products/{{ $p->id }}"
                                   class="group relative rounded-[30px] overflow-hidden bg-white/80 backdrop-blur-2xl border border-white/40 shadow-xl hover:shadow-2xl hover:-translate-y-2 transition duration-500">

                                    <div class="overflow-hidden">

                                        <img src="{{ asset('storage/'.$p->image) }}"
                                             class="h-56 w-full object-cover group-hover:scale-110 transition duration-700">

                                    </div>

                                    <div class="p-5">

                                        <h3 class="font-black text-gray-900 text-lg mb-2 line-clamp-1">
                                            {{ $p->name }}
                                        </h3>

                                        <div class="flex items-center justify-between">

                                            <span class="text-2xl font-black text-blue-700">
                                                ${{ $p->price }}
                                            </span>

                                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:rotate-12 transition">

                                                <i class="fa-solid fa-arrow-right"></i>

                                            </div>

                                        </div>

                                    </div>

                                </a>

                            @endforeach

                        </div>

                    </div>

                @endif

            @else

                <!-- EMPTY -->
                <div class="flex items-center justify-center min-h-[70vh]">

                    <div class="max-w-xl w-full rounded-[40px] bg-white/80 backdrop-blur-2xl border border-white/40 shadow-[0_20px_80px_rgba(0,0,0,0.08)] p-12 text-center">

                        <div class="w-32 h-32 mx-auto rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-2xl shadow-blue-500/30 mb-8">

                            <i class="fa-solid fa-cart-shopping text-white text-5xl"></i>

                        </div>

                        <h2 class="text-5xl font-black text-gray-900 mb-5">
                            Your Cart Is Empty
                        </h2>

                        <p class="text-gray-500 text-lg leading-relaxed mb-10">
                            Looks like you haven’t added anything yet.
                            Start exploring our premium products now.
                        </p>

                        <a href="/"
                           class="group relative inline-flex items-center gap-3 overflow-hidden px-10 py-5 rounded-3xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white text-lg font-black shadow-2xl shadow-blue-500/30 hover:scale-105 transition duration-300">

                            <span class="relative z-10 flex items-center gap-3">

                                <i class="fa-solid fa-bag-shopping"></i>

                                Continue Shopping

                            </span>

                            <div class="absolute inset-0 bg-white/20 translate-x-[-100%] group-hover:translate-x-[100%] transition duration-700"></div>

                        </a>

                    </div>

                </div>

            @endif

        </div>

    </div>

</x-app-layout>