<x-guest-layout>

    <div class="relative min-h-screen overflow-hidden bg-[#0b1120]">

        <!-- BACKGROUND -->
        <div class="absolute inset-0">

            <!-- Gradient -->
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,#2563eb22,transparent_35%),radial-gradient(circle_at_bottom_right,#7c3aed22,transparent_35%)]"></div>

            <!-- Grid -->
            <div class="absolute inset-0 opacity-[0.03]"
                 style="background-image: linear-gradient(to right, white 1px, transparent 1px), linear-gradient(to bottom, white 1px, transparent 1px); background-size: 60px 60px;">
            </div>

            <!-- Glow -->
            <div class="absolute top-[-200px] left-[-150px] w-[500px] h-[500px] bg-blue-500/20 rounded-full blur-3xl"></div>

            <div class="absolute bottom-[-250px] right-[-150px] w-[500px] h-[500px] bg-purple-500/20 rounded-full blur-3xl"></div>

        </div>

        <!-- CONTAINER -->
        <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-10">

            <div class="w-full max-w-7xl grid lg:grid-cols-2 rounded-[40px] overflow-hidden border border-white/10 bg-white/5 backdrop-blur-2xl shadow-[0_30px_100px_rgba(0,0,0,0.45)]">

                <!-- LEFT -->
                <div class="relative hidden lg:flex flex-col justify-between p-20 overflow-hidden bg-gradient-to-br from-[#111827] via-[#0f172a] to-[#020617]">

                    <!-- Decorative -->
                    <div class="absolute inset-0">

                        <div class="absolute top-0 left-0 w-[400px] h-[400px] bg-blue-500/10 rounded-full blur-3xl"></div>

                        <div class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-indigo-500/10 rounded-full blur-3xl"></div>

                    </div>

                    <!-- TOP -->
                    <div class="relative z-10">

                        <!-- Logo -->
                        <div class="flex items-center gap-5 mb-24">

                            <div class="relative">

                                <div class="absolute inset-0 bg-blue-500 blur-2xl opacity-40"></div>

                                <div class="relative w-20 h-20 rounded-[28px] bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 p-[2px] shadow-2xl shadow-blue-500/30">

                                    <div class="w-full h-full rounded-[26px] bg-[#0f172a] flex items-center justify-center">

                                        <img src="{{ asset('images/logo.png') }}"
                                             class="w-11 h-11 object-contain">

                                    </div>

                                </div>

                            </div>

                            <div>

                                <h1 class="text-5xl font-black tracking-tight text-white">
                                    MyStore
                                </h1>

                                <p class="mt-2 text-sm uppercase tracking-[0.45em] text-blue-200">
                                    Premium Shopping
                                </p>

                            </div>

                        </div>

                        <!-- Text -->
                        <div class="max-w-xl">

                            <h2 class="text-7xl font-black leading-[0.95] text-white">

                                Join The
                                Future Of
                                Shopping.

                            </h2>

                            <p class="mt-8 text-xl leading-relaxed text-gray-300">

                                Premium deals, instant checkout,
                                exclusive rewards, luxury experience
                                and a modern platform built for the next generation.

                            </p>

                        </div>

                    </div>

                    <!-- FEATURES -->
                    <div class="relative z-10 grid grid-cols-3 gap-5">

                        <!-- Card -->
                        <div class="group rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl hover:bg-white/10 transition duration-500">

                            <div class="w-14 h-14 rounded-2xl bg-pink-500/15 flex items-center justify-center mb-5 group-hover:scale-110 transition duration-300">

                                <i class="fa-solid fa-gift text-2xl text-pink-400"></i>

                            </div>

                            <h3 class="text-white font-bold text-lg">
                                Exclusive Deals
                            </h3>

                            <p class="mt-2 text-sm text-gray-400 leading-relaxed">
                                Daily premium discounts.
                            </p>

                        </div>

                        <!-- Card -->
                        <div class="group rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl hover:bg-white/10 transition duration-500">

                            <div class="w-14 h-14 rounded-2xl bg-yellow-500/15 flex items-center justify-center mb-5 group-hover:scale-110 transition duration-300">

                                <i class="fa-solid fa-star text-2xl text-yellow-400"></i>

                            </div>

                            <h3 class="text-white font-bold text-lg">
                                Reward Points
                            </h3>

                            <p class="mt-2 text-sm text-gray-400 leading-relaxed">
                                Earn points on every order.
                            </p>

                        </div>

                        <!-- Card -->
                        <div class="group rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-xl hover:bg-white/10 transition duration-500">

                            <div class="w-14 h-14 rounded-2xl bg-cyan-500/15 flex items-center justify-center mb-5 group-hover:scale-110 transition duration-300">

                                <i class="fa-solid fa-bolt text-2xl text-cyan-400"></i>

                            </div>

                            <h3 class="text-white font-bold text-lg">
                                Fast Checkout
                            </h3>

                            <p class="mt-2 text-sm text-gray-400 leading-relaxed">
                                Lightning-fast shopping flow.
                            </p>

                        </div>

                    </div>

                </div>

                <!-- RIGHT -->
                <div class="relative bg-white px-6 py-12 sm:px-10 lg:px-20 flex items-center">

                    <div class="w-full max-w-xl mx-auto">

                        <!-- MOBILE LOGO -->
                        <div class="lg:hidden flex flex-col items-center text-center mb-10">

                            <div class="relative mb-5">

                                <div class="absolute inset-0 bg-blue-500 blur-2xl opacity-30"></div>

                                <div class="relative w-24 h-24 rounded-[30px] bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 flex items-center justify-center shadow-2xl shadow-blue-500/30">

                                    <img src="{{ asset('images/logo.png') }}"
                                         class="w-12 h-12 object-contain">

                                </div>

                            </div>

                            <h1 class="text-5xl font-black bg-gradient-to-r from-blue-700 to-indigo-700 bg-clip-text text-transparent">
                                MyStore
                            </h1>

                            <p class="mt-3 text-gray-500">
                                Premium Shopping Experience
                            </p>

                        </div>

                        <!-- HEADER -->
                        <div class="mb-10">

                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 text-blue-700 text-sm font-bold border border-blue-100 mb-6">

                                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>

                                Welcome To MyStore

                            </span>

                            <h2 class="text-5xl sm:text-6xl font-black text-gray-900 leading-tight">
                                Create
                                Account
                            </h2>

                            <p class="mt-5 text-lg text-gray-500 leading-relaxed">
                                Start your premium shopping journey today.
                            </p>

                        </div>

                        <!-- FORM -->
                        <form method="POST"
                              action="{{ route('register') }}"
                              class="space-y-6">

                            @csrf

                            <!-- NAME -->
                            <div>

                                <label class="block text-sm font-black text-gray-700 mb-3">
                                    Full Name
                                </label>

                                <div class="group relative">

                                    <div class="absolute inset-y-0 left-5 flex items-center text-gray-400 group-focus-within:text-blue-600 transition">

                                        <i class="fa-solid fa-user"></i>

                                    </div>

                                    <input type="text"
                                           name="name"
                                           value="{{ old('name') }}"
                                           required
                                           autofocus
                                           autocomplete="name"
                                           placeholder="Enter your full name"
                                           class="w-full h-16 rounded-3xl border border-gray-200 bg-gray-50 pl-14 pr-5 text-gray-900 placeholder:text-gray-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100 outline-none transition duration-300">

                                </div>

                                <x-input-error :messages="$errors->get('name')" class="mt-2" />

                            </div>

                            <!-- EMAIL -->
                            <div>

                                <label class="block text-sm font-black text-gray-700 mb-3">
                                    Email Address
                                </label>

                                <div class="group relative">

                                    <div class="absolute inset-y-0 left-5 flex items-center text-gray-400 group-focus-within:text-blue-600 transition">

                                        <i class="fa-solid fa-envelope"></i>

                                    </div>

                                    <input type="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           required
                                           autocomplete="username"
                                           placeholder="Enter your email"
                                           class="w-full h-16 rounded-3xl border border-gray-200 bg-gray-50 pl-14 pr-5 text-gray-900 placeholder:text-gray-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100 outline-none transition duration-300">

                                </div>

                                <x-input-error :messages="$errors->get('email')" class="mt-2" />

                            </div>

                            <!-- PASSWORD -->
                            <div>

                                <label class="block text-sm font-black text-gray-700 mb-3">
                                    Password
                                </label>

                                <div class="group relative">

                                    <div class="absolute inset-y-0 left-5 flex items-center text-gray-400 group-focus-within:text-blue-600 transition">

                                        <i class="fa-solid fa-lock"></i>

                                    </div>

                                    <input type="password"
                                           name="password"
                                           required
                                           autocomplete="new-password"
                                           placeholder="Create password"
                                           class="w-full h-16 rounded-3xl border border-gray-200 bg-gray-50 pl-14 pr-5 text-gray-900 placeholder:text-gray-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100 outline-none transition duration-300">

                                </div>

                                <x-input-error :messages="$errors->get('password')" class="mt-2" />

                            </div>

                            <!-- CONFIRM -->
                            <div>

                                <label class="block text-sm font-black text-gray-700 mb-3">
                                    Confirm Password
                                </label>

                                <div class="group relative">

                                    <div class="absolute inset-y-0 left-5 flex items-center text-gray-400 group-focus-within:text-blue-600 transition">

                                        <i class="fa-solid fa-shield-halved"></i>

                                    </div>

                                    <input type="password"
                                           name="password_confirmation"
                                           required
                                           autocomplete="new-password"
                                           placeholder="Confirm password"
                                           class="w-full h-16 rounded-3xl border border-gray-200 bg-gray-50 pl-14 pr-5 text-gray-900 placeholder:text-gray-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100 outline-none transition duration-300">

                                </div>

                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />

                            </div>

                            <!-- BUTTON -->
                            <button type="submit"
                                    class="group relative overflow-hidden w-full h-16 rounded-3xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white text-lg font-black shadow-2xl shadow-blue-500/30 hover:scale-[1.02] transition duration-300">

                                <span class="relative z-10 flex items-center justify-center gap-3">

                                    <i class="fa-solid fa-user-plus group-hover:rotate-12 transition duration-300"></i>

                                    Create Account

                                </span>

                                <div class="absolute inset-0 bg-white/20 translate-x-[-100%] group-hover:translate-x-[100%] transition duration-700"></div>

                            </button>

                            <!-- LOGIN -->
                            <div class="pt-4 text-center">

                                <a href="{{ route('login') }}"
                                   class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-blue-600 transition duration-300">

                                    <i class="fa-solid fa-arrow-right-to-bracket"></i>

                                    Already have an account?

                                </a>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-guest-layout>