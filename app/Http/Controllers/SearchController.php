<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function searchTrips(Request $request)
{
    $from = $request->input('from');
    $to = $request->input('to');
    $price = $request->input('price');
    $date = $request->input('date');

    $token = $request->header('Authorization');
    if (!$token) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $query = DB::table('trips')
        ->join('buses', 'trips.bus_id', '=', 'buses.id')
        ->join('drivers', 'buses.driver_id', '=', 'drivers.id')
        ->select(
            'trips.id',
            'trips.date',
            'trips.departure_time',
            'trips.arrival_time',
            'trips.from',
            'trips.to',
            'trips.price',
            'buses.passenger_load',
            'drivers.name',
            'drivers.rating'
        );

    if ($from) {
        $query->where('trips.from', $from);
    }

    if ($to) {
        $query->where('trips.to', $to);
    }

    if ($price) {
        $query->where('trips.price', '<=', $price);
    }

    if ($date) {
        $query->where('trips.date', $date);
    }

    $trips = $query->get();

    return response()->json($trips);
}

public function trips(Request $request)
{
    $token = $request->header('Authorization');
    if (!$token) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Proceed with the existing functionality of the trips function
    $trips = DB::table('trips')
        ->join('buses', 'trips.bus_id', '=', 'buses.id')
        ->join('drivers', 'buses.driver_id', '=', 'drivers.id')
        ->select('trips.id', 'trips.date', 'trips.departure_time', 'trips.arrival_time', 'trips.from', 'trips.to', 'trips.price', 'buses.passenger_load', 'drivers.name', 'drivers.rating')
        ->get();

    return $trips;
}

    public function tripById(Request $request)
{
    $id = $request->input('id');

    $trip = DB::table('trips')
        ->join('buses', 'trips.bus_id', '=', 'buses.id')
        ->join('drivers', 'buses.driver_id', '=', 'drivers.id')
        ->select('trips.id', 'trips.date', 'trips.departure_time', 'trips.arrival_time', 'trips.from', 'trips.to', 'trips.price', 'trips.bus_id' , 'buses.passenger_load','drivers.id as driver_id', 'drivers.name', 'drivers.rating')
        ->where('trips.id', $id)
        ->first();

    return response()->json($trip);
}

public function getUserById(Request $request)
{
    $token = $request->header('Authorization');
    if (!$token) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    $request->validate([
        'id' => 'required|exists:users,id',
    ]);

    $user = User::find($request->id);
    return response()->json([
        'status' => 'success',
        'user' => $user,
    ]);
}
}
