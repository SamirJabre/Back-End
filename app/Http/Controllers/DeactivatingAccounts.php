<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DeactivatingAccounts extends Controller
{
    public function deleteUnverifiedUsers()
    {
        // Fetch unverified users
        $unverifiedUsers = User::where('is_verified', false)->get();

        // Delete unverified users
        foreach ($unverifiedUsers as $user) {
            $user->delete();
        }

        return response()->json(['message' => 'Unverified users deleted successfully.']);
    }
}