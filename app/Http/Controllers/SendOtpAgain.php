<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendOtpAgain extends Controller
{
    public function updateOtpAndSendEmail(Request $request){
        // Validate the request
        $request->validate([
            'email' => 'required|string|email|max:255',
        ]);
    
        // Find the user by email
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], 404);
        }
    
        // Generate a new 4-digit OTP
        $newOtp = random_int(1000, 9999);
    
        // Update the OTP in the user's record
        $user->otp = $newOtp;
        $user->save();
    
        // Send the new OTP email
        Mail::to($user->email)->send(new OtpMail($newOtp, $user));
    
        return response()->json([
            'status' => 'success',
            'message' => 'OTP updated and email sent successfully',
        ]);
    }
}
