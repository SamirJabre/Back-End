<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class EditProfileController extends Controller
{
    public function updateProfile(Request $request, $user_id)
    {
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

        $user = User::findOrFail($user_id);

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

        $user->save();

        return response()->json(['message' => 'Profile updated successfully'], 200);
    }
}