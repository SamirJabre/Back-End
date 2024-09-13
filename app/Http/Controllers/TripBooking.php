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

        $userId = intval($request->input('user_id'));

        $user = User::find($request->input('user_id'));
        $tripsHistory = json_decode($user->trips_history, true);

        if (!is_array($tripsHistory)) {
            $tripsHistory = [];
        }

        // Check if the trip is already booked
        foreach ($tripsHistory as $trip) {
            if ($trip['trip_id'] == $request->input('trip_id')) {
                return response()->json(['message' => 'Trip already booked'], 200);
            }
        }

        // Retrieve the bus associated with the trip
        $trip = Trip::find($request->input('trip_id'));
        $bus = Bus::find($trip->bus_id);
        $seats = json_decode($bus->seats, true);

        // Check if the seat is available
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

        // Save the updated seats back to the bus
        $bus->seats = json_encode($seats);

        // Update the passenger_load to reflect the number of occupied seats
        $occupiedCount = 0;
        foreach ($seats as $seat) {
            if ($seat['status'] == 'occupied') {
                $occupiedCount++;
            }
        }
        $bus->passenger_load = $occupiedCount;
        $bus->save();

        // Add the new trip to the user's history
        $tripsHistory[] = [
            'trip_id' => $request->input('trip_id'),
            'seat_number' => $seatNumber
        ];
        $user->trips_history = json_encode($tripsHistory);
        $user->save();

        return response()->json(['message' => 'Trip assigned successfully'], 200);
    }
}
