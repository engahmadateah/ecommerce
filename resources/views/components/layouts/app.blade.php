<nav class="bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">

        <!-- Logo -->
        <a href="/" class="text-xl font-bold">🛍️ MyStore</a>

        <!-- Links -->
        <div class="hidden md:flex items-center gap-6">

            <a href="/" class="hover:text-blue-500">Home</a>
            <a href="/cart" class="hover:text-blue-500">
                Cart ({{ count(session('cart', [])) }})
            </a>

            @auth
                <a href="/dashboard" class="hover:text-blue-500">Dashboard</a>
            @endauth
        </div>

        <!-- User -->
        <div class="flex items-center gap-4">

            @auth
                <!-- Username -->
                <span class="text-gray-700 font-medium">
                    {{ auth()->user()->name }}
                </span>

                <!-- Dropdown -->
                <div class="relative">
                    <button onclick="toggleDropdown()" class="bg-gray-200 px-3 py-1 rounded-lg">
                        ⚙️
                    </button>

                    <div id="dropdown"
                         class="hidden absolute right-0 mt-2 w-40 bg-white shadow-lg rounded-lg overflow-hidden">

                        <a href="/profile"
                           class="block px-4 py-2 hover:bg-gray-100">
                            Profile
                        </a>

                        <form method="POST" action="/logout">
                            @csrf
                            <button class="w-full text-left px-4 py-2 hover:bg-gray-100 text-red-500">
                                Logout
                            </button>
                        </form>

                    </div>
                </div>
            @else
                <a href="/login" class="text-blue-500">Login</a>
                <a href="/register" class="text-blue-500">Register</a>
            @endauth

        </div>

    </div>
</nav>

<script>
    function toggleDropdown() {
        document.getElementById('dropdown').classList.toggle('hidden');
    }
</script>