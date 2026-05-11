<nav class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- Logo -->
            <div class="flex">
                <a href="/" class="flex items-center font-bold text-xl">
                    🛍️ MyStore
                </a>

                <!-- Links -->
                <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-6">
                    <a href="/" class="text-gray-700 hover:text-blue-500">Home</a>

                    <a href="/cart" class="text-gray-700 hover:text-blue-500">
                        Cart ({{ count(session('cart', [])) }})
                    </a>
                </div>
            </div>

            <!-- Right Side -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">

                @auth
                    <!-- ⭐ Points + Level -->
                    <div class="flex items-center gap-3 mr-4">

                        <!-- Level Badge -->
                        <span class="text-xs px-3 py-1 rounded-full text-white
                            @if(auth()->user()->level == 'gold') bg-yellow-500
                            @elseif(auth()->user()->level == 'silver') bg-gray-500
                            @else bg-orange-500 @endif
                        ">
                            {{ strtoupper(auth()->user()->level ?? 'bronze') }}
                        </span>

                        <!-- Points -->
                        <span class="text-sm text-gray-600 font-semibold">
                            ⭐ {{ auth()->user()->points ?? 0 }}
                        </span>
                    </div>

                    <!-- Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                                {{ Auth::user()->name }}
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                Profile
                            </x-dropdown-link>

                            <x-dropdown-link :href="url('/orders')">
                                Orders
                            </x-dropdown-link>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Logout
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>

                @else
                    <a href="{{ route('login') }}" class="text-blue-500 mr-4">Login</a>
                    <a href="{{ route('register') }}" class="text-blue-500">Register</a>
                @endauth

            </div>

        </div>
    </div>
</nav>