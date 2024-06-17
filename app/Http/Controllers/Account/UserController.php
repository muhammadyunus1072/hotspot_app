<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Account\UserRepository;

class UserController extends Controller
{
    public function index()
    {
        return view('app.account.user.index');
    }

    public function create()
    {
        return view('app.account.user.detail', ["objId" => null]);
    }

    public function edit(Request $request)
    {
        return view('app.account.user.detail', ["objId" => $request->id]);
    }

    public function search(Request $request)
    {
        return UserRepository::search($request->search);
    }
    public function search_member(Request $request)
    {
        return UserRepository::search_member($request->search);
    }
}
