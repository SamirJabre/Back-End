<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendOtpAgain extends Controller
{
    public function updateOtpAndSendEmail(Request $request){
       
        $request->validate([
            'email' => 'required|string|email|max:255',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], 404);
        }
    
        $newOtp = random_int(1000, 9999);
    
        $user->otp = $newOtp;
        $user->save();
    
        Mail::to($user->email)->send(new OtpMail($newOtp, $user));
    
        return response()->json([
            'status' => 'success',
            'message' => 'OTP updated and email sent successfully',
        ]);
    }
}
