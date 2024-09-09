<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TripBooking extends Controller
{
    public function bookTrip(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'trip_id' => 'required|exists:trips,id'
    ]);

    $userId = intval($request->input('user_id'));

    $user = User::find($request->input('user_id'));
    $tripsHistory = json_decode($user->trips_history, true);

    if (!is_array($tripsHistory)) {
        $tripsHistory = [];
    }

    // Check if the trip is already booked
    if (in_array($request->input('trip_id'), $tripsHistory)) {
        return response()->json(['message' => 'Trip already booked'], 200);
    }

    // Add the new trip
    $tripsHistory[] = $request->input('trip_id');
    $user->trips_history = json_encode($tripsHistory);
    $user->save();

    return response()->json(['message' => 'Trip assigned successfully'], 200);
}
}
