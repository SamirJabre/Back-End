<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DeactivatingAccounts extends Controller
{
    public function deleteUnverifiedUsers(Request $request)
    {
        User::where('email', $request->email)->delete();
    }
}