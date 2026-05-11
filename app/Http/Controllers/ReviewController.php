<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request, $productId)
{
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:500',
    ]);

    Review::updateOrCreate(
        [
            'user_id' => auth()->id(),
            'product_id' => $productId,
        ],
        [
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]
    );

    return back()->with('success', 'Review saved ⭐');
}
}
