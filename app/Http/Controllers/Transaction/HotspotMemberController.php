<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HotspotMemberController extends Controller
{
    public function index()
    {
        return view('app.transaction.hotspot-member.index');
    }

    public function create()
    {
        return view('app.transaction.hotspot-member.detail', ["objId" => null]);
    }

    public function edit(Request $request)
    {
        return view('app.transaction.hotspot-member.detail', ["objId" => $request->id]);
    }
}
