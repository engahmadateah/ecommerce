<x-app-layout>

<div class="min-h-screen relative overflow-hidden bg-gradient-to-br from-[#f8fafc] via-[#ffffff] to-[#eef4ff] py-20">

    <!-- PREMIUM BACKGROUND -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">

        <!-- glows -->
        <div class="absolute top-[-250px] left-[-150px] w-[700px] h-[700px] bg-sky-300/20 blur-3xl rounded-full"></div>

        <div class="absolute bottom-[-250px] right-[-150px] w-[700px] h-[700px] bg-indigo-300/20 blur-3xl rounded-full"></div>

        <div class="absolute top-[35%] left-[40%] w-[350px] h-[350px] bg-pink-200/20 blur-3xl rounded-full"></div>

        <!-- grid -->
        <div class="absolute inset-0 opacity-[0.03]"
             style="background-image:
             linear-gradient(to right, black 1px, transparent 1px),
             linear-gradient(to bottom, black 1px, transparent 1px);
             background-size: 70px 70px;">
        </div>

    </div>

    <div class="relative max-w-7xl mx-auto px-5 lg:px-10">

        <!-- HERO -->
        <div class="text-center mb-16">

            <!-- badge -->
            <div class="inline-flex items-center gap-4 px-6 py-4 rounded-[30px]
                        border border-white/70
                        bg-white/70
                        backdrop-blur-3xl
                        shadow-[0_15px_60px_rgba(15,23,42,0.06)]
                        mb-8">

                <div class="w-14 h-14 rounded-2xl
                            bg-gradient-to-br from-sky-500 via-indigo-500 to-blue-600
                            flex items-center justify-center
                            text-white shadow-xl">

                    <i class="fa-solid fa-envelope text-xl"></i>

                </div>

                <div class="text-left">

                    <p class="text-xs uppercase tracking-[0.35em] text-sky-600 font-black">
                        Contact Support
                    </p>

                    <p class="text-sm text-gray-500 mt-1">
                        Premium customer experience
                    </p>

                </div>

            </div>

            <!-- title -->
            <h1 class="text-6xl lg:text-7xl font-black tracking-tight leading-[0.95] text-gray-900">

                Get In
                <span class="bg-gradient-to-r from-sky-500 via-indigo-500 to-blue-600 bg-clip-text text-transparent">
                    Touch
                </span>

            </h1>

            <!-- desc -->
            <p class="text-xl text-gray-500 leading-relaxed mt-8 max-w-3xl mx-auto">

                We'd love to hear from you. Send us your questions,
                feedback, or business inquiries and our team will respond quickly.

            </p>

        </div>

        <!-- GRID -->
        <div class="grid xl:grid-cols-3 gap-8 items-start">

            <!-- LEFT INFO -->
            <div class="xl:col-span-1 space-y-6">

                <!-- card -->
                <div class="rounded-[36px]
                            border border-white/70
                            bg-white/75
                            backdrop-blur-3xl
                            p-8
                            shadow-[0_20px_80px_rgba(15,23,42,0.06)]">

                    <div class="w-20 h-20 rounded-[28px]
                                bg-gradient-to-br from-sky-500 to-indigo-600
                                flex items-center justify-center
                                text-white text-3xl
                                shadow-[0_15px_50px_rgba(59,130,246,0.30)]
                                mb-7">

                        <i class="fa-solid fa-headset"></i>

                    </div>

                    <h2 class="text-3xl font-black text-gray-900 mb-4">

                        Premium Support

                    </h2>

                    <p class="text-gray-500 leading-relaxed text-lg">

                        Our support team is available to help you with orders,
                        products, and account assistance anytime.

                    </p>

                </div>

                <!-- features -->
                <div class="rounded-[36px]
                            border border-white/70
                            bg-white/75
                            backdrop-blur-3xl
                            p-8
                            shadow-[0_20px_80px_rgba(15,23,42,0.06)]">

                    <div class="space-y-6">

                        <!-- item -->
                        <div class="flex items-start gap-4">

                            <div class="w-14 h-14 rounded-2xl bg-sky-100 flex items-center justify-center shrink-0">

                                <i class="fa-solid fa-bolt text-sky-600"></i>

                            </div>

                            <div>

                                <h4 class="font-black text-gray-900 text-lg mb-1">

                                    Fast Response

                                </h4>

                                <p class="text-gray-500">

                                    Quick replies from our dedicated team.

                                </p>

                            </div>

                        </div>

                        <!-- item -->
                        <div class="flex items-start gap-4">

                            <div class="w-14 h-14 rounded-2xl bg-indigo-100 flex items-center justify-center shrink-0">

                                <i class="fa-solid fa-shield-halved text-indigo-600"></i>

                            </div>

                            <div>

                                <h4 class="font-black text-gray-900 text-lg mb-1">

                                    Secure Communication

                                </h4>

                                <p class="text-gray-500">

                                    Your information is fully protected.

                                </p>

                            </div>

                        </div>

                        <!-- item -->
                        <div class="flex items-start gap-4">

                            <div class="w-14 h-14 rounded-2xl bg-pink-100 flex items-center justify-center shrink-0">

                                <i class="fa-solid fa-gem text-pink-500"></i>

                            </div>

                            <div>

                                <h4 class="font-black text-gray-900 text-lg mb-1">

                                    Premium Experience

                                </h4>

                                <p class="text-gray-500">

                                    Elegant customer care experience.

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- FORM -->
            <div class="xl:col-span-2">

                <div class="relative rounded-[40px]
                            border border-white/70
                            bg-white/75
                            backdrop-blur-3xl
                            overflow-hidden
                            shadow-[0_25px_100px_rgba(15,23,42,0.06)]">

                    <!-- hover glow -->
                    <div class="absolute inset-0 bg-gradient-to-br from-sky-100/30 via-transparent to-indigo-100/20"></div>

                    <div class="relative z-10 p-8 lg:p-12">

                        <!-- success -->
                        @if(session('success'))

                            <div class="mb-8 rounded-3xl
                                        bg-emerald-50
                                        border border-emerald-100
                                        px-6 py-5
                                        text-emerald-600
                                        font-bold">

                                ✅ {{ session('success') }}

                            </div>

                        @endif

                        <!-- errors -->
                        @if($errors->any())

                            <div class="mb-8 rounded-3xl
                                        bg-red-50
                                        border border-red-100
                                        px-6 py-5">

                                <ul class="space-y-2 text-red-500 font-semibold">

                                    @foreach($errors->all() as $error)

                                        <li>• {{ $error }}</li>

                                    @endforeach

                                </ul>

                            </div>

                        @endif

                        <!-- heading -->
                        <div class="mb-10">

                            <h2 class="text-4xl font-black text-gray-900 mb-3">

                                Send Message

                            </h2>

                            <p class="text-lg text-gray-500">

                                Fill out the form below and we’ll get back to you soon.

                            </p>

                        </div>

                        <!-- form -->
                        <form method="POST"
                              action="{{ route('contact.send') }}"
                              class="space-y-7">

                            @csrf

                            <!-- name -->
                            <div>

                                <label class="block text-sm font-black uppercase tracking-[0.15em] text-gray-700 mb-3">

                                    Full Name

                                </label>

                                <div class="relative">

                                    <div class="absolute left-6 top-1/2 -translate-y-1/2 text-sky-500">

                                        <i class="fa-solid fa-user"></i>

                                    </div>

                                    <input type="text"
                                           name="name"
                                           value="{{ old('name') }}"
                                           placeholder="Enter your name"
                                           class="w-full h-[74px]
                                                  rounded-[26px]
                                                  border border-gray-200
                                                  bg-white
                                                  pl-16 pr-6
                                                  text-gray-800 font-semibold
                                                  shadow-lg
                                                  outline-none
                                                  focus:ring-4 focus:ring-sky-100
                                                  focus:border-sky-400
                                                  transition">

                                </div>

                            </div>

                            <!-- email -->
                            <div>

                                <label class="block text-sm font-black uppercase tracking-[0.15em] text-gray-700 mb-3">

                                    Email Address

                                </label>

                                <div class="relative">

                                    <div class="absolute left-6 top-1/2 -translate-y-1/2 text-indigo-500">

                                        <i class="fa-solid fa-envelope"></i>

                                    </div>

                                    <input type="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           placeholder="your@email.com"
                                           class="w-full h-[74px]
                                                  rounded-[26px]
                                                  border border-gray-200
                                                  bg-white
                                                  pl-16 pr-6
                                                  text-gray-800 font-semibold
                                                  shadow-lg
                                                  outline-none
                                                  focus:ring-4 focus:ring-indigo-100
                                                  focus:border-indigo-400
                                                  transition">

                                </div>

                            </div>

                            <!-- message -->
                            <div>

                                <label class="block text-sm font-black uppercase tracking-[0.15em] text-gray-700 mb-3">

                                    Your Message

                                </label>

                                <textarea name="message"
                                          rows="7"
                                          placeholder="Write your message..."
                                          class="w-full rounded-[30px]
                                                 border border-gray-200
                                                 bg-white
                                                 p-6
                                                 text-gray-800
                                                 shadow-lg
                                                 outline-none
                                                 focus:ring-4 focus:ring-sky-100
                                                 focus:border-sky-400
                                                 transition resize-none">{{ old('message') }}</textarea>

                            </div>

                            <!-- button -->
                            <button type="submit"
                                    class="group relative overflow-hidden
                                           w-full h-20 rounded-[28px]
                                           bg-gradient-to-r
                                           from-sky-500
                                           via-indigo-500
                                           to-blue-600
                                           text-white text-xl font-black
                                           shadow-[0_20px_80px_rgba(59,130,246,0.25)]
                                           hover:scale-[1.02]
                                           transition duration-300">

                                <span class="relative z-10 flex items-center justify-center gap-4">

                                    <i class="fa-solid fa-paper-plane group-hover:translate-x-1 transition duration-300"></i>

                                    Send Message

                                </span>

                                <div class="absolute inset-0
                                            bg-white/20
                                            translate-x-[-100%]
                                            group-hover:translate-x-[100%]
                                            transition duration-700">
                                </div>

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</x-app-layout>