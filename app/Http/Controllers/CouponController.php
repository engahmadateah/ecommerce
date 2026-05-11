<?php

namespace App\Http\Controllers;

use App\Models\Coupon;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->get();

        return view('coupons.index', compact('coupons'));
    }

    public function show(Coupon $coupon)
    {
        return view('coupons.show', compact('coupon'));
    }
}