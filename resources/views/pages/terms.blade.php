<x-app-layout>

    <div class="min-h-screen relative overflow-hidden bg-[#f6f8fc]">

        <!-- BACKGROUND -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">

            <!-- soft gradients -->
            <div class="absolute top-[-250px] left-[-180px] w-[700px] h-[700px] bg-amber-200/35 blur-3xl rounded-full"></div>

            <div class="absolute bottom-[-250px] right-[-180px] w-[700px] h-[700px] bg-orange-200/30 blur-3xl rounded-full"></div>

            <div class="absolute top-[45%] left-[45%] w-[400px] h-[400px] bg-yellow-100/20 blur-3xl rounded-full"></div>

            <!-- grid -->
            <div class="absolute inset-0 opacity-[0.025]"
                 style="background-image:
                 linear-gradient(to right, black 1px, transparent 1px),
                 linear-gradient(to bottom, black 1px, transparent 1px);
                 background-size: 90px 90px;">
            </div>

            <!-- radial -->
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.9),transparent_55%)]"></div>

        </div>

        <div class="relative max-w-[1650px] mx-auto px-5 lg:px-10 py-16">

            <!-- HERO -->
            <div class="relative overflow-hidden rounded-[48px]
                        border border-white/70
                        bg-white/80
                        backdrop-blur-3xl
                        shadow-[0_35px_120px_rgba(15,23,42,0.08)]
                        p-10 lg:p-16 mb-14">

                <!-- glow -->
                <div class="absolute top-0 right-0 w-[450px] h-[450px] bg-amber-200/25 blur-3xl rounded-full"></div>

                <div class="absolute bottom-0 left-0 w-[450px] h-[450px] bg-orange-200/20 blur-3xl rounded-full"></div>

                <div class="relative z-10 flex flex-col xl:flex-row xl:items-end xl:justify-between gap-12">

                    <!-- LEFT -->
                    <div class="max-w-4xl">

                        <!-- badge -->
                        <div class="inline-flex items-center gap-4
                                    px-6 py-4 rounded-[28px]
                                    bg-white/90
                                    border border-white
                                    shadow-xl mb-8">

                            <div class="w-16 h-16 rounded-2xl
                                        bg-gradient-to-br from-amber-500 via-orange-500 to-yellow-500
                                        flex items-center justify-center
                                        text-white shadow-[0_15px_50px_rgba(245,158,11,0.35)]">

                                <i class="fa-solid fa-scale-balanced text-2xl"></i>

                            </div>

                            <div>

                                <p class="text-xs uppercase tracking-[0.35em]
                                          text-amber-600 font-black">
                                    Legal Agreement
                                </p>

                                <p class="text-sm text-gray-500 mt-1">
                                    Terms governing platform usage & services
                                </p>

                            </div>

                        </div>

                        <!-- title -->
                        <h1 class="text-6xl lg:text-8xl
                                   font-black tracking-[-0.06em]
                                   leading-[0.9]
                                   text-gray-900">

                            Terms &
                            <span class="bg-gradient-to-r
                                         from-amber-500
                                         via-orange-500
                                         to-yellow-500
                                         bg-clip-text text-transparent">

                                Conditions

                            </span>

                        </h1>

                        <!-- subtitle -->
                        <p class="text-xl lg:text-2xl text-gray-500
                                  leading-relaxed mt-8 max-w-3xl">

                            Please review these terms carefully before using our platform,
                            services, and digital shopping experience.

                        </p>

                    </div>

                    <!-- STATUS -->
                    <div class="grid grid-cols-2 gap-5">

                        <!-- legal -->
                        <div class="rounded-[32px]
                                    bg-white/85
                                    border border-white
                                    backdrop-blur-2xl
                                    p-7 min-w-[220px]
                                    shadow-[0_20px_80px_rgba(15,23,42,0.06)]">

                            <div class="w-16 h-16 rounded-[24px]
                                        bg-amber-100
                                        flex items-center justify-center mb-5">

                                <i class="fa-solid fa-file-contract text-amber-600 text-2xl"></i>

                            </div>

                            <h3 class="text-4xl font-black text-gray-900">
                                Legal
                            </h3>

                            <p class="text-gray-500 mt-3 uppercase tracking-[0.18em] text-xs font-black">
                                Platform Rules
                            </p>

                        </div>

                        <!-- trusted -->
                        <div class="rounded-[32px]
                                    bg-white/85
                                    border border-white
                                    backdrop-blur-2xl
                                    p-7 min-w-[220px]
                                    shadow-[0_20px_80px_rgba(15,23,42,0.06)]">

                            <div class="w-16 h-16 rounded-[24px]
                                        bg-orange-100
                                        flex items-center justify-center mb-5">

                                <i class="fa-solid fa-circle-check text-orange-500 text-2xl"></i>

                            </div>

                            <h3 class="text-4xl font-black text-gray-900">
                                Trusted
                            </h3>

                            <p class="text-gray-500 mt-3 uppercase tracking-[0.18em] text-xs font-black">
                                Secure Experience
                            </p>

                        </div>

                    </div>

                </div>

            </div>

            <!-- CONTENT GRID -->
            <div class="grid xl:grid-cols-[380px_minmax(0,1fr)] gap-10 items-start">

                <!-- SIDEBAR -->
                <div class="space-y-6">

                    <!-- card -->
                    <div class="rounded-[36px]
                                border border-white/70
                                bg-white/85
                                backdrop-blur-3xl
                                p-8
                                shadow-[0_20px_80px_rgba(15,23,42,0.06)]">

                        <div class="w-18 h-18 rounded-[26px]
                                    bg-amber-100
                                    flex items-center justify-center mb-6">

                            <i class="fa-solid fa-user-check text-amber-600 text-3xl"></i>

                        </div>

                        <h3 class="text-3xl font-black text-gray-900 mb-4">
                            User Responsibility
                        </h3>

                        <p class="text-gray-500 leading-8 text-lg">
                            Users are expected to use the platform responsibly
                            and comply with all applicable terms and policies.
                        </p>

                    </div>

                    <!-- card -->
                    <div class="rounded-[36px]
                                border border-white/70
                                bg-white/85
                                backdrop-blur-3xl
                                p-8
                                shadow-[0_20px_80px_rgba(15,23,42,0.06)]">

                        <div class="w-18 h-18 rounded-[26px]
                                    bg-orange-100
                                    flex items-center justify-center mb-6">

                            <i class="fa-solid fa-credit-card text-orange-500 text-3xl"></i>

                        </div>

                        <h3 class="text-3xl font-black text-gray-900 mb-4">
                            Transactions
                        </h3>

                        <p class="text-gray-500 leading-8 text-lg">
                            Orders, payments, and refunds are handled securely
                            according to our operational standards.
                        </p>

                    </div>

                    <!-- card -->
                    <div class="rounded-[36px]
                                border border-white/70
                                bg-white/85
                                backdrop-blur-3xl
                                p-8
                                shadow-[0_20px_80px_rgba(15,23,42,0.06)]">

                        <div class="w-18 h-18 rounded-[26px]
                                    bg-yellow-100
                                    flex items-center justify-center mb-6">

                            <i class="fa-solid fa-shield-halved text-yellow-600 text-3xl"></i>

                        </div>

                        <h3 class="text-3xl font-black text-gray-900 mb-4">
                            Protection
                        </h3>

                        <p class="text-gray-500 leading-8 text-lg">
                            Our platform is designed with security and reliability
                            to ensure a trusted user experience.
                        </p>

                    </div>

                </div>

                <!-- MAIN CONTENT -->
                <div class="relative rounded-[44px]
                            border border-white/70
                            bg-white/85
                            backdrop-blur-3xl
                            overflow-hidden
                            shadow-[0_35px_120px_rgba(15,23,42,0.08)]">

                    <!-- overlay -->
                    <div class="absolute inset-0 bg-gradient-to-br
                                from-amber-100/20
                                via-transparent
                                to-orange-100/20">
                    </div>

                    <div class="relative z-10 p-8 lg:p-14">

                        <!-- top -->
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8 mb-12">

                            <div class="flex items-center gap-6">

                                <div class="w-24 h-24 rounded-[32px]
                                            bg-gradient-to-br
                                            from-amber-500
                                            via-orange-500
                                            to-yellow-500
                                            flex items-center justify-center
                                            text-white text-4xl
                                            shadow-[0_20px_60px_rgba(245,158,11,0.35)]">

                                    <i class="fa-solid fa-scale-balanced"></i>

                                </div>

                                <div>

                                    <h2 class="text-5xl font-black text-gray-900">

                                        Terms Overview

                                    </h2>

                                    <p class="text-gray-500 mt-3 text-xl">

                                        Platform usage & legal guidelines

                                    </p>

                                </div>

                            </div>

                            <!-- update -->
                            <div class="inline-flex items-center gap-3
                                        px-6 py-4 rounded-2xl
                                        bg-[#f8fafc]
                                        border border-gray-100
                                        text-gray-500 font-semibold">

                                <i class="fa-solid fa-clock text-amber-500"></i>

                                Updated Automatically

                            </div>

                        </div>

                        <!-- content -->
                        <div class="rounded-[36px]
                                    bg-gradient-to-br from-[#ffffff] to-[#fffaf5]
                                    border border-gray-100
                                    p-8 lg:p-12
                                    shadow-inner">

                            <div class="prose prose-lg max-w-none
                                        prose-headings:text-gray-900
                                        prose-headings:font-black
                                        prose-p:text-gray-600
                                        prose-p:leading-9
                                        prose-p:text-[17px]
                                        prose-strong:text-gray-900
                                        prose-li:text-gray-600
                                        prose-li:leading-8">

                                {!! nl2br(e($settings->terms ?? 'No terms added yet.')) !!}

                            </div>

                        </div>

                        <!-- footer -->
                        <div class="mt-10 pt-8 border-t border-gray-100
                                    flex flex-col lg:flex-row
                                    lg:items-center lg:justify-between gap-6">

                            <div class="flex items-center gap-4">

                                <div class="w-14 h-14 rounded-2xl
                                            bg-amber-100
                                            flex items-center justify-center">

                                    <i class="fa-solid fa-circle-check text-amber-600 text-xl"></i>

                                </div>

                                <div>

                                    <h4 class="font-black text-gray-900">
                                        Clear & transparent terms
                                    </h4>

                                    <p class="text-gray-500 text-sm mt-1">
                                        Designed for a secure and trusted experience
                                    </p>

                                </div>

                            </div>

                            <div class="text-sm text-gray-400">

                                © {{ date('Y') }} All rights reserved

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>