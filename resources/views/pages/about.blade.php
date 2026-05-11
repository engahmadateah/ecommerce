<x-app-layout>

    <div class="min-h-screen relative overflow-hidden bg-gradient-to-br from-[#f8fafc] via-[#fdfdfd] to-[#eef4ff] py-20">

        <!-- BACKGROUND -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden">

            <div class="absolute top-[-250px] left-[-120px] w-[650px] h-[650px] bg-sky-200/30 blur-3xl rounded-full"></div>

            <div class="absolute bottom-[-250px] right-[-120px] w-[650px] h-[650px] bg-indigo-200/30 blur-3xl rounded-full"></div>

            <div class="absolute inset-0 opacity-[0.03]"
                 style="background-image:
                 linear-gradient(to right, black 1px, transparent 1px),
                 linear-gradient(to bottom, black 1px, transparent 1px);
                 background-size: 70px 70px;">
            </div>

        </div>

        <div class="relative max-w-6xl mx-auto px-4 lg:px-8">

            <!-- HERO -->
            <div class="relative overflow-hidden rounded-[42px]
                        border border-white/70
                        bg-white/75
                        backdrop-blur-3xl
                        shadow-[0_30px_120px_rgba(15,23,42,0.08)]
                        p-10 lg:p-16 mb-12">

                <!-- glow -->
                <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-sky-300/20 blur-3xl rounded-full"></div>

                <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-indigo-300/20 blur-3xl rounded-full"></div>

                <div class="relative z-10">

                    <!-- badge -->
                    <div class="inline-flex items-center gap-4 px-6 py-4 rounded-3xl
                                bg-white/80 border border-white
                                shadow-xl mb-8">

                        <div class="w-14 h-14 rounded-2xl
                                    bg-gradient-to-br from-sky-500 to-indigo-600
                                    flex items-center justify-center
                                    text-white shadow-[0_15px_50px_rgba(59,130,246,0.35)]">

                            <i class="fa-solid fa-circle-info text-xl"></i>

                        </div>

                        <div>

                            <p class="text-xs uppercase tracking-[0.35em]
                                      text-sky-600 font-black">
                                Premium Brand
                            </p>

                            <p class="text-sm text-gray-500 mt-1">
                                Learn more about our story
                            </p>

                        </div>

                    </div>

                    <!-- title -->
                    <h1 class="text-5xl lg:text-7xl font-black
                               tracking-tight leading-[0.95]
                               text-gray-900 mb-6">

                        About
                        <span class="bg-gradient-to-r from-sky-500 via-indigo-500 to-blue-600
                                     bg-clip-text text-transparent">

                            Us

                        </span>

                    </h1>

                    <!-- subtitle -->
                    <p class="text-xl text-gray-500 leading-relaxed max-w-3xl">

                        We create a modern premium shopping experience focused on
                        elegant design, trusted quality, and exceptional customer satisfaction.

                    </p>

                </div>

            </div>

            <!-- CONTENT -->
            <div class="grid lg:grid-cols-3 gap-8">

                <!-- LEFT INFO -->
                <div class="space-y-6">

                    <!-- card -->
                    <div class="rounded-[32px]
                                border border-white/70
                                bg-white/75
                                backdrop-blur-3xl
                                shadow-[0_20px_80px_rgba(15,23,42,0.06)]
                                p-7">

                        <div class="w-16 h-16 rounded-[24px]
                                    bg-sky-100
                                    flex items-center justify-center
                                    mb-5">

                            <i class="fa-solid fa-gem text-sky-600 text-2xl"></i>

                        </div>

                        <h3 class="text-2xl font-black text-gray-900 mb-3">
                            Premium Quality
                        </h3>

                        <p class="text-gray-500 leading-relaxed">
                            Carefully selected products designed for customers
                            who value elegance and quality.
                        </p>

                    </div>

                    <!-- card -->
                    <div class="rounded-[32px]
                                border border-white/70
                                bg-white/75
                                backdrop-blur-3xl
                                shadow-[0_20px_80px_rgba(15,23,42,0.06)]
                                p-7">

                        <div class="w-16 h-16 rounded-[24px]
                                    bg-indigo-100
                                    flex items-center justify-center
                                    mb-5">

                            <i class="fa-solid fa-shield-halved text-indigo-600 text-2xl"></i>

                        </div>

                        <h3 class="text-2xl font-black text-gray-900 mb-3">
                            Trusted Experience
                        </h3>

                        <p class="text-gray-500 leading-relaxed">
                            Secure shopping, fast delivery, and customer-first
                            support every step of the way.
                        </p>

                    </div>

                </div>

                <!-- MAIN CONTENT -->
                <div class="lg:col-span-2">

                    <div class="relative rounded-[38px]
                                border border-white/70
                                bg-white/80
                                backdrop-blur-3xl
                                shadow-[0_25px_100px_rgba(15,23,42,0.08)]
                                overflow-hidden">

                        <!-- hover glow -->
                        <div class="absolute inset-0 bg-gradient-to-br
                                    from-sky-100/30
                                    via-transparent
                                    to-indigo-100/20">
                        </div>

                        <div class="relative z-10 p-8 lg:p-12">

                            <!-- heading -->
                            <div class="flex items-center gap-5 mb-10">

                                <div class="w-20 h-20 rounded-[28px]
                                            bg-gradient-to-br
                                            from-sky-500
                                            to-indigo-600
                                            flex items-center justify-center
                                            text-white text-3xl
                                            shadow-[0_15px_50px_rgba(59,130,246,0.35)]">

                                    <i class="fa-solid fa-building"></i>

                                </div>

                                <div>

                                    <h2 class="text-4xl font-black text-gray-900">

                                        Our Story

                                    </h2>

                                    <p class="text-gray-500 mt-2 text-lg">

                                        Passion for premium digital commerce

                                    </p>

                                </div>

                            </div>

                            <!-- content -->
                            <div class="rounded-[30px]
                                        bg-[#f9fafb]
                                        border border-gray-100
                                        p-8">

                                <div class="prose prose-lg max-w-none
                                            prose-headings:text-gray-900
                                            prose-p:text-gray-600
                                            prose-p:leading-8">

                                    {!! nl2br(e($settings->about ?? 'No about content added yet.')) !!}

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>