<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;

class BusSeatController extends Controller
{
    public function getSeats(Request $request)
    {
        $busId = $request->bus_id;
        $bus = Bus::find($busId);
        $seats = $bus->seats;
        return $seats;
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
}
