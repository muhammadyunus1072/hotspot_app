<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    public function index()
    {
        return view('app.transaction.transaction.index');
    }

    public function create()
    {
        return view('app.transaction.transaction.detail', ["objId" => null]);
    }

    public function edit(Request $request)
    {
        return view('app.transaction.transaction.detail', ["objId" => $request->id]);
    }
}
