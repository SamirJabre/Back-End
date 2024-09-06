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

    // Retrieve the existing trips_history JSON data
    $tripsHistory = json_decode($user->trips_history, true);

    // If trips_history is not an array, initialize it as an empty array
    if (!is_array($tripsHistory)) {
        $tripsHistory = [];
    }

    // Append the new trip ID to the array
    $tripsHistory[] = $request->input('trip_id');

    // Encode the array back to JSON
    $user->trips_history = json_encode($tripsHistory);

    // Save the user model
    $user->save();

    return response()->json(['message' => 'Trip assigned successfully'], 200);
}
}
