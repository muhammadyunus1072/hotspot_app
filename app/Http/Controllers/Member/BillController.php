<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Member\Transaction\BillRepository;

class BillController extends Controller
{
    public function index()
    {
        return view('app.member.bill.index');
    }

    public function create()
    {
        return view('app.member.bill.detail', ["objId" => null]);
    }

    public function edit(Request $request)
    {
        return view('app.member.bill.detail', ["objId" => $request->id]);
    }
    
    public function checkout(Request $request)
    {
        return view('app.member.bill.checkout', ["objId" => $request->id]);
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed === $request->signature_key) {
            if ($request->transaction_status === 'capture' || $request->transaction_status === 'settlement') {
                BillRepository::midtransCallback($request->order_id);
            }
        }
    }
}
