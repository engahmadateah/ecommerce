<nav class="sticky top-0 z-50 w-full bg-white/70 backdrop-blur-2xl border-b border-white/20 shadow-[0_8px_40px_rgba(0,0,0,0.08)]">

    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        <div class="flex items-center justify-between h-24">

            <!-- LEFT -->
            <div class="flex items-center gap-14">

                <!-- LOGO -->
                <a href="/"
                   class="group flex items-center gap-4 transition-all duration-500 hover:scale-105">

                    <div class="relative">

                        <div class="absolute inset-0 bg-blue-500/20 blur-2xl rounded-full scale-150 opacity-0 group-hover:opacity-100 transition duration-500"></div>

                        <div class="relative w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 p-[2px] shadow-xl shadow-blue-500/30">

                            <div class="w-full h-full rounded-2xl bg-white flex items-center justify-center overflow-hidden">

                                <img src="{{ asset('images/logo.png') }}"
                                     class="w-9 h-9 object-contain transition duration-500 group-hover:rotate-12">

                            </div>

                        </div>
                    </div>

                    <div class="flex flex-col leading-none">

                        <span class="text-3xl font-black tracking-tight bg-gradient-to-r from-blue-700 via-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            MyStore
                        </span>

                        <span class="text-[10px] uppercase tracking-[0.35em] text-gray-400 font-bold mt-1">
                            Premium Shopping
                        </span>

                    </div>
                </a>

                <!-- NAV LINKS -->
                <div class="hidden xl:flex items-center gap-2">

                    <!-- HOME -->
                    <a href="/"
                       class="group relative px-5 py-3 rounded-2xl overflow-hidden
                       {{ request()->is('/') ? 'bg-blue-50 shadow-lg shadow-blue-100' : '' }}">

                        <div class="absolute inset-0 bg-blue-50 scale-0 group-hover:scale-100 rounded-2xl transition duration-300"></div>

                        <div class="relative flex items-center gap-2 font-semibold
                        {{ request()->is('/') ? 'text-blue-700' : 'text-gray-700 group-hover:text-blue-700' }}">

                            <i class="fa-solid fa-house text-sm transition duration-300 group-hover:-translate-y-1"></i>

                            <span>Home</span>

                        </div>
                    </a>

                    @auth

                    <!-- CART -->
                    <a href="/cart"
                       class="group relative px-5 py-3 rounded-2xl overflow-hidden
                       {{ request()->is('cart*') ? 'bg-blue-50 shadow-lg shadow-blue-100' : '' }}">

                        <div class="absolute inset-0 bg-blue-50 scale-0 group-hover:scale-100 rounded-2xl transition duration-300"></div>

                        <div class="relative flex items-center gap-2 font-semibold
                        {{ request()->is('cart*') ? 'text-blue-700' : 'text-gray-700 group-hover:text-blue-700' }}">

                            <i class="fa-solid fa-cart-shopping text-sm transition duration-300 group-hover:scale-125 group-hover:rotate-6"></i>

                            <span>Cart</span>

                            @if(count(session('cart', [])) > 0)
                                <span class="flex items-center justify-center min-w-[20px] h-5 px-1 rounded-full bg-gradient-to-r from-red-500 to-pink-500 text-white text-[10px] font-black shadow-lg shadow-red-500/30 animate-pulse">
                                    {{ count(session('cart', [])) }}
                                </span>
                            @endif

                        </div>
                    </a>

                    <!-- ORDERS -->
                    <a href="/orders"
                       class="group relative px-5 py-3 rounded-2xl overflow-hidden
                       {{ request()->is('orders*') ? 'bg-blue-50 shadow-lg shadow-blue-100' : '' }}">

                        <div class="absolute inset-0 bg-blue-50 scale-0 group-hover:scale-100 rounded-2xl transition duration-300"></div>

                        <div class="relative flex items-center gap-2 font-semibold
                        {{ request()->is('orders*') ? 'text-blue-700' : 'text-gray-700 group-hover:text-blue-700' }}">

                            <i class="fa-solid fa-box text-sm transition duration-300 group-hover:rotate-12"></i>

                            <span>Orders</span>

                        </div>
                    </a>

                    <!-- WISHLIST -->
                    <a href="/wishlist"
                       class="group relative px-5 py-3 rounded-2xl overflow-hidden
                       {{ request()->is('wishlist*') ? 'bg-pink-50 shadow-lg shadow-pink-100' : '' }}">

                        <div class="absolute inset-0 bg-pink-50 scale-0 group-hover:scale-100 rounded-2xl transition duration-300"></div>

                        <div class="relative flex items-center gap-2 font-semibold
                        {{ request()->is('wishlist*') ? 'text-pink-600' : 'text-gray-700 group-hover:text-pink-600' }}">

                            <i class="fa-solid fa-heart text-sm transition duration-300 group-hover:scale-125 animate-pulse"></i>

                            <span>Wishlist</span>

                            @if(auth()->user()->wishlist->count() > 0)
                                <span class="flex items-center justify-center min-w-[20px] h-5 px-1 rounded-full bg-gradient-to-r from-pink-500 to-rose-500 text-white text-[10px] font-black shadow-lg shadow-pink-500/30">
                                    {{ auth()->user()->wishlist->count() }}
                                </span>
                            @endif

                        </div>
                    </a>

                    @endauth

                    <!-- BUNDLES -->
                    <a href="/packages"
                       class="group relative px-5 py-3 rounded-2xl overflow-hidden
                       {{ request()->is('packages*') ? 'bg-purple-50 shadow-lg shadow-purple-100' : '' }}">

                        <div class="absolute inset-0 bg-purple-50 scale-0 group-hover:scale-100 rounded-2xl transition duration-300"></div>

                        <div class="relative flex items-center gap-2 font-semibold
                        {{ request()->is('packages*') ? 'text-purple-700' : 'text-gray-700 group-hover:text-purple-700' }}">

                            <i class="fa-solid fa-layer-group text-sm transition duration-300 group-hover:rotate-180"></i>

                            <span>Bundles</span>

                        </div>
                    </a>

                    <!-- COUPONS -->
                    <a href="/coupons"
                       class="group relative px-5 py-3 rounded-2xl overflow-hidden
                       {{ request()->is('coupons*') ? 'bg-emerald-50 shadow-lg shadow-emerald-100' : '' }}">

                        <div class="absolute inset-0 bg-emerald-50 scale-0 group-hover:scale-100 rounded-2xl transition duration-300"></div>

                        <div class="relative flex items-center gap-2 font-semibold
                        {{ request()->is('coupons*') ? 'text-emerald-700' : 'text-gray-700 group-hover:text-emerald-700' }}">

                            <i class="fa-solid fa-ticket text-sm transition duration-300 group-hover:-rotate-12"></i>

                            <span>Coupons</span>

                        </div>
                    </a>

                    <!-- CONTACT -->
                    <a href="{{ route('contact') }}"
                       class="group relative px-5 py-3 rounded-2xl overflow-hidden
                       {{ request()->is('contact*') ? 'bg-cyan-50 shadow-lg shadow-cyan-100' : '' }}">

                        <div class="absolute inset-0 bg-cyan-50 scale-0 group-hover:scale-100 rounded-2xl transition duration-300"></div>

                        <div class="relative flex items-center gap-2 font-semibold
                        {{ request()->is('contact*') ? 'text-cyan-700' : 'text-gray-700 group-hover:text-cyan-700' }}">

                            <i class="fa-solid fa-envelope text-sm transition duration-300 group-hover:translate-x-1"></i>

                            <span>Contact</span>

                        </div>
                    </a>

                    <!-- DEALS -->
                    <a href="{{ route('products.deals') }}"
                       class="relative flex items-center gap-2 px-6 py-3 rounded-2xl text-white font-bold shadow-xl transition duration-300 hover:scale-105
                       {{ request()->is('deals*') ? 'bg-gradient-to-r from-rose-600 to-orange-600 ring-4 ring-rose-200' : 'bg-gradient-to-r from-rose-500 to-orange-500 shadow-rose-500/30' }}">

                        <i class="fa-solid fa-bolt animate-bounce"></i>

                        <span>Today's Deals</span>

                    </a>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="flex items-center gap-4">

                @auth

                    @php
                        $user = auth()->user();
                        $level = $user->level ?? 'bronze';
                        $points = $user->points ?? 0;
                    @endphp

                    <!-- USER CARD -->
                    <div class="hidden lg:flex items-center gap-4 bg-white/80 border border-gray-100 shadow-xl shadow-black/[0.03] rounded-2xl px-4 py-2 hover:scale-105 transition duration-300">

                        <div class="relative">

                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">

                                <i class="fa-solid fa-user"></i>

                            </div>

                            <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white animate-pulse
                                @if($level === 'gold') bg-yellow-400
                                @elseif($level === 'silver') bg-gray-400
                                @else bg-orange-400 @endif">
                            </div>

                        </div>

                        <div class="flex flex-col">

                            <span class="font-bold text-sm text-gray-800">
                                {{ Str::limit($user->name, 14) }}
                            </span>

                            <div class="flex items-center gap-2 mt-1">

                                <span class="flex items-center gap-1 text-[11px] font-black text-amber-500">

                                    <i class="fa-solid fa-star text-[10px] animate-spin"></i>

                                    {{ $points }}

                                </span>

                                <span class="text-[10px] uppercase font-black px-2 py-1 rounded-lg text-white
                                    @if($level === 'gold') bg-gradient-to-r from-yellow-400 to-yellow-600
                                    @elseif($level === 'silver') bg-gradient-to-r from-gray-300 to-gray-500
                                    @else bg-gradient-to-r from-orange-400 to-rose-400 @endif">

                                    {{ $level }}

                                </span>

                            </div>

                        </div>

                    </div>

                    <!-- PROFILE -->
                    <a href="{{ route('profile.edit') }}"
                       class="group relative w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 flex items-center justify-center hover:scale-110 transition duration-300 shadow-lg shadow-blue-500/10">

                        <i class="fa-solid fa-gear text-blue-600 group-hover:rotate-180 transition duration-500"></i>

                    </a>

                    <!-- LOGOUT -->
                    <form method="POST" action="/logout">
                        @csrf

                        <button class="group relative w-12 h-12 rounded-2xl bg-gradient-to-br from-red-50 to-rose-50 border border-red-100 flex items-center justify-center hover:scale-110 transition duration-300 shadow-lg shadow-red-500/10">

                            <i class="fa-solid fa-right-from-bracket text-red-500 group-hover:-translate-x-1 transition duration-300"></i>

                        </button>

                    </form>

                @else

                    <!-- GUEST BUTTONS -->
                    <div class="flex items-center gap-3">

                        <a href="/login"
                           class="px-5 py-2.5 rounded-2xl text-sm font-bold text-gray-700 hover:bg-gray-100 transition duration-300">
                            Login
                        </a>

                        <a href="/register"
                           class="group relative overflow-hidden px-7 py-3 rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white text-sm font-black shadow-2xl shadow-blue-500/30 hover:scale-105 transition duration-300">

                            <span class="relative z-10">
                                Create Account
                            </span>

                            <div class="absolute inset-0 bg-white/20 translate-x-[-100%] group-hover:translate-x-[100%] transition duration-700"></div>

                        </a>

                    </div>

                @endauth

                <!-- MOBILE -->
                <button class="xl:hidden w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-700 hover:bg-gray-200 transition duration-300 hover:rotate-90">

                    <i class="fa-solid fa-bars text-lg"></i>

                </button>

            </div>

        </div>

    </div>

</nav>