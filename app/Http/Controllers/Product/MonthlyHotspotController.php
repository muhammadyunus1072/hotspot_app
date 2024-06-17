<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MonthlyHotspotController extends Controller
{
    public function index()
    {
        return view('app.product.monthly-hotspot.index');
    }

    public function create()
    {
        return view('app.product.monthly-hotspot.detail', ["objId" => null]);
    }

    public function edit(Request $request)
    {
        return view('app.product.monthly-hotspot.detail', ["objId" => $request->id]);
    }
}
