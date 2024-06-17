<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Transaction\PaymentMethodRepository;

class PaymentMethodController extends Controller
{
    public function index()
    {
        return view('app.transaction.payment-method.index');
    }

    public function create()
    {
        return view('app.transaction.payment-method.detail', ["objId" => null]);
    }

    public function edit(Request $request)
    {
        return view('app.transaction.payment-method.detail', ["objId" => $request->id]);
    }

    public function search(Request $request)
    {
        return PaymentMethodRepository::search($request->search);
    }
}
