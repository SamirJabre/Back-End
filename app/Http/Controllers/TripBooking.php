<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TripBooking extends Controller
{
    public function bookTrip(Request $request)
    {
        // Validate the request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'trip_id' => 'required|exists:trips,id',
        ]);

        // Retrieve the user by ID
        $user = User::find($request->input('user_id'));

        // Assign the trip ID to the trips_history field
        $user->trips_history = $request->input('trip_id');

        // Save the user model
        $user->save();

        return response()->json(['message' => 'Trip assigned successfully'], 200);
    }
}
