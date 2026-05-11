@php
    $settings = \App\Models\Setting::first();
@endphp

<footer class="relative mt-24 overflow-hidden bg-gradient-to-br from-[#020617] via-[#0f172a] to-black text-white border-t border-white/5">

    <!-- BACKGROUND GLOW -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden">

        <div class="absolute top-[-180px] left-[-120px] w-[420px] h-[420px] bg-blue-500/10 blur-3xl rounded-full"></div>

        <div class="absolute bottom-[-200px] right-[-120px] w-[420px] h-[420px] bg-indigo-500/10 blur-3xl rounded-full"></div>

        <div class="absolute top-[40%] left-[45%] w-[260px] h-[260px] bg-cyan-400/5 blur-3xl rounded-full"></div>

    </div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-8 py-20">

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-14">

            <!-- LOGO -->
            <div>

                <div class="inline-flex items-center gap-3 mb-6">

                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center shadow-[0_15px_40px_rgba(59,130,246,0.35)]">

                        <i class="fa-solid fa-bag-shopping text-white text-xl"></i>

                    </div>

                    <h2 class="text-4xl font-black tracking-tight bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">

                        {{ $settings->site_name ?? 'MyStore' }}

                    </h2>

                </div>

                <p class="text-gray-400 leading-8 text-[15px] max-w-sm">

                    {{ $settings->about ?? 'Best ecommerce store for amazing products.' }}

                </p>

            </div>

            <!-- LINKS -->
            <div>

                <h3 class="text-xl font-black mb-7 text-white">
                    Quick Links
                </h3>

                <div class="space-y-4">

                    <a href="/"
                       class="group flex items-center gap-3 text-gray-400 hover:text-white transition duration-300">

                        <span class="w-2 h-2 rounded-full bg-blue-500 group-hover:scale-125 transition"></span>

                        Home

                    </a>

                    <a href="/cart"
                       class="group flex items-center gap-3 text-gray-400 hover:text-white transition duration-300">

                        <span class="w-2 h-2 rounded-full bg-blue-500 group-hover:scale-125 transition"></span>

                        Cart

                    </a>

                    <a href="/orders"
                       class="group flex items-center gap-3 text-gray-400 hover:text-white transition duration-300">

                        <span class="w-2 h-2 rounded-full bg-blue-500 group-hover:scale-125 transition"></span>

                        Orders

                    </a>

                    <a href="/coupons"
                       class="group flex items-center gap-3 text-gray-400 hover:text-white transition duration-300">

                        <span class="w-2 h-2 rounded-full bg-blue-500 group-hover:scale-125 transition"></span>

                        Coupons

                    </a>

                    <a href="/contact"
                       class="group flex items-center gap-3 text-gray-400 hover:text-white transition duration-300">

                        <span class="w-2 h-2 rounded-full bg-blue-500 group-hover:scale-125 transition"></span>

                        Contact

                    </a>

                </div>

            </div>

            <!-- INFO -->
            <div>

                <h3 class="text-xl font-black mb-7 text-white">
                    Information
                </h3>

                <div class="space-y-4">

                    <a href="/privacy-policy"
                       class="group flex items-center gap-3 text-gray-400 hover:text-white transition duration-300">

                        <span class="w-2 h-2 rounded-full bg-indigo-500 group-hover:scale-125 transition"></span>

                        Privacy Policy

                    </a>

                    <a href="/terms"
                       class="group flex items-center gap-3 text-gray-400 hover:text-white transition duration-300">

                        <span class="w-2 h-2 rounded-full bg-indigo-500 group-hover:scale-125 transition"></span>

                        Terms & Conditions

                    </a>

                    <a href="/about"
                       class="group flex items-center gap-3 text-gray-400 hover:text-white transition duration-300">

                        <span class="w-2 h-2 rounded-full bg-indigo-500 group-hover:scale-125 transition"></span>

                        About Us

                    </a>

                </div>

            </div>

            <!-- CONTACT -->
            <div>

                <h3 class="text-xl font-black mb-7 text-white">
                    Follow Us
                </h3>

                <!-- SOCIAL -->
                <div class="flex items-center gap-4 mb-8">

                    @if($settings?->facebook)
                        <a href="{{ $settings->facebook }}"
                           target="_blank"
                           class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-xl text-gray-300 hover:bg-blue-500 hover:text-white hover:-translate-y-1 transition duration-300 shadow-xl">

                            <i class="fa-brands fa-facebook-f"></i>

                        </a>
                    @endif

                    @if($settings?->instagram)
                        <a href="{{ $settings->instagram }}"
                           target="_blank"
                           class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-xl text-gray-300 hover:bg-gradient-to-r hover:from-pink-500 hover:to-rose-500 hover:text-white hover:-translate-y-1 transition duration-300 shadow-xl">

                            <i class="fa-brands fa-instagram"></i>

                        </a>
                    @endif

                    @if($settings?->twitter)
                        <a href="{{ $settings->twitter }}"
                           target="_blank"
                           class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-xl text-gray-300 hover:bg-sky-500 hover:text-white hover:-translate-y-1 transition duration-300 shadow-xl">

                            <i class="fa-brands fa-x-twitter"></i>

                        </a>
                    @endif

                    @if($settings?->tiktok)
                        <a href="{{ $settings->tiktok }}"
                           target="_blank"
                           class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-xl text-gray-300 hover:bg-white hover:text-black hover:-translate-y-1 transition duration-300 shadow-xl">

                            <i class="fa-brands fa-tiktok"></i>

                        </a>
                    @endif

                </div>

                <!-- CONTACT INFO -->
                <div class="space-y-4">

                    @if($settings?->email)
                        <div class="flex items-start gap-4 rounded-2xl bg-white/5 border border-white/5 p-4">

                            <div class="w-11 h-11 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center shrink-0">

                                <i class="fa-solid fa-envelope"></i>

                            </div>

                            <div class="min-w-0">

                                <p class="text-xs uppercase tracking-[0.18em] text-gray-500 font-bold mb-1">
                                    Email
                                </p>

                                <p class="text-gray-300 break-all">
                                    {{ $settings->email }}
                                </p>

                            </div>

                        </div>
                    @endif

                    @if($settings?->phone)
                        <div class="flex items-start gap-4 rounded-2xl bg-white/5 border border-white/5 p-4">

                            <div class="w-11 h-11 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">

                                <i class="fa-solid fa-phone"></i>

                            </div>

                            <div>

                                <p class="text-xs uppercase tracking-[0.18em] text-gray-500 font-bold mb-1">
                                    Phone
                                </p>

                                <p class="text-gray-300">
                                    {{ $settings->phone }}
                                </p>

                            </div>

                        </div>
                    @endif

                    @if($settings?->address)
                        <div class="flex items-start gap-4 rounded-2xl bg-white/5 border border-white/5 p-4">

                            <div class="w-11 h-11 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center shrink-0">

                                <i class="fa-solid fa-location-dot"></i>

                            </div>

                            <div>

                                <p class="text-xs uppercase tracking-[0.18em] text-gray-500 font-bold mb-1">
                                    Address
                                </p>

                                <p class="text-gray-300 leading-7">
                                    {{ $settings->address }}
                                </p>

                            </div>

                        </div>
                    @endif

                </div>

            </div>

        </div>

    </div>

    <!-- BOTTOM -->
    <div class="relative border-t border-white/5">

        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-6 flex flex-col md:flex-row items-center justify-between gap-4">

            <p class="text-gray-500 text-sm text-center md:text-left">

                {{ $settings->footer_text ?? '© '.date('Y').' MyStore. All rights reserved.' }}

            </p>

            <div class="flex items-center gap-3 text-gray-500 text-sm">

                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>

                Premium Ecommerce Experience

            </div>

        </div>

    </div>

</footer>