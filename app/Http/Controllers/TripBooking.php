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
        'trip_id' => 'required|exists:trips,id',
        'is_paid' => 'boolean'
    ]);

    $isPaid = $request->input('is_paid', false);
    $userId = intval($request->input('user_id'));

    $user = User::find($request->input('user_id'));
    $tripsHistory = json_decode($user->trips_history, true);

    if (!is_array($tripsHistory)) {
        $tripsHistory = [];
    }

    // Ensure tripsHistory is an array of objects
    if (!empty($tripsHistory) && is_string($tripsHistory)) {
        $tripsHistory = json_decode($tripsHistory, true);
    }

    // Check if the trip is already booked
    foreach ($tripsHistory as $trip) {
        if (isset($trip['trip_id']) && $trip['trip_id'] == $request->input('trip_id')) {
            return response()->json(['message' => 'Trip already booked', 'is_paid' => $trip['is_paid']], 200);
        }
    }

    // Add the new trip with is_paid status
    $tripsHistory[] = [
        'trip_id' => $request->input('trip_id'),
        'is_paid' => $isPaid
    ];
    $user->trips_history = json_encode($tripsHistory);
    $user->save();

    return response()->json(['message' => 'Trip assigned successfully', 'is_paid' => $isPaid], 200);
}
}
