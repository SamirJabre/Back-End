<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Ensure User model is imported

class EditProfileController extends Controller
{
    public function updateProfile(Request $request, $user_id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $user_id,
            'password' => 'nullable|string|min:6',
            'profile_picture' => 'nullable|string',
        ]);

        $token = $request->header('Authorization');
        if (!$token) {
        return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Find the user by ID
        $user = User::findOrFail($user_id);

        // Update user details only if provided
        if (!empty($validatedData['name'])) {
            $user->name = $validatedData['name'];
        }
        if (!empty($validatedData['email'])) {
            $user->email = $validatedData['email'];
        }
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }
        if (!empty($validatedData['profile_picture'])) {
            $user->profile_picture = $validatedData['profile_picture'];
        }

        // Save the updated user model
        $user->save();

        // Return a response
        return response()->json(['message' => 'Profile updated successfully'], 200);
    }
}