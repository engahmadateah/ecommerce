<?php

namespace App\Http\Controllers;

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

    public function addToCart(Package $package, CartService $cart)
    {
        $cart->addPackage($package);

        return back()->with('success', 'Bundle added 🔥');
    }
}
