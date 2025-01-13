<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Member\MemberRepository;
use App\Mail\MailjetTestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class DashboardController extends Controller
{
    public function index()
    {
        $recipientEmail = 'kolakpisank3@gmail.com'; // Replace with recipient's email

        Mail::to($recipientEmail)->send(new MailjetTestMail());
    
        return 'Mailjet email has been sent successfully!';
        // return MemberRepository::getData(2);
        return view('app.dashboard');
    }
   
   public function callback(Request $request)
    {
        Log::info($request->all());
    }

}
