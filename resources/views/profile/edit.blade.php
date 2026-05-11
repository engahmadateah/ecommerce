<x-app-layout>

    <div class="min-h-screen relative overflow-hidden bg-gradient-to-br from-[#f4f7fb] via-[#f8fafc] to-[#eef4ff] py-16">

        <!-- PREMIUM BACKGROUND -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden">

            <div class="absolute top-[-250px] left-[-150px] w-[700px] h-[700px] bg-sky-300/20 blur-3xl rounded-full"></div>

            <div class="absolute bottom-[-250px] right-[-150px] w-[700px] h-[700px] bg-indigo-300/20 blur-3xl rounded-full"></div>

            <div class="absolute top-[35%] left-[40%] w-[350px] h-[350px] bg-pink-200/20 blur-3xl rounded-full"></div>

            <div class="absolute inset-0 opacity-[0.03]"
                 style="background-image:
                 linear-gradient(to right, black 1px, transparent 1px),
                 linear-gradient(to bottom, black 1px, transparent 1px);
                 background-size: 70px 70px;">
            </div>

        </div>

        <div class="relative max-w-7xl mx-auto px-4 lg:px-8">

            <!-- HEADER -->
            <div class="mb-10">

                <div class="inline-flex items-center gap-4 px-6 py-4 rounded-3xl bg-white/70 border border-white shadow-xl backdrop-blur-2xl mb-6">

                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-sky-500 via-indigo-500 to-blue-600 flex items-center justify-center text-white shadow-[0_15px_50px_rgba(59,130,246,0.35)]">

                        <i class="fa-solid fa-user text-xl"></i>

                    </div>

                    <div>

                        <p class="text-xs uppercase tracking-[0.35em] text-sky-600 font-black">
                            Premium Account
                        </p>

                        <p class="text-sm text-gray-500 mt-1">
                            Manage your personal profile & security
                        </p>

                    </div>

                </div>

                <h1 class="text-6xl lg:text-7xl font-black tracking-tight text-gray-900 leading-[0.95]">

                    My
                    <span class="bg-gradient-to-r from-sky-500 via-indigo-500 to-blue-600 bg-clip-text text-transparent">
                        Profile
                    </span>

                </h1>

                <p class="text-xl text-gray-500 mt-6 max-w-2xl leading-relaxed">

                    Customize your personal information, update your password,
                    and manage your account securely with a premium experience.

                </p>

            </div>

            <!-- PROFILE GRID -->
            <div class="grid xl:grid-cols-3 gap-8 items-start">

                <!-- LEFT SIDEBAR -->
                <div class="xl:col-span-1">

                    <div class="sticky top-10 rounded-[40px]
                                border border-white/70
                                bg-white/70
                                backdrop-blur-3xl
                                shadow-[0_25px_100px_rgba(15,23,42,0.06)]
                                overflow-hidden">

                        <!-- TOP -->
                        <div class="relative p-10 pb-28 overflow-hidden">

                            <div class="absolute inset-0 bg-gradient-to-br from-sky-500 via-indigo-500 to-blue-600"></div>

                            <div class="absolute top-[-100px] right-[-80px] w-[250px] h-[250px] bg-white/10 blur-3xl rounded-full"></div>

                            <div class="relative z-10 flex flex-col items-center text-center">

                                <!-- Avatar -->
                                <div class="w-36 h-36 rounded-full
                                            bg-white/20
                                            border border-white/30
                                            backdrop-blur-2xl
                                            flex items-center justify-center
                                            text-white text-6xl
                                            shadow-2xl mb-6">

                                    <i class="fa-solid fa-user"></i>

                                </div>

                                <h2 class="text-3xl font-black text-white mb-2">

                                    {{ auth()->user()->name }}

                                </h2>

                                <p class="text-white/80 text-sm tracking-[0.25em] uppercase font-bold">

                                    Premium Member

                                </p>

                            </div>

                        </div>

                        <!-- STATS -->
                        <div class="relative z-20 -mt-16 px-6 pb-8">

                            <div class="rounded-[30px]
                                        bg-white
                                        border border-gray-100
                                        shadow-[0_20px_80px_rgba(15,23,42,0.08)]
                                        p-6">

                                <div class="space-y-5">

                                    <div class="flex items-center justify-between">

                                        <div class="flex items-center gap-3">

                                            <div class="w-12 h-12 rounded-2xl bg-sky-100 flex items-center justify-center">

                                                <i class="fa-solid fa-shield-halved text-sky-600"></i>

                                            </div>

                                            <div>

                                                <h4 class="font-black text-gray-900">
                                                    Security
                                                </h4>

                                                <p class="text-sm text-gray-500">
                                                    Protected Account
                                                </p>

                                            </div>

                                        </div>

                                        <span class="text-emerald-500 font-black">
                                            Active
                                        </span>

                                    </div>

                                    <div class="flex items-center justify-between">

                                        <div class="flex items-center gap-3">

                                            <div class="w-12 h-12 rounded-2xl bg-indigo-100 flex items-center justify-center">

                                                <i class="fa-solid fa-envelope text-indigo-600"></i>

                                            </div>

                                            <div>

                                                <h4 class="font-black text-gray-900">
                                                    Email
                                                </h4>

                                                <p class="text-sm text-gray-500">
                                                    Verified Profile
                                                </p>

                                            </div>

                                        </div>

                                        <span class="text-emerald-500 font-black">
                                            Verified
                                        </span>

                                    </div>

                                    <div class="flex items-center justify-between">

                                        <div class="flex items-center gap-3">

                                            <div class="w-12 h-12 rounded-2xl bg-orange-100 flex items-center justify-center">

                                                <i class="fa-solid fa-lock text-orange-500"></i>

                                            </div>

                                            <div>

                                                <h4 class="font-black text-gray-900">
                                                    Privacy
                                                </h4>

                                                <p class="text-sm text-gray-500">
                                                    Full Protection
                                                </p>

                                            </div>

                                        </div>

                                        <span class="text-emerald-500 font-black">
                                            Safe
                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- RIGHT CONTENT -->
                <div class="xl:col-span-2 space-y-8">

                    <!-- PROFILE INFO -->
                    <div class="group relative rounded-[40px]
                                border border-white/70
                                bg-white/75
                                backdrop-blur-3xl
                                overflow-hidden
                                shadow-[0_25px_100px_rgba(15,23,42,0.06)]">

                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-700 bg-gradient-to-br from-sky-100/40 via-transparent to-indigo-100/30"></div>

                        <div class="relative z-10 p-8 lg:p-10">

                            <div class="flex items-center gap-5 mb-8">

                                <div class="w-20 h-20 rounded-[28px]
                                            bg-gradient-to-br from-sky-500 to-indigo-600
                                            flex items-center justify-center
                                            text-white text-3xl
                                            shadow-[0_15px_50px_rgba(59,130,246,0.35)]">

                                    <i class="fa-solid fa-user-gear"></i>

                                </div>

                                <div>

                                    <h2 class="text-4xl font-black text-gray-900">

                                        Profile Information

                                    </h2>

                                    <p class="text-gray-500 mt-2 text-lg">

                                        Update your account details and personal info

                                    </p>

                                </div>

                            </div>

                            <div class="rounded-[30px]
                                        bg-[#f9fafb]
                                        border border-gray-100
                                        p-6">

                                @include('profile.partials.update-profile-information-form')

                            </div>

                        </div>

                    </div>

                    <!-- PASSWORD -->
                    <div class="group relative rounded-[40px]
                                border border-white/70
                                bg-white/75
                                backdrop-blur-3xl
                                overflow-hidden
                                shadow-[0_25px_100px_rgba(15,23,42,0.06)]">

                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-700 bg-gradient-to-br from-orange-100/40 via-transparent to-pink-100/30"></div>

                        <div class="relative z-10 p-8 lg:p-10">

                            <div class="flex items-center gap-5 mb-8">

                                <div class="w-20 h-20 rounded-[28px]
                                            bg-gradient-to-br from-orange-500 to-pink-500
                                            flex items-center justify-center
                                            text-white text-3xl
                                            shadow-[0_15px_50px_rgba(249,115,22,0.35)]">

                                    <i class="fa-solid fa-lock"></i>

                                </div>

                                <div>

                                    <h2 class="text-4xl font-black text-gray-900">

                                        Change Password

                                    </h2>

                                    <p class="text-gray-500 mt-2 text-lg">

                                        Keep your account secure with a strong password

                                    </p>

                                </div>

                            </div>

                            <div class="rounded-[30px]
                                        bg-[#f9fafb]
                                        border border-gray-100
                                        p-6">

                                @include('profile.partials.update-password-form')

                            </div>

                        </div>

                    </div>

                    <!-- DELETE ACCOUNT -->
                    <div class="group relative rounded-[40px]
                                border border-red-100
                                bg-white/80
                                backdrop-blur-3xl
                                overflow-hidden
                                shadow-[0_25px_100px_rgba(239,68,68,0.06)]">

                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-700 bg-gradient-to-br from-red-100/40 via-transparent to-orange-100/20"></div>

                        <div class="relative z-10 p-8 lg:p-10">

                            <div class="flex items-center gap-5 mb-8">

                                <div class="w-20 h-20 rounded-[28px]
                                            bg-gradient-to-br from-red-500 to-orange-500
                                            flex items-center justify-center
                                            text-white text-3xl
                                            shadow-[0_15px_50px_rgba(239,68,68,0.35)]">

                                    <i class="fa-solid fa-trash"></i>

                                </div>

                                <div>

                                    <h2 class="text-4xl font-black text-gray-900">

                                        Delete Account

                                    </h2>

                                    <p class="text-gray-500 mt-2 text-lg">

                                        Permanently remove your account and data

                                    </p>

                                </div>

                            </div>

                            <div class="rounded-[30px]
                                        bg-red-50/50
                                        border border-red-100
                                        p-6">

                                @include('profile.partials.delete-user-form')

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>