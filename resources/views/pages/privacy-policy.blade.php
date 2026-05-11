<x-app-layout>

    <div class="min-h-screen relative overflow-hidden bg-[#f6f8fc]">

        <!-- BACKGROUND -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden">

            <!-- soft gradients -->
            <div class="absolute top-[-250px] left-[-180px] w-[700px] h-[700px] bg-sky-200/40 blur-3xl rounded-full"></div>

            <div class="absolute bottom-[-250px] right-[-180px] w-[700px] h-[700px] bg-indigo-200/35 blur-3xl rounded-full"></div>

            <div class="absolute top-[40%] left-[45%] w-[400px] h-[400px] bg-violet-200/20 blur-3xl rounded-full"></div>

            <!-- premium grid -->
            <div class="absolute inset-0 opacity-[0.025]"
                 style="background-image:
                 linear-gradient(to right, black 1px, transparent 1px),
                 linear-gradient(to bottom, black 1px, transparent 1px);
                 background-size: 90px 90px;">
            </div>

            <!-- radial light -->
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.9),transparent_55%)]"></div>

        </div>

        <div class="relative max-w-[1650px] mx-auto px-5 lg:px-10 py-16">

            <!-- HERO -->
            <div class="relative overflow-hidden rounded-[48px]
                        border border-white/70
                        bg-white/75
                        backdrop-blur-3xl
                        shadow-[0_35px_120px_rgba(15,23,42,0.08)]
                        p-10 lg:p-16 mb-14">

                <!-- glow -->
                <div class="absolute top-0 right-0 w-[450px] h-[450px] bg-sky-300/20 blur-3xl rounded-full"></div>

                <div class="absolute bottom-0 left-0 w-[450px] h-[450px] bg-indigo-300/20 blur-3xl rounded-full"></div>

                <div class="relative z-10 flex flex-col xl:flex-row xl:items-end xl:justify-between gap-12">

                    <!-- LEFT -->
                    <div class="max-w-4xl">

                        <!-- badge -->
                        <div class="inline-flex items-center gap-4
                                    px-6 py-4 rounded-[28px]
                                    bg-white/80
                                    border border-white
                                    shadow-xl mb-8">

                            <div class="w-16 h-16 rounded-2xl
                                        bg-gradient-to-br from-sky-500 via-indigo-500 to-blue-600
                                        flex items-center justify-center
                                        text-white shadow-[0_15px_50px_rgba(59,130,246,0.35)]">

                                <i class="fa-solid fa-shield-halved text-2xl"></i>

                            </div>

                            <div>

                                <p class="text-xs uppercase tracking-[0.35em]
                                          text-sky-600 font-black">
                                    Enterprise Security
                                </p>

                                <p class="text-sm text-gray-500 mt-1">
                                    International privacy & protection standards
                                </p>

                            </div>

                        </div>

                        <!-- title -->
                        <h1 class="text-6xl lg:text-8xl font-black
                                   tracking-[-0.06em]
                                   leading-[0.9]
                                   text-gray-900">

                            Privacy
                            <span class="bg-gradient-to-r
                                         from-sky-500
                                         via-indigo-500
                                         to-violet-500
                                         bg-clip-text text-transparent">

                                Policy

                            </span>

                        </h1>

                        <!-- subtitle -->
                        <p class="text-xl lg:text-2xl text-gray-500
                                  leading-relaxed mt-8 max-w-3xl">

                            We are committed to protecting your personal data,
                            maintaining transparency, and delivering a secure
                            digital experience aligned with modern global privacy practices.

                        </p>

                    </div>

                    <!-- RIGHT -->
                    <div class="grid grid-cols-2 gap-5">

                        <!-- card -->
                        <div class="rounded-[32px]
                                    bg-white/85
                                    border border-white
                                    backdrop-blur-2xl
                                    p-7 min-w-[220px]
                                    shadow-[0_20px_80px_rgba(15,23,42,0.06)]">

                            <div class="w-16 h-16 rounded-[24px]
                                        bg-sky-100
                                        flex items-center justify-center mb-5">

                                <i class="fa-solid fa-lock text-sky-600 text-2xl"></i>

                            </div>

                            <h3 class="text-4xl font-black text-gray-900">
                                Secure
                            </h3>

                            <p class="text-gray-500 mt-3 uppercase tracking-[0.18em] text-xs font-black">
                                Protected Systems
                            </p>

                        </div>

                        <!-- card -->
                        <div class="rounded-[32px]
                                    bg-white/85
                                    border border-white
                                    backdrop-blur-2xl
                                    p-7 min-w-[220px]
                                    shadow-[0_20px_80px_rgba(15,23,42,0.06)]">

                            <div class="w-16 h-16 rounded-[24px]
                                        bg-indigo-100
                                        flex items-center justify-center mb-5">

                                <i class="fa-solid fa-user-shield text-indigo-600 text-2xl"></i>

                            </div>

                            <h3 class="text-4xl font-black text-gray-900">
                                Trusted
                            </h3>

                            <p class="text-gray-500 mt-3 uppercase tracking-[0.18em] text-xs font-black">
                                Global Standards
                            </p>

                        </div>

                    </div>

                </div>

            </div>

            <!-- MAIN GRID -->
            <div class="grid xl:grid-cols-[380px_minmax(0,1fr)] gap-10 items-start">

                <!-- SIDEBAR -->
                <div class="space-y-6">

                    <!-- card -->
                    <div class="group rounded-[36px]
                                border border-white/70
                                bg-white/80
                                backdrop-blur-3xl
                                p-8
                                shadow-[0_20px_80px_rgba(15,23,42,0.06)]
                                hover:-translate-y-1
                                transition duration-500">

                        <div class="w-18 h-18 rounded-[26px]
                                    bg-gradient-to-br from-sky-100 to-sky-50
                                    flex items-center justify-center mb-6">

                            <i class="fa-solid fa-database text-sky-600 text-3xl"></i>

                        </div>

                        <h3 class="text-3xl font-black text-gray-900 mb-4">
                            Data Collection
                        </h3>

                        <p class="text-gray-500 leading-8 text-lg">
                            Information is collected only when necessary to improve
                            platform functionality, customer support, and account security.
                        </p>

                    </div>

                    <!-- card -->
                    <div class="group rounded-[36px]
                                border border-white/70
                                bg-white/80
                                backdrop-blur-3xl
                                p-8
                                shadow-[0_20px_80px_rgba(15,23,42,0.06)]
                                hover:-translate-y-1
                                transition duration-500">

                        <div class="w-18 h-18 rounded-[26px]
                                    bg-gradient-to-br from-indigo-100 to-indigo-50
                                    flex items-center justify-center mb-6">

                            <i class="fa-solid fa-eye-slash text-indigo-600 text-3xl"></i>

                        </div>

                        <h3 class="text-3xl font-black text-gray-900 mb-4">
                            Confidentiality
                        </h3>

                        <p class="text-gray-500 leading-8 text-lg">
                            Personal data remains confidential and is never sold
                            or shared without lawful and transparent consent.
                        </p>

                    </div>

                    <!-- card -->
                    <div class="group rounded-[36px]
                                border border-white/70
                                bg-white/80
                                backdrop-blur-3xl
                                p-8
                                shadow-[0_20px_80px_rgba(15,23,42,0.06)]
                                hover:-translate-y-1
                                transition duration-500">

                        <div class="w-18 h-18 rounded-[26px]
                                    bg-gradient-to-br from-emerald-100 to-emerald-50
                                    flex items-center justify-center mb-6">

                            <i class="fa-solid fa-circle-check text-emerald-600 text-3xl"></i>

                        </div>

                        <h3 class="text-3xl font-black text-gray-900 mb-4">
                            Compliance
                        </h3>

                        <p class="text-gray-500 leading-8 text-lg">
                            Built with privacy-first principles inspired by modern
                            international compliance and security frameworks.
                        </p>

                    </div>

                </div>

                <!-- CONTENT -->
                <div class="relative rounded-[44px]
                            border border-white/70
                            bg-white/85
                            backdrop-blur-3xl
                            overflow-hidden
                            shadow-[0_35px_120px_rgba(15,23,42,0.08)]">

                    <!-- overlay -->
                    <div class="absolute inset-0 bg-gradient-to-br
                                from-sky-100/20
                                via-transparent
                                to-indigo-100/20">
                    </div>

                    <div class="relative z-10 p-8 lg:p-14">

                        <!-- top -->
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8 mb-12">

                            <div class="flex items-center gap-6">

                                <div class="w-24 h-24 rounded-[32px]
                                            bg-gradient-to-br
                                            from-sky-500
                                            via-indigo-500
                                            to-blue-600
                                            flex items-center justify-center
                                            text-white text-4xl
                                            shadow-[0_20px_60px_rgba(59,130,246,0.35)]">

                                    <i class="fa-solid fa-file-shield"></i>

                                </div>

                                <div>

                                    <h2 class="text-5xl font-black text-gray-900">

                                        Privacy Overview

                                    </h2>

                                    <p class="text-gray-500 mt-3 text-xl">

                                        Transparency, trust, and protection

                                    </p>

                                </div>

                            </div>

                            <!-- update badge -->
                            <div class="inline-flex items-center gap-3
                                        px-6 py-4 rounded-2xl
                                        bg-[#f8fafc]
                                        border border-gray-100
                                        text-gray-500 font-semibold">

                                <i class="fa-solid fa-clock text-sky-500"></i>

                                Updated Automatically

                            </div>

                        </div>

                        <!-- article -->
                        <div class="rounded-[36px]
                                    bg-gradient-to-br from-[#ffffff] to-[#f8fbff]
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

                                {!! nl2br(e($settings->privacy_policy ?? 'No privacy policy added yet.')) !!}

                            </div>

                        </div>

                        <!-- footer -->
                        <div class="mt-10 pt-8 border-t border-gray-100
                                    flex flex-col lg:flex-row
                                    lg:items-center lg:justify-between gap-6">

                            <div class="flex items-center gap-4">

                                <div class="w-14 h-14 rounded-2xl
                                            bg-emerald-100
                                            flex items-center justify-center">

                                    <i class="fa-solid fa-circle-check text-emerald-600 text-xl"></i>

                                </div>

                                <div>

                                    <h4 class="font-black text-gray-900">
                                        Your privacy is protected
                                    </h4>

                                    <p class="text-gray-500 text-sm mt-1">
                                        Built with trusted modern security practices
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