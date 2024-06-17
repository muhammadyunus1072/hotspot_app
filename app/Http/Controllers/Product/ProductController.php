<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Product\ProductRepository;

class ProductController extends Controller
{
    public function index()
    {
        return view('app.product.product.index');
    }

    public function create()
    {
        return view('app.product.product.detail', ["objId" => null]);
    }

    public function edit(Request $request)
    {
        return view('app.product.product.detail', ["objId" => $request->id]);
    }

    public function search_monthly_hotspot(Request $request)
    {
        return ProductRepository::search_monthly_hotspot($request->search);
    }

    public function search_product(Request $request)
    {
        return ProductRepository::search_product($request->search);
    }
}
