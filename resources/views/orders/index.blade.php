<x-app-layout>

    <div class="min-h-screen bg-gradient-to-br from-slate-100 via-blue-50 to-indigo-100 py-14 overflow-hidden">

        <!-- BACKGROUND -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden">

            <div class="absolute top-[-250px] left-[-120px] w-[550px] h-[550px] bg-blue-400/20 blur-3xl rounded-full"></div>

            <div class="absolute bottom-[-250px] right-[-120px] w-[550px] h-[550px] bg-indigo-400/20 blur-3xl rounded-full"></div>

            <div class="absolute top-[35%] left-[45%] w-[300px] h-[300px] bg-cyan-400/10 blur-3xl rounded-full"></div>

        </div>

        <div class="relative max-w-7xl mx-auto px-4 lg:px-8">

            <!-- SUCCESS -->
            @if(session('success'))

                <div class="mb-8 rounded-[28px] bg-emerald-500 text-white px-6 py-5 shadow-2xl shadow-emerald-500/30 flex items-center gap-4">

                    <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center backdrop-blur-xl">

                        <i class="fa-solid fa-circle-check text-2xl"></i>

                    </div>

                    <div>

                        <h3 class="font-black text-xl">
                            Success
                        </h3>

                        <p class="text-emerald-100">
                            {{ session('success') }}
                        </p>

                    </div>

                </div>

            @endif

            <!-- HERO -->
            <div class="relative overflow-hidden rounded-[40px] bg-gradient-to-br from-[#0f172a] via-[#111827] to-[#1e293b] p-8 lg:p-14 shadow-[0_30px_100px_rgba(0,0,0,0.25)] mb-12">

                <!-- GLOW -->
                <div class="absolute top-0 right-0 w-[350px] h-[350px] bg-blue-500/10 blur-3xl rounded-full"></div>

                <div class="absolute bottom-0 left-0 w-[350px] h-[350px] bg-indigo-500/10 blur-3xl rounded-full"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-10">

                    <!-- LEFT -->
                    <div class="max-w-2xl">

                        <div class="inline-flex items-center gap-3 px-5 py-3 rounded-2xl bg-white/10 border border-white/10 backdrop-blur-xl mb-6">

                            <div class="w-11 h-11 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white shadow-xl">

                                <i class="fa-solid fa-box-open"></i>

                            </div>

                            <span class="text-sm font-bold tracking-wide text-white">
                                Premium Orders Dashboard
                            </span>

                        </div>

                        <h1 class="text-5xl lg:text-7xl font-black leading-[1.05] text-white">

                            My
                            Orders

                        </h1>

                        <p class="text-lg text-gray-300 leading-relaxed mt-6 max-w-xl">

                            Track your purchases, monitor delivery progress,
                            and review your premium shopping history in one elegant place.

                        </p>

                    </div>

                    <!-- STATS -->
                    <div class="grid grid-cols-2 gap-4 w-full lg:w-auto">

                        <div class="rounded-3xl bg-white/10 border border-white/10 backdrop-blur-xl p-6 min-w-[180px]">

                            <div class="w-14 h-14 rounded-2xl bg-blue-500/20 flex items-center justify-center mb-5">

                                <i class="fa-solid fa-cart-shopping text-blue-400 text-2xl"></i>

                            </div>

                            <h3 class="text-4xl font-black text-white">
                                {{ $orders->count() }}
                            </h3>

                            <p class="text-sm text-gray-300 mt-2">
                                Total Orders
                            </p>

                        </div>

                        <div class="rounded-3xl bg-white/10 border border-white/10 backdrop-blur-xl p-6 min-w-[180px]">

                            <div class="w-14 h-14 rounded-2xl bg-emerald-500/20 flex items-center justify-center mb-5">

                                <i class="fa-solid fa-truck-fast text-emerald-400 text-2xl"></i>

                            </div>

                            <h3 class="text-4xl font-black text-white">
                                {{ $orders->where('status', 'completed')->count() }}
                            </h3>

                            <p class="text-sm text-gray-300 mt-2">
                                Completed
                            </p>

                        </div>

                    </div>

                </div>

            </div>

            <!-- ORDERS -->
            @forelse($orders as $order)

                @php

                    $status = strtolower($order->status);

                    $statusColor = match($status) {

                        'completed' => 'from-emerald-500 to-green-600',

                        'pending' => 'from-yellow-400 to-orange-500',

                        'cancelled' => 'from-red-500 to-rose-600',

                        default => 'from-blue-500 to-indigo-600',

                    };

                @endphp

                <div class="group relative overflow-hidden rounded-[35px] bg-white/75 backdrop-blur-2xl border border-white/40 shadow-[0_20px_70px_rgba(0,0,0,0.08)] hover:shadow-[0_25px_80px_rgba(59,130,246,0.18)] transition duration-500 mb-10">

                    <!-- TOP GLOW -->
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-500 bg-gradient-to-br from-blue-500/5 via-transparent to-indigo-500/10"></div>

                    <!-- HEADER -->
                    <div class="relative p-8 border-b border-gray-100/80">

                        <div class="flex items-center justify-between gap-6">

                            <!-- LEFT -->
                            <div class="flex items-center gap-5">

                                <div class="w-20 h-20 rounded-[28px] bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center shadow-2xl shadow-blue-500/30">

                                    <i class="fa-solid fa-box text-3xl"></i>

                                </div>

                                <div>

                                    <span class="text-xs uppercase tracking-[0.35em] text-gray-400 font-black">
                                        Premium Purchase
                                    </span>

                                    <h2 class="text-4xl font-black text-gray-900 mt-2">
                                        Order Summary
                                    </h2>

                                </div>

                            </div>

                            <!-- STATUS -->
                            <div class="flex items-center gap-4">

                                <div class="hidden md:block text-right">

                                    <p class="text-xs uppercase tracking-[0.25em] text-gray-400 font-bold mb-2">
                                        Order Status
                                    </p>

                                    <p class="text-sm text-gray-500">
                                        Updated from dashboard
                                    </p>

                                </div>

                                <div class="px-7 py-3 rounded-2xl bg-gradient-to-r {{ $statusColor }} text-white font-black uppercase tracking-[0.18em] shadow-[0_10px_35px_rgba(0,0,0,0.18)] border border-white/10">

                                    <div class="flex items-center gap-3">

                                        @if($status === 'completed')

                                            <i class="fa-solid fa-circle-check"></i>

                                        @elseif($status === 'pending')

                                            <i class="fa-solid fa-clock"></i>

                                        @elseif($status === 'cancelled')

                                            <i class="fa-solid fa-xmark"></i>

                                        @else

                                            <i class="fa-solid fa-box"></i>

                                        @endif

                                        {{ $order->status }}

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- ITEMS -->
                    <div class="relative p-8">

                        <div class="space-y-5">

                            @foreach($order->items as $item)

                                <div class="group/item flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 rounded-[28px] border border-gray-100 bg-gradient-to-r from-white to-gray-50 p-5 hover:shadow-xl hover:-translate-y-1 transition duration-300">

                                    <!-- PRODUCT -->
                                    <div class="flex items-center gap-5">

                                        <div class="w-20 h-20 rounded-3xl overflow-hidden bg-gray-100 shadow-lg">

                                            @if($item->product && $item->product->image)

                                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                                     class="w-full h-full object-cover group-hover/item:scale-110 transition duration-500">

                                            @else

                                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-indigo-100">

                                                    <i class="fa-solid fa-image text-gray-400 text-2xl"></i>

                                                </div>

                                            @endif

                                        </div>

                                        <div>

                                            <h3 class="text-2xl font-black text-gray-900 mb-2">

                                                {{ $item->product->name ?? 'Deleted Product' }}

                                            </h3>

                                            <div class="flex items-center gap-3 text-sm text-gray-500">

                                                <span class="flex items-center gap-2 px-3 py-1 rounded-xl bg-blue-50 text-blue-700 font-semibold">

                                                    <i class="fa-solid fa-layer-group"></i>

                                                    Quantity: {{ $item->quantity }}

                                                </span>

                                            </div>

                                        </div>

                                    </div>

                                    <!-- PRICE -->
                                    <div class="flex items-center gap-10">

                                        <div class="text-center">

                                            <p class="text-xs uppercase tracking-[0.25em] text-gray-400 font-bold mb-2">
                                                Price
                                            </p>

                                            <h4 class="text-2xl font-black text-gray-900">

                                                ${{ $item->price }}

                                            </h4>

                                        </div>

                                        <div class="w-px h-16 bg-gray-200 hidden lg:block"></div>

                                        <div class="text-center">

                                            <p class="text-xs uppercase tracking-[0.25em] text-gray-400 font-bold mb-2">
                                                Total
                                            </p>

                                            <h4 class="text-3xl font-black bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">

                                                ${{ $item->price * $item->quantity }}

                                            </h4>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                        <!-- FOOTER -->
                        <div class="mt-8 flex items-center justify-between rounded-[30px] bg-gradient-to-r from-slate-900 via-blue-950 to-indigo-950 px-8 py-6 shadow-2xl overflow-hidden relative">

                            <div class="absolute top-0 right-0 w-40 h-40 bg-blue-500/10 blur-3xl rounded-full"></div>

                            <div class="absolute bottom-0 left-0 w-40 h-40 bg-indigo-500/10 blur-3xl rounded-full"></div>

                            <div class="relative z-10 flex items-center gap-5">

                                <div class="w-16 h-16 rounded-3xl bg-gradient-to-r from-emerald-500 to-green-600 text-white flex items-center justify-center shadow-2xl shadow-emerald-500/30">

                                    <i class="fa-solid fa-wallet text-2xl"></i>

                                </div>

                                <div>

                                    <p class="text-xs uppercase tracking-[0.3em] text-gray-400 font-bold mb-2">
                                        Final Payment
                                    </p>

                                    <h3 class="text-5xl font-black text-white">

                                        ${{ $order->total_price }}

                                    </h3>

                                </div>

                            </div>

                            <div class="hidden lg:flex items-center gap-3 text-emerald-400 font-bold text-lg relative z-10">

                                <i class="fa-solid fa-shield-check text-2xl"></i>

                                Secure Order

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <!-- EMPTY -->
                <div class="flex justify-center py-24">

                    <div class="max-w-2xl w-full rounded-[40px] bg-white/80 backdrop-blur-2xl border border-white/40 shadow-[0_20px_80px_rgba(0,0,0,0.08)] p-14 text-center">

                        <div class="relative w-40 h-40 mx-auto mb-10">

                            <div class="absolute inset-0 bg-blue-500/20 blur-3xl rounded-full"></div>

                            <div class="relative w-full h-full rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-[0_25px_70px_rgba(59,130,246,0.45)]">

                                <i class="fa-solid fa-bag-shopping text-white text-6xl"></i>

                            </div>

                        </div>

                        <h2 class="text-5xl font-black text-gray-900 mb-5">
                            No Orders Yet
                        </h2>

                        <p class="text-gray-500 text-lg leading-relaxed mb-10">

                            Your premium shopping journey starts here.
                            Discover luxury products and place your first order today.

                        </p>

                        <a href="/"
                           class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white text-lg font-black shadow-2xl shadow-blue-500/30 hover:scale-105 transition duration-300">

                            <i class="fa-solid fa-store"></i>

                            Start Shopping

                        </a>

                    </div>

                </div>

            @endforelse

        </div>

    </div>

</x-app-layout>