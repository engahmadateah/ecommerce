<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->published();

        // 🔍 Search
        if ($request->search) {
            $term = (string) $request->search;

            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', '%'.$term.'%');

                // Arabic (non-English) words are searched in the translations too.
                // Only for non-ASCII text, so the JSON keys ("name", "ar") never match by accident.
                if (preg_match('/[^\x00-\x7F]/', $term)) {
                    $q->orWhere('translations', 'like', '%'.$term.'%');
                }
            });
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

        $categories = Category::cachedAll();

        // "Today's deals" rail on the first, unfiltered page of the home page only.
        $deals = collect();

        if (! $request->search && ! $request->category && $request->integer('page', 1) === 1) {
            $deals = Product::published()->with('category')
                ->whereNotNull('discount_price')
                ->whereColumn('discount_price', '<', 'price')
                ->withAvg('reviews', 'rating')
                ->withCount('reviews')
                ->latest()
                ->take(8)
                ->get();
        }

        return view('products.index', compact('products', 'categories', 'deals'));
    }

    public function show(Product $product)
    {
        abort_unless($product->is_published, 404);

        $product->load(['reviews.user']);

        $alsoBought = Product::published()->whereIn('products.id', function ($query) use ($product) {
            $query->select('oi2.product_id')
                ->from('order_items as oi1')
                ->join('order_items as oi2', 'oi1.order_id', '=', 'oi2.order_id')
                ->where('oi1.product_id', $product->id)
                ->where('oi2.product_id', '!=', $product->id);
        })
        ->select(
            'products.id',
            'products.slug',
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
        $products = Product::published()->with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')

            // 🔍 search name + description + category
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                          ->orWhere('description', 'like', '%' . $request->search . '%')
                          ->when(preg_match('/[^\x00-\x7F]/', (string) $request->search), fn ($w) => $w->orWhere('translations', 'like', '%' . $request->search . '%'))
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
    $products = Product::published()->with('category')
        ->whereNotNull('discount_price')
        ->whereColumn('discount_price', '<', 'price') // مهم
        ->withAvg('reviews', 'rating')
        ->withCount('reviews')
        ->latest()
        ->paginate(12);

    return view('products.deals', compact('products'));
}
}