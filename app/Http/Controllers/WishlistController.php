<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function toggle(Product $product)
{
    $user = auth()->user();

    if ($user->wishlist()->where('product_id', $product->id)->exists()) {
        $user->wishlist()->detach($product->id);
        return response()->json(['status' => 'removed']);
    } else {
        $user->wishlist()->attach($product->id);
        return response()->json(['status' => 'added']);
    }
}
}
