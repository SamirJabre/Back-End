<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;

class BusLocationController extends Controller
{
    public function updateLocation(Request $request)
    {
        $validatedData = $request->validate([
            'busId' => 'required|integer',
            'current_location' => 'required|string',
        ]);

        $bus = Bus::find($validatedData['busId']);

        if ($bus) {
            $bus->current_location = $validatedData['current_location'];
            $bus->save();
            return response()->json(['message' => 'Location updated successfully'], 200);
        }

        return response()->json(['message' => 'Bus not found'], 404);
    }
}
