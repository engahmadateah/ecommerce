<x-guest-layout>

    <div class="min-h-screen bg-gradient-to-br from-slate-100 via-blue-50 to-indigo-100 flex items-center justify-center px-4 py-10 overflow-hidden">

        <!-- BACKGROUND -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">

            <div class="absolute top-[-250px] left-[-150px] w-[600px] h-[600px] bg-blue-400/20 blur-3xl rounded-full"></div>

            <div class="absolute bottom-[-250px] right-[-150px] w-[600px] h-[600px] bg-indigo-400/20 blur-3xl rounded-full"></div>

        </div>

        <div class="relative w-full max-w-6xl grid lg:grid-cols-2 bg-white/80 backdrop-blur-2xl rounded-[40px] overflow-hidden shadow-[0_30px_80px_rgba(0,0,0,0.12)] border border-white/30">

            <!-- LEFT SIDE -->
            <div class="hidden lg:flex relative flex-col justify-between p-16 bg-gradient-to-br from-[#0f172a] via-[#111827] to-[#1e293b] overflow-hidden">

                <!-- DECOR -->
                <div class="absolute inset-0">

                    <div class="absolute top-0 left-0 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>

                    <div class="absolute bottom-0 right-0 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>

                </div>

                <!-- CONTENT -->
                <div class="relative z-10">

                    <!-- LOGO -->
                    <div class="flex items-center gap-4 mb-20">

                        <div class="w-16 h-16 rounded-3xl bg-white/10 border border-white/10 backdrop-blur-xl flex items-center justify-center shadow-2xl">

                            <img src="{{ asset('images/logo.png') }}"
                                 class="w-9 h-9 object-contain">

                        </div>

                        <div>

                            <h1 class="text-4xl font-black tracking-tight text-white">
                                MyStore
                            </h1>

                            <p class="text-xs uppercase tracking-[0.4em] text-blue-200 mt-1">
                                Secure Access
                            </p>

                        </div>

                    </div>

                    <!-- TEXT -->
                    <div class="space-y-8 max-w-xl">

                        <h2 class="text-6xl font-black leading-[1.05] text-white">
                            Forgot Your Password?
                        </h2>

                        <p class="text-lg leading-relaxed text-gray-300">
                            No worries. Enter your email address and we’ll send you
                            a secure password reset link instantly.
                        </p>

                    </div>

                </div>

                <!-- FEATURES -->
                <div class="relative z-10 grid grid-cols-3 gap-4 mt-16">

                    <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl p-5">

                        <div class="w-12 h-12 rounded-2xl bg-pink-500/20 flex items-center justify-center mb-4">

                            <i class="fa-solid fa-shield text-pink-400 text-xl"></i>

                        </div>

                        <h3 class="font-bold text-white text-sm">
                            Secure Reset
                        </h3>

                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl p-5">

                        <div class="w-12 h-12 rounded-2xl bg-yellow-500/20 flex items-center justify-center mb-4">

                            <i class="fa-solid fa-envelope text-yellow-400 text-xl"></i>

                        </div>

                        <h3 class="font-bold text-white text-sm">
                            Email Verification
                        </h3>

                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl p-5">

                        <div class="w-12 h-12 rounded-2xl bg-cyan-500/20 flex items-center justify-center mb-4">

                            <i class="fa-solid fa-lock text-cyan-400 text-xl"></i>

                        </div>

                        <h3 class="font-bold text-white text-sm">
                            Protected Account
                        </h3>

                    </div>

                </div>

            </div>

            <!-- RIGHT SIDE -->
            <div class="relative flex items-center justify-center bg-white px-6 py-10 lg:px-16">

                <div class="w-full max-w-md">

                    <!-- MOBILE LOGO -->
                    <div class="lg:hidden flex flex-col items-center mb-10">

                        <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center shadow-2xl shadow-blue-500/30 mb-5">

                            <img src="{{ asset('images/logo.png') }}"
                                 class="w-10 h-10 object-contain">

                        </div>

                        <h1 class="text-4xl font-black bg-gradient-to-r from-blue-700 to-indigo-700 bg-clip-text text-transparent">
                            MyStore
                        </h1>

                    </div>

                    <!-- HEADER -->
                    <div class="mb-10">

                        <h2 class="text-5xl font-black text-gray-900 mb-4 leading-tight">
                            Reset Password
                        </h2>

                        <p class="text-gray-500 text-lg leading-relaxed">
                            Enter your email and receive a password reset link.
                        </p>

                    </div>

                    <!-- STATUS -->
                    <x-auth-session-status class="mb-6" :status="session('status')" />

                    <!-- FORM -->
                    <form method="POST"
                          action="{{ route('password.email') }}"
                          class="space-y-6">

                        @csrf

                        <!-- EMAIL -->
                        <div>

                            <label class="block text-sm font-bold text-gray-700 mb-3">
                                Email Address
                            </label>

                            <div class="relative">

                                <i class="fa-solid fa-envelope absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>

                                <input type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required
                                       autofocus
                                       placeholder="Enter your email"
                                       class="w-full h-14 rounded-2xl border border-gray-200 bg-gray-50 pl-14 pr-5 text-gray-900 placeholder:text-gray-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100 outline-none transition duration-300">

                            </div>

                            <x-input-error :messages="$errors->get('email')" class="mt-2" />

                        </div>

                        <!-- BUTTON -->
                        <button type="submit"
                                class="group relative overflow-hidden w-full h-14 rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white text-lg font-black shadow-2xl shadow-blue-500/30 hover:scale-[1.02] transition duration-300">

                            <span class="relative z-10 flex items-center justify-center gap-3">

                                <i class="fa-solid fa-paper-plane"></i>

                                Send Reset Link

                            </span>

                            <div class="absolute inset-0 bg-white/20 translate-x-[-100%] group-hover:translate-x-[100%] transition duration-700"></div>

                        </button>

                        <!-- LOGIN -->
                        <div class="text-center pt-3">

                            <a href="{{ route('login') }}"
                               class="text-sm font-semibold text-gray-500 hover:text-blue-600 transition duration-300">

                                Back to Login

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</x-guest-layout>