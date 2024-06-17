<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Controllers\Controller;
use App\Repositories\Member\MemberRepository;

class DashboardController extends Controller
{
    public function index()
    {
        // return MemberRepository::getData(2);
        return view('app.dashboard');
    }

}
