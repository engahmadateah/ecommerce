<x-app-layout>
<div class="max-w-4xl mx-auto py-10">

    <h1 class="text-2xl font-bold mb-6">Shipping Information</h1>

    <form method="POST" action="/checkout">
        @csrf

        <input name="full_name" placeholder="Full Name" class="w-full mb-3 p-2 border" required>
        <input name="phone" placeholder="Phone" class="w-full mb-3 p-2 border" required>
        <input name="address" placeholder="Address" class="w-full mb-3 p-2 border" required>
        <input name="city" placeholder="City" class="w-full mb-3 p-2 border" required>
        <input name="country" placeholder="Country" class="w-full mb-3 p-2 border" required>

        <button class="bg-black text-white px-6 py-2 rounded">
            Continue to Payment →
        </button>

    </form>

</div>
</x-app-layout>