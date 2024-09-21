<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;

class BusSeatController extends Controller
{
    public function getSeats(Request $request)
    {
        $busId = $request->bus_id;
        $token = $request->header('Authorization');
        if (!$token) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
        $bus = Bus::find($busId);
        $seats = $bus->seats;
        return json_decode($seats);
    }




    public function bookSeat($busId, $seatNumber)
    {
        $bus = Bus::find($busId);
        $seats = $bus->seats;
        $seat = $seats[$seatNumber - 1];
        if ($seat['status'] === 'occupied') {
            return response()->json(['message' => 'Seat is already occupied'], 400);
        }
        $seat['status'] = 'occupied';
        $seats[$seatNumber - 1] = $seat;
        $bus->seats = $seats;
        $bus->save();
        return response()->json(['message' => 'Seat booked successfully']);
    }
    public function updateSeat(Request $request)
    {
        $validatedData = $request->validate([
            'busId' => 'required|integer',
            'seatNumber' => 'required|integer',
            'distance' => 'required|numeric',
        ]);
    
        $bus = Bus::find($validatedData['busId']);
        if (!$bus) {
            return response()->json(['message' => 'Bus not found'], 404);
        }
    
        $seats = json_decode($bus->seats);
        if (!isset($seats[$validatedData['seatNumber'] - 1])) {
            return response()->json(['message' => 'Seat not found'], 404);
        }
    
        $seat = $seats[$validatedData['seatNumber'] - 1];
        $distance = $validatedData['distance'];
    
        if ($distance < 50) {
            $seat->status = 'occupied';
        } else {
            $seat->status = 'available';
        }
    
        $bus->seats = json_encode($seats);
        $bus->save();
    
        return response()->json(['message' => 'Seat updated successfully', 'bus' => $bus]);
    }

}
