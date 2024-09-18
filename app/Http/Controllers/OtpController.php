<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    public function validateOtp(Request $request){
        $request->validate([
            'email' => 'required|string|email|max:255',
            'otp' => 'required|integer',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], 404);
        }
    
        if ($user->otp == $request->otp) {
            $user->is_verified = true;
            $user->email_verified_at = now();
            $user->otp = null;
            $user->save();
    
            return response()->json([
                'status' => 'success',
                'message' => 'OTP verified successfully. User is now verified.',
                'user' => $user,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP',
            ], 400);
        }
    }
}
