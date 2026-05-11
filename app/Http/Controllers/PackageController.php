<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Services\CartService;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::with('products')->latest()->get();

        return view('packages.index', compact('packages'));
    }

    public function show(Package $package)
    {
        $package->load('products');

        return view('packages.show', compact('package'));
    }
    public function addToCart(Package $package)
    {
        $cart = session()->get('cart', []);

        $key = 'package_' . $package->id;

        // إذا موجود زيد الكمية
        if (isset($cart[$key])) {
            $cart[$key]['quantity']++;
        } else {

            $cart[$key] = [
                'name' => $package->name,
                'price' => $package->price, // ✅ سعر البكج
                'quantity' => 1,
                'image' => $package->products->first()->image ?? null,
                'is_package' => true // 🔥 مهم
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Bundle added 🔥');
    }
}
