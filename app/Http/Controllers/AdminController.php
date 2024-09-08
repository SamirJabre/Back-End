<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $admin = Admin::where('email', $request->email)->first();
        $token = $admin->createToken('AdminToken')->plainTextToken;
            return response()->json([
                'admin' => $admin,
                'token' => $token,
            ], 200);
        
    }
    public function getAllUsers()
    {
        $users = User::all();
        return response()->json([
            'users' => $users,
        ], 200);
    }
}