<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;

class TripBooking extends Controller
{
    public function bookTrip(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'trip_id' => 'required|exists:trips,id',
            'seat_number' => 'required|integer|min:1|max:42'
        ]);
        $token = $request->header('Authorization');
        if (!$token) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

        $userId = intval($request->input('user_id'));

        $user = User::find($request->input('user_id'));
        $tripsHistory = json_decode($user->trips_history, true);

        if (!is_array($tripsHistory)) {
            $tripsHistory = [];
        }

        foreach ($tripsHistory as $trip) {
            if ($trip['trip_id'] == $request->input('trip_id')) {
                return response()->json(['message' => 'Trip already booked'], 200);
            }
        }

        $trip = Trip::find($request->input('trip_id'));
        $bus = Bus::find($trip->bus_id);
        $seats = json_decode($bus->seats, true);

        $seatNumber = intval($request->input('seat_number'));
        foreach ($seats as &$seat) {
            if ($seat['seat_number'] == $seatNumber) {
                if ($seat['status'] == 'occupied') {
                    return response()->json(['message' => 'Seat is already occupied'], 200);
                }
                // Mark the seat as occupied
                $seat['status'] = 'occupied';
                break;
            }
        }

        $bus->seats = json_encode($seats);

        $occupiedCount = 0;
        foreach ($seats as $seat) {
            if ($seat['status'] == 'occupied') {
                $occupiedCount++;
            }
        }
        $bus->passenger_load = $occupiedCount;
        $bus->save();

        $tripsHistory[] = [
            'trip_id' => $request->input('trip_id'),
            'seat_number' => $seatNumber
        ];
        $user->trips_history = json_encode($tripsHistory);
        $user->save();

        return response()->json(['message' => 'Trip assigned successfully'], 200);
    }
}
