<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class UserHelper
{
    public static function role()
    {
        $user = Auth::user();
                
        $roles = $user->getRoleNames(); 
        return $roles[0];
    }

    public static function id()
    {
        return Auth::user()->id;
    }
}
