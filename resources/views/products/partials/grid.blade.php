@forelse($products as $product)
<div class="relative bg-white rounded-2xl shadow hover:shadow-xl transition p-4 flex flex-col cursor-pointer">

    <a href="{{ route('products.show', $product) }}"
       class="absolute inset-0 z-10"></a>

    <img src="{{ asset('storage/' . $product->image) }}"
         class="w-full h-48 object-cover rounded-xl mb-4">

    <h2 class="text-lg font-semibold mb-1">
        {{ $product->name }}
    </h2>

    <!-- ⭐ مهم -->
    <div class="text-yellow-400 text-sm mb-1">
        {!! str_repeat('⭐', round($product->reviews_avg_rating ?? 0)) !!}
        <span class="text-gray-500 text-xs">
            ({{ $product->reviews_count ?? 0 }})
        </span>
    </div>

    <!-- 📂 مهم -->
    <p class="text-sm text-gray-500 mb-2">
        {{ $product->category->name ?? 'No Category' }}
    </p>

    <p class="font-bold text-xl mb-4">
        ${{ $product->price }}
    </p>

</div>
@empty
<p>No products found.</p>
@endforelse