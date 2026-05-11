<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // 🔍 Search
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 📂 Filter by Category
        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        // ⭐ Reviews + Count
        $products = $query
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->latest()
            ->paginate(8);

        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load(['reviews.user']);

        $alsoBought = Product::whereIn('products.id', function ($query) use ($product) {
            $query->select('oi2.product_id')
                ->from('order_items as oi1')
                ->join('order_items as oi2', 'oi1.order_id', '=', 'oi2.order_id')
                ->where('oi1.product_id', $product->id)
                ->where('oi2.product_id', '!=', $product->id);
        })
        ->select(
            'products.id',
            'products.name',
            'products.price',
            'products.image'
        )
        ->withAvg('reviews', 'rating')
        ->withCount('reviews')
        ->take(4)
        ->get();

        return view('products.show', compact('product','alsoBought'));
    }

    public function search(Request $request)
    {
        $products = Product::with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')

            // 🔍 search name + description + category
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                          ->orWhere('description', 'like', '%' . $request->search . '%')
                          ->orWhereHas('category', function ($cat) use ($request) {
                              $cat->where('name', 'like', '%' . $request->search . '%');
                          });
                });
            })

            // 📂 category filter
            ->when($request->category, function ($q) use ($request) {
                $q->where('category_id', $request->category);
            })

            // 🔥 sorting
            ->when($request->sort === 'rating', function ($q) {
                $q->orderByDesc('reviews_avg_rating');
            })

            ->when($request->sort === 'popular', function ($q) {
                $q->orderByDesc('reviews_count');
            })

            ->latest()
            ->take(12)
            ->get();

        return view('products.partials.grid', compact('products'));
    }
    public function deals()
{
    $products = Product::with('category')
        ->whereNotNull('discount_price')
        ->whereColumn('discount_price', '<', 'price') // مهم
        ->withAvg('reviews', 'rating')
        ->withCount('reviews')
        ->latest()
        ->paginate(12);

    return view('products.deals', compact('products'));
}
}